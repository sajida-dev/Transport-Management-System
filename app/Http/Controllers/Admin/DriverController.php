<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Transporter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Notifications\DriverCreated;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Display a listing of the drivers.
     */
    public function index(Request $request): View
    {
        $query = Driver::with(['user', 'transporter']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by transporter
        if ($request->filled('transporter_id')) {
            $query->where('transporter_id', $request->transporter_id);
        }

        $drivers = $query->latest()->paginate(15);
        $transporters = Transporter::active()->get();

        return view('admin.drivers.index', compact('drivers', 'transporters'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create(): View
    {
        $transporters = Transporter::active()
            ->select('id', 'company_name')
            ->get();

        return view('admin.drivers.form', compact('transporters'));
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(StoreDriverRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Create associated user
            $user = User::create([
                'name' => "{$validated['first_name']} {$validated['last_name']}",
                'first_name'   => $validated['first_name'],
                'last_name'    => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
            ]);

            // Handle file uploads
            $profilePhotoPath = $request->file('profile_photo')?->store('drivers/photos', 'public');
            $licensePhotoPath = $request->file('license_photo')?->store('drivers/documents', 'public');
            $medicalPhotoPath = $request->file('medical_certificate_photo')?->store('drivers/documents', 'public');

            // Prepare driver data
            $driverData = collect($validated)
                ->except(['first_name', 'last_name', 'email', 'password', 'profile_photo', 'phone_number'])
                ->merge([
                    'user_id' => $user->id,
                    'profile_photo' => $profilePhotoPath,
                    'license_photo' => $licensePhotoPath,
                    'medical_certificate_photo' => $medicalPhotoPath,
                    'status' => 'active',
                ])
                ->toArray();

            if ($request->hasFile('kyc_documents')) {
                $kycFiles = collect($request->file('kyc_documents'))
                    ->map(fn($file) => $file->store('drivers/kyc_documents', 'public'))
                    ->toArray();

                $driverData['kyc_documents'] = json_encode($kycFiles);
            }

            // Create driver
            $driver = Driver::create($driverData);

            // Notify admins
            User::whereHas('roles', fn($q) => $q->where('name', 'admin'))
                ->get()
                ->each(fn($admin) => $admin->notify(new DriverCreated($driver)));

            DB::commit();

            return redirect()->route('admin.drivers.index')->with('success', 'Driver created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to create driver: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver): View
    {
        $driver->load(['user', 'transporter', 'trucks', 'bookings', 'kycVerifications']);
        return view('admin.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver): View
    {
        $driver->load(['user', 'transporter']);
        $transporters = Transporter::active()->get();

        return view('admin.drivers.form', compact('driver', 'transporters'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Update associated user
            $driver->user->update([
                'name' => "{$validated['first_name']} {$validated['last_name']}",
                'first_name'   => $validated['first_name'],
                'last_name'    => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
            ]);

            // Handle updated uploads
            if ($request->hasFile('profile_photo')) {
                if ($driver->user->profile_photo) Storage::disk('public')->delete($driver->user->profile_photo);
                $driver->user->profile_photo = $request->file('profile_photo')->store('drivers/photos', 'public');
            }

            if ($request->hasFile('license_photo')) {
                if ($driver->license_photo) Storage::disk('public')->delete($driver->license_photo);
                $driver->license_photo = $request->file('license_photo')->store('drivers/documents', 'public');
            }

            if ($request->hasFile('medical_certificate_photo')) {
                if ($driver->medical_certificate_photo) Storage::disk('public')->delete($driver->medical_certificate_photo);
                $driver->medical_certificate_photo = $request->file('medical_certificate_photo')->store('drivers/documents', 'public');
            }

            if ($request->hasFile('kyc_documents')) {
                // Optionally clear existing files if needed
                $kycFiles = collect($request->file('kyc_documents'))
                    ->map(fn($file) => $file->store('drivers/kyc_documents', 'public'))
                    ->toArray();
                $driver->kyc_documents = json_encode($kycFiles);
            }
            $driverData = collect($validated)
                ->except(['first_name', 'last_name', 'email', 'password', 'profile_photo', 'phone_number'])
                ->merge([
                    'user_id' => $driver->user->id,
                    'profile_photo' => $driver->user->profilePhotoPath,
                    'license_photo' => $driver->licensePhotoPath,
                    'medical_certificate_photo' => $driver->medicalPhotoPath,
                ])
                ->toArray();

            $driver->update($driverData);

            DB::commit();

            return redirect()->route('admin.drivers.index')->with('success', 'Driver updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update driver: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        try {
            // Delete associated files
            if ($driver->profile_photo) {
                Storage::disk('public')->delete($driver->profile_photo);
            }
            if ($driver->license_photo) {
                Storage::disk('public')->delete($driver->license_photo);
            }
            if ($driver->medical_certificate_photo) {
                Storage::disk('public')->delete($driver->medical_certificate_photo);
            }

            // Delete driver and associated user
            $driver->user->delete();
            $driver->delete();

            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete driver: ' . $e->getMessage());
        }
    }

    /**
     * Update driver status.
     */
    public function updateStatus(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended,expired,pending_verification',
            'notes' => 'nullable|string',
        ]);

        $driver->update($validated);

        return back()->with('success', 'Driver status updated successfully.');
    }

    /**
     * Assign driver to truck.
     */
    public function assignToTruck(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
        ]);

        // Remove driver from current truck if any
        $driver->trucks()->update(['driver_id' => null]);

        // Assign to new truck
        $driver->trucks()->where('id', $validated['truck_id'])->update(['driver_id' => $driver->id]);

        return back()->with('success', 'Driver assigned to truck successfully.');
    }
}

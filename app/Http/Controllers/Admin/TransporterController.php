<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransporterRequest;
use App\Http\Requests\UpdateTransporterRequest;
use App\Models\Transporter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TransporterController extends Controller
{
    /**
     * Display a listing of transporters.
     */
    public function index(Request $request): View
    {
        $query = Transporter::with(['user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            if ($request->verification_status === 'pending') {
                $query->where('status', 'pending_verification');
            } elseif ($request->verification_status === 'verified') {
                $query->where('status', 'active');
            }
        }

        $transporters = $query->latest()->paginate(15);

        return view('admin.transporters.index', compact('transporters'));
    }

    /**
     * Show the form for creating a new transporter.
     */
    public function create(): View
    {
        $users = User::all();
        $transporter = null;
        return view('admin.transporters.form', compact('users', 'transporter'));
    }

    /**
     * Store a newly created transporter in storage.
     */
    public function store(StoreTransporterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            // Create user account for transporter
            $user = User::create([
                'name' => $validated['company_name'],
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(12)), // Temporary password
            ]);

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('transporters/logos', 'public');
            }

            // Create transporter record
            $transporter = Transporter::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'registration_number' => $validated['registration_number'],
                'tax_id' => $validated['tax_id'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
                'contact_person_name' => $validated['contact_person_name'],
                'contact_person_phone' => $validated['contact_person_phone'],
                'contact_person_email' => $validated['contact_person_email'],
                'operating_license_number' => $validated['operating_license_number'],
                'operating_license_expiry' => $validated['operating_license_expiry'],
                'insurance_policy_number' => $validated['insurance_policy_number'],
                'insurance_expiry' => $validated['insurance_expiry'],
                'status' => 'active',
                'notes' => $validated['notes'],
                'logo' => $logoPath,
            ]);

            return redirect()->route('admin.transporters.index')
                ->with('success', 'Transporter created successfully.');
        } catch (\Exception $e) {
            // Clean up uploaded files if transporter creation fails
            if ($logoPath) Storage::disk('public')->delete($logoPath);

            return back()->withInput()
                ->with('error', 'Failed to create transporter: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transporter.
     */
    public function show(Transporter $transporter): View
    {
        $transporter->load(['user', 'drivers', 'trucks', 'bookings', 'invoices', 'kycVerifications']);
        return view('admin.transporters.show', compact('transporter'));
    }

    /**
     * Show the form for editing the specified transporter.
     */
    public function edit(Transporter $transporter): View
    {
        $users = User::all();

        return view('admin.transporters.form', compact('transporter', 'users'));
    }

    /**
     * Update the specified transporter in storage.
     */
    public function update(UpdateTransporterRequest $request, Transporter $transporter): RedirectResponse
    {
        $validated = $request->validated();

        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                if ($transporter->logo) {
                    Storage::disk('public')->delete($transporter->logo);
                }
                $validated['logo'] = $request->file('logo')->store('transporters/logos', 'public');
            }

            // Update transporter
            $transporter->update($validated);

            // Update user name if changed
            $transporter->user->update([
                'name' => $validated['company_name'],
                'email' => $validated['email'],
            ]);

            return redirect()->route('admin.transporters.index')
                ->with('success', 'Transporter updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update transporter: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified transporter from storage.
     */
    public function destroy(Transporter $transporter): RedirectResponse
    {
        try {
            // Delete associated files
            if ($transporter->logo) {
                Storage::disk('public')->delete($transporter->logo);
            }

            // Delete transporter and associated user
            $transporter->user->delete();
            $transporter->delete();

            return redirect()->route('admin.transporters.index')
                ->with('success', 'Transporter deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete transporter: ' . $e->getMessage());
        }
    }

    /**
     * Update transporter status.
     */
    public function updateStatus(Request $request, Transporter $transporter): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended,pending_verification',
            'notes' => 'nullable|string',
        ]);

        $transporter->update($validated);

        return back()->with('success', 'Transporter status updated successfully.');
    }

    /**
     * Suspend transporter account.
     */
    public function suspend(Transporter $transporter): RedirectResponse
    {
        $transporter->update(['status' => 'suspended']);

        return back()->with('success', 'Transporter account suspended successfully.');
    }

    /**
     * Reactivate transporter account.
     */
    public function reactivate(Transporter $transporter): RedirectResponse
    {
        $transporter->update(['status' => 'active']);

        return back()->with('success', 'Transporter account reactivated successfully.');
    }
}

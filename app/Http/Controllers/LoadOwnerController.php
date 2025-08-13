<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoadOwnerRequest;
use App\Http\Requests\UpdateLoadOwnerRequest;
use App\Models\LoadOwner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LoadOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loadOwners = LoadOwner::with('user')->latest()->paginate(20);
        return view('admin.load_owners.index', compact('loadOwners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.load_owners.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoadOwnerRequest $request): RedirectResponse
    {
        DB::transaction(
            function () use ($request) {
                // 1. Create User
                $user = User::create([
                    'name' => $request->name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'gender' => $request->gender,
                    'nrc' => $request->nrc,
                    'address' => $request->address,
                    'profile_image_url' => $request->profile_image_url,
                    'role' => $request->role,
                    'password' => bcrypt($request->password),
                ]);

                // 2. Create Load Owner
                $loadOwner = new LoadOwner($request->only([
                    'company_name',
                    'contact_person_name',
                    'contact_person_phone',
                    'contact_person_email',
                    'city',
                    'state',
                    'postal_code',
                    'country',
                    'tax_id',
                    'business_license_number',
                    'business_license_expiry',
                    'status',
                    'notes',
                ]));

                $loadOwner->user_id = $user->id;

                // 3. Upload logo if exists
                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('logos', 'public');
                    $loadOwner->logo = $logoPath;
                }

                // 4. Upload documents (as JSON array of paths)
                if ($request->hasFile('documents')) {
                    $documentPaths = [];
                    foreach ($request->file('documents') as $doc) {
                        $documentPaths[] = $doc->store('documents', 'public');
                    }
                    $loadOwner->documents = json_encode($documentPaths);
                }

                // 5. Save load owner
                $loadOwner->save();
            }
        );

        return redirect()->route('admin.load_owners.index')->with('success', 'Load Owner created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(LoadOwner $load_owner)
    {
        $user = $load_owner->user;
        return view('admin.load_owners.show', compact('load_owner', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoadOwner $load_owner)
    {
        $user = $load_owner->user;

        return view('admin.load_owners.form', compact('load_owner', 'user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoadOwnerRequest $request, LoadOwner $load_owner)
    {
        DB::transaction(function () use ($request, $load_owner) {
            // 1. Update user
            $user = $load_owner->user;

            $user->update([
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'nrc' => $request->nrc,
                'address' => $request->address,
                'profile_image_url' => $request->profile_image_url,
                'role' => $request->role,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => bcrypt($request->password)]);
            }

            // 2. Update load owner
            $load_owner->fill($request->only([
                'company_name',
                'contact_person_name',
                'contact_person_phone',
                'contact_person_email',
                'city',
                'state',
                'postal_code',
                'country',
                'tax_id',
                'business_license_number',
                'business_license_expiry',
                'status',
                'notes',
            ]));

            // 3. Replace logo if uploaded
            if ($request->hasFile('logo')) {
                if ($load_owner->logo) {
                    Storage::disk('public')->delete($load_owner->logo);
                }
                $load_owner->logo = $request->file('logo')->store('logos', 'public');
            }

            // 4. Replace documents if uploaded
            if ($request->hasFile('documents')) {
                if ($load_owner->documents) {
                    foreach (json_decode($load_owner->documents) as $oldDoc) {
                        Storage::disk('public')->delete($oldDoc);
                    }
                }

                $paths = [];
                foreach ($request->file('documents') as $doc) {
                    $paths[] = $doc->store('documents', 'public');
                }
                $load_owner->documents = json_encode($paths);
            }

            $load_owner->save();
        });

        return redirect()->route('admin.load_owners.index')->with('success', 'Load Owner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoadOwner $load_owner)
    {
        $load_owner->delete();
        $load_owner->user()->delete();

        return redirect()->route('admin.load_owners.index')
            ->with('success', 'Load Owner deleted successfully.');
    }

    public function toggleStatus(LoadOwner $loadOwner)
    {
        $loadOwner->status = $loadOwner->status === 'active' ? 'inactive' : 'active';
        $loadOwner->save();

        return redirect()->back()->with('success', 'Load owner status updated successfully.');
    }
    public function suspend(LoadOwner $loadOwner)
    {
        $loadOwner->status = 'suspended';
        $loadOwner->save();

        return redirect()->back()->with('success', 'Load owner suspended successfully.');
    }
}

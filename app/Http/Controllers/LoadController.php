<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoadRequest;
use App\Http\Requests\UpdateLoadRequest;
use App\Models\Assignment;
use App\Models\Driver;
use App\Models\Load;
use App\Models\LoadOwner;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statuses = ['pending', 'assigned', 'in_transit', 'delivered', 'cancelled', 'completed'];
        $selection = $request->query('status', 'all');

        $loads = Load::when(in_array($selection, $statuses), function ($q) use ($selection) {
            return $q->where('status', $selection);
        })
            ->with(['truck.driver', 'loadOwner'])
            ->orderBy('created_at', 'desc')
            ?->paginate(15)
            ->withQueryString();

        return view('admin.loads.index', compact('loads', 'selection', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $drivers = Driver::where('status', 'available')->get();
        $trucks = Truck::where('status', 'available')->get();
        $loadOwners = LoadOwner::with('user')->get()->mapWithKeys(function ($loadOwner) {
            return [$loadOwner->id => $loadOwner->user->name ?? 'Unknown'];
        });
        return view('admin.loads.form', compact('drivers', 'trucks', 'loadOwners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoadRequest $request)
    {
        $validated = $request->validated();

        $load = Load::create($validated);

        return redirect()->route('admin.loads')->with('success', 'Load created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Load $load)
    {
        $load->loadMissing(['truck.driver', 'loadOwner']);
        $drivers = Driver::with('user')->get()->mapWithKeys(function ($drivers) {
            return [$drivers->id => $drivers->user->name ?? 'Unknown'];
        });

        $selection = $request->query('status', 'all');

        return view('admin.loads.show', compact('load', 'selection', 'drivers'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Load $load)
    {
        $drivers = Driver::where('status', 'available')->get();
        $trucks = Truck::where('status', 'available')->get();
        $loadOwners = LoadOwner::with('user')->get()->mapWithKeys(function ($loadOwner) {
            return [$loadOwner->id => $loadOwner->user->name ?? 'Unknown'];
        });
        return view('admin.loads.form', compact('load', 'drivers', 'trucks', 'loadOwners'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoadRequest $request, Load $load)
    {
        $data = $request->validated();
        $load->update($data);

        return redirect()->back()->with('success', 'Load updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Load $load)
    {
        $load->delete();
        return redirect()->route('admin.loads.index')->with('success', 'Load deleted successfully');
    }
    public function assign(Request $request, Load $load)
    {
        $data = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'truck_id' => 'required|exists:trucks,id',
        ]);

        $load->update([
            'status' => 'assigned',
            'assigned_driver_id' => $data['driver_id'],
            'assigned_truck_id' => $data['truck_id'],
        ]);

        return redirect()->back()->with('success', 'Load assigned successfully');
    }

    public function matchSuggestions(Load $load)
    {
        // Placeholder for real logic
        $availableTrucks = Truck::where('status', 'available')->take(5)->get();
        $availableDrivers = Driver::where('status', 'available')->take(5)->get();

        return view('admin.loads.match-suggestions', compact('load', 'availableTrucks', 'availableDrivers'));
    }
    public function updateStatus(Request $request, Load $load)
    {
        DB::beginTransaction(function () use ($load, $request) {
            try {
                $request->validate([
                    'action' => 'required|in:assign,cancel',
                    'cancel_reason' => 'required_if:action,cancel|string|nullable',
                ]);

                $data = ['status' => $request->action];

                if ($request->action === 'assign_driver') {
                    Assignment::updateOrCreate(
                        ['load_id' => $load->id],
                        ['driver_id' => $request->driver_id,]
                    );
                    $data['status'] = 'assigned';
                }

                if ($request->action === 'reject') {
                    $data['cancel_reason'] = $request->cancel_reason;
                }

                $load->update($data);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Failed to update load status: ' . $e->getMessage()]);
            }
        });
        return redirect()->route('admin.loads.show', $load)->with('success', 'Load status updated successfully.');
    }
}

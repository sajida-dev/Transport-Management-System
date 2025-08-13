<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;

class TransporterTruckController extends Controller
{
    public function allTrucks()
    {
        // This method is used to fetch all trucks for a transporter
        // It can be used in the sidebar or other places where all trucks are needed

        $trucks = Truck::with('driver', 'transporter')->latest()->get();

        return view('admin.transporters.trucks.allTrucks', compact('trucks'));
    }
    public function index(Transporter $transporter)
    {
        $selection = request('selection', 'all');

        $query = $transporter->trucks()->with('driver')->latest();

        if ($selection !== 'all') {
            $query->where('status', $selection);
        }

        $trucks = $query->get();

        return view('admin.transporters.trucks.index', compact('transporter', 'trucks', 'selection'));
    }

    public function create(Transporter $transporter)
    {
        $drivers = Driver::with('user')
            ->where('transporter_id', $transporter->id)
            ->get()
            ->mapWithKeys(function ($driver) {
                return [$driver->id => optional($driver->user)->first_name];
            });

        return view('admin.transporters.trucks.create', compact('transporter', 'drivers'));
    }

    public function store(StoreTruckRequest $request, Transporter $transporter)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['transporter_id'] = $transporter->id;

            // Upload photo if available
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('trucks/photos', 'public');
            }

            // Upload documents if available
            if ($request->hasFile('documents')) {
                $documents = [];
                foreach ($request->file('documents') as $file) {
                    $documents[] = $file->store('trucks/documents', 'public');
                }
                $data['documents'] = json_encode($documents);
            }

            $truck = Truck::create($data);

            DB::commit();

            return redirect()
                ->route('admin.transporters.trucks.index', $transporter)
                ->with('success', 'Truck added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Truck store error: ' . $e->getMessage());

            return back()->with('error', 'Failed to add truck. Please try again.');
        }
    }

    public function show(Transporter $transporter, Truck $truck)
    {
        return view('admin.transporters.trucks.show', compact('transporter', 'truck'));
    }

    public function edit(Transporter $transporter, Truck $truck)
    {
        $drivers = Driver::with('user')
            ->where('transporter_id', $transporter->id)
            ->get()
            ->mapWithKeys(function ($driver) {
                return [$driver->id => optional($driver->user)->first_name];
            });

        return view('admin.transporters.trucks.edit', compact('transporter', 'truck', 'drivers'));
    }

    public function update(UpdateTruckRequest $request, Transporter $transporter, Truck $truck)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Upload photo if available
            if ($request->hasFile('photo')) {
                if ($truck->photo) {
                    Storage::disk('public')->delete($truck->photo);
                }
                $data['photo'] = $request->file('photo')->store('trucks/photos', 'public');
            }

            // Upload new documents
            if ($request->hasFile('documents')) {
                if ($truck->documents) {
                    foreach (json_decode($truck->documents) as $doc) {
                        Storage::disk('public')->delete($doc);
                    }
                }

                $documents = [];
                foreach ($request->file('documents') as $file) {
                    $documents[] = $file->store('trucks/documents', 'public');
                }
                $data['documents'] = json_encode($documents);
            }

            $truck->update($data);

            DB::commit();

            return redirect()
                ->route('admin.transporters.trucks.index', $transporter)
                ->with('success', 'Truck updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Truck update error: ' . $e->getMessage());

            return back()->with('error', 'Failed to update truck.');
        }
    }

    public function destroy(Transporter $transporter, Truck $truck)
    {
        try {
            DB::beginTransaction();

            // Delete associated files
            if ($truck->photo) {
                Storage::disk('public')->delete($truck->photo);
            }

            if ($truck->documents) {
                foreach (json_decode($truck->documents) as $doc) {
                    Storage::disk('public')->delete($doc);
                }
            }

            $truck->delete();

            DB::commit();

            return redirect()
                ->route('admin.transporters.trucks.index', $transporter)
                ->with('success', 'Truck deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Truck delete error: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete truck.');
        }
    }
}

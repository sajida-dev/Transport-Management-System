<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\BookingRequest;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Load;
use App\Models\Transporter;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selection = $request->query('selection', 'all');
        $query = Booking::with(['truck', 'customer', 'transporter']);
        // Apply status filter if not 'all'
        if ($selection !== 'all') {
            switch ($selection) {
                case 'pending':
                    $query->where('order_status', 'pending');
                    break;
                case 'confirmed':
                    $query->where('order_status', 'approved');
                    break;
                case 'in_transit':
                    $query->where('order_status', 'in_transit');
                    break;
                case 'completed':
                    $query->where('order_status', 'completed');
                    break;
                case 'cancelled':
                    $query->where('order_status', 'cancelled');
                    break;
                default:
                    // Fallback: no filtering or throw error
                    break;
            }
        }
        $bookings = $query->orderBy('added_date', 'desc')->paginate(20);

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'selection' => $selection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Passing options for selects: id => name arrays
        $loads = Load::pluck('title', 'id')->toArray();
        $transporters = Transporter::pluck('company_name', 'id')->toArray();
        $trucks = Truck::all()->mapWithKeys(function ($truck) {
            $label = "{$truck->registration_number} - {$truck->make} {$truck->model} ({$truck->year}, {$truck->capacity_tonnes}T)";
            return [$truck->id => $label];
        })->toArray();
        $drivers = Driver::with('user')->get()->mapWithKeys(function ($driver) {
            return [$driver->id => $driver->user->name];
        })->toArray();
        $users = User::pluck('name', 'id')->toArray();

        return view('admin.bookings.form', compact('loads', 'transporters', 'trucks', 'drivers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingRequest $request)
    {
        $request['booking_number'] = Booking::generateBookingNumber();
        Booking::create($request->validated());

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $loads = Load::pluck('title', 'id')->toArray();
        $transporters = Transporter::pluck('company_name', 'id')->toArray();
        $trucks = Truck::all()->mapWithKeys(function ($truck) {
            $label = "{$truck->registration_number} - {$truck->make} {$truck->model} ({$truck->year}, {$truck->capacity_tonnes}T)";
            return [$truck->id => $label];
        })->toArray();
        $drivers = Driver::with('user')->get()->mapWithKeys(function ($driver) {
            return [$driver->id => $driver->user->name];
        })->toArray();
        $users = User::pluck('name', 'id')->toArray();

        return view('admin.bookings.form', compact('booking', 'loads', 'transporters', 'trucks', 'drivers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}

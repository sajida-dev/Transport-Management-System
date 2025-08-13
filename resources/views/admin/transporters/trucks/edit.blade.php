@extends('admin.layouts.app')
@section('title', 'Edit Truck Management')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Truck</h1>
    @include('admin.transporters.trucks.form', ['truck' => $truck, 'transporter' => $transporter, 'drivers' => $drivers])
@endsection

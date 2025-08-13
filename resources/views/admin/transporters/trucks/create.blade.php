@extends('admin.layouts.app')
@section('title', 'Create Truck Management')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add New Truck</h1>
@include('admin.transporters.trucks.form', ['transporter' => $transporter, 'drivers' => $drivers])
@endsection
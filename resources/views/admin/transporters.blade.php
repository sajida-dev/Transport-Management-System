@extends('admin.main.layout')

@section('content')

<script src='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css' rel='stylesheet' />
<style>
    body { margin: 0; padding: 0; }
    #map { width: 100%; max-width: 100%; height: 400px; }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">




<script>
    // Function to initialize all data and listeners
function initializeDashboard() {
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');



// Set up the real-time listener specifically for pending orders

setupRealTimePendingTransportListener('{{$status}}', csrfToken);
    

}
    
    // Initialize the dashboard data and listeners on window load
    window.onload = initializeDashboard;
    </script>

    
    
                <div class="container-fluid">
                    <h3 class="text-dark mb-4">Trucks</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">User Info</p>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 text-nowrap">
                                    <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label class="form-label">Show&nbsp;<select class="d-inline-block form-select form-select-sm">
                                                <option value="10" selected="">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>&nbsp;</label></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-md-end dataTables_filter" id="dataTable_filter"><label class="form-label"><input type="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search"></label></div>
                                </div>
                            </div>
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone#</th>
                                            <th>Gender</th>
                                            <th>Verification</th>
                                            <th>Joined Date</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody id="loadOwnersList">
                                        <!-- Rows will be dynamically added here by JavaScript -->
                                    </tbody>
                                </table>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of 27</p>
                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="page-item disabled"><a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a></li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            @endsection

@extends('admin.main.layout')

@section('content')
<div class="container-fluid">
    <h3 class="text-dark mb-4">Load Owners</h3>
    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">User Info</p>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2" role="grid">
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
                        @foreach($loadOwners as $owner)
                        <tr>
                            <td>
                                <img class="rounded-circle me-2" width="30" height="30" src="{{ $owner->profileImage ?? asset('img/avatars/avatar1.jpeg') }}" alt="">
                                {{ $owner->first_name }} {{ $owner->last_name }}
                            </td>
                            <td>{{ $owner->phone_number ?? 'N/A' }}</td>
                            <td>{{ $owner->gender ?? 'N/A' }}</td>
                            <td>{{ $owner->verified ? 'Verified' : 'Not Verified' }}</td>
                            <td>{{ $owner->added_date ? date('Y-m-d H:i:s', $owner->added_date->seconds) : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    setupRealTimePendingLoadOwnersListener(csrfToken);
});
</script>
@endsection

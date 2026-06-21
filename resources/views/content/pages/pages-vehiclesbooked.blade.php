@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Booked Vehicles')

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection

@section('vendor-script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {

    var vehiclesTable = $('#vehiclesTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        dom: "<'row mb-3'<'col-md-6'l><'col-md-6 text-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Search vehicles...',
        },
        columnDefs: [{
            orderable: false,
            targets: -1
        }]
    });
});
</script>
@endsection

@section('content')



<!-- Table -->
<div class="card">

    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center py-1 mb-2">
            <div>
                <h4 class="mb-0">
                    <span class="text-muted fw-light">
                        eCommerce /
                    </span>
                    Booked Vehicles
                </h4>
            </div>
        </div>

    </div>

    <div class="card-body">

       

            <table id="vehiclesTable" class="table-bordered table table-hover table-striped align-middle w-100">
                <thead class="table-light">

                    <tr>
                        <th>Vehicle Name</th>
                        <th>Vehicle Type</th>
                        @if(Auth::user()->role == 'admin')
                        <th>Branch</th>
                        @endif

                        <th>Registration Number</th>

                        <th>Rate Hr</th>
                        <th>Rate 8Hrs</th>
                        <th>Rate / Day</th>
                        <th>Vehicle Image</th>




                    </tr>

                </thead>

                <tbody>

                    @foreach($vehicles as $vehicle)

                    <tr>
                        <td>{{ ucfirst($vehicle->vehicle_name) }}</td>
                        <td>{{ ucfirst($vehicle->vehicle_type) }}</td>
                        @if(Auth::user()->role == 'admin')
                        <td>{{ ucfirst($vehicle->branch) }}</td>
                        @endif
                        <td>{{ strtoupper($vehicle->registration_number) }}</td>


                        <td>{{ $vehicle->rate_per_hour }}</td>
                        <td>{{ $vehicle->rate_max_8hour }}</td>
                        <td>{{ $vehicle->rate_per_day }}</td>
                        <td>
                            <img src="{{ asset($vehicle->vehicle_image) }}" class="rounded" width="60">
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>

    

    </div>

</div>



@endsection
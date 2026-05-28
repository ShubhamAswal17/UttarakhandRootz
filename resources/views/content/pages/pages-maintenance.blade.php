@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Maintenance')



@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

@endsection

@section('vendor-script')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection
@section('page-script')

<script>
document.addEventListener('DOMContentLoaded', function() {

    if ($('#MaintenanceTable').length) {

        $('#MaintenanceTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],

            language: {
                search: '',
                searchPlaceholder: 'Search maintenance records...'
            },

            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });
    }

});
</script>
@endsection

@section('content')
<!-- Table -->
<div class="card">

    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">

            <div>
                <h4 class="mb-0">

                    Maintenance Inventory
                </h4>
            </div>



        </div>


    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="MaintenanceTable" class="table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>Service ID</th>
                        <th>User Name</th>
                        <th>Bike Name</th>
                        <th>Registration number</th>
                        <th>Insurence upto</th>
                        <th>Service Date</th>
                        <th>Service Return Date</th>
                        <th>Service Issue</th>
                        <th>Service Amount</th>
                        <th>Service Status</th>
                        <th>Update Status</th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($vehicles as $vehicle)
                    <tr>
                        <td>1</td>
                        <td>{{ auth()->user()->name }}</td>
                        <td>{{ $vehicle->vehicle_name }}</td>
                        <td>{{ $vehicle->registration_number }}</td>
                        <td>{{ $vehicle->insurence_upto }}</td>
                        <td>Service Date</td>
                        <td>Service Return Date</td>
                        <td>Service Issue</td>
                        <td>Service Amount</td>
                        <td>Service Status</td>
                        <td>
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#addVehicleOffcanvas" data-vehicle-id="{{ $vehicle->id }}">

                                update status

                            </button>

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

        <td>Maintenance Status</td>

        </tr>

        </tbody>

        </table>

    </div>

</div>

</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addVehicleOffcanvas" style="width:500px; overflow-y:auto;">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            Update Maintenance Status
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="UpdateMaintenanceForm">
            @csrf
            <input type="hidden" id="RowIndex">

            <div class="row g-3">

                <!-- Customer Name -->
                <div class="col-6 col-md-12">
                    <label class="form-label">User Name</label>

                    <input type="text" name="customerName" class="form-control" id="customerName"
                        value="{{ auth()->user()->name }}" readonly required>

                </div>

                <!-- Vehicle Name -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Vehicle Name</label>
                    <input type="hidden" id="vehicleId" value="{{ $vehicle->id }}" name="vehicleId"
                        class="form-control">
                    <input type="text" id="vehicle_name" class="form-control" value="{{ $vehicle->vehicle_name }}"
                        readonly>
                </div>


                <!-- Service date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Date</label>

                    <input type="datetime-local" name="startDateTime" class="form-control" id="startDateTime"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <!-- Service Return date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Return Date</label>

                    <input type="datetime-local" name="returnDateTime" class="form-control" id="returnDateTime"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
                <!-- Service Issue -->
                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Service Issue
                    </label>

                    <textarea name="issue" class="form-control" id="issue" rows="3"></textarea>

                </div>

                <!-- Maintenance Status -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="serviceStatus" id="status" class="form-select" required>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="maintenance">
                            Maintenance
                        </option>

                        <option value="rented">
                            available
                        </option>

                    </select>

                </div>
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Service Amount
                    </label>

                    <input type="number" name="serviceAmount" class="form-control" id="serviceAmount"
                        placeholder="Service Amount">

                </div>

            </div>

            <div class="row mt-4">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="offcanvas">
                        Cancel
                    </button>
                </div>

                <div class="col-6">
                    <button type="submit" id="saveCustomerBtn" class="btn btn-primary w-100">
                        Maintenance Status
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>


@endsection
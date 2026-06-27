@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Maintenance')



@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection

@section('vendor-script')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('page-script')

<script>
document.addEventListener('DOMContentLoaded', function() {

    if ($('#MaintenanceTable').length) {

        $('#MaintenanceTable').DataTable({
            pageLength: 10,

            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']],
            dom: "<'row mb-3'<'col-md-6'l><'col-md-6 text-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",

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

$(document).on('click', '.update-maintenance-btn', function(e) {
    e.preventDefault();
    var maintenanceid = $(this).data('maintenance-id');
    $.ajax({

        url: '/maintenance/edit/' + maintenanceid,

        method: 'GET',

        success: function(response) {
            console.log(response);
            $('#RowIndex').val(response.maintenance.id);
            $('#user_name').val(response.maintenance.user_name);
            $('#update_VehicleName').val(response.maintenance.vehicle_name);
            $('#service_Date').val(response.maintenance.service_date);
            $('#service_Return').val(response.maintenance.return_date);
            $('#service_Issue').val(response.maintenance.service_issue);
            $('#vendor_name').val(response.maintenance.vendor_name);
            $('#payment_type').val(response.maintenance.payment_type);
            $('#payment_status').val(response.maintenance.payment_status);
            if (response.maintenance.bill_image) {
                $('#current_bill_image').html('<a href="/' + response.maintenance.bill_image +
                    '" target="_blank">Current bill image</a>');
            } else {
                $('#current_bill_image').html('');
            }
            $('#service_Status').val(response.maintenance.service_status);
            $('#service_Amount').val(response.maintenance.service_amount);
        },

        error: function(xhr) {

            console.log(xhr);

            alert('Failed to fetch maintenance details.');
        }
    });


});

$('#UpdateMaintenanceForm').submit(function(e) {
    e.preventDefault();

    var maintenanceid = $('#RowIndex').val();
    var form = document.getElementById('UpdateMaintenanceForm');
    var formData = new FormData(form);

    $.ajax({
        url: '/maintenance/update/' + maintenanceid,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                }).then(() => {
                    location.reload();
                });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'please fill all input',
                    text: xhr.responseJSON?.message || 'please fill all input.'
                });
            }
    });
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

            <table id="MaintenanceTable" class="table-bordered table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        @if(Auth::user()->role === 'admin')
                        <th>branch</th>
                        <th>Employee Name</th>
                        @endif
                        <th>Bike Name</th>
                        <th>Registration number</th>
                        <th>Insurance upto</th>
                        <th>Service Date</th>
                        <th>Service Issue</th>
                        <th>Service Return Date</th>
                        <th>Vendor Name</th>
                        <th>Bill Image</th>
                        <th>Payment Type</th>
                        <th>Service Amount</th>
                        <th>Service Status</th>
                        <th>Payment Status</th>
                        @if(Auth::user()->role !== 'admin')
                        <th>Action</th>
                        @endif
                    </tr>

                </thead>

                <tbody>
                    @foreach ($maintenance as $maintenance_data)
                    <tr>
                        @if(Auth::user()->role === 'admin')
                        <td>{{ ucfirst($maintenance_data->branch) }}</td>
                        <td>{{ ucfirst($maintenance_data->user_name) }}</td>
                        @endif
                        <td>{{ ucfirst($maintenance_data->vehicle_name) }}</td>
                        <td>{{ strtoupper($maintenance_data->registration_number) }}</td>
                        <td>{{ date('d/m/Y', strtotime($maintenance_data->insurance_upto)) }}</td>
                        <td>{{ date('d/m/Y', strtotime($maintenance_data->service_date)) }}</td>
                        <td>{{ ucfirst($maintenance_data->service_issue) }}</td>
                        <td>{{ $maintenance_data->return_date ? date('d/m/Y', strtotime($maintenance_data->return_date)) : '' }}
                        </td>
                        <td>{{ ucfirst($maintenance_data->vendor_name) }}</td>
                        <td>
                            @if($maintenance_data->bill_image)
                            <a href="/{{ $maintenance_data->bill_image }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>{{ ucfirst($maintenance_data->payment_type) }}</td>
                        <td>{{ $maintenance_data->service_amount }}</td>
                        <td>{{ ucfirst($maintenance_data->service_status) }}</td>
                        <td>{{ ucfirst($maintenance_data->payment_status) }}</td>
                        @if(Auth::user()->role !== 'admin')
                        <td>
                            <button class="btn btn-primary update-maintenance-btn" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#addVehicleOffcanvas"
                                data-maintenance-id="{{ $maintenance_data->id }}">
                                Update
                            </button>
                        </td>
                        @endif
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

        <form id="UpdateMaintenanceForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="RowIndex">

            <div class="row g-3">

                <!-- Customer Name -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Employee Name</label>

                    <input type="text" name="user_name" class="form-control" id="user_name" readonly>

                </div>

                <!-- Vehicle Name -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Vehicle Name</label>
                    <input type="text" name="update_VehicleName" id="update_VehicleName" class="form-control" value=""
                        readonly>
                </div>


                <!-- Service date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Date</label>

                    <input type="date" name="service_Date" class="form-control" id="service_Date"
                        value="{{ now()->format('Y-m-d') }}">
                </div>

                <!-- Service Return date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service Return Date</label>

                    <input type="date" name="service_Return" class="form-control" id="service_Return"
                        value="{{ now()->format('Y-m-d') }}">
                </div>
                <!-- Service Issue -->
                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Service Issue
                    </label>

                    <textarea name="service_Issue" id="service_Issue" class="form-control" rows="3"></textarea>

                </div>

                <!-- Vendor Name -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Vendor Name</label>
                    <input type="text" name="vendor_name" id="vendor_name" class="form-control"
                        placeholder="Vendor Name">
                </div>

                <!-- Bill Image -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Bill Image</label>
                    <input type="file" name="bill_image" id="bill_image" class="form-control">
                    <div id="current_bill_image" class="mt-2"></div>
                </div>

                <!-- Payment Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-select">
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <!-- Payment Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>
                        <option value="Partial">Partial</option>
                    </select>
                </div>

                <!-- Maintenance Status -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="service_Status" id="service_Status" class="form-select">

                        <option value="Pending">
                            Pending
                        </option>

                        <option value="In Progress">
                            In Progress
                        </option>

                        <option value="Completed">
                            Completed
                        </option>

                    </select>

                </div>
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Service Amount
                    </label>

                    <input type="text" name="service_Amount" class="form-control" id="service_Amount"
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
                        Update
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>


@endsection
@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'bookings')


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

    if ($('#BookingTable').length) {

        $('#BookingTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],

            language: {
                search: '',
                searchPlaceholder: 'Search bookings...'
            },

            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });
    }

});


$(document).ready(function() {
    $('#UpdateBookingForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        
        $.ajax({
            url:{{('bookings-update/id')}},
            method:'POST',
            data:formData,
             success: function(response) {
                alert(' Update successfully!');
                location.reload();
            },
            error: function(xhr) {
                alert('Error adding bookings. Please try again.');
            }
            
        })


    })
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

                    Bookings Inventory
                </h4>
            </div>
        </div>


    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="BookingTable" class="table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>Booking id</th>
                        <th>Customer Name</th>
                        <th>Vehicle id</th>
                        <th>Vehicle Name</th>
                        <th>Registration Number</th>
                        <th>Rent Hours</th>
                        <th>Rent Price</th>
                        <th>Booking Date</th>
                        <th>Return Date</th>
                        <th>Booking Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($bookings as $booking)


                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->customer->customer_name }}</td>
                        <td>{{ $booking->vehicle->id }}</td>
                        <td>{{ $booking->vehicle->vehicle_name }}</td>
                        <td>{{ $booking->vehicle->registration_number }}</td>
                        <td>{{ $booking->customer->rental_type }}</td>
                        <td>{{ $booking->customer->price }}</td>
                        <td>{{ $booking->booking_date }}</td>
                        <td>{{ $booking->return_date }}</td>
                        <td>{{ $booking->status }}</td>
                        <td>
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#addVehicleOffcanvas" data-booking-id="{{ $booking->id }}"> Update Bookings</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addVehicleOffcanvas" style="width:500px; overflow-y:auto;">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            Update Booking
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="UpdateBookingForm">
            @csrf
            <input type="hidden" id="RowIndex">

            <div class="row g-3">

                <!-- Customer Name -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Customer Name</label>

                    <input type="text" name="customerName" class="form-control" id="customerName" value="John Doe"
                        readonly required>
                </div>

                <!-- Vehicle Name -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Vehicle Name</label>

                    <input type="text" id="vehicleName" name="vehicleName" class="form-control"
                        value="Royal Enfield Classic 350" readonly>
                </div>
                <!-- Registration_number -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Vehicle Registration</label>

                    <input type="text" id="registrationNumber" name="registrationNumber" class="form-control"
                        value="ABC-1234" readonly>
                </div>

                <!-- Start date -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date Time</label>

                    <input type="datetime-local" name="startDateTime" class="form-control" id="startDateTime"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <!-- End Date -->
                <div class="col-6 col-md-12">
                    <label class="form-label">End Date Time</label>

                    <input type="datetime-local" name="return_date" class="form-control" id="endDateTime"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <!-- Booking Status -->
                <div class="col-6 col-md-12">
                    <label class="form-label">Booking Status</label>

                    <input type="text" name="bookingStatus" class="form-control" id="bookingStatus" value="{{ $booking->status }}">
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
                        Update Booking
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>


@endsection
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
    $('.updateBookingBtn').on('click', function(e) {
        e.preventDefault();

        let bookingId = $(this).data('booking-id');
        // Debugging line to check if bookingId is correct
        $.ajax({
            url: '/bookings/update/' + bookingId,
            type: 'GET',
            success: function(response) {
                console.log(response);
                $('#customerName').val(response.customer.customer_name);
                $('#vehicleName').val(response.vehicle.vehicle_name);
                $('#registrationNumber').val(response.vehicle.registration_number);
                $('#booking_date').val(
                    response.booking.booking_date ?
                    response.booking.booking_date.replace(' ', 'T').slice(0, 16) :
                    ''
                );

                $('#return_date').val(
                    response.booking.return_date ?
                    response.booking.return_date.replace(' ', 'T').slice(0, 16) :
                    ''
                );
                $('#bookingStatus').val(response.booking.status);
                $('#RowIndex').val(response.booking.id);
            },
            error: function(xhr) {
                alert('Error fetching booking data. Please try again.');
            }
        });
    });
});

$(document).ready(function() {
    $('#UpdateBookingForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        var bookingId = $('#RowIndex').val();

        $.ajax({

            url: '/booking/' + bookingId + '/update',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(' Update successfully!');
                location.reload();
            },
            error: function(xhr) {
                console.log(xhr.status);
                console.log(xhr.responseText);
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
                        <!-- <th>Vehicle id</th> -->
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
                        <td>{{ $booking->vehicle->vehicle_name }}</td>
                        <td>{{ $booking->vehicle->registration_number }}</td>
                        <td>{{ $booking->customer->rental_type }}</td>
                        <td>{{ $booking->customer->price }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->return_date)->format('d-m-Y h:i A') }}</td>
                        <td>{{ $booking->status }}</td>
                        <td>
                            <button class="btn btn-primary updateBookingBtn" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#addVehicleOffcanvas" data-booking-id="{{ $booking->id }}"> Update
                            </button>
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
                    <label for="customerName" class="form-label">Customer Name</label>

                    <input type="text" name="updateCustomerName" class="form-control" id="customerName" value="val"
                        readonly required>
                </div>

                <!-- Vehicle Name -->
                <div class="col-6 col-md-12">
                    <label for="vehicleName" class="form-label">Vehicle Name</label>

                    <input type="text" id="vehicleName" name="vehicleName" class="form-control" value="" readonly>
                </div>
                <!-- Registration_number -->
                <div class="col-6 col-md-12">
                    <label for="registrationNumber" class="form-label">Vehicle Registration</label>

                    <input type="text" id="registrationNumber" name="registrationNumber" class="form-control" value=""
                        readonly>
                </div>

                <!-- Start date -->
                <div class="col-6 col-md-12">
                    <label for="booking_date" class="form-label">Booking Date</label>

                    <input type="datetime-local" name="booking_date" class="form-control" id="booking_date"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <!-- End Date -->
                <div class="col-6 col-md-12">
                    <label for="endDateTime" class="form-label">Return Date</label>

                    <input type="datetime-local" name="return_date" class="form-control" id="return_date"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
                <!-- Payment type  -->
                <div class="col-6 col-md-12">
                    <label for="paymentType" class="form-label">Payment Type</label>

                    <select name="paymentType" class="form-select" required id="paymentType">
                        <option value="Cash">Cash
                        </option>
                        <option value="Online">Online
                        </option>
                        <option value="Card">Card
                        </option>
                    </select>

                </div>
                <!-- Booking Status -->
                <div class="col-6 col-md-12">
                    <label for="bookingStatus" class="form-label">Booking Status</label>

                    <select name="status" class="form-select" required id="bookingStatus">
                        <option value="booked">Booked
                        </option>
                        <option value="completed">Completed
                        </option>
                        <option value="hold">Hold
                        </option>

                    </select>

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
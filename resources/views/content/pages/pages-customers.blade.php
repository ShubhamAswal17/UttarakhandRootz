@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Customers')


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

    if ($('#CustomerTable').length) {

        $('#CustomerTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']],
            dom: "<'row mb-3'<'col-md-6'l><'col-md-6 text-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",

            language: {
                search: '',
                searchPlaceholder: 'Search customers...'
            },

            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });
    }

});

// All vehicle data from Laravel
let vehicles = @json($vehicles);


// Registration Number -> Vehicle Details
function getVehicleDetails() {

    let registrationNumber =
        document.getElementById('vehicleDropdown').value;

    let vehicle =
        vehicles.find(v =>
            v.registration_number == registrationNumber
        );

    if (vehicle) {

        // Vehicle Name
        document.getElementById('vehicleName').value =
            vehicle.vehicle_name;

        // Vehicle ID in hidden field
        document.getElementById('vehicleId').value =
            vehicle.id;

        // Reset price
        document.getElementById('vehiclePrice').value = '';
        document.getElementById('rentalType').value = '';
        document.getElementById('rentalHours').value = '';
        showRentalHoursField(false);

    } else {

        document.getElementById('vehicleName').value = '';
        document.getElementById('vehicleId').value = '';
        document.getElementById('vehiclePrice').value = '';
        document.getElementById('rentalHours').value = '';
        showRentalHoursField(false);
    }
}

function showRentalHoursField(show) {
    let container = document.getElementById('rentalHoursContainer');
    if (container) {
        container.style.display = show ? 'block' : 'none';
    }
}

function showRentalDaysField(show) {
    let container = document.getElementById('rentalDaysContainer');
    if (container) {
        container.style.display = show ? 'block' : 'none';
    }
}

// STEP 3
// Rental Type -> Price
function getVehiclePrice() {
    let registrationNumber =
        document.getElementById('vehicleDropdown').value;

    let rentalType =
        document.getElementById('rentalType').value;

    let vehicle =
        vehicles.find(v =>
            v.registration_number == registrationNumber
        );

    if (vehicle) {
        let price = '';

        // Per Hour
        if (rentalType == 'hour') {
            showRentalHoursField(true);
            showRentalDaysField(false);
            document.getElementById('rentalDays').value = '';
            let hours = parseFloat(document.getElementById('rentalHours').value) || 0;
            if (hours > 0) {
                price = (vehicle.rate_per_hour * hours).toFixed(2);
            } else {
                price = '';
            }
        }

        // 12 Hours
        else if (rentalType == '12 hours') {
            showRentalHoursField(false);
            showRentalDaysField(false);
            document.getElementById('rentalHours').value = '';
            document.getElementById('rentalDays').value = '';
            price = vehicle.rate_max_12hour;
        }

        // Per Day
        else if (rentalType == 'day') {
            showRentalDaysField(true);
            let days = parseFloat(document.getElementById('rentalDays').value) || 0;
            if (days > 0) {
                price = (vehicle.rate_per_day * days).toFixed(2);
            } else {
                price = '';
            }
        }

        // No rental type selected
        else {
            showRentalDaysField(false);
            document.getElementById('rentalDays').value = '';
            showRentalHoursField(false);
            document.getElementById('rentalHours').value = '';
        }

        // Set Price
        document.getElementById('vehiclePrice').value =
            price;
    } else {
        document.getElementById('vehiclePrice').value = '';
        showRentalHoursField(false);
        showRentalDaysField(false);
    }
}

function getRegistrationNumbers() {
    let vehicleType =
        document.getElementById('vehicleType').value;

    let dropdown =
        document.getElementById('vehicleDropdown');

    // Reset dropdown
    dropdown.innerHTML =
        '<option value="">Select Registration Number</option>';

    // Reset other fields
    document.getElementById('vehicleName').value = '';
    document.getElementById('vehiclePrice').value = '';
    document.getElementById('rentalType').value = '';
    document.getElementById('rentalHours').value = '';
    document.getElementById('rentalDays').value = '';



    // Filter only AVAILABLE vehicles
    let filteredVehicles = vehicles.filter(v =>
        v.vehicle_type == vehicleType &&
        v.status == 'Available'
    );



    // If no vehicle available
    if (filteredVehicles.length == 0) {
        dropdown.innerHTML =
            '<option value="">No Vehicle Available</option>';

        return;
    }



    // Add registration numbers
    filteredVehicles.forEach(vehicle => {

        dropdown.innerHTML += `
            <option value="${vehicle.registration_number}">
                ${vehicle.registration_number}
            </option>
        `;

    });
}

$(document).ready(function() {
    $('#addCustomerForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $.ajax({
            url: '{{ route("customers-add") }}',
            method: 'POST',
            data: formData,
            dataType: 'json',
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
                    title: 'Please Fill All Input.',
                    text: xhr.responseJSON?.message || 'Please Fill All Input.'
                });
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

                    Customers Inventory
                </h4>
            </div>
            @if(auth()->user()->role == 'manager' || auth()->user()->role == 'employee')
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#addVehicleOffcanvas">
                Add Customer
            </button>
            @endif
        </div>


    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="CustomerTable" class="table-bordered table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>Bill No</th>
                        @if(Auth::user()->role == 'admin')
                        <th>Branch</th>
                        @endif
                        <th>Customer Name</th>
                        <th>Phone Number</th>
                        <th>Email Address</th>
                        <th>Address</th>
                        <th>Id Proof Type</th>
                        <th>Id Proof Number</th>
                        <th>Vehicle Name</th>

                        <th>Licence</th>
                        <th>Registration Number</th>
                        <th>Rent Hours</th>



                    </tr>

                </thead>

                <tbody>

                    @foreach($customers as $customer)

                    <tr>
                        <td>{{ $customer->bill_number }}</td>

                        @if(Auth::user()->role == 'admin')

                        <td>{{ $customer->vehicle?->branch}}</td>

                        @endif

                        <td>{{ ucfirst($customer->customer_name) }}</td>
                        <td>{{ ucfirst($customer->phone_number) }}</td>
                        <td>{{ ucfirst($customer->email) }}</td>
                        <td>{{ ucfirst($customer->address) }}</td>
                        <td>{{ ucfirst($customer->id_proof_type) }}</td>
                        <td>{{ strtoupper($customer->id_proof_number) }}</td>
                        <td>{{ ucfirst($customer->vehicle_name) }}</td>
                        <td>{{ $customer->licence_number }}</td>
                        <td>{{ strtoupper($customer->registration_number) }}</td>
                        @if($customer->rental_type == 'hour')
                        <td>{{ucfirst($customer->rentalHours ) }} Hours</td>
                        @elseif($customer->rental_type == 'day')
                        <td>{{$customer->rentalDays}} {{ ucfirst($customer->rental_type) }}</td>
                        @else
                        <td>{{ ucfirst($customer->rental_type) }}</td>
                        @endif


                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addVehicleOffcanvas" style="width:700px; overflow-y:auto;">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            Add Customer
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="addCustomerForm">
            @csrf
            <input type="hidden" id="RowIndex">

            <div class="row">

                <!-- Customer Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer Name</label>

                    <input type="text" name="customerName" class="form-control" id="customerName" required>
                </div>

                <!-- Phone Number -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>

                    <input type="text" name="phoneNumber" class="form-control" id="phoneNumber" required>
                </div>

                <!-- Email Address -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>

                    <input type="email" name="emailAddress" class="form-control" id="emailAddress" required>
                </div>
                <!--  Address -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>

                    <input type="text" name="address" class="form-control" id="address" required>
                </div>
                <!-- Id Proof Type -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Id Proof Type</label>

                    <select name="idProofType" id="idProofType" class="form-select" required>
                        <option value="Adhar">Adhar</option>
                        <option value="PAN">PAN</option>
                        <option value="Passport">Passport</option>
                    </select>
                </div>

                <!-- Id Proof Number -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Id Proof Number</label>

                    <input type="text" name="idProofNumber" class="form-control" id="idProofNumber" required>

                </div>
                <!--  Licence  Number -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Licence Number</label>

                    <input type="text" name="licenceNumber" class="form-control" id="licenceNumber" required>
                </div>

                <!--  Bill  Number -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bill Number</label>

                    <input type="text" name="billNumber" class="form-control" id="billNumber" required>
                </div>




                {{-- Vehicle Type --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Type</label>

                    <select name="vehicleType" id="vehicleType" class="form-select" onchange="getRegistrationNumbers()"
                        required>

                        <option value="">Select Vehicle Type</option>

                        @foreach($vehicles->unique('vehicle_type') as $type)
                        <option value="{{ $type->vehicle_type }}">
                            {{ $type->vehicle_type }}
                        </option>
                        @endforeach

                    </select>
                </div>



                {{-- Registration Number --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Registration Number
                    </label>

                    <select name="registration_no" id="vehicleDropdown" class="form-select"
                        onchange="getVehicleDetails()" required>

                        <option value="">Select Registration Number</option>

                    </select>

                    <input type="hidden" name="vehicle_id" id="vehicleId">

                </div>



                {{-- Vehicle Name --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Vehicle Name
                    </label>

                    <input type="text" id="vehicleName" name="vehicleName" class="form-control" readonly>

                </div>



                {{-- Rental Hours --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rent Hours
                    </label>

                    <select name="rental_type" class="form-select" id="rentalType" onchange="getVehiclePrice()">

                        <option value="">Select Rental Type</option>

                        <option value="hour">Per Hour</option>
                        <option value="12 hours">12 Hours</option>
                        <option value="day">Per Day</option>

                    </select>

                </div>

                <div class="col-md-6 mb-3" id="rentalHoursContainer" style="display:none;">
                    <label class="form-label">
                        Enter Hours
                    </label>
                    <input type="number" name="rentalHours" id="rentalHours" class="form-control" min="1" step="1"
                        placeholder="Hours" oninput="getVehiclePrice()">
                </div>

                <div class="col-md-6 mb-3" id="rentalDaysContainer" style="display:none;">
                    <label class="form-label">
                        Enter Days
                    </label>
                    <input type="number" name="rentalDays" id="rentalDays" class="form-control" min="1" step="1"
                        placeholder="Days" oninput="getVehiclePrice()">
                </div>


                {{-- Price --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Price
                    </label>

                    <input type="text" name="vehiclePrice" id="vehiclePrice" class="form-control" readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Action
                    </label>

                    <div class="row">
                        <div class="col-md-3 mb-1 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas"
                                fdprocessedid="wudsz">
                                Cancel
                            </button>
                        </div>
                        <div class="col-md-9 mb-1">
                            <button type="submit" id="saveCustomerBtn" class="btn btn-primary waves-effect waves-light"
                                fdprocessedid="lfy86e">
                                Save Customer
                            </button>
                        </div>
                    </div>
                </div>


            </div>

        </form>

    </div>

</div>


@endsection
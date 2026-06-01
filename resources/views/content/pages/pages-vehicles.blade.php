@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Vehicles')

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
@endsection

@section('vendor-script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {

    var vehiclesTable = $('#vehiclesTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
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

// $(document).ready(function(){
//     $('#saveVehicleBtn').on('click', function(e){
//         e.preventDefault();
//         var formData = $('#addVehicleForm').serialize();
//         alert(formData);

//     });
// })
$(document).ready(function() {
    $('#addVehicleForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        // Log form data for debugging
        $.ajax({
            url: '{{ route("vehicles-add") }}',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('Failed to add vehicle: ' + response.message);
                }
            },
            error: function(xhr) {

                console.log(xhr);

                console.log(xhr.responseText);

                alert(xhr.responseText);
            }
        });
    });
});
$(document).on('click', '.update-vehicle-btn', function() {

    var vehicleId = $(this).data('vehicle-id');

    $.ajax({

        url: '/vehicles/edit/' + vehicleId,

        method: 'GET',

        success: function(response) {

            console.log(response);
            $('#vehicle_id').val(response.vehicle.id);
            $('#update_vehicleName').val(response.vehicle.vehicle_name);
            $('#update_vehicleType').val(response.vehicle.vehicle_type);
            $('#updateseating_capacity').val(response.vehicle.seating_capacity);
            $('#update_additionalFeature').val(response.vehicle.additional_features);
            $('#update_registrationNumber').val(response.vehicle.registration_number);
            $('#update_brand').val(response.vehicle.brand);
            $('#update_modelName').val(response.vehicle.model);
            $('#update_fuelType').val(response.vehicle.fuel_type);
            $('#update_rentalRatePerHour').val(response.vehicle.rate_per_hour);
            $('#update_rentalRate8Hours').val(response.vehicle.rate_max_8hour);
            $('#update_rentalRatePerDay').val(response.vehicle.rate_per_day);
            $('#update_vehicleImage').val(response.vehicle.vehicle_image);
            $('#imagePreview').attr('src', '/' + response.vehicle.vehicle_image);
            $('#update_description').val(response.vehicle.description);
            $('#update_status').val(response.vehicle.status);
            $('#update_insurence_Upto').val(response.vehicle.insurance_upto);
            if (response.vehicle.status == 'Available') {
                $('#statusContainer').html(`
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehicle status</label>
                            <select name="status" class="form-select" id="status">
                                 <option value="Available">Available</option>
                                <option value="Maintenance">Maintenance</option>
                               
                            </select>
                        </div>
                `);
            } else {
                $('#statusContainer').html('');
            }

        },

        error: function(xhr) {

            console.log(xhr);

            alert('Failed to fetch vehicle details.');
        }
    });
});

$(document).ready(function(){
    $('#updateVehicleForm').submit(function(e){
       e.preventDefault();
        var formData = new FormData(this);
         $.ajax({
            url: '{{ route("vehicles-update") }}',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('Failed to update vehicle: ' + response.message);
                }
            },
            error: function(xhr) {

                console.log(xhr);

                console.log(xhr.responseText);

                alert(xhr.responseText);
            }
        });
    })
})  
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
                    Vehicles
                </h4>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#addVehicleOffcanvas">
                Add Vehicle
            </button>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="vehiclesTable" class="table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>Vehicle Name</th>
                        <th>Vehicle Type</th>
                        <th>Seating Capacity</th>
                        <th>Additional Feature</th>
                        <th>Registration Number</th>
                        <th>Fuel Type</th>
                        <th>Rate Hr</th>
                        <th>Rate 8Hrs</th>
                        <th>Rate / Day</th>
                        <th>Vehicle Image</th>

                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($vehicles as $vehicle)

                    <tr>
                        <td>{{ $vehicle->vehicle_name }}</td>
                        <td>{{ $vehicle->vehicle_type }}</td>
                        <td>{{ $vehicle->seating_capacity }}</td>
                        <td>{{ $vehicle->additional_features }}</td>
                        <td>{{ $vehicle->registration_number }}</td>

                        <td>{{ $vehicle->fuel_type }}</td>
                        <td>{{ $vehicle->rate_per_hour }}</td>
                        <td>{{ $vehicle->rate_max_8hour }}</td>
                        <td>{{ $vehicle->rate_per_day }}</td>
                        <td>
                            <img src="{{ asset($vehicle->vehicle_image) }}" class="rounded" width="60">
                        </td>
                        <td>
                            @if($vehicle->activeBooking)
                            <span class="badge bg-warning">Booked</span>
                            @else
                            @if($vehicle->status === 'Available')
                            <span class="badge bg-success">Available</span>
                            @elseif($vehicle->status === 'Maintenance')
                            <span class="badge bg-danger">Maintenance</span>
                            @else
                            <span class="badge bg-info">{{ $vehicle->status }}</span>
                            @endif
                            @endif
                        </td>
                        <td>
                            <!-- <div class="row">

                                <div class="d-flex justify-content-end gap-2 "> -->
                            <button class="btn btn-primary update-vehicle-btn" type="button"
                                data-vehicle-id="{{ $vehicle->id }}" data-bs-toggle="offcanvas"
                                data-bs-target="#UpdateVehicleOffcanvas">
                                Update
                            </button>



                            <!-- </div>
                            </div> -->


                        </td>
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
            Add Vehicle
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="addVehicleForm">
            @csrf
            <input type="hidden" id="vehicleRowIndex">

            <div class="row">

                <!-- Vehicle Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Name</label>

                    <input type="text" name="vehicleName" class="form-control" id="vehicleName" required>
                </div>

                <!-- Vehicle Type -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">Vehicle Type</label>

                    <select name="vehicleType" id="vehicleType" class="form-select" required>

                        <option value="Car">Car</option>
                        <option value="Bike">Bike</option>
                        <option value="Scooty">Scooty</option>

                    </select>

                </div>

                <!-- Seating Capacity -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Seating Capacity
                    </label>

                    <select name="seatingCapacity" id="seatingCapacity" class="form-select" required>

                        <option value="2 ">2 Seater</option>
                        <option value="4 ">4 Seater</option>
                        <option value="5 ">5 Seater</option>
                        <option value="7 ">7 Seater</option>

                    </select>

                </div>

                <!-- Additional Feature -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Additional Feature
                    </label>

                    <select name="additionalFeature" id="additionalFeature" class="form-select" required>

                        <option value="AC">AC</option>
                        <option value="Non AC">Non AC</option>
                        <option value="Bluetooth">Bluetooth</option>
                        <option value="GPS">GPS</option>

                    </select>

                </div>

                <!-- Registration -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Registration Number
                    </label>

                    <input type="text" name="registrationNumber" class="form-control" id="registrationNumber" required>

                </div>

                <!-- Brand -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Brand
                    </label>

                    <select name="brand" id="brand" class="form-select" required>

                        <option value="Honda">Honda</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Maruti">Maruti</option>
                        <option value="Royal Enfield">Royal Enfield</option>

                    </select>

                </div>

                <!-- Model -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Model Name
                    </label>

                    <select name="modelName" id="modelName" class="form-select" required>

                        <option value="City">City</option>
                        <option value="Swift">Swift</option>
                        <option value="Fortuner">Fortuner</option>
                        <option value="Activa">Activa</option>
                        <option value="Himalayan">Himalayan</option>

                    </select>

                </div>

                <!-- Fuel -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Fuel Type
                    </label>

                    <select name="fuelType" id="fuelType" class="form-select" required>

                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="EV">EV</option>

                    </select>

                </div>

                <!-- Rate Hr -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rate Per Hour
                    </label>

                    <input type="number" name="rentalRatePerHour" class="form-control" id="rentalRatePerHour" required>

                </div>

                <!-- Rate 8 Hr -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rate 8 Hours
                    </label>

                    <input type="number" name="rentalRate8Hours" class="form-control" id="rentalRate8Hours" required>

                </div>

                <!-- Rate Day -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rate Per Day
                    </label>

                    <input type="number" name="rentalRatePerDay" class="form-control" id="rentalRatePerDay" required>

                </div>

                <!-- Vehicle Image -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Vehicle Image
                    </label>

                    <input type="file" name="vehicleImage" class="form-control" id="vehicleImage">

                </div>



                <!-- Description -->
                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description" class="form-control" id="description" rows="3"></textarea>

                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Insurence Upto</label>

                    <input type="datetime-local" name="insurenceUpto" class="form-control" id="insurence_Upto"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
                <div class="row">

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">

                            Cancel

                        </button>

                        <button type="submit" id="saveVehicleBtn" class="btn btn-primary">

                            Save Vehicle

                        </button>

                    </div>
                </div>

            </div>

        </form>

    </div>

</div>


<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="UpdateVehicleOffcanvas" style="width:700px; overflow-y:auto;">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            Update Vehicle
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="updateVehicleForm">
            @csrf

            <input type="hidden" name="vehicle_id" id="vehicle_id" value="">
            <div class="row">

                <!-- Vehicle Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Name</label>

                    <input type="text" name="vehicleName" class="form-control" id="update_vehicleName" required>
                </div>

                <!-- Vehicle Type -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">Vehicle Type</label>

                    <select name="vehicleType" id="update_vehicleType" class="form-select" required>

                        <option value="Car">Car</option>
                        <option value="Bike">Bike</option>
                        <option value="Scooty">Scooty</option>

                    </select>

                </div>

                <!-- Seating Capacity -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Seating Capacity
                    </label>

                    <select name="seatingCapacity" id="updateseating_capacity" class="form-select" required>

                        <option value="2">2 Seater</option>
                        <option value="4">4 Seater</option>
                        <option value="5">5 Seater</option>
                        <option value="7">7 Seater</option>

                    </select>

                </div>

                <!-- Additional Feature -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Additional Feature
                    </label>

                    <select name="additionalFeature" id="update_additionalFeature" class="form-select" required>

                        <option value="AC">AC</option>
                        <option value="Non AC">Non AC</option>
                        <option value="Bluetooth">Bluetooth</option>
                        <option value="GPS">GPS</option>

                    </select>

                </div>

                <!-- Registration -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Registration Number
                    </label>

                    <input type="text" name="registrationNumber" class="form-control" id="update_registrationNumber"
                        required>

                </div>

                <!-- Brand -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Brand
                    </label>

                    <select name="brand" id="update_brand" class="form-select" required>

                        <option value="Honda">Honda</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Maruti">Maruti</option>
                        <option value="Royal Enfield">Royal Enfield</option>

                    </select>

                </div>

                <!-- Model -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Model Name
                    </label>

                    <select name="modelName" id="update_modelName" class="form-select" required>

                        <option value="City">City</option>
                        <option value="Swift">Swift</option>
                        <option value="Fortuner">Fortuner</option>
                        <option value="Activa">Activa</option>
                        <option value="Himalayan">Himalayan</option>

                    </select>

                </div>

                <!-- Fuel -->

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Fuel Type
                    </label>

                    <select name="fuelType" id="update_fuelType" class="form-select" required>

                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="EV">EV</option>

                    </select>

                </div>

                <!-- Rate Hr -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rate Per Hour
                    </label>

                    <input type="number" name="rentalRatePerHour" class="form-control" id="update_rentalRatePerHour"
                        required>

                </div>

                <!-- Rate 8 Hr -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rate 8 Hours
                    </label>

                    <input type="number" name="rentalRate8Hours" class="form-control" id="update_rentalRate8Hours"
                        required>

                </div>

                <!-- Rate Day -->
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Rate Per Day
                    </label>

                    <input type="number" name="rentalRatePerDay" class="form-control" id="update_rentalRatePerDay"
                        required>

                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Insurence Upto</label>

                    <input type="datetime-local" name="insurenceUpto" class="form-control" id="update_insurence_Upto"
                        value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description" class="form-control" id="update_description"></textarea>

                </div>


                <!-- Vehicle Image -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Vehicle Image
                    </label>
                    <input type="text" name="vehicleImage" class="form-control" id="update_vehicleImage">
                </div>
                
                <div class="col-md-6 mb-3">
                    <div id="statusContainer"></div>
                </div>
                <div class="col-md-6 mb-3">

                    <img id="imagePreview" width="100">

                </div>
                <div class="col-md-6 mb-3">


                    <div class="row">

                        <div class="d-flex justify-content-between gap-2 mt-4">

                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">

                                Cancel

                            </button>

                            <button type="submit" id="saveVehicleBtn" class="btn btn-primary">

                                Update Vehicle

                            </button>

                        </div>
                    </div>

                </div>







            </div>

        </form>

    </div>

</div>

@endsection
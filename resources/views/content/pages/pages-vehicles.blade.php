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
  document.addEventListener('DOMContentLoaded', function () {
    var vehiclesTable = null;

    if (document.querySelector('#vehiclesTable')) {
      vehiclesTable = $('#vehiclesTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        language: {
          search: '_INPUT_',
          searchPlaceholder: 'Search vehicles...',
        },
        columnDefs: [
          { orderable: false, targets: -1 }
        ]
      });
    }

    function updateVehicleTypeFields() {
      var selectedType = $('#vehicleType').val();
      if (selectedType === 'Car') {
        $('#carTypeGroup').removeClass('d-none');
        $('#bikeTypeGroup').addClass('d-none');
      } else {
        $('#bikeTypeGroup').removeClass('d-none');
        $('#carTypeGroup').addClass('d-none');
      }
    }

    function resetVehicleForm() {
      $('#addVehicleForm')[0].reset();
      $('#vehicleRowIndex').val('');
      $('#addVehicleForm button[type=submit]').text('Save Vehicle');
      updateVehicleTypeFields();
    }

    $('#vehicleType').on('change', updateVehicleTypeFields);
    updateVehicleTypeFields();

    $('#vehiclesTable tbody').on('click', '.update-vehicle-btn', function () {
      var row = vehiclesTable.row($(this).closest('tr'));
      var data = row.data();
      var typeDetail = data[6] || '';

      $('#vehicleName').val(data[0]);
      $('#vehicleType').val(data[1]);
      $('#registrationNumber').val(data[2]);
      $('#brand').val(data[3]);
      $('#modelName').val(data[4]);
      $('#fuelType').val(data[5]);
      $('#status').val($(data[10]).text() || data[10]);
      $('#vehicleRowIndex').val(row.index());
      $('#addVehicleForm button[type=submit]').text('Update Vehicle');

      updateVehicleTypeFields();
      if ($('#vehicleType').val() === 'Car') {
        $('#carType').val(typeDetail);
      } else {
        $('#bikeType').val(typeDetail);
      }

      var offcanvasEl = document.querySelector('#addVehicleOffcanvas');
      var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
      offcanvas.show();
    });

    $('#addVehicleForm').on('submit', function (event) {
      event.preventDefault();
      var vehicleName = $('#vehicleName').val();
      var vehicleType = $('#vehicleType').val();
      var registrationNumber = $('#registrationNumber').val();
      var brand = $('#brand').val();
      var modelName = $('#modelName').val();
      var fuelType = $('#fuelType').val();
      var typeDetail = vehicleType === 'Car' ? $('#carType').val() : $('#bikeType').val();
      var rentalRatePerHour = parseFloat($('#rentalRatePerHour').val()).toFixed(2);
      var rentalRate8Hours = parseFloat($('#rentalRate8Hours').val()).toFixed(2);
      var rentalRatePerDay = parseFloat($('#rentalRatePerDay').val()).toFixed(2);
      var status = $('#status').val();
      var actionButton = '<button type="button" class="btn btn-sm btn-outline-primary update-vehicle-btn">Update</button>';
      var statusBadge = '<span class="badge ' + (status === 'Available' ? 'bg-success' : status === 'Rented' ? 'bg-primary' : 'bg-warning text-dark') + '">' + status + '</span>';
      var rowIndex = $('#vehicleRowIndex').val();
      var rowValues = [
        vehicleName,
        vehicleType,
        registrationNumber,
        brand,
        modelName,
        fuelType,
        typeDetail,
        rentalRatePerHour,
        rentalRate8Hours,
        rentalRatePerDay,
        statusBadge,
        actionButton
      ];

      if (rowIndex !== '') {
        vehiclesTable.row(rowIndex).data(rowValues).draw(false);
      } else {
        vehiclesTable.row.add(rowValues).draw(false);
      }

      var offcanvasEl = document.querySelector('#addVehicleOffcanvas');
      var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
      if (offcanvas) {
        offcanvas.hide();
      }
      resetVehicleForm();
    });
  });
</script>
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-4">
  <div>
    <h4 class="mb-0">
      <span class="text-muted fw-light">eCommerce /</span> Vehicles
    </h4>
  </div>
  <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#addVehicleOffcanvas" aria-controls="addVehicleOffcanvas">
    Add Vehicle
  </button>
</div>

<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">Vehicle Inventory</h5>
    <small class="text-muted">Browse your vehicle list with search, pagination, and Bootstrap styling.</small>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="vehiclesTable" class="table table-hover table-striped table-borderless align-middle w-100" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>Vehicle</th>
            <th>Type</th>
            <th>Registration</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Fuel</th>
            <th>Type Detail</th>
            <th>Rate / hr</th>
            <th>Rate 8 hrs</th>
            <th>Rate / day</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Toyota Camry</td>
            <td>Car</td>
            <td>KA01AB1234</td>
            <td>Toyota</td>
            <td>City</td>
            <td>Petrol</td>
            <td>5 Seater</td>
            <td>15.00</td>
            <td>100.00</td>
            <td>180.00</td>
            <td><span class="badge bg-success">Available</span></td>
            <td><button type="button" class="btn btn-sm btn-outline-primary update-vehicle-btn">Update</button></td>
          </tr>
          <tr>
            <td>Honda Civic</td>
            <td>Car</td>
            <td>MH12CD5678</td>
            <td>Honda</td>
            <td>City</td>
            <td>Diesel</td>
            <td>5 Seater</td>
            <td>18.00</td>
            <td>110.00</td>
            <td>200.00</td>
            <td><span class="badge bg-warning text-dark">Maintenance</span></td>
            <td><button type="button" class="btn btn-sm btn-outline-primary update-vehicle-btn">Update</button></td>
          </tr>
          <tr>
            <td>Royal Enfield Himalayan</td>
            <td>Bike</td>
            <td>DL04EF9012</td>
            <td>Royal Enfield</td>
            <td>Himalayan</td>
            <td>Petrol</td>
            <td>Sportsbike</td>
            <td>12.00</td>
            <td>80.00</td>
            <td>150.00</td>
            <td><span class="badge bg-success">Available</span></td>
            <td><button type="button" class="btn btn-sm btn-outline-primary update-vehicle-btn">Update</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addVehicleOffcanvas" aria-labelledby="addVehicleOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="addVehicleOffcanvasLabel">Add Vehicle</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="addVehicleForm">
      <input type="hidden" id="vehicleRowIndex" name="vehicle_row_index" value="" />
      <div class="mb-3">
        <label for="vehicleName" class="form-label">Vehicle Name</label>
        <input type="text" class="form-control" id="vehicleName" name="vehicle_name" required />
      </div>
      <div class="mb-3">
        <label for="vehicleType" class="form-label">Vehicle Type</label>
        <select id="vehicleType" name="vehicle_type" class="form-select" required>
          <option value="Bike">Bike</option>
          <option value="Car">Car</option>
          <option value="Scooty">Scooty</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="registrationNumber" class="form-label">Registration Number</label>
        <input type="text" class="form-control" id="registrationNumber" name="registration_number" required />
      </div>
      <div class="mb-3">
        <label for="brand" class="form-label">Brand</label>
        <select id="brand" name="brand" class="form-select" required>
          <option value="Honda">Honda</option>
          <option value="Maruti">Maruti</option>
          <option value="Hyundai">Hyundai</option>
          <option value="Royal Enfield">Royal Enfield</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="modelName" class="form-label">Model Name</label>
        <select id="modelName" name="model_name" class="form-select" required>
          <option value="Activa">Activa</option>
          <option value="Swift">Swift</option>
          <option value="City">City</option>
          <option value="Himalayan">Himalayan</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="fuelType" class="form-label">Fuel Type</label>
        <select id="fuelType" name="fuel_type" class="form-select" required>
          <option value="Petrol">Petrol</option>
          <option value="Diesel">Diesel</option>
          <option value="EV">EV</option>
        </select>
      </div>
      <div class="mb-3" id="bikeTypeGroup">
        <label for="bikeType" class="form-label">Bike Type</label>
        <select id="bikeType" name="bike_type" class="form-select">
          <option value="scooty">Scooty</option>
          <option value="bikes">Bikes</option>
          <option value="sportsbike">Sportsbike</option>
        </select>
      </div>
      <div class="mb-3 d-none" id="carTypeGroup">
        <label for="carType" class="form-label">Car Type</label>
        <select id="carType" name="car_type" class="form-select">
          <option value="5 seater">5 Seater</option>
          <option value="7 seater">7 Seater</option>
          <option value="ac">AC</option>
          <option value="non ac">Non AC</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="rentalRatePerHour" class="form-label">Rental Rate Per Hour</label>
        <input type="number" step="0.01" class="form-control" id="rentalRatePerHour" name="rental_rate_per_hour" required />
      </div>
      <div class="mb-3">
        <label for="rentalRate8Hours" class="form-label">Rental Rate 8 Hours</label>
        <input type="number" step="0.01" class="form-control" id="rentalRate8Hours" name="rental_rate_8_hours" required />
      </div>
      <div class="mb-3">
        <label for="rentalRatePerDay" class="form-label">Rental Rate Per Day</label>
        <input type="number" step="0.01" class="form-control" id="rentalRatePerDay" name="rental_rate_per_day" required />
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select" required>
          <option value="Available">Available</option>
          <option value="Rented">Rented</option>
          <option value="Maintenance">Maintenance</option>
        </select>
      </div>
      <div class="d-flex justify-content-end gap-2 mt-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Vehicle</button>
      </div>
    </form>
  </div>
</div>

@endsection


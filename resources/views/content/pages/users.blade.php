@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Users')


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

    if ($('#usertable').length) {

        $('#usertable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],

            language: {
                search: '',
                searchPlaceholder: 'Search users...'
            },

            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });
    }

});

$(document).ready(function() {
    $('.updateuserBtn').on('click', function(e) {
        e.preventDefault();

        let employeeId = $(this).data('user-id');
        // alert(employeeId);
        $.ajax({
            url: '/employee/update/' + employeeId,
            type: 'GET',
            success: function(response) {
                console.log(response);
                $('#RowIndex').val(employeeId);
                $('#employeeName').val(response.user.name);
                $('#employeeEmail').val(response.user.email);
                $('#employeeGender').val(response.user.gender);
                $('#employeeMobile').val(response.user.mobile);
                $('#employeeDistrict').val(response.user.district);
                $('#employeeSalary').val(response.user.salary);
                $('#employeerole').val(response.user.role);
                $('#employeeDoj').val(response.user.joining_date.split(' ')[0]);
                $('#employeeStatus').val(response.user.status);
            },
            error: function(xhr) {
                alert('Error fetching employee data. Please try again.');
            }
        });
    });
});


$(document).ready(function() {
    $('#UpdateEmployeeForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        console.log(formData);
        var employeeid = $('#RowIndex').val();

        $.ajax({

            url: '/employee/update/' + employeeid,
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
                    Employee Inventory
                </h4>
            </div>
        </div>


    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="usertable" class="table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                       
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Branch</th>
                        <th>Salary</th>
                        <th>role</th>
                        <th>DOJ</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)


                    <tr>                       
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->gender }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->branch }}</td>
                        <td>{{ $user->salary }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('d-m-Y') }}</td>
                        <td>{{ $user->status }}</td>
                        <td>
                            <button class="btn btn-primary updateuserBtn" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#addVehicleOffcanvas" data-user-id="{{ $user->id }}"> Update
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
            Update User
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="UpdateEmployeeForm">
            @csrf
            <input type="hidden" id="RowIndex">

            <div class="row g-3">

                <!-- Employee Name -->
                <div class="col-6 col-md-12">
                    <label for="Employeename" class="form-label">Employee Name</label>

                    <input type="text" name="employeeName" class="form-control" id="employeeName" required>
                </div>

                <!-- Employee Email -->
                <div class="col-6 col-md-12">
                    <label for="Employeemail" class="form-label">Employee Email</label>

                    <input type="email" id="employeeEmail" name="employeeEmail" class="form-control">
                </div>
                <!-- Employee number -->
                <div class="col-6 col-md-12">
                    <label for="Employeenumber" class="form-label">Employee Mobile</label>

                    <input type="text" id="employeeMobile" name="employeeMobile" class="form-control">
                </div>
                <!-- Employee salary -->
                <div class="col-6 col-md-12">
                    <label for="Employeesalary" class="form-label">Employee Salary</label>

                    <input type="text" id="employeeSalary" name="employeeSalary" class="form-control">
                </div>
                <!-- Employee designation -->
                <div class="col-6 col-md-12">
                    <label for="Employeerole" class="form-label">Employee Role</label>
                        <select name="employeerole" class="form-select" required id="employeerole">
                         <option value="manager">Manager
                        </option>
                        <option value="employee">Employee
                        </option>
                       
                    </select>


                </div>
                <!-- Employeedoj -->
                <div class="col-6 col-md-12">
                    <label for="Employeedoj" class="form-label">Employee doj</label>
                    <input type="date" name="employeeDoj" class="form-control" id="employeeDoj" value="">
                </div>

                <!-- Employee status  -->
                <div class="col-6 col-md-12">
                    <label for="Employeestatus" class="form-label">Employee Status</label>
                    <select name="employeeStatus" class="form-select" required id="employeeStatus">
                        <option value="inactive">In Active
                        </option>
                        <option value="active">Active
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
                        Update Employee
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>


@endsection
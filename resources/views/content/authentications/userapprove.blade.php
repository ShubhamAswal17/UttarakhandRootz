@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'UsersApprove')


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

    if ($('#usertable').length) {

        $('#usertable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            lengthMenu: [10, 25, 50, 100],
            dom:
        "<'row mb-3'<'col-md-6'l><'col-md-6 text-end'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",

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
        $.ajax({
            url: '/employee/approval/'+employeeId,
            type: 'GET',
            success: function(response) {
                  $('#row-' + employeeId).remove();
                console.log(response);

            },
            error: function(xhr) {
                alert('Error fetching employee data. Please try again.');
            }
        });
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
                    Employee Approvals
                </h4>
            </div>
        </div>


    </div>

    <div class="card-body">

        

            <table id="usertable" class="table-bordered table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Branch</th>
                        <th>Designation</th>
                        <th>DOJ</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)


                    <tr id="row-{{ $user->id }}">
                        <td>{{ ucfirst($user->name) }}</td>
                        <td>{{ ucfirst($user->email) }}</td>
                        <td>{{ ucfirst($user->gender) }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ ucfirst($user->branch) }}</td>
                        <td>{{ ucfirst($user->designation)  }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($user->status) }}</td>
                        <td>
                            <button class="btn btn-primary updateuserBtn" type="button" 
                                 data-user-id="{{ $user->id }}"> Approved
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>


    </div>

</div>




@endsection
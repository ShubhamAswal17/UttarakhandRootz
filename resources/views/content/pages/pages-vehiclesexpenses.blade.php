@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Vehicles Expenses')


@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

@endsection

@section('page-style')
@if(Auth::user()->role === 'employee')
<style>
.employee-info-col {
    display: none !important;
}
</style>
@endif
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

    if ($('#VehicleExpense').length) {

        $('#VehicleExpense').DataTable({
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

    var selectedExpenseRow = null;

    function resetExpenseForm() {
        selectedExpenseRow = null;
        $('#expense_id').val('');
        $('#UpdateMaintenanceForm')[0].reset();
        $('#Vehiclesexpenses .offcanvas-title').text('Add / Update Vehicle Expense');
        $('#saveExpenseBtn').text('Save Expense');
    }

    $('#addExpenseBtn').on('click', function() {
        resetExpenseForm();
    });

    $('#UpdateMaintenanceForm').submit(function(e) {
        e.preventDefault();

        var expenseId = $('#expense_id').val();
        var submitUrl = expenseId ? '{{ url('/Expense/vehicle') }}/'+expenseId :
            '{{ route("Expense-vehicle-store") }}';
        var formData = new FormData(this);

        $.ajax({
            url: submitUrl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                     Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    })
                    var expense = response.expense;
                    var table = $('#VehicleExpense').DataTable();
                    var billLink = expense.bill_image ? '<a href="/' + expense.bill_image +
                        '" target="_blank">View</a>' : '';
                    var actionHtml =
                        '<button class="btn btn-primary edit-expense-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#Vehiclesexpenses" data-expense-id="' +
                        expense.id + '">Edit</button>';

                    if (selectedExpenseRow && expenseId) {
                        table.row(selectedExpenseRow).data([
                            expense.id,

                            expense.vehicle_name,
                            expense.registration_number,
                            expense.expense_type,
                            expense.expense_date,
                            expense.vendor_name,
                            billLink,
                            expense.expense_description,
                            expense.payment_type,
                            expense.expense_amount,
                            expense.expense_status,
                            actionHtml
                        ]).draw(false);
                    } else {
                        function ucwords(str) {
                            return str ? str.toLowerCase().replace(/\b\w/g, c => c
                                .toUpperCase()) : '';
                        }

                        table.row.add([
                            expense.id,
                            ucwords(expense.vehicle_name),
                            ucwords(expense.registration_number),
                            ucwords(expense.expense_type),
                            expense.expense_date,
                            ucwords(expense.vendor_name),
                            billLink,
                            ucwords(expense.expense_description),
                            ucwords(expense.payment_type),
                            expense.expense_amount,
                            ucwords(expense.expense_status),
                            actionHtml
                        ]).draw(false);
                    }

                    var offcanvasEl = document.getElementById('Vehiclesexpenses');
                    var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                    resetExpenseForm();
                   

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

    $(document).on('click', '.edit-expense-btn', function() {
        selectedExpenseRow = $(this).closest('tr');
        var rowData = $('#VehicleExpense').DataTable().row(selectedExpenseRow).data();

        $('#expense_id').val(rowData[0]);
        $('#employee_id').val(rowData[1]);
        $('#employee_name').val(rowData[2]);
        $('#vehicle_name').val(rowData[3]);
        $('#registration_number').val(rowData[4]);
        $('#expense_type').val(rowData[5]);
        $('#expense_date').val(rowData[6]);
        $('#vendor_name').val(rowData[7]);
        $('#expense_description').val(rowData[9]);
        $('#payment_type').val(rowData[10]);
        $('#expense_amount').val(rowData[11]);
        $('#expense_status').val(rowData[12]);
        $('#Vehiclesexpenses .offcanvas-title').text('Update Vehicle Expense');
        $('#saveExpenseBtn').text('Update Expense');
    });

});
</script>

</script>
@endsection

@section('content')
<!-- Table -->
<div class="card">

    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">

            <div>
                <h4 class="mb-0">

                    Vehicles Expenses Inventory
                </h4>
            </div>
            @if(Auth::user()->role !== 'admin')
            <div>
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#Vehiclesexpenses" id="addExpenseBtn">
                    Add Expense
                </button>
            </div>
            @endif

        </div>


    </div>

    <div class="card-body">

        <div class="table-responsive">


            <table id="VehicleExpense" class="table-bordered table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        @if(Auth::user()->role == 'admin')
                        <th>Branch</th>
                        <th>Employee Name</th>
                        @endif
                        <th>vehicle Name</th>
                        <th>Registration Number</th>
                        <th>Expense type</th>
                        <th>Expense Date</th>
                        <th>Vendor Name</th>
                        <th>Bill Image</th>
                        <th>Expense Discription</th>
                        <th>Payment type</th>
                        <th>Expense Amount</th>
                        <th>Expense Status</th>
                        @if(Auth::user()->role !== 'admin')
                        <th>Action</th>
                        @endif
                    </tr>

                </thead>

                <tbody>
                    @foreach($expenses as $expense)
                    <tr data-expense-id="{{ $expense->id }}">
                        <td>{{ $expense->id }}</td>
                        @if(Auth::user()->role == 'admin')
                        <td>{{ ucfirst($expense->employee_branch) }}</td>
                        <td>{{ ucfirst($expense->employee_name) }}</td>
                        @endif

                        <td>{{ ucfirst($expense->vehicle_name) }}</td>
                        <td>{{ strtoupper($expense->registration_number) }}</td>
                        <td>{{ ucfirst($expense->expense_type) }}</td>
                        <td>{{ $expense->expense_date }}</td>
                        <td>{{ ucfirst($expense->vendor_name) }}</td>
                        <td>
                            @if($expense->bill_image)
                            <a href="/{{ $expense->bill_image }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>{{ ucfirst($expense->expense_description) }}</td>
                        <td>{{ ucfirst($expense->payment_type) }}</td>
                        <td>{{ $expense->expense_amount }}</td>
                        <td>{{ ucfirst($expense->expense_status) }}</td>
                        @if(Auth::user()->role !== 'admin')
                        <td>
                            <button class="btn btn-primary edit-expense-btn" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#Vehiclesexpenses" data-expense-id="{{ $expense->id }}">
                                Edit
                            </button>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="Vehiclesexpenses" style="width:500px; overflow-y:auto;">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            Add / Update Vehicle Expense
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="UpdateMaintenanceForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="expense_id" id="expense_id">

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Vehicle Name</label>
                    <input type="text" name="vehicle_name" class="form-control" id="vehicle_name"
                        placeholder="Vehicle Name" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="registration_number" class="form-control" id="registration_number"
                        placeholder="Registration Number" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Type</label>
                    <select name="expense_type" id="expense_type" class="form-select">
                        <option value="cleaning">Cleaning</option>
                        <option value="insurence">Insurence</option>
                        <option value="pollution">Pollution</option>
                        <option value="packages expenses">Packages Expenses</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" class="form-control" id="expense_date"
                        value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control" id="vendor_name"
                        placeholder="Vendor Name" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Bill Image</label>
                    <input type="file" name="bill_image" class="form-control" id="bill_image" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Description</label>
                    <textarea name="expense_description" id="expense_description" class="form-control" rows="3"
                        placeholder="Expense Description" ></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-select" required>
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Amount</label>
                    <input type="number" step="0.01" name="expense_amount" class="form-control" id="expense_amount"
                        placeholder="Expense Amount" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Status</label>
                    <select name="expense_status" id="expense_status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>

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
                    <button type="submit" id="saveExpenseBtn" class="btn btn-primary w-100">
                        Save Expense
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>




@endsection
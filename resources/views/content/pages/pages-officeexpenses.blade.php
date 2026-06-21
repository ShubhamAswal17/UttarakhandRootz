@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Office Expenses')


@section('vendor-style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection

@section('vendor-script')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
@endsection
@section('page-style')

@endsection
@section('page-script')

<script>
document.addEventListener('DOMContentLoaded', function() {

    if ($('#OfficeExpense').length) {

        $('#OfficeExpense').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
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

    function resetOfficeExpenseForm() {
        selectedExpenseRow = null;
        $('#expense_id').val('');
        $('#OfficeExpenseForm')[0].reset();
        $('#OfficeExpensesOffcanvas .offcanvas-title').text('Add / Update Office Expense');
        $('#saveOfficeExpenseBtn').text('Save Expense');
    }

    $('#addOfficeExpenseBtn').on('click', function() {
        resetOfficeExpenseForm();
    });

    $('#OfficeExpenseForm').submit(function(e) {
        e.preventDefault();

        var expenseId = $('#expense_id').val();
        var submitUrl = expenseId ? '{{ url(' / Expense / office ') }}/' + expenseId :
            '{{ route("Expense-office-store") }}';
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
                    var expense = response.expense;
                    var table = $('#OfficeExpense').DataTable();
                    var billLink = expense.bill_image ? '<a href="/' + expense.bill_image +
                        '" target="_blank">View</a>' : '';
                    var actionHtml =
                        '<button class="btn btn-primary edit-office-expense-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#OfficeExpensesOffcanvas" data-expense-id="' +
                        expense.id + '">Edit</button>';

                    if (selectedExpenseRow && expenseId) {
                        table.row(selectedExpenseRow).data([
                            expense.id,
                            expense.expense_type,
                            expense.vendor_name,
                            expense.vendor_number,
                            billLink,
                            expense.expense_date,
                            expense.expense_description,
                            expense.payment_type,
                            expense.expense_amount,
                            expense.expense_status,
                            actionHtml
                        ]).draw(false);
                    } else {
                        table.row.add([
                            expense.id,
                            expense.expense_type,
                            expense.vendor_name,
                            expense.vendor_number,
                            billLink,
                            expense.expense_date,
                            expense.expense_description,
                            expense.payment_type,
                            expense.expense_amount,
                            expense.expense_status,
                            actionHtml
                        ]).draw(false);
                    }

                    var offcanvasEl = document.getElementById('OfficeExpensesOffcanvas');
                    var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                    resetOfficeExpenseForm();
                    alert('Expense saved successfully');
                } else {
                    alert('Failed to save expense');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                if (xhr.status === 403) {
                    var response = xhr.responseJSON;
                    alert(response.message);
                } else {
                    alert('Something went wrong while saving the expense.');
                }
            }
        });
    });

    $(document).on('click', '.edit-office-expense-btn', function() {
        selectedExpenseRow = $(this).closest('tr');
        var rowData = $('#OfficeExpense').DataTable().row(selectedExpenseRow).data();

        $('#expense_id').val(rowData[0]);
        $('#expense_type').val(rowData[3]);
        $('#vendor_name').val(rowData[4]);
        $('#vendor_number').val(rowData[5]);
        $('#expense_date').val(rowData[7]);
        $('#expense_description').val(rowData[8]);
        $('#payment_type').val(rowData[9]);
        $('#expense_amount').val(rowData[10]);
        $('#expense_status').val(rowData[11]);
        $('#OfficeExpensesOffcanvas .offcanvas-title').text('Update Office Expense');
        $('#saveOfficeExpenseBtn').text('Update Expense');
    });

});
</script>
@endsection

@section('content')
@if(!in_array(Auth::user()->role, ['admin', 'manager']))
<div class="container mt-5">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Access Denied!</strong> Only admins and managers can access this page.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@else
<!-- Table -->
<div class="card">

    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">

            <div>
                <h4 class="mb-0">

                    Office Expenses Inventory
                </h4>
            </div>

            <div>
                @if(Auth::user()->role !== 'admin')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#OfficeExpensesOffcanvas" id="addOfficeExpenseBtn">
                    Add Expense
                </button>
                @endif
            </div>

        </div>


    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="OfficeExpense" class="table-bordered table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th> ID</th>
                        @if(Auth::user()->role === 'admin')
                        <th>Manager branch</th>
                        <th>Manager Name</th>
                        @endif
                        <th>Expense type</th>
                        <th>Vendor Name</th>
                        <th>Vendor Number</th>
                        <th>bill Image</th>
                        <th>Expense Date</th>
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
                        @if(Auth::user()->role === 'admin')
                        <td>{{ $expense->manager_branch }}</td>
                        <td>{{ $expense->manager_name }}</td>
                        @endif
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->expense_type }}</td>
                        <td>{{ $expense->vendor_name }}</td>
                        <td>{{ $expense->vendor_number }}</td>
                        <td>
                            @if($expense->bill_image)
                            <a href="/{{ $expense->bill_image }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>{{ $expense->expense_date }}</td>
                        <td>{{ $expense->expense_description }}</td>
                        <td>{{ $expense->payment_type }}</td>
                        <td>{{ $expense->expense_amount }}</td>
                        <td>{{ $expense->expense_status }}</td>
                        @if(Auth::user()->role !== 'admin')
                        <td>

                            <button class="btn btn-primary edit-office-expense-btn" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#OfficeExpensesOffcanvas"
                                data-expense-id="{{ $expense->id }}">
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




<div class="offcanvas offcanvas-end" tabindex="-1" id="OfficeExpensesOffcanvas" style="width:500px; overflow-y:auto;">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            Add / Update Office Expense
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

    </div>

    <div class="offcanvas-body">

        <form id="OfficeExpenseForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="expense_id" id="expense_id">

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Expense Type</label>
                    <select name="expense_type" id="expense_type" class="form-select">
                        <option value="office expense">Office Expense</option>
                        <option value="office rent">Office Rent</option>
                        <option value="garage rent">Garage Rent</option>
                        @if(Auth::user()->role === 'admin')
                        <option value="salary">Salary</option>
                        @endif
                        <option value="office cleaning">Office Cleaning</option>
                        <option value="advertisement">Advertisement</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control" id="vendor_name"
                        placeholder="Vendor Name">
                </div>

                <div class="col-12">
                    <label class="form-label">Vendor Number</label>
                    <input type="text" name="vendor_number" class="form-control" id="vendor_number"
                        placeholder="Vendor Number">
                </div>

                <div class="col-12">
                    <label class="form-label">Bill Image</label>
                    <input type="file" name="bill_image" class="form-control" id="bill_image">
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Date</label>
                    <input type="date" name="expense_date" class="form-control" id="expense_date"
                        value="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Description</label>
                    <textarea name="expense_description" id="expense_description" class="form-control" rows="3"
                        placeholder="Expense Description"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-select">
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Expense Amount</label>
                    <input type="number" step="0.01" name="expense_amount" class="form-control" id="expense_amount"
                        placeholder="Expense Amount">
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
                    <button type="submit" id="saveOfficeExpenseBtn" class="btn btn-primary w-100">
                        Save Expense
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>
@endif

@endsection
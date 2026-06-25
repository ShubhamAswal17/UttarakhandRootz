@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Payments')


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

    if ($('#PaymentsTable').length) {

        $('#PaymentsTable').DataTable({
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
</script>
@endsection

@section('content')
<!-- Table -->
<div class="card">

    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2">

            <div>
                <h4 class="mb-0">

                    Payment Inventory
                </h4>
            </div>

        </div>


    </div>

    <div class="card-body">

       

            <table id="PaymentsTable" class="table-bordered table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        @if(auth()->user()->role == 'admin')
                        <th>Payment ID</th>
                        @endif
                        <th>Customer Name</th>
                        <th>Payment branch</th>
                        <th>Booking ID</th>
                        <th>Payment Date</th>
                        <th>Payment Amount</th>
                        <th>Payment Mode</th>
                        <th>Payment Status</th>
                    </tr>

                </thead>

                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        @if(auth()->user()->role == 'admin')
                        <td>{{ $payment->id }}</td>
                        @endif
                        <td>{{ ucfirst($payment->customer->customer_name ?? '') }}</td>
                        <td>{{ ucfirst($payment->booking->branch)  ?? '' }}</td>
                        <td>{{ $payment->booking_id }}</td>
                        <td>{{ $payment->booking->booking_date ? \Carbon\Carbon::parse($payment->booking->booking_date)->format('d-m-Y h:i A') : '' }}
                        </td>
                        <td>{{ $payment->payment_amount }}</td>
                        <td>{{ ucfirst($payment->payment_mode) }}</td>
                        <td>{{ ucfirst($payment->payment_status) }}</td>

                    </tr>
                    @endforeach
                </tbody>

            </table>

        

    </div>

</div>




@endsection
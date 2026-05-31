@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Payments')


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

    if ($('#CustomerTable').length) {

        $('#CustomerTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],

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

        <div class="table-responsive">

            <table id="CustomerTable" class="table table-hover table-striped align-middle w-100">

                <thead class="table-light">

                    <tr>
                        <th>Payment ID</th>
                        <th>Customer Name</th>
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
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->customer->customer_name ?? '' }}</td>                    
                        <td>{{ $payment->booking_id }}</td>
                        <td>{{ $payment->booking->booking_date ? \Carbon\Carbon::parse($payment->booking->booking_date)->format('d-m-Y h:i A') : '' }}</td>
                        <td>{{ $payment->payment_Amount }}</td>
                        <td>{{ $payment->payment_mode }}</td>
                        <td>{{ $payment->payment_status }}</td>
                  
                    </tr>
                    @endforeach
                </tbody>

            </table>
                    
        </div>

    </div>

</div>




@endsection

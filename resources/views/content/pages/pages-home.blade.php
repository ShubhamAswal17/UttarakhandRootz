@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')


@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/app-ecommerce-dashboard.js')}}"></script>
@endsection

@section('content')
@php
$totalEmployees = $branchEmployees->count();

$bookingProgress = max(10, min(100, $currentMonthBookings->count()));

$vehicleProgress = max(10, min(100, $totalVehicles));
$revenueProgress = max(10, min(100, $currentMonthRevenue > 0 ? 100 : 0));
@endphp
<div class="row">
    <!-- View sales -->
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Welcome, {{ Auth::user()->name }} 🎉</h5>
                        <p class="mb-2">Revenue of the Month</p>
                        <h4 class="text-primary mb-1"> ₹{{ number_format($currentMonthRevenue,2) }}</h4>
                        <a href="javascript:;" class="btn btn-primary">View Bookings</a>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/card-advance-sale.png')}}" height="140"
                            alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View sales -->

    <!-- Statistics -->
    <div class="col-xl-8 mb-4 col-lg-7 col-12">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">Statistics</h5>
                    <small class="text-muted">Month</small>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2"><i
                                    class="ti ti-chart-pie-2 ti-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $currentMonthBookings->count() }}+</h5>
                                <small> Bookings</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2"><i class="ti ti-users ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $currentMonthCustomers }}+</h5>
                                <small>Customers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2"><i
                                    class="fa-solid fa-car fa-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $totalVehicles }}+</h5>
                                <small>Vehicles</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2"><i
                                    class="ti ti-currency-dollar ti-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">₹{{ number_format($currentMonthExpense, 2) }}</h5>
                                <small>Expense</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Statistics -->


    <div class="col-xl-4 col-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title m-0 me-2">
                    <h5 class="m-0 me-2">Popular Bookings</h5>
                    <small class="text-muted">top booked vehicles</small>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="popularProduct" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="popularProduct">
                        <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                        <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($popularBookings as $vehicle)
                    <li class="d-flex mb-4">
                        <div class="d-flex w-100 justify-content-between align-items-center">

                            <div>
                                <h6 class="mb-0">{{ $vehicle->vehicle_name }}</h6>

                                <small class="text-muted d-block">
                                    {{ $vehicle->registration_number }}
                                </small>
                            </div>

                            <div class="text-end">
                                <p class="mb-0 fw-medium">
                                    ₹{{ number_format($vehicle->revenue,2) }}
                                </p>

                                <small class="text-muted">
                                    {{ $vehicle->total_bookings }} bookings
                                </small>
                            </div>

                        </div>
                    </li>
                    @empty
                    <li>No booking data found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>


    <div class="col-12 col-xl-8 mb-4">
        <div class="card">
            <div class="card-header  align-items-center">
                <h3 class="card-title mb-0">Earning Reports</h3>
                <h6 class="mb-0"><span class="text-muted me-2">Curent Week Earnings Overview</span></h6>

            </div>
            <div class="card-body">
                <div class="row d-flex align-items-center g-md-8">
                    <div class="col-6 col-md-5 ">
                        <div class=" gap-2 mb-3 flex-wrap">
                            <h2 class="mb-0">
                                ₹{{ number_format($currentWeekRevenue,2) }}
                            </h2>

                            @if($weeklyRevenueGrowthPercent >= 0)
                            <div class="badge rounded bg-label-success">
                                +{{ $weeklyRevenueGrowthPercent }}%
                            </div>
                            @else
                            <div class="badge rounded bg-label-danger">
                                {{ $weeklyRevenueGrowthPercent }}%
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 col-md-5  ">
                        <!-- <small class="text-body">You informed of this week compared to last week</small> -->
                    </div>

                </div>
                <div class="border rounded p-4 mt-5">
                    <div class="row gap-4 gap-sm-0">
                        <div class="col-12 col-sm-3">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-primary p-1"><i
                                        class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                <h6 class="mb-0 fw-normal">Bookings</h6>
                            </div>
                            <h4 class="my-2">{{ $currentWeekBookings }}+</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar" role="progressbar" style="width:50%"
                                    aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-primary p-1"><i
                                        class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                <h6 class="mb-0 fw-normal">Customer</h6>
                            </div>
                            <h4 class="my-2">{{ $currentWeekCustomers }}+</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-info p-1"><i
                                        class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                <h6 class="mb-0 fw-normal">Vehicle</h6>
                            </div>
                            <h4 class="my-2"> {{ $currentWeekVehiclesAdded }}+</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-danger p-1"><i
                                        class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                <h6 class="mb-0 fw-normal">Expense</h6>
                            </div>
                            <h4 class="my-2">₹{{ number_format($currentWeekExpense,2) }}</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 80%"
                                    aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h6 class="mb-2">Revenue Growth</h6>
                                <h4 class="mb-2">₹{{ number_format($monthRevenueDifference,2) }}</h4>
                                <p class="mb-0"><span class="text-muted me-2">Last Monthly Report</span><span
                                        class="badge bg-label-success">{{number_format($MonthlyRevenueGrowthPercent,2)}}%</span>
                                </p>
                            </div>
                            <span class="avatar me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-smart-home text-body"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                            <div>
                                <h6 class="mb-2">Expenses</h6>
                                <h4 class="mb-2">₹{{ number_format($monthlyExpenseDifference,2) }}</h4>
                                <p class="mb-0"><span class="text-muted me-2">Last Monthly Report</span>
                                    <!-- <span>if - marking to low huwa hai if + to jad</span> -->
                                    <span
                                        class="badge bg-label-success">{{number_format($monthlyExpenseGrowthPercent,2)}}%</span>
                                </p>
                            </div>
                            <span class="avatar p-2 me-lg-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-device-laptop text-body"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h6 class="mb-2">Revenue Growth</h6>
                                <h4 class="mb-2">₹{{ number_format($weeklyRevenueDifference,2) }}</h4>
                                <p class="mb-0"><span class="text-muted me-2">Last weekly Report</span><span
                                        class="badge bg-label-success">@if($weeklyRevenueGrowthPercent >= 0)
                                        <span class="badge bg-label-success">
                                            +{{ $weeklyRevenueGrowthPercent }}%
                                        </span>
                                        @else
                                        <span class="badge bg-label-danger">
                                            {{ $weeklyRevenueGrowthPercent }}%
                                        </span>
                                        @endif</span></p>
                            </div>
                            <span class="avatar me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-smart-home text-body"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                            <div>
                                <h6 class="mb-2">Expenses</h6>
                                <h4 class="mb-2">₹{{ number_format($weeklyExpenseDifference,2) }}</h4>
                                <p class="mb-0"><span class="text-muted me-2">Last weekly Report</span>
                                <span> <!-- <span>if -(minus) marking to last hafte me uske phele wale hafte se kaam kharch huwa hai</span> --></span>
                                <span
                                        class="badge bg-label-success">@if($WeeklyExpenseGrowthPercent >= 0)
                                        <span class="badge bg-label-warning">
                                            +{{ $WeeklyExpenseGrowthPercent }}%
                                        </span>
                                        @else
                                        <span class="badge bg-label-success">
                                            {{ $WeeklyExpenseGrowthPercent }}%
                                        </span>
                                        @endif</span></p>
                            </div>
                            <span class="avatar p-2 me-lg-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-device-laptop text-body"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endsection
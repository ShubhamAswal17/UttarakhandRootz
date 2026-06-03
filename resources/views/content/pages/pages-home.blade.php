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
<div class="row">
    <!-- View sales -->
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Welcome, {{ Auth::user()->name }} 🎉</h5>
                        <p class="mb-2">Revenue of the Day</p>
                        <h4 class="text-primary mb-1">$5.9k</h4>
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
                    <small class="text-muted">Updated 1 month ago</small>
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
                                <small>Monthly Bookings</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2"><i class="ti ti-users ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $currentMonthCustomers->count() }}+</h5>
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
                                <h5 class="mb-0">{{ number_format($currentMonthRevenue, 2) }}</h5>
                                <small>Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Statistics -->






    <div class="col-xl-4 col-12">
        <div class="row">
            <!-- Expenses -->
            <div class="col-xl-6 mb-4 col-md-3 col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">82.5k</h5>
                        <small class="text-muted">Expenses</small>
                    </div>
                    <div class="card-body">
                        <div id="expensesChart"></div>
                        <div class="mt-md-2 text-center mt-lg-3 mt-3">
                            <small class="text-muted mt-3">$21k Expenses more than last month</small>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Expenses -->

            <!-- Profit last month -->
            <div class="col-xl-6 mb-4 col-md-3 col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Profit</h5>
                        <small class="text-muted">Last Month</small>
                    </div>
                    <div class="card-body">
                        <div id="profitLastMonth"></div>
                        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                            <h4 class="mb-0">624k</h4>
                            <small class="text-success">+8.24%</small>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Profit last month -->

            <!-- Generated Leads -->
            <div class="col-xl-12 mb-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <div class="card-title mb-auto">
                                    <h5 class="mb-1 text-nowrap">Generated Leads</h5>
                                    <small>Monthly Report</small>
                                </div>
                                <div class="chart-statistics">
                                    <h3 class="card-title mb-1">4,350</h3>
                                    <small class="text-success text-nowrap fw-medium"><i
                                            class='ti ti-chevron-up me-1'></i> 15.8%</small>
                                </div>
                            </div>
                            <div id="generatedLeadsChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Generated Leads -->
        </div>
    </div>

    <!-- Revenue Report -->
    <div class="col-12 col-xl-8 mb-4">
        <div class="card">
            <div class="card-body p-0">
                <div class="row row-bordered g-0">
                    <div class="col-md-8 position-relative p-4">
                        <div class="card-header d-inline-block p-0 text-wrap position-absolute">
                            <h5 class="m-0 card-title">Revenue Report</h5>
                        </div>
                        <div id="totalRevenueChart" class="mt-n1"></div>
                    </div>
                    <div class="col-md-4 p-4">
                        <div class="text-center mt-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                    id="budgetId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <script>
                                    document.write(new Date().getFullYear())
                                    </script>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="budgetId">
                                    <a class="dropdown-item prev-year1" href="javascript:void(0);">
                                        <script>
                                        document.write(new Date().getFullYear() - 1)
                                        </script>
                                    </a>
                                    <a class="dropdown-item prev-year2" href="javascript:void(0);">
                                        <script>
                                        document.write(new Date().getFullYear() - 2)
                                        </script>
                                    </a>
                                    <a class="dropdown-item prev-year3" href="javascript:void(0);">
                                        <script>
                                        document.write(new Date().getFullYear() - 3)
                                        </script>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-center pt-4 mb-0">$25,825</h3>
                        <p class="mb-4 text-center"><span class="fw-medium">Revenue: </span>56,800</p>
                        <div class="px-3">
                            <div id="budgetChart"></div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary">Increase Budget</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Revenue Report -->


        <div class="col-xl-4 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Popular Bookings</h5>
                        <small class="text-muted">Total 10.4k Visitors</small>
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
                        <li class="d-flex mb-4 pb-1">
                            <div class="me-3">
                                <img src="{{ asset('assets/img/products/iphone.png')}}" alt="User" class="rounded"
                                    width="46">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Ntorq</h6>
                                    <small class="text-muted d-block">CH01AT3403</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <p class="mb-0 fw-medium">$999.29</p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="me-3">
                                <img src="{{ asset('assets/img/products/nike-air-jordan.png')}}" alt="User"
                                    class="rounded" width="46">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Himalayan</h6>
                                    <small class="text-muted d-block">CH01AT3404</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <p class="mb-0 fw-medium">$72.40</p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="me-3">
                                <img src="{{ asset('assets/img/products/headphones.png')}}" alt="User" class="rounded"
                                    width="46">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Royal Enfield 350</h6>
                                    <small class="text-muted d-block">CH01AT3405</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <p class="mb-0 fw-medium">$99</p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="me-3">
                                <img src="{{ asset('assets/img/products/amazon-echo.png')}}" alt="User" class="rounded"
                                    width="46">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Thar roxx</h6>
                                    <small class="text-muted d-block">CH01AT3407</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <p class="mb-0 fw-medium">$79.40</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-12 col-xl-8 mb-4">
            <div class="card">
                <div class="card-header  align-items-center">
                    <h3 class="card-title mb-0">Earning Reports</h3>
                    <h6 class="mb-0"><span class="text-muted me-2">Weekly Earnings Overview</span></h6>

                </div>
                <div class="card-body">
                    <div class="row d-flex align-items-center g-md-8">
                        <div class="col-6 col-md-5 ">
                            <div class=" gap-2 mb-3 flex-wrap">
                                <h2 class="mb-0">$468</h2>
                                <div class="badge rounded bg-label-success">+4.2%</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-5  ">
                            <small class="text-body">You informed of this week compared to last week</small>
                        </div>

                    </div>
                    <div class="border rounded p-5 mt-5">
                        <div class="row gap-4 gap-sm-0">
                            <div class="col-12 col-sm-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="badge rounded bg-label-primary p-1"><i
                                            class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                    <h6 class="mb-0 fw-normal">Earnings</h6>
                                </div>
                                <h4 class="my-2">$545.69</h4>
                                <div class="progress w-75" style="height:4px">
                                    <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="badge rounded bg-label-info p-1"><i
                                            class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                    <h6 class="mb-0 fw-normal">Profit</h6>
                                </div>
                                <h4 class="my-2">$256.34</h4>
                                <div class="progress w-75" style="height:4px">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="badge rounded bg-label-danger p-1"><i
                                            class="ti ti-currency-dollar ti-sm icon-18px"></i></div>
                                    <h6 class="mb-0 fw-normal">Expense</h6>
                                </div>
                                <h4 class="my-2">$74.19</h4>
                                <div class="progress w-75" style="height:4px">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 65%"
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
                                <h6 class="mb-2">In-store Sales</h6>
                                <h4 class="mb-2">$5,345.43</h4>
                                <p class="mb-0"><span class="text-muted me-2">5k orders</span><span
                                        class="badge bg-label-success">+5.7%</span></p>
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
                                <h6 class="mb-2">Website Sales</h6>
                                <h4 class="mb-2">$674,347.12</h4>
                                <p class="mb-0"><span class="text-muted me-2">21k orders</span><span
                                        class="badge bg-label-success">+12.4%</span></p>
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
                            class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                            <div>
                                <h6 class="mb-2">Discount</h6>
                                <h4 class="mb-2">$14,235.12</h4>
                                <p class="mb-0 text-muted">6k orders</p>
                            </div>
                            <span class="avatar p-2 me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-gift text-body"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-2">Affiliate</h6>
                                <h4 class="mb-2">$8,345.23</h4>
                                <p class="mb-0"><span class="text-muted me-2">150 orders</span><span
                                        class="badge bg-label-danger">-3.5%</span></p>
                            </div>
                            <span class="avatar p-2">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-wallet text-body"></i></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
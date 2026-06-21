@extends('layouts/layoutMaster')

@section('title', 'User Profile - Profile')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}">
@endsection

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
@endsection


@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">User Profile /</span> Profile
</h4>


<!-- Header -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    

                         @if(Auth::user()->image)
                            <img src="{{ asset('uploads/profile/' . Auth::user()->image) }}" alt="Avatar Image"
                                class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
                            @else
                            <img src="{{ asset('assets/img/avatars/14.png') }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                            @endif
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">

                            <h4> {{ ucfirst(auth()->user()->name) }}</h4>
                            <ul
                                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-color-swatch'></i> {{ ucfirst(auth()->user()->role) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-map-pin'></i> {{ ucwords(auth()->user()->address) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-calendar'></i> {{ auth()->user()->created_at }}

                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Header -->

<!-- Navbar pills -->
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-sm-row mb-4">
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                        class='ti-xs ti ti-user-check me-1'></i> Profile</a></li>
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
            <li class="nav-item"><a class="nav-link" href="{{url('profile-teams')}}"><i
                        class='ti-xs ti ti-link me-1'></i> Employee</a></li>
            @endif
        </ul>
    </div>
</div>
<!--/ Navbar pills -->

<!-- User Profile Content -->
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-5">
        <!-- About User -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">About</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-user text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Full Name:</span>
                        <span>{{ ucfirst(auth()->user()->name) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-check text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Status:</span>
                        <span>{{ ucfirst(auth()->user()->status) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-crown text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Role:</span>
                        <span>{{ ucfirst(auth()->user()->role) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-flag text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Branch:</span>
                        <span>{{ ucfirst(auth()->user()->branch) }}</span></li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-file-description text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Languages:</span> <span>English </span></li>
                </ul>
                <small class="card-text text-uppercase">Contacts</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-phone-call"></i><span
                            class="fw-medium mx-2 text-heading">Contact:</span>
                        <span>{{ auth()->user()->mobile }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3"><i class='ti ti-map-pin'></i><span
                            class="fw-medium mx-2 text-heading">Address:</span>
                        <span>{{ ucwords(auth()->user()->address) }}</span></li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-mail"></i><span
                            class="fw-medium mx-2 text-heading">Email:</span>
                        <span>{{ ucfirst(auth()->user()->email) }}</span>
                    </li>
                </ul>

            </div>
        </div>







        <!--/ Profile Overview -->
    </div>
    <div class="col-xl-8 col-lg-7 col-md-7">
        <!-- Activity Timeline -->

        <!--/ Activity Timeline -->
        <div class="row g-4">
            @if(Auth::user()->role == 'admin')
            @foreach($users as $user)

            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">

                        <div class="mx-auto my-3">
                        
                            @if( $data['manager']->image)
                            <img src="{{ asset('uploads/profile/' . $data['manager']->image) }}" alt="Avatar Image"
                                class="rounded-circle w-px-100" />
                            @else
                            <img src="{{ asset('assets/img/avatars/12.png') }}" alt="Avatar Image"
                                class="rounded-circle w-px-100" />
                            @endif
                        </div>
                        <h4 class="mb-1 card-title">{{ ucfirst($user['manager']->name)}}</h4>
                        <span class="pb-1">{{ ucfirst($user['manager']->email) }}</span>
                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                            <a href="javascript:;" class="me-1"><span
                                    class="badge bg-label-danger">{{ ucfirst($user['manager']->role )}}</span></a>
                            <a href="javascript:;"><span
                                    class="badge bg-label-info">{{ ucfirst($user['manager']->branch )}}</span></a>
                        </div>

                        <div class="d-flex align-items-center justify-content-around my-3 py-1">
                            <div>
                                <h4 class="mb-0">{{ $user['vehicle_count'] }}</h4>
                                <span>Vehicles</span>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $user['booking_count'] }}</h4>
                                <span>Bookings</span>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $user['customer_count'] }}</h4>
                                <span>customers</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:;"
                                class="btn btn-label-primary d-flex align-items-center me-3 waves-effect"><i
                                    class="ti-xs me-1 ti ti-mail me-1"></i>Connect</a>
                            <a href="javascript:;" class="btn btn-label-secondary btn-icon waves-effect"><i
                                    class="ti ti-phone ti-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
            @else


            @if(Auth::user()->role == 'manager')
            @foreach ($employeesData as $data)
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="dropdown btn-pinned">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="ti ti-dots-vertical text-muted"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
                            </ul>
                        </div>
                        <div class="mx-auto my-3">
                            @if( $data['employee']->image)
                            <img src="{{ asset('uploads/profile/' . $data['employee']->image) }}" alt="Avatar Image"
                                class="rounded-circle w-px-100" />
                            @else
                            <img src="{{ asset('assets/img/avatars/12.png') }}" alt="Avatar Image"
                                class="rounded-circle w-px-100" />
                            @endif
                        </div>
                        <h4 class="mb-1 card-title">{{ ucfirst($data['employee']->name) }}</h4>
                        <span class="pb-1">{{ ucfirst($data['employee']->email )}}</span>
                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                            <a href="javascript:;" class="me-1"><span
                                    class="badge bg-label-danger">{{ ucfirst($data['employee']->role) }}</span></a>
                            <a href="javascript:;"><span
                                    class="badge bg-label-info">{{ ucfirst($data['branch']) }}</span></a>
                        </div>

                        <div class="d-flex align-items-center justify-content-around my-3 py-1">
                            <div>
                                <h4 class="mb-0">{{ $data['total_vehicles'] }}</h4>
                                <span>Vehicles</span>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $data['employee_bookings'] }}</h4>
                                <span>Bookings</span>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $data['employee_customers'] }}</h4>
                                <span>Customer</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:;" class="btn btn-label-primary d-flex align-items-center me-3"><i
                                    class="ti-xs me-1 ti ti-mail me-1"></i>Connect</a>
                            <a href="javascript:;" class="btn btn-label-secondary btn-icon"><i
                                    class="ti ti-phone ti-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            @endif




            <!--/ Teams -->
        </div>
        <!-- Projects table -->

        <!--/ Projects table -->
    </div>
</div>
<!--/ User Profile Content -->
@endsection
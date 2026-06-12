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
                    <img src="{{ asset('assets/img/avatars/14.png') }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">

                            <h4> {{ auth()->user()->name }}</h4>
                            <ul
                                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-color-swatch'></i> {{ auth()->user()->role }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-map-pin'></i> {{ auth()->user()->address }}
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
            <li class="nav-item"><a class="nav-link" href="{{url('profile-teams')}}"><i
                        class='ti-xs ti ti-link me-1'></i> Teams</a></li>
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
                        <span>{{ auth()->user()->name }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-check text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Status:</span> <span>{{ auth()->user()->status }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-crown text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Role:</span> <span>{{ auth()->user()->role }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-flag text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Country:</span> <span>USA</span></li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-file-description text-heading"></i><span
                            class="fw-medium mx-2 text-heading">Languages:</span> <span>English</span></li>
                </ul>
                <small class="card-text text-uppercase">Contacts</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-phone-call"></i><span
                            class="fw-medium mx-2 text-heading">Contact:</span>
                        <span>{{ auth()->user()->mobile }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-brand-skype"></i><span
                            class="fw-medium mx-2 text-heading">Skype:</span> <span>john.doe</span></li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-mail"></i><span
                            class="fw-medium mx-2 text-heading">Email:</span> <span>{{ auth()->user()->email }}</span>
                    </li>
                </ul>
                <small class="card-text text-uppercase">Teams</small>
                <ul class="list-unstyled mb-0 mt-3">

                    <li class="d-flex align-items-center mb-3"><i class="ti ti-brand-angular text-danger me-2"></i>
                        <div class="d-flex flex-wrap"><span class="fw-medium me-2 text-heading">
                                Employee</span><span>
                                @if(Auth::user()->role == 'admin')
                                {{$users->where('approval', 'approve')->count()}}
                                @else
                                {{$users->where('role', 'employee')
                                          ->where('approval', 'approve')
                                          ->where('branch', auth()->user()->branch)
                                          ->count() }}
                                @endif
                            </span></div>
                    </li>

                </ul>
            </div>
        </div>


        <!--/ About User -->
        <!-- Profile Overview -->
        <div class="card mb-4">
            <div class="card-body">
                <p class="card-text text-uppercase">Overview</p>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-check"></i><span
                            class="fw-medium mx-2">Task Compiled:</span> <span>13.5k</span></li>
                    <li class="d-flex align-items-center mb-3"><i class="ti ti-layout-grid"></i><span
                            class="fw-medium mx-2">Projects Compiled:</span> <span>146</span></li>
                    <li class="d-flex align-items-center"><i class="ti ti-users"></i><span
                            class="fw-medium mx-2">Connections:</span> <span>897</span></li>
                </ul>
            </div>
        </div>

         @if(Auth::user()->role == 'admin')
            @foreach($users as $user)
            @if($user->role == 'manager')
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="dropdown btn-pinned">
                    <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                        aria-expanded="false" fdprocessedid="fzyjw"><i
                            class="ti ti-dots-vertical text-muted"></i></button>
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
                    <img src="http://127.0.0.1:8000/assets/img/avatars/3.png" alt="Avatar Image"
                        class="rounded-circle w-px-100">
                </div>
                <h4 class="mb-1 card-title">{{ $user->name }}</h4>
                <span class="pb-1">{{ $user->email }}</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <a href="javascript:;" class="me-1"><span class="badge bg-label-secondary">{{ $user->role }}</span></a>
                    <a href="javascript:;"><span class="badge bg-label-warning">{{ $user->branch }}</span></a>
                </div>

                <div class="d-flex align-items-center justify-content-around my-3 py-1">
                    <div>
                        <h4 class="mb-0">18</h4>
                        <span>Vehicles</span>
                    </div>
                    <div>
                        <h4 class="mb-0">834</h4>
                        <span>Bookings</span>
                    </div>
                    <div>
                        <h4 class="mb-0">129</h4>
                        <span>Customer</span>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="javascript:;"
                        class="btn btn-primary d-flex align-items-center me-3 waves-effect waves-light"><i
                            class="ti-xs me-1 ti ti-user-check me-1"></i>Connected</a>
                    <a href="javascript:;" class="btn btn-label-secondary btn-icon waves-effect"><i
                            class="ti ti-mail ti-sm"></i></a>
                </div>
            </div>
        </div>
        @endif

        @endforeach
        @endif





        <!--/ Profile Overview -->
    </div>
    <div class="col-xl-8 col-lg-7 col-md-7">
        <!-- Activity Timeline -->

        <!--/ Activity Timeline -->
        <div class="row g-4">
            @if(Auth::user()->role == 'admin')
            @foreach($users as $user)

            @if($user->role == 'employee')
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="dropdown btn-pinned">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                                aria-expanded="false" fdprocessedid="l9e9tp"><i
                                    class="ti ti-dots-vertical text-muted"></i></button>
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
                            <img src="http://127.0.0.1:8000/assets/img/avatars/12.png" alt="Avatar Image"
                                class="rounded-circle w-px-100">
                        </div>
                        <h4 class="mb-1 card-title">{{ $user->name }}</h4>
                        <span class="pb-1">{{ $user->role }}</span>
                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                            <a href="javascript:;" class="me-1"><span class="badge bg-label-danger">Angular</span></a>
                            <a href="javascript:;"><span class="badge bg-label-info">React</span></a>
                        </div>

                        <div class="d-flex align-items-center justify-content-around my-3 py-1">
                            <div>
                                <h4 class="mb-0">112</h4>
                                <span>Projects</span>
                            </div>
                            <div>
                                <h4 class="mb-0">23.1k</h4>
                                <span>Tasks</span>
                            </div>
                            <div>
                                <h4 class="mb-0">1.28k</h4>
                                <span>Connections</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:;"
                                class="btn btn-label-primary d-flex align-items-center me-3 waves-effect"><i
                                    class="ti-xs me-1 ti ti-user-plus me-1"></i>Connect</a>
                            <a href="javascript:;" class="btn btn-label-secondary btn-icon waves-effect"><i
                                    class="ti ti-mail ti-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif

            @if(Auth::user()->role == 'manager')
            @foreach($users as $user)
            @if($user->role == 'employee')
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="dropdown btn-pinned">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                                aria-expanded="false" fdprocessedid="fzyjw"><i
                                    class="ti ti-dots-vertical text-muted"></i></button>
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
                            <img src="http://127.0.0.1:8000/assets/img/avatars/3.png" alt="Avatar Image"
                                class="rounded-circle w-px-100">
                        </div>
                        <h4 class="mb-1 card-title">{{ $user->name}}</h4>
                        <span class="pb-1">UI Designer</span>
                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                            <a href="javascript:;" class="me-1"><span class="badge bg-label-secondary">Figma</span></a>
                            <a href="javascript:;"><span class="badge bg-label-warning">Sketch</span></a>
                        </div>

                        <div class="d-flex align-items-center justify-content-center my-3 py-1">
                            <div>
                                <h4 class="mb-0">18</h4>
                                <span class="p-2">Projects</span>
                            </div>

                            <div>
                                <h4 class="mb-0">129</h4>
                                <span class="p-2">Connections</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:;"
                                class="btn btn-primary d-flex align-items-center me-3 waves-effect waves-light"><i
                                    class="ti-xs me-1 ti ti-user-check me-1"></i>Connected</a>
                            <a href="javascript:;" class="btn btn-label-secondary btn-icon waves-effect"><i
                                    class="ti ti-mail ti-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif


            @if(Auth::user()->role == 'employee')
            @foreach($users as $user)
            @if($user->role == 'employee')
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="dropdown btn-pinned">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                                aria-expanded="false" fdprocessedid="fzyjw"><i
                                    class="ti ti-dots-vertical text-muted"></i></button>
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
                            <img src="http://127.0.0.1:8000/assets/img/avatars/3.png" alt="Avatar Image"
                                class="rounded-circle w-px-100">
                        </div>
                        <h4 class="mb-1 card-title">{{ $user->name}}</h4>
                        <span class="pb-1">{{ $user->role}}</span>
                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                            <a href="javascript:;" class="me-1"><span class="badge bg-label-secondary">Figma</span></a>
                            <a href="javascript:;"><span class="badge bg-label-warning">Sketch</span></a>
                        </div>

                        <div class="d-flex align-items-center justify-content-center my-3 py-1">
                            <div>
                                <h4 class="mb-0">18</h4>
                                <span class="p-2">Projects</span>
                            </div>

                            <div>
                                <h4 class="mb-0">129</h4>
                                <span class="p-2">Connections</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="javascript:;"
                                class="btn btn-primary d-flex align-items-center me-3 waves-effect waves-light"><i
                                    class="ti-xs me-1 ti ti-user-check me-1"></i>Connected</a>
                            <a href="javascript:;" class="btn btn-label-secondary btn-icon waves-effect"><i
                                    class="ti ti-mail ti-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif




            <!--/ Teams -->
        </div>
        <!-- Projects table -->

        <!--/ Projects table -->
    </div>
</div>
<!--/ User Profile Content -->
@endsection
@extends('layouts/layoutMaster')

@section('title', 'Loyal Customer - Connections')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')



<div class="row g-4">

    @foreach($loyalcustomer as $customerdetails)
    @if($customerdetails->booking_count == 5 || $customerdetails->booking_count >= 10)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body text-center">

                <div class="mx-auto my-3">
                    <img src="{{ asset('assets/img/avatars/12.png') }}" alt="Avatar Image"
                        class="rounded-circle w-px-100" />
                </div>
                <h4 class="mb-1 card-title">{{$customerdetails->name}} {{ $customerdetails->booking_count }}
                </h4>
                <span class="pb-1">{{$customerdetails->email}}</span>
                <div class="d-flex align-items-center justify-content-center my-3 gap-2">
                    <a href="javascript:;"><span class="badge bg-label-info">{{$customerdetails->branch}}</span></a>
                </div>
                <div class="d-flex align-items-center justify-content-around my-3 py-1">

                    <div>
                        
                        <h4 class="mb-0">
                            <div class="rating">
                                <!-- <span> single star mean 5 count  6th booking 50%off</span> -->
                                @if($customerdetails->booking_count == 5)
                                <i class="fas fa-star text-warning fs-2"></i>
                                @elseif($customerdetails->booking_count >= 10)
                                <i class="fas fa-star text-danger fs-2"></i>
                                <i class="fas fa-star text-warning fs-1"></i>
                                <i class="fas fa-star text-danger fs-2"></i>
                                @endif
                            </div>
                         
                        </h4>
                        <span>Rating</span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{$customerdetails->phone_number}}</h4>
                        <span>Mobile</span>
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
    @endif
    @endforeach



</div>
<!--/ Connection Cards -->
@endsection
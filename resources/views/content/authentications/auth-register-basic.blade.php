@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
<script>
$(document).ready(function() {
    $('.userdata').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        //alert('Button clicked!'); // Alert to confirm button click
        var formdata = $('#formAuthentication').serialize(); // Serialize form data
        //alert(formdata); // Log the serialized form data to the console
        $.ajax({
            url: "{{ route('auth-register') }}", // URL to send the POST request to
            method: "POST", // HTTP method
            data: formdata, // Data to be sent in the request
            dataType: 'json', // Expected response type
            success: function(response) {

                if (response.status === 'success') {
                    window.location.href =
                        "{{ route('auth-login') }}"; // Redirect to the login page

                } else {

                    alert('Registration failed!');

                }

            },

            error: function(xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });

                    alert(errorMessage);

                } else {

                    alert('Something went wrong!');

                }

            }
        })
    });
});
</script>
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">

            <!-- Register Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="{{url('/')}}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20,"withbg"=>'fill:
                                #fff;'])</span>
                            <span
                                class="app-brand-text demo text-body fw-bold ms-1">{{config('variables.templateName')}}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2">Adventure starts here 🚀</h4>
                    <p class="mb-4">Make your service management easy and fun!</p>

                    <form id="formAuthentication" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="Name"
                                placeholder="Enter your full name" autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>

                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Gender" id="male" value="Male">
                                    <label class="form-check-label" for="male">
                                        Male
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Gender" id="female"
                                        value="Female">
                                    <label class="form-check-label" for="female">
                                        Female
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Gender" id="other" value="Other">
                                    <label class="form-check-label" for="other">
                                        Other
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="Mobile"
                                placeholder="Enter your mobile number" autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="Address" class="form-label">Address</label>
                            <input type="text" name="Address" class="form-control" placeholder="Address"
                                aria-label="Address" autocomplete="address">
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch</label>
                            <select id="branch" name="branch" class="form-select" required>
                                <option value="">Select Branch</option>
                                @if($vehiclelocations->whereNotNull('branch')->count())
                                @foreach($vehiclelocations as $location)
                                @if(!empty($location->branch))
                                <option value="{{ $location->branch }}">
                                    {{ $location->branch }}
                                </option>
                                @endif
                                @endforeach
                                @else
                                <option value="Nainital">Nainital</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="Email"
                                placeholder="Enter your email" autocomplete="email">
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="Password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                                <label class="form-check-label" for="terms-conditions">
                                    I agree to
                                    <a href="javascript:void(0);">privacy policy & terms</a>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="userdata btn btn-primary d-grid w-100">
                            Sign up
                        </button>

                    </form>

                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="{{url('/')}}">
                            <span>Sign in instead</span>
                        </a>
                    </p>


                </div>
            </div>
            <!-- Register Card -->
        </div>
    </div>
</div>
@endsection
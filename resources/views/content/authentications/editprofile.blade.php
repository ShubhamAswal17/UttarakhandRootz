@extends('layouts/layoutMaster')

@section('title', 'Account settings - Account')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />

@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>

<script>
$(document).ready(function() {
    $('#formAccountSettingsbtn').on('click', function(e) {
        e.preventDefault();

        let formData = new FormData($('#formAccountSettings')[0]);

        $.ajax({
            url: '/editprofile/update',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);

                if (xhr.status == 422) {
                    console.log(xhr.responseJSON.errors);
                }
            }
        });
    });
    $('.deactivate-account').on('click', function(e) {
        e.preventDefault();

        var data = $(this).closest('form').serialize();

        console.log(data);
        $.ajax({
            url: '/deactivate-account',
            type: 'POST',
            data: data,
            success: function(response) {

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            }).then(() => {
                window.location.href = '/';
            });

        },
        error: function(xhr) {
            console.log(xhr.responseJSON);
        }
        });
    });


});
</script>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="card mb-2">
            <h5 class="card-header">Profile Details</h5>
            <!-- Account -->
            <div class="card-body">
                <form id="formAccountSettings" onsubmit="return false">
                    @csrf
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{  asset('uploads/profile/' . Auth::user()->image) }}" alt="user-avatar"
                            class="d-block w-px-100 h-px-100 rounded" name="picture" id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="ti ti-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" class="account-file-input" name="userpic" hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                                <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <div class="text-muted">Allowed JPG, GIF or PNG. Max size of 800K</div>
                        </div>
                    </div>
            </div>
            <hr class="my-0">
            <div class="card-body">

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label">Full Name</label>
                        <input class="form-control" type="text" id="firstName" name="Name"
                            value="{{auth()->user()->name}}" autofocus />
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="text" id="email" name="Email"
                            value="{{auth()->user()->email}}" />
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Gender</label>

                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Gender" id="male" value="Male"
                                    {{ auth()->user()->gender == 'Male' ? 'checked' : '' }}>
                                <label class="form-check-label" for="male">
                                    Male
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Gender" id="female" value="Female"
                                    {{ auth()->user()->gender == 'Female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="female">
                                    Female
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Gender" id="other" value="Other"
                                    {{ auth()->user()->gender == 'Other' ? 'checked' : '' }}>
                                <label class="form-check-label" for="other">
                                    Other
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="phoneNumber">Phone Number</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">IN (+91)</span>
                            <input type="text" id="mobile" name="Mobile" class="form-control"
                                value="{{auth()->user()->mobile}}" placeholder="202 555 0111" />
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="Address"
                            value="{{auth()->user()->address}}" placeholder="Address" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="designation" class="form-label">Designation</label>
                        <input class="form-control" type="text" id="designation" value="{{auth()->user()->designation}}"
                            readonly placeholder="California" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="oldPassword" class="form-label">Old Password</label>
                        <input type="Password" class="form-control" id="oldPassword" name="oldPassword"
                            placeholder="231465" minlength="6" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="Password" class="form-label">New Password</label>
                        <input type="Password" class="form-control" id="Password" name="newPassword"
                            placeholder="231465" minlength="6" />
                    </div>

                </div>
                <div class="mt-2">
                    <button type="submit" id="formAccountSettingsbtn" class="btn btn-primary me-2">Save changes</button>
                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
        <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                <div class="mb-3 col-12 mb-0">
                    <div class="alert alert-warning">
                        <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                        <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                    </div>
                </div>
                <form id="formAccountDeactivation" onsubmit="return false">
                    @csrf
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="accountActivation"
                            id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">I confirm my account
                            deactivation</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
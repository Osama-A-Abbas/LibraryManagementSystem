@extends('layouts.book-layout')

@section('title', 'Profile Management')

@section('content')
    <div class="row">
        <div class="col-md-10 offset-1 mt-5">
            <h1>Profile</h1>

            <div class="card mb-4">
                <div class="card-header">Profile Information</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Update Password</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Delete Account</div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('base')

@section('title', 'Change password')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <h1 class="text-center text-muted mb-3 mt-5">Change password</h1>
            <p class="text-center text-muted mb-3">Please enter your new password</p>

            <form action="{{ route('app_change_password', ['token'=>$activation_token]) }}" method="post">
                @csrf

                 {{-- on inclut les messages d'alerte --}}
                @include('alerts.alert-message')

                <label for="new-password" class="form-label">New password</label>
                <input type="password" name="new-password" id="new-password" class="form-control mb-3 @error('password-error') is-invalid @enderror @error('password-success') is-valid @enderror" value="@if(Session::has('old_new_password')){{Session::get('old_new_password')}}@endif" placeholder="Enter the new password">

                <label for="new-password-confirm" class="form-label">New password confirmation</label>
                <input type="password" name="new-password-confirm" id="new-password-confirm" class="form-control mb-3 @error('password-confirm-error') is-invalid @enderror" placeholder="Confirm your new password" value="@if(Session::has('old_password_confirm')){{Session::get('old_password_confirm')}}@endif">

                <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-primary" type="submit">Change password</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@extends('base')

@section('title', 'Account activation')

@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <h1 class="text-center text-muted mb-3 mt-5">Account Activation</h1>

            {{-- on inclut les messages d'alerte --}}
                @include('alerts.alert-message')

        <form action="{{ route('app_activation_code', ['token'=> $token]) }}" method="post">
            @csrf
            <div class="mb-2">
                <label for="activation_code" class="form-label md-6">Activation Code</label>
                <input type="text" name="activation_code" id="activation_code" class="form-control @if(Session::has('danger')) is-invalid @endif" value="@if(Session::has('activation_code')) {{ Session::get('activation_code') }} @endif" required>
            </div>

            <div class="row mb-3" >
                <div class="col-md-6">
                    <a href="{{ route('app_change_email_address', ['token'=>$token]) }}">Change your email address</a>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('app_resend_activation_code', ['token'=>$token]) }}">Resend the activation code</a>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Activate</button>
            </div>


        </form>
        </div>


    </div>


</div>



@endsection

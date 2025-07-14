@extends('base')

@section('title', 'Change your email address')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <h2 class="text-center text-muted mb-3 mt-5">Change Email Address</h2>

                {{-- on inclut les messages d'alerte --}}
                @include('alerts.alert-message')

                <form action="{{ route('app_change_email_address', ['token'=>$token]) }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="new-email" class="form-label md-6 text-center">New email address </label>
                        <input type="email" name="new-email" id="new-email" placeholder="Enter the new email address" class="form-control @if(Session::has('danger')) is-invalid @endif" value="@if(Session::has('new_email')) {{ Session::get('new_email') }} @endif" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Change email address</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@extends('base')

@section('title', 'Forgot password')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <h1 class="text-center text-muted mb-3 mt-5">Forgot password</h1>
            <p class="text-center text-muted mb-3">Please enter your email address. we'll send you a link to reset your password</p>

            <form method="POST" action="{{ route('app_forgot_password') }}">
                {{-- protéger le formulaire contre les attaques et injections --}}
                @csrf

                {{-- on inclut les messages d'alerte --}}
                @include('alerts.alert-message')

                @error('email')
               <div class="alert alert-danger text-center" role="alert">
                    {{$message}}
               </div>
                @enderror
                @error('password')
               <div class="alert alert-danger text-center" role="alert">
                    {{$message}}
               </div>
                @enderror

                <label for="email-send" class="form-label">Email</label>
                <input type="email" name="email-send" id="email-send" class="form-control mb-3 @error('email-success') is-valid @enderror @error('email-error') is-invalid @enderror" value=" @if(Session::has('old_email')) {{Session::get('old_email')}}  @endif "  required autocomplete="email" placeholder="Enter your mail" autofocus>

                <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-primary" type="submit">Reset password</button>
                </div>

                <p class="text-center text-muted">Back to <a href="{{ route('login') }}">login </a></p>
            </form>

        </div>
    </div>
</div>

@endsection()

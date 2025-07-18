@extends('base')

@section('title', 'Login')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <h1 class="text-center text-muted mb-3 mt-5">Please sign in</h1>
            <p class="text-center text-muted mb-3">Your articles are waiting for you</p>

            <form method="POST" action="{{ route('login') }}">
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

                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control mb-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>

                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control mb-2 @error('password') is-invalid @enderror" required autocomplete="current-password">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="remember" name="remember" {{ old('remember') ? 'checked' : ''}}>
                            <label class="form-check-label" for="remember" class="form-label">Remember me</label>
                        </div>
                    </div>

                    <div class="col-md-6 text-end">
                        <a href="{{ route('app_forgot_password') }}">Forgot password?</a>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Sign in</button>
                </div>
                    <p class="text-center text-muted mt-3">Not registered yet? <a href="{{ route('register') }}">Create an account</a></p-text-center>
            </form>
    </div>
</div>

@endsection()

    <nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link @if (Request::route()->getName() == 'app_home') active @endif" aria-current="page" href="{{ route('app_home') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if (Request::route()->getName() == 'app_about') active @endif" aria-current="page" href="{{ route('app_about') }}">About</a>
        </li>
      </ul>
    </div>
  </div>
  <div class="btn-group">
    @guest
         <a href="{{ route('login') }}" class="btn btn-light aria-expanded="false>Login</a>
         <a href="{{ route('register') }}" class="btn btn-light aria-expanded="false>Register</a>
    @endguest
    {{-- <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        My account
    </button>
    <ul class="dropdown-menu">
        <li class="dropdown-item" href="{{ route('login') }}">Login</li>
        <li class="dropdown-item" href="{{ route('register') }}">Register</li>
    </ul> --}}

    @auth
    <button type="button" class="btn btn-light aria-expanded="false>
        {{ Auth::user()->name }}
    </button>
    <a href="{{ route('app_logout') }}" class="btn btn-light aria-expanded="false>Logout</a>

    @endauth

</div>
</nav>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ config('app.name') }} - @yield('title') </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">



</head>

<body>
{{--Notre navbar --}}
    @include('navbar.navbar')

    {{--Tous nos contenus seront affichés ici--}}
    @yield('content')

{{--Nos scripts javascript--}}
    @include('script')
</body>

</html>

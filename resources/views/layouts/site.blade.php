<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RSHP') }}</title>
    <style>
        /* Minimal site layout styles (no Bootstrap/AdminLTE) */
        body { font-family: Arial, Helvetica, sans-serif; margin:0; padding:0; background:#f5f7fa; color:#222; }
        .site-header { display:flex; justify-content:space-between; align-items:center; padding:14px 24px; background:#fff; box-shadow: 0 1px 0 rgba(0,0,0,0.05); }
        .site-header .brand { font-weight:700; color:#0b2d82; text-decoration:none; }
        .site-header .nav { display:flex; gap:12px; }
        .site-header .nav a { color:#0b2d82; text-decoration:none; font-weight:600; }
        main { padding: 24px; max-width:1100px; margin: 0 auto; }
    </style>
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ url('/') }}">{{ config('app.name', 'RSHP') }}</a>
        <nav class="nav">
            @guest
                @if (Route::has('login'))<a href="{{ route('login') }}">Login</a>@endif
                @if (Route::has('register'))<a href="{{ route('register') }}">Register</a>@endif
            @else
                <a href="#">{{ Auth::user()->nama }}</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            @endguest
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>

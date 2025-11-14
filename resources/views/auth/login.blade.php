<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'RSHP') }}</title>
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-card">
        <h1 class="login-title">Login</h1>
        <p class="login-subtitle">{{ config('app.name', 'RSHP') }}</p>
        
        @if ($errors->any())
            <div class="alert-danger">
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div style="margin-bottom: 16px;">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; margin-top: 4px;"
                >
            </div>
            
            <div style="margin-bottom: 16px;">
                <label for="password" class="form-label">Password</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; margin-top: 4px;"
                >
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; color: #0B2D82;">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember Me
                </label>
            </div>
            
            <button 
                type="submit" 
                class="btn-primary"
                style="width: 100%; padding: 14px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.3s ease;"
            >
                Login
            </button>
            
            @if (Route::has('password.request'))
                <div style="text-align: center; margin-top: 16px;">
                    <a href="{{ route('password.request') }}" style="color: #245ee7; text-decoration: none; font-size: 14px;">
                        Forgot Your Password?
                    </a>
                </div>
            @endif
        </form>
    </div>
</body>
</html>

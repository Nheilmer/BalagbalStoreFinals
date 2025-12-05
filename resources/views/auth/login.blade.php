@extends('app-guest')

@section('title', 'Login - Balagbal Store')

@section('css')
    @vite(['resources/css/login.css']) 
    {{-- <link rel="stylesheet" href="{{ asset('css/login.css') }}"> --}}
@endsection

@section('content')
    <div class="login-wrapper">
        <!-- Left Branding Section -->
        <div class="login-branding">
            <div class="branding-content">
                <div class="brand-logo">
                    🛍️
                </div>
                <h1>Balagbag Store</h1>
                <p>Welcome back! Access your account to continue shopping</p>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h2>Login</h2>
                    <p>Sign in to your account</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($errors->has('message'))
                        <div class="alert alert-error">
                            {{ $errors->first('message') }}
                        </div>
                    @endif

                    <button type="submit" class="btn-login">Login</button>
                </form>

                <div class="divider">or</div>

                <div class="login-footer">
                    <p>New here?</p>
                    <p>Don't have an account yet?</p>
                    <a href="/register" class="btn-register">Create Account</a>
                </div>
            </div>
        </div>
    </div>
@endsection

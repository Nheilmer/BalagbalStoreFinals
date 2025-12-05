@extends('app-guest')

@section('title', 'Register - Balagbal Store')

@section('css')
    @vite(['resources/css/register.css'])
    {{-- <link rel="stylesheet" href="{{ asset('css/register.css') }}"> --}}
@endsection

@section('content')
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1>Create Account</h1>
                <p>Join Balagbal Store today</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="register-form">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Choose a username" value="{{ old('username') }}" required>
                        @error('username')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" value="{{ old('firstname') }}" required>
                        @error('firstname')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" value="{{ old('lastname') }}" required>
                        @error('lastname')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="address">Address <span class="optional">(Optional)</span></label>
                    <input type="text" id="address" name="address" placeholder="Enter your address" value="{{ old('address') }}">
                </div>

                <div class="form-group full-width">
                    <label for="phone">Phone Number <span class="optional">(Optional)</span></label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" value="{{ old('phone') }}">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-register">Register</button>
                </div>
            </form>

            <div class="divider">or</div>

            <div class="register-footer">
                <p>Already have an account? <a href="/login" class="link-login">Sign in here</a></p>
            </div>
        </div>
    </div>
@endsection

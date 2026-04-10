@extends('layouts.main')
@section('title', 'Login')

@section('content')
<section class="login-section">
<div class="login-card">
<h2>Login to Your Account</h2>

<form method="POST" action="{{ route('login') }}">
@csrf
<input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
@error('email')<div class="auth-error">{{ $message }}</div>@enderror

<input type="password" name="password" placeholder="Password" required>
@error('password')<div class="auth-error">{{ $message }}</div>@enderror

<label style="display:block;text-align:left;margin:10px 0;font-size:13px;">
<input type="checkbox" name="remember"> Remember me
</label>

<button type="submit" class="login-btn">Login</button>

<p class="register-text">
Don't have an account?
<a href="{{ route('register') }}">Register</a>
</p>
</form>
</div>
</section>
@endsection

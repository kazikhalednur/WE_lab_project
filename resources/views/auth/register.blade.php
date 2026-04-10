@extends('layouts.main')
@section('title', 'Register')

@section('content')
<section class="login-section">
<div class="login-card" style="width:400px;">
<h2>Create Account</h2>

<form method="POST" action="{{ route('register') }}">
@csrf
<input type="text"  name="name"     placeholder="Full Name"        value="{{ old('name') }}" required autofocus>
@error('name')<div class="auth-error">{{ $message }}</div>@enderror

<input type="email" name="email"    placeholder="Email Address"   value="{{ old('email') }}" required>
@error('email')<div class="auth-error">{{ $message }}</div>@enderror

<input type="password" name="password" placeholder="Password" required>
@error('password')<div class="auth-error">{{ $message }}</div>@enderror

<input type="password" name="password_confirmation" placeholder="Confirm Password" required>

<select name="role" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ccc;border-radius:5px;">
<option value="attendee"  {{ old('role','attendee')==='attendee' ? 'selected':'' }}>Attendee — book event tickets</option>
<option value="organizer" {{ old('role')==='organizer' ? 'selected':'' }}>Organizer — host my own events</option>
</select>
@error('role')<div class="auth-error">{{ $message }}</div>@enderror

<button type="submit" class="login-btn">Register</button>

<p class="register-text">
Already have an account?
<a href="{{ route('login') }}">Login</a>
</p>
</form>
</div>
</section>
@endsection

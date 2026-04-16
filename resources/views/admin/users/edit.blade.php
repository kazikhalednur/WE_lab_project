@extends('layouts.main')
@section('title', 'Edit User')

@section('content')
<section class="container">
<h1>Edit User #{{ $user->id }}</h1>

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="form">
@csrf @method('PUT')
<div class="row"><label>Name</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
<div class="row"><label>Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
<div class="row"><label>Role</label>
<select name="role" required>
<option value="attendee"  {{ $user->role==='attendee'?'selected':'' }}>Attendee</option>
<option value="organizer" {{ $user->role==='organizer'?'selected':'' }}>Organizer</option>
<option value="admin"     {{ $user->role==='admin'?'selected':'' }}>Admin</option>
</select></div>
<div class="row"><label>New Password (optional)</label><input type="password" name="password"></div>
<div class="row"><label>Confirm Password</label><input type="password" name="password_confirmation"></div>
<div class="row">
<button class="btn">Update</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
</div>
</form>
</section>
@endsection

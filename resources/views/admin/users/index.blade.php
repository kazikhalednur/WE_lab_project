@extends('layouts.main')
@section('title', 'Manage Users')

@section('content')
<section class="container">
<h1>Users</h1>

<form method="GET" action="{{ route('admin.users.index') }}" style="margin:20px 0;">
<input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or email..."
style="padding:8px;border:1px solid #ccc;border-radius:5px;width:280px;">
<select name="role" style="padding:8px;border:1px solid #ccc;border-radius:5px;">
<option value="">All Roles</option>
<option value="attendee"  {{ request('role')==='attendee' ? 'selected':'' }}>Attendee</option>
<option value="organizer" {{ request('role')==='organizer' ? 'selected':'' }}>Organizer</option>
<option value="admin"     {{ request('role')==='admin' ? 'selected':'' }}>Admin</option>
</select>
<button class="btn">Filter</button>
</form>

<table class="table">
<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
<tbody>
@forelse($users as $u)
<tr>
<td>{{ $u->id }}</td>
<td>{{ $u->name }}</td>
<td>{{ $u->email }}</td>
<td><span class="role-badge role-{{ $u->role }}">{{ $u->role }}</span></td>
<td>{{ $u->created_at->format('d M Y') }}</td>
<td>
<a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warn">Edit</a>
@if($u->id !== auth()->id())
<form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline;"
onsubmit="return confirm('Delete this user and all their data?');">
@csrf @method('DELETE')
<button class="btn btn-sm btn-danger">Delete</button>
</form>
@endif
</td>
</tr>
@empty
<tr><td colspan="6">No users found.</td></tr>
@endforelse
</tbody>
</table>

<div class="pagination-wrap">{{ $users->links() }}</div>
</section>
@endsection

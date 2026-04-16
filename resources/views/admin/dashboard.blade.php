@extends('layouts.main')
@section('title', 'Admin Dashboard')

@section('content')
<section class="container">
<h1>Admin Dashboard</h1>

<div class="stats">
<div class="stat-card"><span class="value">{{ $stats['users'] }}</span><span class="label">Total Users</span></div>
<div class="stat-card"><span class="value">{{ $stats['attendees'] }}</span><span class="label">Attendees</span></div>
<div class="stat-card"><span class="value">{{ $stats['organizers'] }}</span><span class="label">Organizers</span></div>
<div class="stat-card"><span class="value">{{ $stats['events'] }}</span><span class="label">Events</span></div>
<div class="stat-card"><span class="value">{{ $stats['published'] }}</span><span class="label">Published</span></div>
<div class="stat-card"><span class="value">{{ $stats['tickets_sold'] }}</span><span class="label">Tickets Sold</span></div>
<div class="stat-card"><span class="value">{{ $stats['checked_in'] }}</span><span class="label">Checked-in</span></div>
<div class="stat-card"><span class="value">{{ number_format($stats['revenue'], 0) }}</span><span class="label">Revenue (BDT)</span></div>
</div>

<div style="margin:20px 0;">
<a href="{{ route('admin.users.index') }}" class="btn">Manage Users</a>
<a href="{{ route('admin.events') }}" class="btn">Manage Events</a>
<a href="{{ route('scanner.index') }}" class="btn btn-secondary">QR Scanner</a>
</div>

<h2>Recent Tickets</h2>
<table class="table">
<thead><tr><th>Code</th><th>Attendee</th><th>Event</th><th>Category</th><th>Status</th><th>Booked</th></tr></thead>
<tbody>
@forelse($recentTickets as $t)
<tr>
<td><span class="code">{{ $t->unique_code }}</span></td>
<td>{{ $t->user->name ?? '—' }}</td>
<td>{{ $t->event->title ?? '—' }}</td>
<td>{{ $t->category->name ?? '—' }}</td>
<td>
<span class="status-badge status-{{ $t->payment_status }}">{{ strtoupper($t->payment_status) }}</span>
@if($t->is_used)<span class="status-badge status-used">USED</span>@endif
</td>
<td>{{ $t->created_at->diffForHumans() }}</td>
</tr>
@empty
<tr><td colspan="6">No tickets yet.</td></tr>
@endforelse
</tbody>
</table>
</section>
@endsection

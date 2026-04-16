@extends('layouts.main')
@section('title', 'Manage Events')

@section('content')
<section class="container">
<h1>All Events</h1>

<form method="GET" action="{{ route('admin.events') }}" style="margin:20px 0;">
<input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title..."
style="padding:8px;border:1px solid #ccc;border-radius:5px;width:280px;">
<button class="btn">Search</button>
</form>

<table class="table">
<thead><tr><th>ID</th><th>Title</th><th>Organizer</th><th>Date</th><th>Status</th><th>Sold</th><th>Actions</th></tr></thead>
<tbody>
@forelse($events as $e)
<tr>
<td>{{ $e->id }}</td>
<td>{{ $e->title }}</td>
<td>{{ $e->organizer->name ?? '—' }}</td>
<td>{{ $e->date->format('d M Y') }}</td>
<td><span class="status-badge status-{{ $e->status }}">{{ strtoupper($e->status) }}</span></td>
<td>{{ $e->sold }}</td>
<td>
<a href="{{ route('events.show', $e) }}" class="btn btn-sm">View</a>
<form method="POST" action="{{ route('admin.events.destroy', $e) }}" style="display:inline;"
onsubmit="return confirm('Delete this event?');">
@csrf @method('DELETE')
<button class="btn btn-sm btn-danger">Delete</button>
</form>
</td>
</tr>
@empty
<tr><td colspan="7">No events.</td></tr>
@endforelse
</tbody>
</table>

<div class="pagination-wrap">{{ $events->links() }}</div>
</section>
@endsection

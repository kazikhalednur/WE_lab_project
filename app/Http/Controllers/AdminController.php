<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'users'        => User::count(),
            'attendees'    => User::where('role', User::ROLE_ATTENDEE)->count(),
            'organizers'   => User::where('role', User::ROLE_ORGANIZER)->count(),
            'events'       => Event::count(),
            'published'    => Event::where('status', Event::STATUS_PUBLISHED)->count(),
            'tickets_sold' => Ticket::where('payment_status', 'paid')->count(),
            'revenue'      => (float) DB::table('tickets')
                ->join('ticket_categories', 'ticket_categories.id', '=', 'tickets.category_id')
                ->where('tickets.payment_status', 'paid')
                ->sum('ticket_categories.price'),
            'checked_in'   => Ticket::where('is_used', true)->count(),
        ];

        $recentTickets = Ticket::with('user', 'event', 'category')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTickets'));
    }

    public function index(Request $request): View
    {
        $query = User::query()->orderBy('id');
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }
        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,'.$user->id],
            'role'     => ['required', 'in:attendee,organizer,admin'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->role  = $data['role'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('status', 'You cannot delete yourself.');
        }
        $user->delete();
        return back()->with('status', 'User deleted.');
    }

    public function events(Request $request): View
    {
        $query = Event::with('organizer')
            ->withCount(['tickets as sold' => fn ($q) => $q->where('payment_status', 'paid')])
            ->orderByDesc('id');

        if ($search = $request->input('q')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $events = $query->paginate(10)->withQueryString();
        return view('admin.events', compact('events'));
    }

    public function destroyEvent(Event $event): RedirectResponse
    {
        $event->delete();
        return back()->with('status', 'Event deleted.');
    }
}

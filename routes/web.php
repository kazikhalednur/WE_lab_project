<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/',          [EventController::class, 'home'])->name('home');
Route::get('/events',    [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/about',     [PageController::class, 'about'])->name('about');
Route::get('/contact',   [PageController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Smart redirect to the right dashboard based on role
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'organizer' => redirect()->route('organizer.dashboard'),
            default     => redirect()->route('attendee.dashboard'),
        };
    })->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |---------------------------------------------------------------
    | ATTENDEE
    |---------------------------------------------------------------
    */
    Route::middleware('role:attendee')
        ->prefix('attendee')->name('attendee.')->group(function () {
            Route::get('/dashboard',          [BookingController::class, 'dashboard'])->name('dashboard');
            Route::get('/book/{event}',       [BookingController::class, 'create'])->name('book');
            Route::post('/book/{event}',      [BookingController::class, 'store'])->name('book.store');
            Route::get('/tickets',            [BookingController::class, 'tickets'])->name('tickets');
            Route::post('/tickets/{ticket}/pay', [BookingController::class, 'pay'])->name('tickets.pay');
            Route::get('/tickets/{ticket}/pdf',  [BookingController::class, 'pdf'])->name('tickets.pdf');
        });

    /*
    |---------------------------------------------------------------
    | ORGANIZER
    |---------------------------------------------------------------
    */
    Route::middleware('role:organizer')
        ->prefix('organizer')->name('organizer.')->group(function () {
            Route::get('/dashboard', [EventController::class, 'organizerDashboard'])->name('dashboard');

            Route::get('/events/create',           [EventController::class, 'create'])->name('events.create');
            Route::post('/events',                 [EventController::class, 'store'])->name('events.store');
            Route::get('/events/{event}/edit',     [EventController::class, 'edit'])->name('events.edit');
            Route::put('/events/{event}',          [EventController::class, 'update'])->name('events.update');
            Route::delete('/events/{event}',       [EventController::class, 'destroy'])->name('events.destroy');
            Route::post('/events/{event}/toggle',  [EventController::class, 'toggleStatus'])->name('events.toggle');
            Route::get('/events/{event}/analytics',[EventController::class, 'analytics'])->name('events.analytics');
            Route::get('/events/{event}/attendees.csv', [EventController::class, 'exportAttendees'])->name('events.attendees');
        });

    /*
    |---------------------------------------------------------------
    | ADMIN
    |---------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::get('/users',              [AdminController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/edit',  [AdminController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}',       [AdminController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}',    [AdminController::class, 'destroy'])->name('users.destroy');

            Route::get('/events',             [AdminController::class, 'events'])->name('events');
            Route::delete('/events/{event}',  [AdminController::class, 'destroyEvent'])->name('events.destroy');
        });

    /*
    |---------------------------------------------------------------
    | SCANNER (organizer + admin)
    |---------------------------------------------------------------
    */
    Route::middleware('role:organizer,admin')->group(function () {
        Route::get('/scanner',         [ScannerController::class, 'index'])->name('scanner.index');
        Route::post('/ticket/verify',  [TicketController::class, 'verify'])->name('ticket.verify');
    });
});

require __DIR__.'/auth.php';

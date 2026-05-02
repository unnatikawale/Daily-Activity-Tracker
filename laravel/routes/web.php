<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::get('/dashboard', [ActivityController::class, 'index'])->middleware('auth')->name('dashboard');

// Monthly tracker route
Route::get('/monthly-tracker', [ActivityController::class, 'monthlyTracker'])->name('monthly-tracker');

// Test monthly tracker route without authentication
Route::get('/test-monthly-tracker', [ActivityController::class, 'monthlyTracker']);

// Temporary dashboard route without authentication for testing
Route::get('/test-dashboard', [ActivityController::class, 'index']);

// Simple test route to verify controller works
Route::get('/test-simple', function() {
    return 'Test route is working!';
});

// Test the dashboard view directly
Route::get('/test-view', function() {
    return view('dashboard', [
        'activities' => collect([
            (object)[
                'id' => 1,
                'title' => 'Test Activity',
                'description' => 'Test description',
                'activity_date' => date('Y-m-d'),
                'completed' => false,
                'created_at' => now()
            ]
        ]),
        'selectedDate' => date('Y-m-d'),
        'completedCount' => 0,
        'totalCount' => 1
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('activities', ActivityController::class);
    Route::patch('/activities/{activity}/toggle-complete', [ActivityController::class, 'toggleComplete'])->name('activities.toggle-complete');
    Route::get('/activities/dates-with-activities', [ActivityController::class, 'getDatesWithActivities'])->name('activities.dates-with-activities');
});

// Move copy route outside auth for testing
Route::post('/activities/copy', [ActivityController::class, 'copyActivities'])->name('activities.copy');

// Monthly activities routes
Route::post('/monthly-activities/store', [ActivityController::class, 'storeMonthlyActivity'])->name('monthly-activities.store');
Route::post('/activities/toggle-by-title-date', [ActivityController::class, 'toggleActivityByTitleDate']);

// Test route for monthly activities without authentication
Route::post('/test-monthly-activities/store', [ActivityController::class, 'storeMonthlyActivity']);

// Add a simple test route to check authentication
Route::get('/test-auth', function() {
    return response()->json([
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'session_id' => session()->getId()
    ]);
});

// Temporary route without authentication for testing
Route::get('/activities-dates-test', function() {
    try {
        $activities = \App\Models\Activity::orderBy('activity_date', 'desc')->get();
        $dates = [];
        $activitiesByDate = [];

        foreach ($activities as $activity) {
            $date = $activity->activity_date->format('Y-m-d');
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
            if (!isset($activitiesByDate[$date])) {
                $activitiesByDate[$date] = [];
            }
            $activitiesByDate[$date][] = [
                'id' => $activity->id,
                'title' => $activity->title,
                'description' => $activity->description,
                'activity_date' => $activity->activity_date->format('Y-m-d'),
                'completed' => $activity->completed
            ];
        }

        return response()->json([
            'dates' => $dates,
            'activities' => $activitiesByDate,
            'debug' => [
                'total_activities' => $activities->count(),
                'dates_count' => count($dates)
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Database error',
            'message' => $e->getMessage()
        ], 500);
    }
});

require __DIR__.'/auth.php';

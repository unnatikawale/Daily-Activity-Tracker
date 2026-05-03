<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // Handle both authenticated and non-authenticated users for testing
        $userId = Auth::id();
        if ($userId) {
            $activities = Activity::where('user_id', $userId)
                                ->where('activity_date', $date)
                                ->orderBy('created_at', 'desc')
                                ->get();
        } else {
            // For testing, show all activities or create sample data
            $activities = Activity::where('activity_date', $date)
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
            // If no activities exist, create some sample data
            if ($activities->count() === 0) {
                $activities = collect([
                    (object)[
                        'id' => 1,
                        'title' => 'Sample Activity 1',
                        'description' => 'This is a sample activity for testing',
                        'activity_date' => $date,
                        'completed' => false,
                        'created_at' => now()
                    ],
                    (object)[
                        'id' => 2,
                        'title' => 'Sample Activity 2',
                        'description' => 'Another sample activity',
                        'activity_date' => $date,
                        'completed' => true,
                        'created_at' => now()
                    ]
                ]);
            }
        }
        
        return view('dashboard', [
            'activities' => $activities,
            'selectedDate' => $date,
            'completedCount' => $activities->where('completed', true)->count(),
            'totalCount' => $activities->count()
        ]);
    }

    /**
     * Display the analytics dashboard.
     */
    public function analytics(Request $request)
    {
        $userId = Auth::id();
        $period = $request->input('period', '30'); // Default to last 30 days
        
        // Get date range
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($period);
        
        // Get all activities for the user in the date range
        // Handle both authenticated and non-authenticated users for testing
        if ($userId) {
            $activities = Activity::where('user_id', $userId)
                                ->whereBetween('activity_date', [$startDate, $endDate])
                                ->orderBy('activity_date', 'desc')
                                ->get();
        } else {
            // For testing, show all activities
            $activities = Activity::whereBetween('activity_date', [$startDate, $endDate])
                                ->orderBy('activity_date', 'desc')
                                ->get();
        }
        
        // Calculate statistics
        $totalActivities = $activities->count();
        $completedActivities = $activities->where('completed', true)->count();
        $pendingActivities = $totalActivities - $completedActivities;
        $completionRate = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100, 1) : 0;
        
        // Daily completion data for chart
        $dailyData = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            // Filter activities by comparing formatted date strings
            $dayActivities = $activities->filter(function($activity) use ($dateStr) {
                return $activity->activity_date->format('Y-m-d') === $dateStr;
            });
            
            $dailyData[] = [
                'date' => $date->format('M j'),
                'total' => $dayActivities->count(),
                'completed' => $dayActivities->where('completed', true)->count(),
                'pending' => $dayActivities->where('completed', false)->count()
            ];
        }
        
        // Weekly completion data
        $weeklyData = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::today()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::today()->subWeeks($i)->endOfWeek();
            
            if ($userId) {
                $weekActivities = Activity::where('user_id', $userId)
                                        ->whereBetween('activity_date', [$weekStart, $weekEnd])
                                        ->get();
            } else {
                // For testing, get all activities
                $weekActivities = Activity::whereBetween('activity_date', [$weekStart, $weekEnd])
                                        ->get();
            }
            
            $weeklyData[] = [
                'week' => 'Week ' . (4 - $i),
                'total' => $weekActivities->count(),
                'completed' => $weekActivities->where('completed', true)->count(),
                'completion_rate' => $weekActivities->count() > 0 
                    ? round(($weekActivities->where('completed', true)->count() / $weekActivities->count()) * 100, 1) 
                    : 0
            ];
        }
        
        // Activity type distribution (based on title patterns)
        $activityTypes = [
            'work' => $activities->filter(function($a) {
                return preg_match('/(work|meeting|project|task|office)/i', $a->title);
            })->count(),
            'exercise' => $activities->filter(function($a) {
                return preg_match('/(exercise|gym|workout|run|walk|fitness)/i', $a->title);
            })->count(),
            'personal' => $activities->filter(function($a) {
                return preg_match('/(personal|home|family|shopping)/i', $a->title);
            })->count(),
            'learning' => $activities->filter(function($a) {
                return preg_match('/(learn|study|read|course|book)/i', $a->title);
            })->count(),
            'other' => $activities->filter(function($a) {
                return !preg_match('/(work|meeting|project|task|office|exercise|gym|workout|run|walk|fitness|personal|home|family|shopping|learn|study|read|course|book)/i', $a->title);
            })->count()
        ];
        
        // Get streak from database
        if ($userId) {
            $streakRecord = \App\Models\Streak::getForUser($userId);
            $streak = $streakRecord->current_streak;
            $longestStreak = $streakRecord->longest_streak;
        } else {
            // For testing, set default values
            $streak = 0;
            $longestStreak = 0;
        }
        
        // Generate tips based on performance
        $tips = [];
        if ($completionRate >= 80) {
            $tips[] = "Excellent! You're completing " . $completionRate . "% of your activities. Keep up the great work!";
        } elseif ($completionRate >= 60) {
            $tips[] = "Good progress! Try to complete a few more activities daily to reach 80% completion rate.";
        } else {
            $tips[] = "Focus on completing your most important activities first. Small improvements lead to big results!";
        }
        
        if ($streak >= 7) {
            $tips[] = "Amazing! You have a " . $streak . "-day streak of completing activities. Maintain this momentum!";
        } elseif ($streak >= 3) {
            $tips[] = "You're on a " . $streak . "-day streak. Keep going to build a strong habit!";
        } else {
            $tips[] = "Start building a streak by completing at least one activity every day.";
        }
        
        if ($longestStreak > 0 && $streak < $longestStreak) {
            $tips[] = "Your longest streak was " . $longestStreak . " days. You can beat it!";
        }
        
        if ($pendingActivities > 10) {
            $tips[] = "You have " . $pendingActivities . " pending activities. Consider prioritizing or breaking them into smaller tasks.";
        }
        
        // Debug logging
        \Log::info('Analytics data:', [
            'totalActivities' => $totalActivities,
            'completedActivities' => $completedActivities,
            'pendingActivities' => $pendingActivities,
            'dailyData' => $dailyData,
            'weeklyData' => $weeklyData,
            'activityTypes' => $activityTypes,
            'userId' => $userId
        ]);

        return view('analytics', [
            'period' => $period,
            'totalActivities' => $totalActivities,
            'completedActivities' => $completedActivities,
            'pendingActivities' => $pendingActivities,
            'completionRate' => $completionRate,
            'dailyData' => $dailyData,
            'weeklyData' => $weeklyData,
            'activityTypes' => $activityTypes,
            'streak' => $streak,
            'longestStreak' => $longestStreak,
            'tips' => $tips,
            'startDate' => $startDate->format('M j, Y'),
            'endDate' => $endDate->format('M j, Y')
        ]);
    }

    /**
     * Display the monthly tracker.
     */
    public function monthlyTracker(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $userId = Auth::id();
        
        // For testing, if no user, use user ID 1
        if (!$userId) {
            $userId = 1;
        }
        
        // Debug: Check what's in the database
        \Log::info('Querying monthly activities:', [
            'user_id' => $userId,
            'month' => $month,
            'month_format' => Carbon::parse($month . '-01')->format('Y-m-d')
        ]);
        
        // Get all monthly activities for the user (or all activities for testing)
        // Parse the month to get year and month for comparison
        $carbonMonth = Carbon::parse($month . '-01');
        $year = $carbonMonth->year;
        $monthNum = $carbonMonth->month;
        
        if (request()->is('test-monthly-tracker') || !Auth::check()) {
            // For testing, get all monthly activities regardless of user
            $monthlyActivities = \App\Models\MonthlyActivity::whereYear('month', $year)
                ->whereMonth('month', $monthNum)
                ->get();
        } else {
            // For authenticated users, get only their activities
            $monthlyActivities = \App\Models\MonthlyActivity::where('user_id', $userId)
                ->whereYear('month', $year)
                ->whereMonth('month', $monthNum)
                ->get();
        }

        // Debug: Log the activities we found
        \Log::info('Monthly activities for user ' . $userId . ' in ' . $month . ':', [
            'monthly_activities_count' => $monthlyActivities->count(),
            'activities' => $monthlyActivities->pluck('title')->toArray(),
            'is_authenticated' => Auth::check(),
            'route' => request()->path()
        ]);
            
        // Get all days in the month
        $date = Carbon::parse($month . '-01');
        $daysInMonth = $date->daysInMonth;
        $days = [];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $date->copy()->day($day);
            $days[] = [
                'date' => $currentDate,
                'day' => $day,
                'weekday' => $currentDate->format('D'),
                'is_weekend' => in_array($currentDate->dayOfWeek, [0, 6]) // Sunday=0, Saturday=6
            ];
        }
        
        // Get completion status for each activity and day from the new completion table
        $completionData = [];
        if ($monthlyActivities->isNotEmpty()) {
            foreach ($monthlyActivities as $activity) {
                $activityKey = $activity->id;
                $completionData[$activityKey] = [];
                
                // Get all completions for this monthly activity in one query
                $completions = \App\Models\MonthlyActivityCompletion::where('monthly_activity_id', $activity->id)
                    ->where('user_id', $userId)
                    ->whereBetween('date', [$date->copy()->startOfMonth()->format('Y-m-d'), $date->copy()->endOfMonth()->format('Y-m-d')])
                    ->get()
                    ->keyBy(function($item) {
                        return \Carbon\Carbon::parse($item->date)->day;
                    });
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $completionData[$activityKey][$day] = isset($completions[$day]) ? $completions[$day]->completed : false;
                }
            }
        }
        
        return view('monthly-tracker', [
            'month' => $month,
            'days' => $days,
            'monthlyActivities' => $monthlyActivities,
            'completionData' => $completionData,
            'monthName' => $date->format('F Y')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if we're dealing with multiple activities or a single one
        if ($request->has('activities')) {
            // Handle multiple activities
            $activities = $request->input('activities');
            
            // Validate all activities at once
            $request->validate([
                'activities.*.title' => 'nullable|string|max:255',
                'activities.*.description' => 'nullable|string|max:1000',
                'activities.*.activity_date' => 'nullable|date|after_or_equal:today'
            ]);

            // Custom validation: ensure at least one activity has a title
            $hasValidActivity = false;
            foreach ($activities as $activityData) {
                if (!empty(trim($activityData['title']))) {
                    $hasValidActivity = true;
                    break;
                }
            }

            if (!$hasValidActivity) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['activities' => 'Please provide at least one activity title.']);
            }

            $createdCount = 0;
            $firstDate = null;

            foreach ($activities as $activityData) {
                // Skip if title is empty
                if (empty(trim($activityData['title']))) {
                    continue;
                }
                
                // Use selected date if no date provided
                $activityDate = $activityData['activity_date'] ?? date('Y-m-d');

                Activity::create([
                    'user_id' => Auth::id(),
                    'title' => $activityData['title'],
                    'description' => $activityData['description'] ?? null,
                    'activity_date' => $activityDate,
                    'completed' => false
                ]);

                $createdCount++;

                // Update streak if activity is for today or yesterday
                $carbonDate = Carbon::parse($activityDate);
                if ($carbonDate->isToday() || $carbonDate->isYesterday()) {
                    $streak = \App\Models\Streak::getForUser(Auth::id());
                    $streak->updateStreakForDate($activityDate);
                }

                if ($firstDate === null) {
                    $firstDate = $activityDate;
                }
            }

            if ($createdCount > 0) {
                $message = $createdCount > 1 
                    ? "{$createdCount} activities created successfully!" 
                    : "Activity created successfully!";
                    
                return redirect()->route('dashboard', ['date' => $firstDate])
                                ->with('success', $message);
            } else {
                return redirect()->route('dashboard')
                                ->with('error', 'No activities were created. Please provide at least one activity title.');
            }
        } else {
            // Handle single activity (backward compatibility)
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'activity_date' => 'required|date|after_or_equal:today'
            ]);

            Activity::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'activity_date' => $request->activity_date,
                'completed' => false
            ]);

            // Update streak if activity is for today or yesterday
            $carbonDate = Carbon::parse($request->activity_date);
            if ($carbonDate->isToday() || $carbonDate->isYesterday()) {
                $streak = \App\Models\Streak::getForUser(Auth::id());
                $streak->updateStreakForDate($request->activity_date);
            }

            return redirect()->route('dashboard', ['date' => $request->activity_date])
                            ->with('success', 'Activity created successfully!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activity::where('user_id', Auth::id())->findOrFail($id);
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activity = Activity::where('user_id', Auth::id())->findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'activity_date' => 'required|date|after_or_equal:today'
        ]);

        $activity->update($request->all());

        return redirect()->route('dashboard', ['date' => $activity->activity_date])
                        ->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::where('user_id', Auth::id())->findOrFail($id);
        $date = $activity->activity_date;
        $activity->delete();

        // Update streak
        $streak = \App\Models\Streak::getForUser(Auth::id());
        $streak->updateStreakForDate($date);

        return redirect()->route('dashboard', ['date' => $date])
                        ->with('success', 'Activity deleted successfully!');
    }

    /**
     * Toggle activity completion status.
     */
    public function toggleComplete(string $id)
    {
        $activity = Activity::where('user_id', Auth::id())->findOrFail($id);
        $activity->completed = !$activity->completed;
        $activity->save();

        // Update streak
        $streak = \App\Models\Streak::getForUser(Auth::id());
        $streak->updateStreakForDate($activity->activity_date);

        return response()->json([
            'success' => true,
            'completed' => $activity->completed,
            'message' => $activity->completed ? 'Activity marked as completed!' : 'Activity marked as incomplete!'
        ]);
    }

    /**
     * Get all dates with activities for the current user.
     */
    public function getDatesWithActivities()
    {
        try {
            $userId = Auth::id();
            
            // For testing, if no user, get all activities
            if (!$userId) {
                $activities = Activity::orderBy('activity_date', 'desc')->get();
            } else {
                $activities = Activity::where('user_id', $userId)
                                    ->orderBy('activity_date', 'desc')
                                    ->get();
            }

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
                    'user_id' => $userId,
                    'total_activities' => $activities->count(),
                    'dates_count' => count($dates),
                    'sample_date' => count($dates) > 0 ? $dates[0] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Copy activities from one date to another.
     */
    public function copyActivities(Request $request)
    {
        try {
            $request->validate([
                'activity_ids' => 'required|array',
                'activity_ids.*' => 'integer|exists:activities,id',
                'target_date' => 'required|date'
            ]);

            $activityIds = $request->input('activity_ids');
            $targetDate = $request->input('target_date');
            $copiedCount = 0;

            // Handle both authenticated and non-authenticated users
            $userId = Auth::id();

            foreach ($activityIds as $activityId) {
                // For testing, if no user, get any activity
                if ($userId) {
                    $originalActivity = Activity::where('user_id', $userId)->findOrFail($activityId);
                } else {
                    $originalActivity = Activity::findOrFail($activityId);
                }
                
                // Check if activity already exists on target date
                if ($userId) {
                    $existingActivity = Activity::where('user_id', $userId)
                                               ->where('title', $originalActivity->title)
                                               ->where('activity_date', $targetDate)
                                               ->first();
                } else {
                    $existingActivity = Activity::where('title', $originalActivity->title)
                                               ->where('activity_date', $targetDate)
                                               ->first();
                }
                
                if (!$existingActivity) {
                    $newActivityData = [
                        'title' => $originalActivity->title,
                        'description' => $originalActivity->description,
                        'activity_date' => $targetDate,
                        'completed' => false
                    ];
                    
                    // Only add user_id if authenticated
                    if ($userId) {
                        $newActivityData['user_id'] = $userId;
                    }
                    
                    Activity::create($newActivityData);
                    $copiedCount++;
                }
            }

            $message = $copiedCount > 0 
                ? "Successfully copied {$copiedCount} activity" . ($copiedCount > 1 ? 'ies' : '') . " to " . Carbon::parse($targetDate)->format('F j, Y')
                : "No new activities were copied (they may already exist on the target date).";

            return response()->json([
                'success' => true,
                'copied_count' => $copiedCount,
                'message' => $message,
                'debug' => [
                    'user_id' => $userId,
                    'activity_ids' => $activityIds,
                    'target_date' => $targetDate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Copy failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new monthly activity.
     */
    public function storeMonthlyActivity(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'month' => 'required|date'
            ]);

            $userId = Auth::id();
            
            // For testing, if no user, use a default user ID
            if (!$userId) {
                $userId = 1; // Use user ID 1 for testing
            }

            // Check if activity already exists for this month (only in monthly_activities table)
            $carbonMonth = Carbon::parse($request->month);
            $year = $carbonMonth->year;
            $monthNum = $carbonMonth->month;
            
            $existingActivity = \App\Models\MonthlyActivity::where('user_id', $userId)
                                                          ->where('title', $request->title)
                                                          ->whereYear('month', $year)
                                                          ->whereMonth('month', $monthNum)
                                                          ->first();

            if ($existingActivity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Activity already exists for this month'
                ]);
            }

            \App\Models\MonthlyActivity::create([
                'user_id' => $userId,
                'title' => $request->title,
                'description' => $request->description,
                'month' => Carbon::parse($request->month)->startOfMonth()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Monthly activity added successfully!'
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry error specifically
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Activity already exists for this month'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle activity completion by title and date.
     */
    public function toggleActivityByTitleDate(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'activity_date' => 'required|date',
                'completed' => 'required'
            ]);

            // Convert string boolean to actual boolean
            $completedValue = $request->input('completed');
            if (in_array(strtolower($completedValue), ['true', '1', 'yes', 'on'])) {
                $completed = true;
            } elseif (in_array(strtolower($completedValue), ['false', '0', 'no', 'off'])) {
                $completed = false;
            } else {
                $completed = filter_var($completedValue, FILTER_VALIDATE_BOOLEAN);
            }

            $userId = Auth::id();
            
            // For testing, if no user, use user ID 1
            if (!$userId) {
                $userId = 1;
            }

            // Find the monthly activity by title and month
            $carbonMonth = Carbon::parse($request->activity_date);
            $year = $carbonMonth->year;
            $monthNum = $carbonMonth->month;
            
            $monthlyActivity = \App\Models\MonthlyActivity::where('user_id', $userId)
                ->where('title', $request->title)
                ->whereYear('month', $year)
                ->whereMonth('month', $monthNum)
                ->first();

            if (!$monthlyActivity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Monthly activity not found'
                ], 404);
            }

            // Find or create the completion record
            $completion = \App\Models\MonthlyActivityCompletion::where('monthly_activity_id', $monthlyActivity->id)
                ->where('user_id', $userId)
                ->where('date', $request->activity_date)
                ->first();

            if ($completion) {
                // Update existing completion
                $completion->completed = $completed;
                $completion->save();
            } else {
                // Create new completion record
                \App\Models\MonthlyActivityCompletion::create([
                    'monthly_activity_id' => $monthlyActivity->id,
                    'user_id' => $userId,
                    'date' => $request->activity_date,
                    'completed' => $completed
                ]);
            }

            // Update streak
            $streak = \App\Models\Streak::getForUser($userId);
            $streak->updateStreakForDate($request->activity_date);

            return response()->json([
                'success' => true,
                'message' => $completed ? 'Activity marked as completed!' : 'Activity marked as incomplete!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recalculate streak for the authenticated user.
     */
    public function recalculateStreak(Request $request)
    {
        try {
            $userId = Auth::id();
            $streak = \App\Models\Streak::getForUser($userId);
            $streak->recalculate();

            return response()->json([
                'success' => true,
                'message' => 'Streak recalculated successfully!',
                'current_streak' => $streak->current_streak,
                'longest_streak' => $streak->longest_streak
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

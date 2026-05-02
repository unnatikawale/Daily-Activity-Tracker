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
}

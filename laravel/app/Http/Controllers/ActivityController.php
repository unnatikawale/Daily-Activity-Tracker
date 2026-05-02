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
        $activities = Activity::where('user_id', Auth::id())
                            ->where('activity_date', $date)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
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
}

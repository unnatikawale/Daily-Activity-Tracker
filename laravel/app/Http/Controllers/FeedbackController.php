<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::orderBy('created_at', 'desc')->paginate(10);
        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000'
        ]);

        $feedback = Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'message' => $request->message,
            'is_approved' => true // Auto-approved
        ]);

        return redirect()->route('feedback.index')->with('success', 'Thank you for your feedback! It has been posted successfully.');
    }

    public function getRecentFeedbacks()
    {
        return Feedback::orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}

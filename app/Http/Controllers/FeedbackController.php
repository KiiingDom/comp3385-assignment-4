<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * Show the feedback form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store the feedback in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => $user->id,
            'message' => $data['message'],
        ]);

        return redirect()->route('feedback.create')->with('success', 'Feedback submitted successfully.');
    }

    /**
     * List all feedback for admin review.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $feedback = Feedback::all();
        return view('admin.feedback.index', compact('feedback'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Feedback;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();
        $feedback = Feedback::all();
        return view('admin.index', compact('users', 'feedback'));
    }

    /**
     * Show the user management interface.
     *
     * @return \Illuminate\View\View
     */
    public function manageUsers()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the feedback management interface.
     *
     * @return \Illuminate\View\View
     */
    public function manageFeedback()
    {
        $feedback = Feedback::all();
        return view('admin.feedback.index', compact('feedback'));
    }

    /**
     * Delete a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a feedback.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFeedback($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
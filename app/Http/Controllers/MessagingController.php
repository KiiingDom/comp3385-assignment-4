<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class MessagingController extends Controller
{
    /**
     * Show the messaging interface.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = $this->getConversations($user);
        return view('messaging.index', compact('conversations'));
    }

    /**
     * Retrieve the user's conversations.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getConversations(User $user)
    {
        return Message::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with('sender', 'recipient')
            ->get()
            ->groupBy('recipient_id');
    }

    /**
     * Send a new message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $data['recipient_id'],
            'message' => $data['message'],
        ]);

        return redirect()->route('messaging.index')->with('success', 'Message sent successfully.');
    }
}
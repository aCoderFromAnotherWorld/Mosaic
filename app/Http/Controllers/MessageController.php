<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->conversations()
            ->with(['users' => function($query) {
                $query->where('users.id', '!=', auth()->id());
            }])
            ->withCount(['messages' => function($query) {
                $query->where('is_read', false)
                      ->where('user_id', '!=', auth()->id());
            }])
            ->latest('updated_at')
            ->get();

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // Check if user is part of conversation
        if (!$conversation->users->contains(auth()->user())) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $otherUser = $conversation->users->where('id', '!=', auth()->id())->first();

        return view('messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Find or create conversation
        $conversation = auth()->user()->conversations()
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([auth()->id(), $user->id]);
        }

        // Create message
        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Update conversation timestamp
        $conversation->touch();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user'),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }
}
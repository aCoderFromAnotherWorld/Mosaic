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

    public function create(User $user)
    {
        // Check if user is trying to message themselves
        if (auth()->id() === $user->id) {
            return redirect()->route('messages.index')->with('error', 'You cannot message yourself.');
        }

        // Find existing conversation
        $conversation = auth()->user()->conversations()
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();

        if ($conversation) {
            return redirect()->route('messages.show', $conversation);
        }

        // Create new conversation
        $conversation = Conversation::create();
        $conversation->users()->attach([auth()->id(), $user->id]);

        return redirect()->route('messages.show', $conversation);
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
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Check if either message or attachment is provided
        if (!$request->message && !$request->attachment) {
            return back()->withErrors(['message' => 'Either a message or attachment is required.']);
        }

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

        $messageData = [
            'user_id' => auth()->id(),
            'message' => $request->message,
        ];

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('attachments', $filename, 'public');

            $messageData['attachment_path'] = $path;
            $messageData['attachment_name'] = $file->getClientOriginalName();
            $messageData['attachment_type'] = $file->getMimeType();
            $messageData['attachment_size'] = $file->getSize();
        }

        // Create message
        $message = $conversation->messages()->create($messageData);

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

    public function update(Request $request, Message $message)
    {
        // Check if user owns the message
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message->update([
            'message' => $request->message,
            'is_edited' => true,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user'),
            ]);
        }

        return redirect()->back();
    }

    public function destroy(Message $message)
    {
        // Check if user owns the message
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $conversation = $message->conversation;
        $message->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }
}
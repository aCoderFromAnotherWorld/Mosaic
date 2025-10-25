<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()
            ->with(['media', 'reactions', 'comments'])
            ->latest()
            ->paginate(10);

        $shareFriends = auth()->user()->friends()
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->orderBy('users.name')
            ->get();

        $shareFollowers = auth()->user()->followers()
            ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
            ->orderBy('users.name')
            ->get();

        return view('posts.index', compact('posts', 'shareFriends', 'shareFollowers'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200', // 50MB max
        ]);

        // Determine post type
        $type = 'text';
        if ($request->hasFile('media')) {
            $firstFile = $request->file('media')[0];
            $mimeType = $firstFile->getMimeType();
            
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            }
        }

        // Create post
        $post = auth()->user()->posts()->create([
            'content' => $request->content,
            'type' => $type,
        ]);

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $mimeType = $file->getMimeType();
                $mediaType = str_starts_with($mimeType, 'image/') ? 'image' : 'video';
                
                $path = $file->store('posts/' . $post->id, 'public');
                
                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'media_type' => $mediaType,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('feed')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load([
            'user',
            'media',
            'reactions.user',
            'comments.user',
            'comments.likes.user',
            'comments.replies.user',
            'comments.replies.likes.user',
        ]);

        $shareFriends = collect();
        $shareFollowers = collect();

        if (auth()->check()) {
            $shareFriends = auth()->user()->friends()
                ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
                ->orderBy('users.name')
                ->get();

            $shareFollowers = auth()->user()->followers()
                ->select('users.id', 'users.name', 'users.username', 'users.profile_picture')
                ->orderBy('users.name')
                ->get();
        }

        return view('posts.show', compact('post', 'shareFriends', 'shareFollowers'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $request->validate([
            'content' => 'nullable|string|max:5000',
        ]);

        $post->update([
            'content' => $request->content,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        // Delete media files
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        $post->delete();

        return redirect()->route('feed')->with('success', 'Post deleted successfully!');
    }

    public function share(Request $request, Post $post)
    {
        $user = $request->user();

        $validated = $request->validate([
            'recipients' => ['required', 'array', 'min:1'],
            'recipients.*' => ['integer', 'exists:users,id'],
        ]);

        $shareableIds = $user->friends()->pluck('users.id')
            ->merge($user->followers()->pluck('users.id'))
            ->unique()
            ->reject(fn ($id) => $id === $user->id)
            ->values();

        $recipientIds = collect($validated['recipients'])
            ->unique()
            ->filter(fn ($id) => $shareableIds->contains($id))
            ->values();

        if ($recipientIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one valid recipient.',
            ], 422);
        }

        $recipients = User::whereIn('id', $recipientIds)->get();

        foreach ($recipients as $recipient) {
            $conversation = $user->conversations()
                ->whereHas('users', function ($query) use ($recipient) {
                    $query->where('users.id', $recipient->id);
                })
                ->first();

            if (!$conversation) {
                $conversation = Conversation::create();
                $conversation->users()->attach([$user->id, $recipient->id]);
            }

            $conversation->messages()->create([
                'user_id' => $user->id,
                'message' => 'shared_post:' . $post->id,
            ]);

            $conversation->touch();
        }

        return response()->json([
            'success' => true,
            'message' => 'Post shared successfully.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required|in:like,love,wow,sad,angry',
        ]);

        $existingReaction = $post->reactions()->where('user_id', auth()->id())->first();
        $isNewReaction = !$existingReaction;

        $reaction = Reaction::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ],
            [
                'type' => $request->type,
            ]
        );

        // Create notification for post reaction (only if it's a new reaction or different type)
        if ($isNewReaction || $existingReaction->type !== $request->type) {
            if ($post->user_id !== auth()->id()) { // Don't notify if reacting to own post
                $post->user->notifications()->create([
                    'sender_id' => auth()->id(),
                    'type' => 'post_reaction',
                    'message' => '<strong>' . auth()->user()->name . '</strong> reacted to your post with ' . $request->type . '.',
                    'data' => ['url' => route('feed') . '#post-' . $post->id, 'post_id' => $post->id],
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reactions_count' => $post->reactions()->count(),
            ]);
        }

        return back();
    }

    public function destroy(Post $post)
    {
        $post->reactions()->where('user_id', auth()->id())->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'reactions_count' => $post->reactions()->count(),
            ]);
        }

        return back();
    }
}

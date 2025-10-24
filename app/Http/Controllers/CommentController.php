<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentReaction;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->allComments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        // Create notification for comment (only if not commenting on own post)
        if ($post->user_id !== auth()->id()) {
            $post->user->notifications()->create([
                'sender_id' => auth()->id(),
                'type' => 'comment',
                'message' => '<strong>' . auth()->user()->name . '</strong> commented on your post.',
                'data' => ['url' => route('feed') . '#post-' . $post->id, 'post_id' => $post->id, 'comment_id' => $comment->id],
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
            ]);
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }

    public function like(Request $request, Comment $comment)
    {
        $user = $request->user();

        $reaction = CommentReaction::updateOrCreate(
            [
                'user_id' => $user->id,
                'comment_id' => $comment->id,
            ],
            [
                'type' => 'like',
            ]
        );

        if ($comment->user_id !== $user->id && $reaction->wasRecentlyCreated) {
            $comment->user->notifications()->create([
                'sender_id' => $user->id,
                'type' => 'comment_like',
                'message' => '<strong>' . e($user->name) . '</strong> liked your comment.',
                'data' => [
                    'url' => route('posts.show', $comment->post) . '#comment-' . $comment->id,
                    'post_id' => $comment->post_id,
                    'comment_id' => $comment->id,
                ],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes_count' => $comment->likes()->count(),
            ]);
        }

        return back();
    }

    public function unlike(Request $request, Comment $comment)
    {
        CommentReaction::where('comment_id', $comment->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes_count' => $comment->likes()->count(),
            ]);
        }

        return back();
    }
}

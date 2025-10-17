<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
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
}
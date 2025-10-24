<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get posts from users that the current user follows + own posts
        $followingIds = $user->following()->pluck('users.id');
        $followingIds->push($user->id);
        
        $posts = Post::with([
                'user',
                'media',
                'reactions',
                'comments.user',
                'comments.likes.user',
                'comments.replies.user',
                'comments.replies.likes.user',
            ])
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->paginate(10);
        
        return view('feed.index', compact('posts'));
    }
}

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

        $reaction = Reaction::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ],
            [
                'type' => $request->type,
            ]
        );

        if ($request->ajax()) {
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

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'reactions_count' => $post->reactions()->count(),
            ]);
        }

        return back();
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot follow yourself!');
        }

        auth()->user()->following()->syncWithoutDetaching($user->id);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Following successfully!']);
        }

        return back()->with('success', 'Following successfully!');
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Unfollowed successfully!']);
        }

        return back()->with('success', 'Unfollowed successfully!');
    }
}
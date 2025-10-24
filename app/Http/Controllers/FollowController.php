<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot follow yourself!');
        }

        $authUser = auth()->user();
        $alreadyFollowing = $authUser->isFollowing($user->id);

        $authUser->following()->syncWithoutDetaching($user->id);

        if (!$alreadyFollowing) {
            Notification::create([
                'user_id' => $user->id,
                'sender_id' => $authUser->id,
                'type' => 'follow',
                'message' => '<strong>' . e($authUser->name) . '</strong> started following you.',
                'data' => [
                    'url' => route('profile.show', $authUser->username),
                ],
                'is_read' => false,
            ]);
        }

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

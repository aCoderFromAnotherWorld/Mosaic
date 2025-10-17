<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function sendRequest(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot send a friend request to yourself.');
        }

        if (auth()->user()->isFriend($user->id)) {
            return back()->with('error', 'You are already friends with this user.');
        }

        if (auth()->user()->friendRequests()->where('receiver_id', $user->id)->exists()) {
            return back()->with('error', 'You have already sent a friend request to this user.');
        }

        if ($user->friendRequests()->where('receiver_id', auth()->id())->exists()) {
            return back()->with('error', 'This user has already sent you a friend request.');
        }

        DB::table('friend_requests')->insert([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create notification for friend request
        $user->notifications()->create([
            'sender_id' => auth()->id(),
            'type' => 'friend_request',
            'message' => '<strong>' . auth()->user()->name . '</strong> sent you a friend request.',
            'data' => ['url' => route('profile.show', auth()->user()->username)],
        ]);

        return back()->with('success', 'Friend request sent successfully.');
    }

    public function acceptRequest(User $user)
    {
        $request = DB::table('friend_requests')
            ->where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if (!$request) {
            return back()->with('error', 'Friend request not found.');
        }

        DB::transaction(function () use ($user, $request) {
            // Update friend request status
            DB::table('friend_requests')
                ->where('id', $request->id)
                ->update(['status' => 'accepted']);

            // Add to friends table (bidirectional)
            DB::table('friends')->insert([
                ['user_id' => auth()->id(), 'friend_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
                ['user_id' => $user->id, 'friend_id' => auth()->id(), 'created_at' => now(), 'updated_at' => now()],
            ]);
        });

        // Create notification for accepted friend request
        $user->notifications()->create([
            'sender_id' => auth()->id(),
            'type' => 'friend_accepted',
            'message' => '<strong>' . auth()->user()->name . '</strong> accepted your friend request.',
            'data' => ['url' => route('profile.show', auth()->user()->username)],
        ]);

        return back()->with('success', 'Friend request accepted.');
    }

    public function declineRequest(User $user)
    {
        DB::table('friend_requests')
            ->where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->update(['status' => 'declined']);

        return back()->with('success', 'Friend request declined.');
    }

    public function removeFriend(User $user)
    {
        if (!auth()->user()->isFriend($user->id)) {
            return back()->with('error', 'You are not friends with this user.');
        }

        DB::table('friends')
            ->where(function ($query) use ($user) {
                $query->where('user_id', auth()->id())->where('friend_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('friend_id', auth()->id());
            })
            ->delete();

        return back()->with('success', 'Friend removed successfully.');
    }

    public function cancelRequest(User $user)
    {
        DB::table('friend_requests')
            ->where('sender_id', auth()->id())
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->delete();

        return back()->with('success', 'Friend request cancelled.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $users = collect();

        if (!empty($query)) {
            $users = User::where('id', '!=', auth()->id())
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('username', 'like', '%' . $query . '%');
                })
                ->withCount(['followers', 'following'])
                ->limit(20)
                ->get()
                ->map(function ($user) use ($request) {
                    $authUser = $request->user();

                    $user->is_friend = $authUser->isFriend($user->id);
                    $user->is_following = $authUser->isFollowing($user->id);
                    $user->has_pending_request = $user->friendRequests()
                        ->where('sender_id', $authUser->id)
                        ->where('status', 'pending')
                        ->exists();
                    $user->has_received_request = $authUser->friendRequests()
                        ->where('sender_id', $user->id)
                        ->where('status', 'pending')
                        ->exists();
                    return $user;
                });
        }

        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'query' => $query
            ]);
        }

        return view('search.index', compact('users', 'query'));
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json(['users' => []]);
        }

        $users = User::where('id', '!=', auth()->id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('username', 'like', '%' . $query . '%');
            })
            ->select('id', 'name', 'username', 'profile_picture')
            ->limit(10)
            ->get();

        return response()->json(['users' => $users]);
    }
}

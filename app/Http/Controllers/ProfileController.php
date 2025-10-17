<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()
            ->with(['media', 'reactions', 'comments'])
            ->latest()
            ->paginate(12);
        
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $isFollowing = auth()->user()->isFollowing($user->id);
        
        return view('profile.show', compact('user', 'posts', 'followersCount', 'followingCount', 'isFollowing'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        $request->validated();

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'bio' => $request->bio,
        ];

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        // Handle cover picture
        if ($request->hasFile('cover_picture')) {
            if ($user->cover_picture) {
                Storage::disk('public')->delete($user->cover_picture);
            }
            $data['cover_picture'] = $request->file('cover_picture')->store('covers', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show', $user->username)->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile and cover pictures
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        if ($user->cover_picture) {
            Storage::disk('public')->delete($user->cover_picture);
        }

        // Delete media files from posts
        foreach ($user->posts as $post) {
            foreach ($post->media as $media) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/feed')->with('success', 'Account deleted successfully!');
    }
}
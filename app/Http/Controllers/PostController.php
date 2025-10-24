<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()
            ->with(['media', 'reactions', 'comments'])
            ->latest()
            ->paginate(10);
        
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200', // 50MB max
        ]);

        // Determine post type
        $type = 'text';
        if ($request->hasFile('media')) {
            $firstFile = $request->file('media')[0];
            $mimeType = $firstFile->getMimeType();
            
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            }
        }

        // Create post
        $post = auth()->user()->posts()->create([
            'content' => $request->content,
            'type' => $type,
        ]);

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $mimeType = $file->getMimeType();
                $mediaType = str_starts_with($mimeType, 'image/') ? 'image' : 'video';
                
                $path = $file->store('posts/' . $post->id, 'public');
                
                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'media_type' => $mediaType,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('feed')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load([
            'user',
            'media',
            'reactions.user',
            'comments.user',
            'comments.likes.user',
            'comments.replies.user',
            'comments.replies.likes.user',
        ]);
        
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $request->validate([
            'content' => 'nullable|string|max:5000',
        ]);

        $post->update([
            'content' => $request->content,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        // Delete media files
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        $post->delete();

        return redirect()->route('feed')->with('success', 'Post deleted successfully!');
    }
}

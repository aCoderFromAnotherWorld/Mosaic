<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Follow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users
        $user1 = User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Photography enthusiast and travel lover 📸',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Digital artist | Creative soul 🎨',
        ]);

        $user3 = User::create([
            'name' => 'Mike Johnson',
            'username' => 'mikejohnson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Tech blogger and coffee addict ☕',
        ]);

        // Create follows
        $user1->following()->attach([$user2->id, $user3->id]);
        $user2->following()->attach([$user1->id, $user3->id]);
        $user3->following()->attach($user1->id);

        // Create posts
        $post1 = Post::create([
            'user_id' => $user1->id,
            'content' => 'Just finished an amazing photoshoot! Can\'t wait to share the results with you all. 📷✨',
            'type' => 'text',
        ]);

        $post2 = Post::create([
            'user_id' => $user2->id,
            'content' => 'Working on a new digital art piece. Here\'s a sneak peek of the work in progress!',
            'type' => 'text',
        ]);

        $post3 = Post::create([
            'user_id' => $user3->id,
            'content' => 'New blog post about the latest web development trends. Check it out!',
            'type' => 'text',
        ]);

        $post4 = Post::create([
            'user_id' => $user1->id,
            'content' => 'Exploring the city streets today. The architecture here is absolutely stunning! 🏛️',
            'type' => 'text',
        ]);

        // Create reactions
        Reaction::create([
            'user_id' => $user2->id,
            'post_id' => $post1->id,
            'type' => 'like',
        ]);

        Reaction::create([
            'user_id' => $user3->id,
            'post_id' => $post1->id,
            'type' => 'love',
        ]);

        Reaction::create([
            'user_id' => $user1->id,
            'post_id' => $post2->id,
            'type' => 'love',
        ]);

        // Create comments
        Comment::create([
            'user_id' => $user2->id,
            'post_id' => $post1->id,
            'content' => 'This looks amazing! Can\'t wait to see the full results! 😍',
        ]);

        Comment::create([
            'user_id' => $user3->id,
            'post_id' => $post1->id,
            'content' => 'Great work as always, John!',
        ]);

        $parentComment = Comment::create([
            'user_id' => $user1->id,
            'post_id' => $post2->id,
            'content' => 'Your art style is incredible! Keep it up! 🎨',
        ]);

        Comment::create([
            'user_id' => $user2->id,
            'post_id' => $post2->id,
            'parent_id' => $parentComment->id,
            'content' => 'Thank you so much! That means a lot! 💙',
        ]);
    }
}
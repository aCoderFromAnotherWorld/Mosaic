<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('feed');
});

Route::get('/dashboard', function () {
    return redirect()->route('feed');
})->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    
    // Posts
    Route::resource('posts', PostController::class);
    
    // Reactions
    Route::post('/posts/{post}/react', [ReactionController::class, 'store'])->name('posts.react');
    Route::delete('/posts/{post}/react', [ReactionController::class, 'destroy'])->name('posts.unreact');
    
    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    // Follow
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
});

require __DIR__.'/auth.php';

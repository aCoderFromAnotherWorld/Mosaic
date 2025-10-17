<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('feed');
});

Route::get('/dashboard', function () {
    return redirect()->route('feed');
})->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/users', [SearchController::class, 'searchUsers'])->name('search.users');

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
    Route::get('/profile', function () {
        return redirect()->route('profile.show', auth()->user()->username);
    })->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    // Follow
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');

    // Friends
    Route::post('/users/{user}/friend-request', [FriendController::class, 'sendRequest'])->name('users.friend-request');
    Route::post('/users/{user}/accept-friend', [FriendController::class, 'acceptRequest'])->name('users.accept-friend');
    Route::post('/users/{user}/decline-friend', [FriendController::class, 'declineRequest'])->name('users.decline-friend');
    Route::delete('/users/{user}/remove-friend', [FriendController::class, 'removeFriend'])->name('users.remove-friend');
    Route::delete('/users/{user}/cancel-friend-request', [FriendController::class, 'cancelRequest'])->name('users.cancel-friend-request');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/create/{user}', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

require __DIR__.'/auth.php';

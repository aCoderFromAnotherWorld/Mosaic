<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CommentReaction;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'profile_picture',
        'cover_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->withTimestamps();
    }

    public function isFriend($userId)
    {
        return $this->friends()->where('friend_id', $userId)->exists();
    }

    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friend_requests', 'receiver_id', 'sender_id')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friend_requests', 'sender_id', 'receiver_id')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    public function commentReactions()
    {
        return $this->hasMany(CommentReaction::class);
    }

    public function hasLikedComment(int $commentId): bool
    {
        return $this->commentReactions()->where('comment_id', $commentId)->exists();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'message', 'is_read', 'is_edited', 'attachment_path', 'attachment_name', 'attachment_type', 'attachment_size'];

    protected $casts = [
        'is_read' => 'boolean',
        'is_edited' => 'boolean',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
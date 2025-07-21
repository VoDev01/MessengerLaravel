<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageAttachment extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMessageAttachmentFactory> */
    use HasFactory;

    protected $fillable = [
        'attachment',
    ];

    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id', 'id');
    }
}

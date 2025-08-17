<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'text',
        'chat_id',
        'sender_id'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: function($value) { 
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s'); 
            },
            set: function($value) { 
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s'); 
        });
    }

    protected function updatedAt(): Attribute
    {return Attribute::make(
            get: function($value) { 
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s'); 
            },
            set: function($value) { 
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s'); 
        });
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(ChatMessageAttachment::class, 'chat_message_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

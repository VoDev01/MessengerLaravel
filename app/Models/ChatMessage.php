<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $text
 * @property int $chat_id
 * @property int $sender_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatMessageAttachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\User $sender
 * @method static \Database\Factories\ChatMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
            get: function ($value)
            {
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s');
            },
            set: function ($value)
            {
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s');
            }
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value)
            {
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s');
            },
            set: function ($value)
            {
                return (new Carbon($value, 'Europe/Moscow'))->format('Y-m-d H:i:s');
            }
        );
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

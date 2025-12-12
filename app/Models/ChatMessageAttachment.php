<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $attachment
 * @property int $message_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ChatMessage $message
 * @method static \Database\Factories\ChatMessageAttachmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessageAttachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatMessageAttachment extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMessageAttachmentFactory> */
    use HasFactory;

    protected $fillable = [
        'attachment',
    ];

    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'message_id', 'id');
    }
}

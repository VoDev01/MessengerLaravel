<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $link_name
 * @property string $logo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $visibility
 * @property string $type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ChatFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereLinkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereVisibility($value)
 * @mixin \Eloquent
 */
class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'link_name'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function (Chat $chat) {
            $chat->logo = $chat->logo ?? 'https://letters.noticeable.io/' . strtoupper(substr($chat->name, 0, 1)) . rand(0, 19) . '.png'; 
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $linkName = str_contains($value, '@') ? $value : '@' . $value;
        return $this->with('users')->where('link_name', $linkName)->firstOrFail();
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_users', 'chat_id', 'user_id')->withPivot('role_id');
    }
}
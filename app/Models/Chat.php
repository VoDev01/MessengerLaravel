<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',

    ];

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
        return $this->belongsToMany(User::class, 'chat_users', 'user_id', 'chat_id');
    }
}
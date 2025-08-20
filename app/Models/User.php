<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'pfp',
        'default_pfp'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        //'password',
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

    public function resolveRouteBinding($value, $field = null)
    {
        $linkName = str_contains('@', $value) ? $value : '@' . $value;
        $user = User::with(['chats', 'chats.messages'])->where('link_name', $linkName)->get()->first();
        return $user;
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_users', 'user_id', 'chat_id')->withPivot('role_id');
    }

    public function chatRoles()
    {
        return $this->belongsToMany(Role::class, 'chat_users', 'user_id', 'role_id')->withPivot('chat_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $fillable = [
        'role'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_users', 'role_id', 'user_id');
    }
}
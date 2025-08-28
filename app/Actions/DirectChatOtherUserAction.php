<?php

namespace App\Actions;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DirectChatOtherUserAction
{
    public static function getOtherUser(Chat $chat): User
    {
        return $chat->users[0] === Auth::user() ? $chat->users[0] : $chat->users[1];
    }
}
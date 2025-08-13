<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.private.{chat}', function (User $user, Chat $chat) {
    if(!Auth::check())
        return false;

    foreach($chat->users as $chatUser)
    {
        if($chatUser->id === $user->id)
        {
            return true;
        }
    }
    return false;
});

Broadcast::channel('chat.{chat}', function (User $user, Chat $chat){
    return Auth::id() === $user->id;
});
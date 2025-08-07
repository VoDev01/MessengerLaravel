<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.private.{name}', function (User $user, string $name) {
    if(!Auth::check())
        return false;

    $chat = Chat::where('name', $name)->get()->first();

    foreach($chat->users as $chatUser)
    {
        if($chatUser->id === $user->id)
        {
            return true;
        }
    }
    return false;
});

Broadcast::channel('chat.{name}', function (User $user, string $name){
    return Auth::check();
});
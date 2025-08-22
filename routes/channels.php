<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;

Broadcast::channel('chat.private.{chat}', function (User $user, Chat $chat) {
    return Gate::inspect('view', $chat)->allowed();
});

Broadcast::channel('chat.{chat}', function (User $user, Chat $chat){
    return Auth::id() === $user->id;
});

Broadcast::channel('chat.direct.{chat}', function(User $user, Chat $chat) {
    return Gate::inspect('view', $chat)->allowed();
});

Broadcast::channel('user.{user}', function(User $user) {
    return Auth::id() === $user->id;
});
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

Broadcast::channel('user.direct.{user}', function(User $user) {
    $chat = Chat::where('link_name', $user->link_name)->get()->first();
    return Gate::inspect('viewDirect', [$user, Auth::user(), $chat]);
});

Broadcast::channel('user.{user}', function(User $user) {
    return Auth::id() === $user->id;
});
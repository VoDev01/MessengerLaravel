<?php

namespace App\Listeners\User;

use App\Events\User\UserStatusChangedEvent;
use App\Models\User;
use Illuminate\Events\Dispatcher;

class UserSubscriber
{
    public function __construct() {
        //
    }

    public function handleUserStatusChanged(UserStatusChangedEvent $event)
    {
        $event->user->online = $event->online;
        $event->user->save();
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserStatusChangedEvent::class, [UserSubscriber::class, 'handleUserStatusChanged']);
    }
}
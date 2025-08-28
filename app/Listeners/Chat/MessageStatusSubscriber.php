<?php

namespace App\Listeners\Chat;

use App\Enums\ChatMessageStatusEnum;
use App\Events\Chat\MessageDeliveredEvent;
use App\Events\Chat\MessageSeenEvent;
use App\Events\Chat\PrivateMessageDeliveredEvent;
use App\Models\ChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class MessageStatusSubscriber
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handleMessageDelivered(MessageDeliveredEvent $event): void
    {
        $message = ChatMessage::where('id', $event->id)->get()->first();
        $message->status = ChatMessageStatusEnum::Delivered->value;
        $message->save();
    }

    public function handleMessageSeen(MessageSeenEvent $event): void
    {
        $messageIds = array_column($event->messages, 'id');
        $messages = ChatMessage::whereIn('id', $messageIds)->get();
        foreach ($messages as $message)
        {
            $message->status = ChatMessageStatusEnum::Seen->value;
            $message->save();
        }
    }

    public function handlePrivateMessageDelivered(PrivateMessageDeliveredEvent $event): void
    {
        $message = ChatMessage::where('id', $event->messageId)->get()->first();
        $message->status = ChatMessageStatusEnum::Delivered->value;
        $message->save();
    }

    public function subcribe(Dispatcher $events)
    {
        $events->listen(MessageDeliveredEvent::class, [MessageStatusSubscriber::class, 'handleMessageDelivered']);
        $events->listen(PrivateMessageDeliveredEvent::class, [MessageStatusSubscriber::class, 'handlePrivateMessageDelivered']);
    }
}

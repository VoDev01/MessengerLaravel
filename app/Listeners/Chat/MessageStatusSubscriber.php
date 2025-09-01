<?php

namespace App\Listeners\Chat;

use App\Actions\DirectChatOtherUserAction;
use App\Enums\ChatMessageStatusEnum;
use App\Events\Chat\MessageDeliveredEvent;
use App\Events\Chat\MessageSeenEvent;
use App\Events\Chat\MessageSentEvent;
use App\Events\Chat\PrivateMessageDeliveredEvent;
use App\Models\Chat;
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
    public function handleMessageSent(MessageSentEvent $event): void
    {
        $message = ChatMessage::where('id', $event->message->id)->get()->first();
        $message->status = ChatMessageStatusEnum::Sent->value;
        $message->save();
        $event->message->status = $message->status;
        MessageDeliveredEvent::dispatch($event->channel, $event->message, $event->currentUserId);
    }

    public function handleMessageDelivered(MessageDeliveredEvent $event): void
    {
        $message = ChatMessage::where('id', $event->message->id)->get()->first();
        $message->status = ChatMessageStatusEnum::Delivered->value;
        $message->save();
        $event->message->status = $message->status;
    }

    public function handleMessageSeen(MessageSeenEvent $event): void
    {
        $messageIds = array_column($event->messages, 'messageId');
        $messages = ChatMessage::whereIn('id', $messageIds)->get();
        foreach ($messages as $message)
        {
            $message->status = ChatMessageStatusEnum::Seen->value;
            $message->save();
        }
    }

    public function subcribe(Dispatcher $events)
    {
        $events->listen(MessageSentEvent::class, [MessageStatusSubscriber::class, 'handleMessageSent']);
        $events->listen(MessageDeliveredEvent::class, [MessageStatusSubscriber::class, 'handleMessageDelivered']);
        $events->listen(MessageSeenEvent::class, [MessageStatusSubscriber::class, 'handleMessageSeen']);
    }
}

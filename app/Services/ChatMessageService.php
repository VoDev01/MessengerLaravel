<?php

namespace App\Services;

use App\Models\Chat;
use App\DTO\ChatMessageDTO;
use App\Enums\ChatTypeEnum;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Enums\ChatVisibilityEnum;
use App\Enums\ChatMessageStatusEnum;
use Illuminate\Support\Facades\Auth;
use App\Events\Chat\MessageSeenEvent;
use App\Events\Chat\MessageSentEvent;
use App\Events\Chat\MessageDeliveredEvent;

class ChatMessageService
{ 
    public function storeMessage(Chat $chat, array $validated)
    {
        $messageId = ChatMessage::create([
            'sender_id' => Auth::id(),
            'chat_id' => $chat->id,
            'text' => $validated['text']
        ])->id;

        $message = new ChatMessageDTO($messageId);

        $unreadMessagesCount = ChatMessage::selectRaw('chat_id, COUNT(id) as unread_messages_count')
        ->where('chat_id', $chat->id)
        ->where('status', ChatMessageStatusEnum::Sent->value)
        ->groupBy('chat_id')
        ->get()
        ->first();

        $unreadMessagesCount = isset($unreadMessagesCount) ? $unreadMessagesCount->unread_messages_count : 0;

        if ($chat->visibility === ChatVisibilityEnum::Public->value)
        {
            MessageSentEvent::dispatch('chat.', $message, $unreadMessagesCount);
        }

        else if ($chat->visibility === ChatVisibilityEnum::Private->value)
        {
            $channel = $chat->type === ChatTypeEnum::Group->value ? 'chat.private.' : 'chat.direct.';

            MessageSentEvent::dispatch($channel, $message, $unreadMessagesCount);
        }

        return response()->json(['messageId' => $message->id]);
    }

    public function messageSeen(Chat $chat, Request $request)
    {

        $messages = json_decode($request->messages, true);
        
        $channel = match ($chat->type) {
            ChatTypeEnum::Group->value => 'chat.',
            ChatTypeEnum::Direct->value => 'chat.direct.'
        };

        if($chat->type !== ChatTypeEnum::Direct->value)
            $channel = $chat->visibility === ChatVisibilityEnum::Private->value ? $channel . 'private.' : $channel;

        broadcast(new MessageSeenEvent($messages, $chat->link_name, $channel))->toOthers();

        return response()->json(['success' => true]);
    }

    public function messageDelivered(Chat $chat, Request $request)
    {
        $messages = json_decode($request->messages, true);
        
        $channel = match ($chat->type) {
            ChatTypeEnum::Group->value => 'chat.',
            ChatTypeEnum::Direct->value => 'chat.direct.'
        };

        if($chat->type !== ChatTypeEnum::Direct->value)
            $channel = $chat->visibility === ChatVisibilityEnum::Private->value ? $channel . 'private.' : $channel;

        broadcast(new MessageDeliveredEvent($messages, $chat->link_name, $channel))->toOthers();

        return response()->json(['success' => true]);
    }

    public function updateMessage(array $validated)
    {
        $message = ChatMessage::where('id', $validated['id']);

        $message->update(['text' => $validated['text']]);

        $message->save();

        return response()->json(['message' => $message]);
    }

    public function deleteMessage(array $validated)
    {

        ChatMessage::destroy($validated['id']);

        return response()->json();
    }
}
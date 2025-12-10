<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
use App\DTO\ChatMessageDTO;
use App\Enums\ChatTypeEnum;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Enums\ChatVisibilityEnum;
use Illuminate\Support\Facades\DB;
use App\Services\Interface\Service;
use App\Enums\ChatMessageStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Events\Chat\MessageSeenEvent;
use App\Events\Chat\MessageSentEvent;
use App\Actions\CreateDirectChatAction;
use App\Actions\LoadChatMessagesAction;
use App\Events\Chat\MessageDeliveredEvent;

class ChatService implements Service
{
    public function group($chat, Request $request)
    {
        if (Chat::where('name', $chat->name)->get() === null)
            abort(404);

        $userIsInChat = Gate::inspect('view', $chat)->allowed();
        if($chat->visibility === ChatVisibilityEnum::Private->value)
        {
            if(!$userIsInChat)
                return view('chat.private-chat-restricted', ['chat' => $chat, 'currentUser' => Auth::user(), 'userIsInChat' => $userIsInChat]);
        }

        $chat = Chat::with('users')->where('id', $chat->id)->get()->first();

        return LoadChatMessagesAction::load($chat, $request, $userIsInChat, 'chat.index');
    }

    public function direct(User $user, Request $request)
    {

        if(User::where('link_name', $user->link_name)->get() === null)
            abort(404);

        $chat = CreateDirectChatAction::create($user);

        return LoadChatMessagesAction::load($chat, $request, true, 'chat.direct');
    }

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

    public function join(Chat $chat)
    {
        $user = User::where('id', Auth::id())->get()->first();
        $date = Carbon::now()->format('Y-m-d H:i:s');

        DB::insert('INSERT INTO chat_users (chat_id, user_id, role_id, created_at, updated_at) VALUES(?,?,?,?,?)', 
            [
                $chat->id, 
                $user->id,
                Role::where('role', 'User')->get()->first()->id,
                $date,
                $date
            ]
        );

        $chatMessages = ChatMessage::with('sender')->where('chat_id', $chat->id)->get();

        return response()->json(['messages' => $chatMessages, 'currentUserId' => Auth::id(), 'chatName' => $chat->name]);
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
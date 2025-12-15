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

    
}
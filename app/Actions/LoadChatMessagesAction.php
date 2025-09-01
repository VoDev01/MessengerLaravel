<?php

namespace App\Actions;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadChatMessagesAction
{
    public static function load(Chat $chat, Request $request, bool $userIsInChat, string $view)
    {
        $messages = ChatMessage::with(['sender:id,name,link_name,pfp', 'chat:id,name,link_name'])
            ->where('chat_id', $chat->id);

        if($request->expectsJson())
        {
            $messages = $messages->where('created_at', '<', $request->lastMessageTime)->orderBy('created_at', 'desc');
            $messagesGet = $messages->get();

            if($messagesGet->count() <= 0)
                return response()->json();
            else if($messagesGet->count() == 1)
            {
                $messages = array_reverse($messagesGet->toArray());
                return response()->json(['messages' => $messages, 'currentUserId' => Auth::id()]);
            }
            else
            {
                $messages = array_reverse($messages->limit(10)->get()->toArray());
                return response()->json(['messages' => $messages, 'currentUserId' => Auth::id()]);
            }
        }
        else
        {
            return view($view, ['messages' => $messages->orderBy('created_at', 'desc')->limit(10)->get()->reverse(), 'chat' => $chat, 'currentUser' => Auth::user(), 'userIsInChat' => $userIsInChat]);
        }
    } 
}
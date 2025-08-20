<?php

namespace App\Actions;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleChatMessagesAction
{
    public static function handle(Chat $chat, Request $request, bool $userIsInChat, string $view)
    {
        $messages = ChatMessage::with(['sender', 'chat'])
            ->where('chat_id', $chat->id);

        if($request->expectsJson())
        {
            $messages = $messages->where('created_at', '>', $request->lastMessageTime);
            if($messages->get()->count() <= 0)
                return response()->json();
            else if($messages->get()->count() == 1)
            {
                $messages = $messages->get();
                return response()->json(['messages' => $messages->toArray(), 'currentUserId' => Auth::id()]);
            }
            else
            {
                $messages = $messages->limit(10)->get();
                return response()->json(['messages' => $messages->toArray(), 'currentUserId' => Auth::id()]);
            }
        }
        else
        {
            return view($view, ['messages' => $messages->limit(10)->get(), 'chat' => $chat, 'currentUser' => Auth::user(), 'userIsInChat' => $userIsInChat]);
        }
    } 
}
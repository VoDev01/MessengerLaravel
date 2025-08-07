<?php

namespace App\Http\Controllers\Chat;

use App\Enums\ChatVisibilityEnum;
use App\Events\Chat\MessageSentEvent;
use App\Events\Chat\PrivateMessageSentEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Chat $chat)
    {
        $users = $chat->users;
        $currentUser = $users[array_search(Auth::user(), $users)];

        if (Chat::where('name', $chat->name)->get() === null)
            return redirect()->back(404);

        $messages = ChatMessage::with(['sender', 'chat'])
            ->where('sender_id', $currentUser)
            ->where('chat_id', $chat->id)
            ->get();

        return response()->json($messages);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Chat $chat, Request $request)
    {
        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'chat_id' => $chat->id,
            'text' => $request->message,
        ]);
        if ($chat->visibility === ChatVisibilityEnum::Public->value)
            broadcast(new MessageSentEvent($message, Auth::user()))->toOthers();

        else if ($chat->visibility === ChatVisibilityEnum::Private->value)
            broadcast(new PrivateMessageSentEvent($message, Auth::user()))->toOthers();

        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}

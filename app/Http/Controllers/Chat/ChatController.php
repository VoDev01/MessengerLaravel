<?php

namespace App\Http\Controllers\Chat;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Enums\ChatVisibilityEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\Chat\MessageSentEvent;
use App\Events\Chat\MessageDeliveredEvent;
use App\Events\Chat\PrivateMessageSentEvent;
use App\Events\Chat\PrivateMessageDeliveredEvent;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Chat $chat, Request $request)
    {
        if (Chat::where('name', $chat->name)->get() === null)
            return redirect()->back(404);

        $userIsInChat = Gate::inspect('view', $chat)->allowed();
        if($chat->visibility === ChatVisibilityEnum::Private->value)
        {
            if(!$userIsInChat)
                return view('chat.private-chat-restricted', ['chat' => $chat, 'currentUser' => Auth::user(), 'userIsInChat' => $userIsInChat]);
        }

        $chat = Chat::with('users')->where('id', $chat->id)->get()->first();

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
            return view('chat.index', ['messages' => $messages->limit(10)->get(), 'chat' => $chat, 'currentUser' => Auth::user(), 'userIsInChat' => $userIsInChat]);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Chat $chat, Request $request)
    {
        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'chat_id' => $chat->id,
            'text' => $request->text,
        ])->id;

        $message = ChatMessage::with(['sender'])->where('id', $message)->get()->first();

        if ($chat->visibility === ChatVisibilityEnum::Public->value)
        {
            MessageDeliveredEvent::dispatch($chat->link_name, $message->id, Auth::user());
            broadcast(new MessageSentEvent($message, Auth::user()))->toOthers();
        }

        else if ($chat->visibility === ChatVisibilityEnum::Private->value)
        {
            PrivateMessageDeliveredEvent::dispatch($chat->link_name, $message->id, Auth::user());
            broadcast(new PrivateMessageSentEvent($message, Auth::user()))->toOthers();
        }

        return response()->json(['messageId' => $message->id]);
    }

    public function join(Chat $chat)
    {
        $user = User::where('id', Auth::id())->get()->first();
        $date = Carbon::now()->format('Y-m-d H:i:s');
        DB::insert('INSERT INTO chat_users (chat_id, user_id, role_id, created_at, updated_at) VALUES(?,?,?,?,?)', 
        [$chat->id, $user->id, Role::where('role', 'User')->get()->first()->id, $date, $date]);
        $chatMessages = ChatMessage::with('sender')->where('chat_id', $chat->id)->get();
        return response()->json(['messages' => $chatMessages, 'currentUserId' => Auth::id(), 'chatName' => $chat->name]);
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

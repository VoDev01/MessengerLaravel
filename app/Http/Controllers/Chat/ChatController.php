<?php

namespace App\Http\Controllers\Chat;

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
use App\Events\Chat\PrivateMessageSentEvent;
use Carbon\Carbon;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Chat $chat, Request $request)
    {
        if (Chat::where('name', $chat->name)->get() === null)
            return redirect()->back(404);

        $chat = Chat::with('users')->where('id', $chat->id)->get()->first();

        $userIsInChat = false;
        foreach ($chat->users as $user)
        {
            if ($user->id === Auth::id())
            {
                $userIsInChat = true;
                break;
            }
        }

        $messages = ChatMessage::with(['sender', 'chat'])
            ->where('chat_id', $chat->id);

        if($request->expectsJson())
        {
            $messages = $messages->where('created_at', '>', $request->lastMessageTime);
            if($messages->get()->count() <= 0)
                return response()->json();
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
            broadcast(new MessageSentEvent($message, Auth::user()))->toOthers();

        else if ($chat->visibility === ChatVisibilityEnum::Private->value)
            broadcast(new PrivateMessageSentEvent($message, Auth::user()))->toOthers();

        return response()->json(['success' => true]);
    }

    public function join(Chat $chat)
    {
        $user = User::where('id', Auth::id())->get()->first();
        $date = Carbon::now()->format('Y-m-d H:i:s');
        DB::insert('INSERT INTO chat_users (chat_id, user_id, role_id, created_at, updated_at) VALUES(?,?,?,?,?)', 
        [$chat->id, $user->id, Role::where('role', 'User')->get()->first()->id, $date, $date]);
        return response()->json(['success' => true]);
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

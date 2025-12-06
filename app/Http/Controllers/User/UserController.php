<?php

namespace App\Http\Controllers\User;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\ChatMessageStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\User\UserStatusChangedEvent;

class UserController extends Controller
{
    public function home()
    {
        $chats = array();
        $userChatsIds = DB::select('SELECT chats.id FROM chats RIGHT JOIN chat_users AS cu ON chats.id = cu.chat_id WHERE cu.user_id = ?', [Auth::id()]);
    
        foreach($userChatsIds as $chatId)
        {
            array_push($chats, Chat::find($chatId->id));
        }

        $userChatsIds = array_map(function($elem){ return $elem = $elem->id; }, $userChatsIds);

        $unreadMessagesCount = ChatMessage::selectRaw('chat_id, COUNT(id) as unread_messages_count')
        ->whereIn('chat_id', $userChatsIds)
        ->where('status', ChatMessageStatusEnum::Sent->value)
        ->groupBy('chat_id')
        ->get();

        return view('home', ['currentUser' => Auth::user(), 'chats' => $chats, 'unreadMessagesCount' => $unreadMessagesCount]);
    }
    public function profile()
    {
        return view('profile');
    }
    public function logout(Request $request)
    {
        UserStatusChangedEvent::dispatch(Auth::user(), false);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    public function chats()
    {
        return response()->json(['chats' => Chat::with('users')->where('user_id', Auth::id())]);
    }
}

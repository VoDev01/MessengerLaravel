<?php

namespace App\Http\Controllers\User;

use App\Events\User\UserStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function home()
    {
        $chats = array();
        $chatIds = DB::select('SELECT chats.id FROM chats RIGHT JOIN chat_users AS cu ON chats.id = cu.chat_id WHERE cu.user_id = ?', [Auth::id()]);
    
        foreach($chatIds as $chatId)
        {
            array_push($chats, Chat::find($chatId->id));
        }
        
        return view('home', ['currentUser' => Auth::user(), 'chats' => $chats]);
    }
    public function profile()
    {
        return view('profile');
    }
    public function logout(Request $request)
    {
        broadcast(new UserStatusChangedEvent(Auth::user(), false))->toOthers();
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

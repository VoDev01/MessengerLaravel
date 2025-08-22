<?php

namespace App\Actions;

use App\Enums\ChatTypeEnum;
use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
use App\Enums\ChatVisibilityEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateDirectChatAction
{
    public static function getDirectLink(Chat $chat)
    {
        return "@direct_{$chat->users[0]->id}_{$chat->users[1]->id}";
    }
    public static function getDirectName(Chat $chat)
    {
        return "Direct_{$chat->users[0]->id}_{$chat->users[1]->id}";
    }
    public static function create(User $user): Chat
    {
        if (count(DB::select('SELECT * FROM chats WHERE chats.link_name = ?', ["@direct_{$user->id}_" . Auth::id()])) == 0)
        {
            DB::insert('INSERT INTO chats (name, link_name, logo, created_at, updated_at, visibility, type) VALUES(?,?,?,?,?,?,?)', [
                "Direct_{$user->id}_" . Auth::id(),
                "@direct_{$user->id}_" . Auth::id(),
                $user->pfp,
                Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s'),
                Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s'),
                ChatVisibilityEnum::Private->value,
                ChatTypeEnum::Direct->value
            ]);
        }

        $chat = Chat::with('users')->where('link_name', "@direct_{$user->id}_" . Auth::id())->get()->first();

        if (count(DB::select('SELECT * FROM chat_users WHERE chat_users.chat_id = ?', [$chat->id])) == 0)
        {
            $time = Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s');
            DB::insert(
                'INSERT INTO chat_users (chat_id, user_id, role_id, created_at, updated_at) VALUES 
                (:chat_id1,:user_id1,:role_id1, :time, :time1), (:chat_id2, :user_id2, :role_id2, :time2, :time3)',
                [
                    ':chat_id1' => $chat->id,
                    ':user_id1' => $user->id,
                    ':role_id1' => Role::where('role', 'User')->get()->first()->id,
                    ':chat_id2' => $chat->id,
                    ':user_id2' => Auth::id(),
                    ':role_id2' => Role::where('role', 'User')->get()->first()->id,
                    ':time' => $time,
                    ':time1' => $time,
                    ':time2' => $time,
                    ':time3' => $time 
                ]
            );
        }

        return $chat;
    }
}

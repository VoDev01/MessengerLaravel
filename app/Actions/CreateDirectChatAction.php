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
    public static function create(User $user): Chat
    {
        if (count(DB::select('SELECT * FROM chats WHERE chats.link_name = ?', [$user->link_name])) == 0)
        {
            DB::insert('INSERT INTO chats (name, link_name, logo, created_at, updated_at, visibility, type) VALUES(?,?,?,?,?,?,?)', [
                $user->name,
                $user->link_name,
                $user->pfp,
                Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s'),
                Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s'),
                ChatVisibilityEnum::Private->value,
                ChatTypeEnum::Direct->value
            ]);
        }

        $chat = DB::select('SELECT * FROM chats WHERE chats.link_name = ?', [$user->link_name]);

        if (count(DB::select('SELECT * FROM chat_users WHERE chat_users.chat_id = ?', [$chat[0]->id])) == 0)
        {
            DB::insert(
                'INSERT INTO chat_users (chat_id, user_id, role_id, created_at, updated_at) VALUES 
                (:chat_id1,:user_id1,:role_id1, :timestamp, :timestamp), (:chat_id2, :user_id2, :role_id2, :timestamp, :timestamp)',
                [
                    ':chat_id1' => $chat[0]->id,
                    ':user_id1' => $user->id,
                    ':role_id1' => Role::where('role', 'User')->get()->first()->id,
                    ':chat_id2' => $chat[0]->id,
                    ':user_id2' => Auth::id(),
                    ':role_id2' => Role::where('role', 'User')->get()->first()->id,
                    ':timestamp' => Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s')
                ]
            );
        }

        $chat = Chat::hydrate(DB::select('SELECT * FROM chats INNER JOIN chat_users AS cu ON cu.chat_id = chats.id INNER JOIN users ON cu.user_id = users.id WHERE cu.id IS NOT NULL AND users.id IN (?,?)', [$user->id, Auth::id()]))->first();

        return $chat;
    }
}

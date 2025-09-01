<?php

namespace App\DTO;

use Illuminate\Support\Facades\DB;

class ChatMessageDTO
{
    public int $id;
    public string $text;
    public int $chat_id;
    public int $sender_id;
    public string $created_at;
    public string $updated_at;
    public string $status;
    public string $chat_link_name;
    public string $sender_name;
    public string $sender_link_name;
    public string $pfp;

    public function __construct(int $id) {
        $message = (object) DB::select('SELECT cm.*, chats.link_name as chat_link_name, users.name as sender_name, users.link_name as sender_link_name, users.pfp FROM chat_messages AS cm JOIN chats ON cm.chat_id = chats.id JOIN users ON cm.sender_id = users.id WHERE cm.id = ?', [$id])[0];
        foreach($message as $key => $value)
        {
            $this->$key = $value;
        }
    }
}
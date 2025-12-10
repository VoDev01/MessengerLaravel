<?php

namespace App\Services;

use App\Models\Chat;
use App\Services\Interface\APIService;

class ChatAPIService implements APIService
{
    public function index()
    {   
        $chats = Chat::paginate(15);

        return $chats;
    }

    public function show(int $id)
    {
        $chat = Chat::find($id);

        return $chat;
    }

    public function edit(int $id, array $validated)
    {
        $validated = array_filter($validated, fn ($k, $v) => isset($v) && !empty($v));

        $chat = Chat::where('id', $id)->get()->first();
        $chat->update($validated);

        return $chat;
    }

    public function store(array $validated)
    {
        Chat::create($validated);
    }

    public function delete(int $id)
    {
        Chat::destroy($id);
    }
}
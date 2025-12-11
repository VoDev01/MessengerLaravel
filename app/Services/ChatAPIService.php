<?php

namespace App\Services;

use App\Models\Chat;
use App\Services\Interface\APIService;
use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ChatAPIService implements APIService
{
    public function index(): Chat
    {
        $chats = Chat::paginate(15);

        return $chats;
    }

    public function show(int|string $id): Chat
    {
        $chat = null;
        try
        {
            if (is_int($id))
                $chat = Chat::find($id);
            else if (is_string($id))
                $chat = Chat::where('link_name', $id)->get()->first();
            else
                throw new InvalidArgumentException("Parameter must be of type int or string");
        }
        catch (Exception $e)
        {
            Log::warning($e->getMessage());
        }

        return $chat;
    }

    public function edit(int|string $id, array $validated): Chat
    {
        $validated = array_filter($validated, fn($k, $v) => isset($v) && !empty($v), ARRAY_FILTER_USE_BOTH);
        $chat = null;


        try
        {
            if (is_int($id))
                $chat = Chat::where('id', $id)->get()->first();
            else if (is_string($id))
                $chat = Chat::where('link_name', $id)->get()->first();
            else
                throw new InvalidArgumentException("Parameter must be of type int or string");
        }
        catch (Exception $e)
        {
            Log::warning($e->getMessage());
        }
        finally
        {
            if(isset($chat))
                $chat->update($validated);
        }

        return $chat;
    }

    public function store(array $validated): bool
    {
        try
        {
            Chat::create($validated);
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }
        finally
        {
            return true;
        }
    }

    public function delete(int|string $id): void
    {
        $chat = null;

        try
        {
            if (is_int($id))
                Chat::destroy($id);
            else if (is_string($id))
                $chat = Chat::where('link_name', $id)->get()->first();
            else
                throw new InvalidArgumentException("Parameter must be of type int or string");
        }
        catch (Exception $e)
        {
            Log::warning($e->getMessage());
        }
        finally
        {
            if(isset($chat))
                Chat::destroy($chat->id);
        }
    }
}

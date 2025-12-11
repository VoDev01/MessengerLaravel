<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Http\Controllers\Controller;
use App\Actions\AttachMediaToMessageAction;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Requests\Chat\DeleteMessageRequest;
use App\Http\Requests\Chat\UpdateMessageRequest;
use App\Http\Requests\Chat\MessageAttachmentRequest;

class ChatAPIController extends Controller
{
    public function __construct(protected ChatService $chatService)
    {}
    
    public function group(Request $request)
    {
        $chat = Chat::where('link_name', $request->chat_link_name)->get()->first();
        return $this->chatService->group($chat, $request);
    }
    public function direct(Request $request)
    {
        $user = User::where('link_name', $request->user_link_name)->get()->first();
        return $this->chatService->direct($user, $request);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SendMessageRequest $request)
    {
        $validated = $request->validated();
        $chat = Chat::where('link_name', $request->chat_link_name)->get()->first();
        return $this->chatService->storeMessage($chat, $validated);
    }

    public function join(Request $request)
    {
        $chat = Chat::where('link_name', $request->chat_link_name)->get()->first();
        return $this->chatService->join($chat);
    }

    public function seen(Request $request)
    {
        $chat = Chat::where('link_name', $request->chat_link_name)->get()->first();
        return $this->chatService->messageSeen($chat, $request);
    }

    public function delivered(Request $request)
    {
        $chat = Chat::where('link_name', $request->chat_link_name)->get()->first();
        return $this->chatService->messageDelivered($chat, $request);
    }

    public function attach(MessageAttachmentRequest $request)
    {
        $validated = $request->validated();

        AttachMediaToMessageAction::attach(ChatMessage::find($request->message_id), $validated['attachments']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request)
    {
        $validated = $request->validated();
        $this->chatService->updateMessage($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteMessageRequest $request)
    {
        $validated = $request->validated();
        $this->chatService->deleteMessage($validated);
    }
}

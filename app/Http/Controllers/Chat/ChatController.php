<?php

namespace App\Http\Controllers\Chat;

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

class ChatController extends Controller
{

    public function __construct(protected ChatService $chatService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function group(Chat $chat, Request $request)
    {
        return $this->chatService->group($chat, $request);
    }
    public function direct(User $user, Request $request)
    {
        return $this->chatService->direct($user, $request);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Chat $chat, SendMessageRequest $request)
    {
        $validated = $request->validated();
        return $this->chatService->storeMessage($chat, $validated);
    }

    public function join(Chat $chat)
    {
        return $this->chatService->join($chat);
    }

    public function seen(Chat $chat, Request $request)
    {
        return $this->chatService->messageSeen($chat, $request);
    }

    public function delivered(Chat $chat, Request $request)
    {
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

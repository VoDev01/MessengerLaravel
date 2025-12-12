<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ChatAPICreateRequest;
use App\Http\Requests\API\V1\ChatAPIEditRequest;
use App\Services\ChatBuilderAPIService;
use Illuminate\Http\Request;

class ChatBuilderAPIController extends Controller
{
    public function __construct(protected ChatBuilderAPIService $chatService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return response()->json(['chats' => $this->chatService->index()]);  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChatAPICreateRequest $request)
    {
        $validated = $request->validated();
        $this->chatService->store($validated);
        return response()->json([true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id)
    {
        return response()->json(['chat' => $this->chatService->show($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string|int $id, ChatAPIEditRequest $request)
    {
        $validated = $request->validated();
        return response()->json(['chat' => $this->chatService->edit($id, $validated)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        $this->chatService->delete($id);
        return response()->json([true]);
    }
}

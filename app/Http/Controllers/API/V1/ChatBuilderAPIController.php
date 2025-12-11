<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ChatAPICreateRequest;
use App\Http\Requests\API\V1\ChatAPIEditRequest;
use App\Services\ChatAPIService;
use Illuminate\Http\Request;

class ChatBuilderAPIController extends Controller
{
    public function __construct(protected ChatAPIService $chatService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return $this->chatService->index();  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChatAPICreateRequest $request)
    {
        $validated = $request->validated();
        $this->chatService->store($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string|int $id)
    {
        return $this->chatService->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string|int $id, ChatAPIEditRequest $request)
    {
        $validated = $request->validated();
        return $this->chatService->edit($id, $validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        $this->chatService->delete($id);
    }
}

<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\Chat;
use App\Services\ChatAPIService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatAPIServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $chats = Chat::factory(15)->create();

        $chatAPI = new ChatAPIService();

        $this->assertNotEmpty($chatAPI->index());
    }

    public function testCreate(): void
    {
        $chat = Chat::factory()->make();

        $chatAPI = new ChatAPIService();

        $this->assertTrue($chatAPI->store($chat->toArray()));
    }

    public function testShow(): void
    {
        $chat = Chat::factory()->create();

        $chatAPI = new ChatAPIService();

        $show = $chatAPI->show($chat->id);

        $this->assertEquals($chat->id, $show->id);

        $show = $chatAPI->show($chat->link_name);

        $this->assertEquals($chat->id, $show->id);
    }

    public function testEdit(): void
    {
        $chat = Chat::factory()->create();

        $chatAPI = new ChatAPIService();

        $edit = $chatAPI->edit($chat->id, array('name' => '1' . $chat->name));

        $this->assertNotEquals($chat->name, $edit->name);
    
    }

    public function testDelete(): void
    {
        $chat = Chat::factory()->create();

        $chatAPI = new ChatAPIService();

        $chatAPI->delete($chat->id);

        $this->assertDatabaseMissing('chats', ['id' => $chat->id]);
    }
}

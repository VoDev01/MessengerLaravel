<?php

namespace Tests\Feature\Http\Controllers\Chat;

use App\Enums\ChatVisibilityEnum;
use App\Events\Chat\MessageSentEvent;
use App\Events\Chat\PrivateMessageSentEvent;
use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testMessageSent(): void
    {
        Event::fake();

        $chat = Chat::factory()->create(['visibility' => ChatVisibilityEnum::Public->value]);
        $role = Role::factory()->create();
        $user = User::factory()->hasAttached($chat, ['role_id' => $role->id], 'chats')->create();

        $response = $this->actingAs($user)->post("/chat/$chat->name/store", ['message' => 'hello world!']);
        $response->assertSessionHasNoErrors();
        Event::assertDispatched(MessageSentEvent::class);
    }

    public function testPrivateMessageSent(): void
    {
        Event::fake();

        $chat = Chat::factory()->create(['visibility' => ChatVisibilityEnum::Private->value]);
        $role = Role::factory()->create();
        $user = User::factory()->hasAttached($chat, ['role_id' => $role->id], 'chats')->create();

        $response = $this->actingAs($user)->post("/chat/$chat->name/store", ['message' => 'hello world!']);
        $response->assertSessionHasNoErrors();
        Event::assertDispatched(PrivateMessageSentEvent::class);
    }
}

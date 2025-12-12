<?php

namespace Tests\Feature\API\V1;

use App\Enum\TestRouteMethods;
use App\Enums\ChatTypeEnum;
use App\Enums\ChatVisibilityEnum;
use App\Models\ApplicationAPIConsumer;
use App\Models\Chat;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatBuilderAPITest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $chat = Chat::factory(15)->create()->first();
        $apiUser = ApplicationAPIConsumer::factory()->create();
        $testRouteAuthSerivce = new TestRouteForAuthService($apiUser);
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder', $this);

        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has('chats.data', 15)
                    ->has(
                        'chats.data.0',
                        fn(AssertableJson $json) =>
                        $json->where('link_name', $chat->link_name)
                            ->etc()
                    )
            );
    }

    public function testShow(): void
    {
        $chat = Chat::factory(15)->create()->first();
        $apiUser = ApplicationAPIConsumer::factory()->create();
        $testRouteAuthSerivce = new TestRouteForAuthService($apiUser);
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder/' . $chat->link_name, $this);

        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'chat',
                    fn(AssertableJson $json) =>
                    $json->where('link_name', $chat->link_name)
                        ->etc()
                )
            );
    }

    public function testStore(): void
    {
        $chat = Chat::factory()->create();
        $apiUser = ApplicationAPIConsumer::factory()->create();
        $testRouteAuthSerivce = new TestRouteForAuthService($apiUser, TestRouteMethods::POST);
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder/create', $this, true, [
            'name' => $chat->name,
            'link_name' => $chat->link_name,
            'visibility' => ChatVisibilityEnum::Public,
            'type' => ChatTypeEnum::Group
        ]);

        $response->assertStatus(422);

        $chat = Chat::factory()->make();
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder/create', $this, data: [
            'name' => $chat->name,
            'link_name' => $chat->link_name,
            'visibility' => ChatVisibilityEnum::Public,
            'type' => ChatTypeEnum::Group
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('chats', ['link_name' => $chat->link_name]);
    }

    public function testEdit(): void
    {
        $chat = Chat::factory()->create();
        $apiUser = ApplicationAPIConsumer::factory()->create();
        $testRouteAuthSerivce = new TestRouteForAuthService($apiUser, TestRouteMethods::PUT);
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder/update/' . $chat->link_name, $this, data: [
            'name' => 'NEW NAME',
            'link_name' => '@new_link_name',
        ]);

        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has('chat', fn(AssertableJson $json) =>
                $json->where('name', 'NEW NAME')
                    ->where('link_name', '@new_link_name')
                    ->etc()
                )
        );

        $this->assertDatabaseHas('chats', ['link_name' => '@new_link_name', 'name' => 'NEW NAME']);
    }

    public function testDelete(): void
    {
        $chat = Chat::factory()->create()->first();
        $apiUser = ApplicationAPIConsumer::factory()->create(['app_url' => 'https://uniqueere.url']);
        $testRouteAuthSerivce = new TestRouteForAuthService($apiUser, TestRouteMethods::DELETE);
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder/delete/' . $chat->link_name, $this, data: ['id' => $chat->id]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('chats', ['id' => $chat->id]);

        $chat = Chat::factory()->create()->first();
        $apiUser = ApplicationAPIConsumer::factory()->create(['app_url' => 'https://unique.url']);
        $response = $testRouteAuthSerivce->testAPIJWTAuth('/api/v1/chat/builder/delete/' . $chat->link_name, $this, data: ['id' => $chat->link_name]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('chats', ['link_name' => $chat->link_name]);
    }
}

<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin(): void
    {
        $user = User::factory()->create();

        $response = $this->post("/postLogin", ['email' => $user->email.'mm', 'password' => '11223344']);
        $response->assertSessionHasErrors(['email']);

        $response = $this->post("/postLogin", ['email' => $user->email, 'password' => '1122334412']);
        $response->assertSessionHasErrors(['password']);

        $response = $this->post("/postLogin", ['email' => $user->email, 'password' => '11223344']);
        $response->assertRedirect();
    }

    public function testRegister(): void
    {
        $user = User::factory()->make();

        $response = $this->post("/postRegister", ['email' => $user->email, "name" => $user->name, "password" => "1122334455", "password_confirm" => "111222333444"]);
        $response->assertSessionHasErrors(['password']);

        $response = $this->post("/postRegister", ['email' => $user->email, "name" => $user->name, "password" => "1122334455", "password_confirm" => "1122334455"]);
        $this->assertDatabaseHas('users', ['email' => $user->email]);
        $response->assertRedirect();
    }
}
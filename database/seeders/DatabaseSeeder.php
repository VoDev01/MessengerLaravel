<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $chat = Chat::factory()->create();

        User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), ['chat_id' => $chat->id], relationship: 'chatRoles')->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->hasAttached(Role::factory(), ['chat_id' => $chat->id], relationship: 'chatRoles')->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    }
}

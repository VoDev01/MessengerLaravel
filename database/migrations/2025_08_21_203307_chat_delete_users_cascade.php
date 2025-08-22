<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_users', function (Blueprint $table) {
            DB::statement('SET foreign_key_checks = 0');
            $table->dropForeign(['chat_id']);
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            DB::statement('SET foreign_key_checks = 1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_users', function (Blueprint $table) {
            DB::statement('SET foreign_key_checks = 0');
            $table->dropForeign(['chat_id']);
            $table->foreign('chat_id')->references('id')->on('chats');
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('user');
            DB::statement('SET foreign_key_checks = 1');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_message_attachments', function (Blueprint $table) {
            $table->dropForeign(['chat_message_id']);
            $table->dropColumn('chat_message_id');
            $table->foreignId('message_id')->after('attachment')->references('id')->on('chat_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_message_attachments', function (Blueprint $table) {
            $table->dropForeign(['message_id']);
            $table->dropColumn('message_id');
            $table->foreignId('chat_message_id')->after('attachment')->references('id')->on('chat_messages')->onDelete('cascade');
        });
    }
};

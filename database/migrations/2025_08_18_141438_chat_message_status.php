<?php

use App\Enums\ChatMessageStatusEnum;
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
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->enum('status', [
                ChatMessageStatusEnum::Processing->value,
                ChatMessageStatusEnum::Deleted->value, 
                ChatMessageStatusEnum::Edited->value,
                ChatMessageStatusEnum::Not_Sent->value,
                ChatMessageStatusEnum::Sent->value,
                ChatMessageStatusEnum::Delivered->value,
                ChatMessageStatusEnum::Seen->value
            ])->default(ChatMessageStatusEnum::Processing->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

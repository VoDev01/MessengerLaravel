<?php

use App\Enums\ChatVisibilityEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->enum('visibility', [
                ChatVisibilityEnum::Public->value, ChatVisibilityEnum::Private->value, ChatVisibilityEnum::Presence->value
            ])->default(ChatVisibilityEnum::Public->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
};

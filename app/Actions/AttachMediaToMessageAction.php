<?php

namespace App\Actions;

use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttachMediaToMessageAction
{
    public static function attach(ChatMessage $message, array $attachments)
    {
        dd($attachments);
        foreach ($attachments as $attachment)
        {
            if(!Storage::put($_FILES['attachments']['tmp_name'], $attachment))
                abort(500);
            DB::statement('INSERT INTO chat_message_attachments (attachment, message_id, created_at, updated_at) VALUES(?,?,?,?)', [
                $attachment,
                $message->id,
                Carbon::now(),
                Carbon::now()
            ]);
        }
    }
}

<?php

namespace App\Enums;

enum ChatMessageStatusEnum: string
{
    case Processing = 'PROCESSING';
    case Edited = 'EDITED';
    case Deleted = 'DELETED';
    case Not_Sent = 'NOT_SENT';
    case Sent = 'SENT';
    case Delivered = 'DELIVERED';
    case Seen = 'SEEN';
}
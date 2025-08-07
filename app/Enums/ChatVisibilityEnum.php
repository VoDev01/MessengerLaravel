<?php

namespace App\Enums;

enum ChatVisibilityEnum: string
{
    case Public = 'PUBLIC';
    case Private = 'PRIVATE';
    case Presence = 'PRESENCE';
}
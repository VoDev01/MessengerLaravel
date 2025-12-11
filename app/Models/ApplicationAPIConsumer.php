<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ApplicationAPIConsumer extends Authenticable implements JWTSubject
{
    public $timestamps = false;

    public $table = "app_api_consumers";

    public $primaryKey = 'app_id';

    public $fillable = [
        'app_id',
        'app_url',
        'app_api_endpoint'
    ];

    public $authPasswordName = 'app_key';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            "issuer_url" => $_SERVER["SERVER_PROTOCOL"].$_SERVER["SERVER_NAME"].$_SERVER["SERVER_PORT"],
            "scope" => ["rw-messages", "rw-users", "rw-chats", "r-roles", "r-db"]
        ];
    }
}

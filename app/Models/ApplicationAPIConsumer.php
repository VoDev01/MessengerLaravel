<?php

namespace App\Models;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $app_id
 * @property string $app_url
 * @property string $app_api_endpoint
 * @property string $app_secret
 * @method static \Database\Factories\ApplicationAPIConsumerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer whereAppApiEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer whereAppSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApplicationAPIConsumer whereAppUrl($value)
 * @mixin \Eloquent
 */
class ApplicationAPIConsumer extends Authenticable implements JWTSubject
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $table = "app_api_consumers";

    protected $primaryKey = 'app_id';

    protected $fillable = [
        'app_id',
        'app_url',
        'app_api_endpoint',
        'app_secret'
    ];

    protected $keyType = 'string';

    protected $authPasswordName = 'app_secret';

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->app_id))
            {
                $model->app_id = Str::ulid();
            }
            if(empty($model->app_secret))
            {
                $model->app_secret = Str::random(16);
            }
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            "app_id" => $this->app_id,
            "app_secret" => $this->app_secret,
            //"issuer_url" => $_SERVER["SERVER_PROTOCOL"].$_SERVER["SERVER_NAME"].$_SERVER["SERVER_PORT"],
            "scope" => ["rw-messages", "rw-users", "rw-chats", "r-roles", "r-db"]
        ];
    }
}

<?php

namespace App\Providers;

use App\Models\ApplicationAPIConsumer;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\ServiceProvider;

class ApplicationAPIConsumerUserProvider implements UserProvider
{

    public function retrieveById($identifier)
    {
        return ApplicationAPIConsumer::create([
            'app_id' => $identifier,
        ]);
    }
    public function retrieveByToken($identifier, $token)
    {
        return;
    }
    public function updateRememberToken(Authenticatable $user, $token)
    {
        return;
    }
    public function retrieveByCredentials(array $credentials)
    {
        if(!array_key_exists("app_secret", $credentials))
        { 
            return null;
        }

        return ApplicationAPIConsumer::where('app_id', $credentials['app_id'])->where(
            'app_secret', $credentials['app_secret']
        )->get()->first();
    }
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user->app_id === $credentials["app_id"] && $user->app_secret === $credentials["app_secret"];
    }
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        return;
    }
}

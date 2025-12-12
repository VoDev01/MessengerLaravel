<?php

namespace App\Providers;

use App\Models\ApplicationAPIConsumer;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Policies\Chat\ChatPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Chat::class, ChatPolicy::class); 

        Auth::provider("db_app_consumer", function(Application $app, array $config){
            return new ApplicationAPIConsumerUserProvider();
        });
    }
}

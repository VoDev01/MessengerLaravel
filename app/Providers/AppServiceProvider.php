<?php

namespace App\Providers;

use App\Http\Controllers\Chat\ChatController;
use App\Models\Chat;
use App\Policies\Chat\ChatPolicy;
use App\Services\ChatService;
use App\Services\Interface\Service;
use Illuminate\Support\Facades\Gate;
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
    }
}

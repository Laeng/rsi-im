<?php

namespace App\Providers;

use App\Services\RSI\Interfaces\RsiServiceInterface;
use App\Services\RSI\RsiService;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        Passport::ignoreMigrations();

        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(RsiServiceInterface::class, RsiService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}

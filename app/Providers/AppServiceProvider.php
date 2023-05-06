<?php

namespace App\Providers;

use App\Services\JWTService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $config = config('services.jwt');

        $this->app->bind(JWTService::class, function ($app) use ($config) {
            return new JWTService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

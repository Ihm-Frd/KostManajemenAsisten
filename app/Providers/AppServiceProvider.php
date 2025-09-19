<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Responses\FilamentLogoutResponse;
use Filament\Http\Responses\Auth\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //tambahan rute logout
        $this->app->bind(
            LogoutResponse::class,
            FilamentLogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

}

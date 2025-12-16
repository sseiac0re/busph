<?php

namespace App\Providers;

use App\Interfaces\ScheduleRepositoryInterface;
use App\Repositories\ScheduleRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\RouteRepositoryInterface;
use App\Repositories\RouteRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RouteRepositoryInterface::class, RouteRepository::class);
        $this->app->bind(\App\Interfaces\RouteRepositoryInterface::class, \App\Repositories\RouteRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
    }

    /**
 * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
        URL::forceScheme('https');
        }
    }
}

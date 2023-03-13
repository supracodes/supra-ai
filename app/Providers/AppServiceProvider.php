<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment(['development'])) {
            if (app()->runningInConsole()) {
                $this->app->register(\NunoMaduro\LaravelPot\PotServiceProvider::class);
            }
        }


        $this->app->bind('path.base', function () {
            return app()->environment(['development']) ? base_path() : getenv('HOME') . '/.supracodes/gpt';
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}

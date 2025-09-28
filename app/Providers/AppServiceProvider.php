<?php

namespace App\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Vite assets for Filament
        FilamentAsset::register([
            Css::make('app-styles', Vite::asset('resources/css/app.css')),
            Js::make('app-scripts', Vite::asset('resources/js/app.js')),
        ]);
    }
}

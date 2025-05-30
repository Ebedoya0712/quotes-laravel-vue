<?php

namespace Quotes;

use Illuminate\Support\ServiceProvider;

class QuotesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            base_path('packages/quotes/dist') => public_path('vendor/quotes'),
        ], 'quotes-assets');

        $this->publishes([
            base_path('packages/quotes/config/quotes.php') => config_path('quotes.php'),
        ], 'quotes-config');

        // Cargar rutas del paquete
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Cargar vistas del paquete
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'quotes');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/quotes.php', 'quotes'
        );
    }
}

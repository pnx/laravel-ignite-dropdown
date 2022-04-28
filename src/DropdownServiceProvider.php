<?php

namespace Ignite;

use Illuminate\Support\ServiceProvider;

class DropdownServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/ignite/dropdown'),
        ], 'ignite-views');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/ignite'),
        ], 'ignite-translation');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ignite-dropdown');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ignite');
    }
}

<?php

namespace App\Providers;

use mmghv\LumenRouteBinding\RouteBindingServiceProvider as BaseRouteBindingServiceProvider;

class RouteBindingServiceProvider extends BaseRouteBindingServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot()
    {
        // The binder instance
        $binder = $this->binder;

        // Here we define our bindings
        $binder->implicitBind('App\Models');
    }
}
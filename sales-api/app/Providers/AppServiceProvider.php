<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\CustomerInterface', 'App\Repositories\CustomerRepository');
        $this->app->bind('App\Interfaces\AddressInterface', 'App\Repositories\AddressRepository');
        $this->app->bind('App\Interfaces\SaleInterface', 'App\Repositories\SaleRepository');
    }
}

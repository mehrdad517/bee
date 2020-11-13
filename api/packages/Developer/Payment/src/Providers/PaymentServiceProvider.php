<?php

namespace Developer\Payment\Providers;

use Developer\Payment\Payment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('payment', function () {
            return new Payment();
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $views = __DIR__ . '/../views/';

        $this->loadViewsFrom($views, 'gateway');;
    }
}

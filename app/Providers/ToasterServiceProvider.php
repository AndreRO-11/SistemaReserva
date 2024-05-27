<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Masmerise\Toaster\Toaster;

class ToasterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //Toaster::livewire();
    }
}

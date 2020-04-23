<?php

namespace Nhinstein\Lamapay;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable;


class LamapayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
    
        /**
         * Load view template
         */
        $this->loadViewsFrom( __DIR__ . '/../Resources/Views', 'lamapay');

        /**
         * Publish 
         * php artisan vendor:publish --tag=assets --force
         */
        $this->publishes([
            __DIR__ . '/../dist/app.js' => public_path('/js/lamapay/app.js'),
            __DIR__ . '/../dist/app.css' => public_path('/css/lamapay/app.css'),
            __DIR__ . '/../Resources/sass/' => base_path('/resources/sass/lamapay'),
            __DIR__ . '/../Resources/js/' => base_path('/resources/js/lamapay'),
        ], 'assets');
        
        /**
         * Publish 
         * php artisan vendor:publish --tag=core
         */
        $this->publishes([
            __DIR__ . '/config/lamapay.php' => base_path('/config/lamapay.php'),
            __DIR__ . '/Http/Controllers/Web' => app_path('Http/Controllers/Web'),
            __DIR__ . '/Repositories/' => app_path('Repositories'),
            __DIR__ . '/../Resources/Views/vendor/' => base_path('/resources/views/vendor/lamapay/')
        ], 'core');

        /**
         * Migration file
         */
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }
}

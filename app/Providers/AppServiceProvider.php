<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FirebaseAuth::class, function ($app) {
            
            // CORRECCIÓN: storage_path() ya apunta a la carpeta /storage
            // Solo necesitamos la ruta interna: app/firebase_credentials.json
            $path = storage_path('app/firebase_credentials.json');

            // Inicializamos la Factory con la ruta corregida
            $factory = (new Factory)->withServiceAccount($path);

            return $factory->createAuth();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
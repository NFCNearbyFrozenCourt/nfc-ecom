<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Firebase;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $serviceAccount = config('firebase.service_account');
            
            $factory = (new Factory)
                ->withServiceAccount($serviceAccount);
            
            if (config('firebase.database_url')) {
                $factory = $factory->withDatabaseUri(config('firebase.database_url'));
            }
            
            return $factory->createFirebaseProject();
        });
        
        $this->app->singleton('firebase.auth', function ($app) {
            return $app->make('firebase')->getAuth();
        });
        
        $this->app->singleton('firebase.database', function ($app) {
            return $app->make('firebase')->getDatabase();
        });
        
        $this->app->singleton('firebase.storage', function ($app) {
            return $app->make('firebase')->getStorage();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

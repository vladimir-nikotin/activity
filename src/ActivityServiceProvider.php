<?php

namespace Vladi\Activity;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ActivityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('jsonrpcResponse', function($app) {
            return new JsonRpcResponse();
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Route::group([
            'prefix' => 'api',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }
}

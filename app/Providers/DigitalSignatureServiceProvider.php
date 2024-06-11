<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class DigitalSignatureServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('DigitalSignatureService', function ($app) {
            $config = Config::get('services.digital_signature');
            // Assuming DigitalSignatureService is a class that handles the integration with the digital signature API
            return new \App\Services\DigitalSignatureService($config['api_key'], $config['endpoint']);
        });
    }
}

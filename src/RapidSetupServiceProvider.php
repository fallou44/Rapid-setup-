<?php

namespace Fadildev\RapidSetup;

use Illuminate\Support\ServiceProvider;
use Fadildev\RapidSetup\Commands\InstallProjectCommand;

class RapidSetupServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallProjectCommand::class,
            ]);
        }
    }

    public function boot()
    {
        // Publier le fichier de configuration
        $this->publishes([
            __DIR__ . '/Config/rapid-setup.php' => config_path('rapid-setup.php'),
        ], 'rapid-setup-config');
    }
}

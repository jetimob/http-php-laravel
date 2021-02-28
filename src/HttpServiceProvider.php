<?php

namespace Jetimob\Http;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Jetimob\Http\Console\InstallCommand;

class HttpServiceProvider extends ServiceProvider implements DeferrableProvider
{
    private function getConfigPath(): string
    {
        return sprintf('%s/../config/config.php', __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'http');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getConfigPath() => config_path('http.php'),
            ], 'config');

            $this->commands([InstallCommand::class]);
        }

        $this->app->bind('jetimob.http', static function () {
            return new Http(config('http', []));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Http::class];
    }
}

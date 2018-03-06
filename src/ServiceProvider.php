<?php
/**
 * Created by PhpStorm.
 * User: snower
 * Date: 18/3/6
 * Time: 上午11:32
 */

namespace Snower\LaravelForsun;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config.php');

        if ($this->app instanceof LaravelApplication) {
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $source => config_path('forsun.php'),
                ]);
            }
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('forsun');
        }

        $this->mergeConfigFrom($source, 'forsun');
    }

    /**
     * Register the provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Forsun::class, function ($app) {
            $forsun = new Forsun(config('forsun'));
            return $forsun;
        });

        $this->app->alias(Forsun::class, 'forsun');
    }

    /**
     * Get config value by key.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    private function config($key, $default = null)
    {
        return $this->app->make('config')->get("forsun.{$key}", $default);
    }
}
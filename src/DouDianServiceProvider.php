<?php

namespace ManbinZheng\EasyDouDian;

use Illuminate\Support\ServiceProvider;

class DouDianServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/doudian.php', 'doudian'
        );
        $this->app->singleton("doudian", function ($app) {
            return new EasyDouDian(config('doudian.options.default.app_id'), config('doudian.options.default.app_secret'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/doudian.php' => config_path('doudian.php'), // 发布配置文件到 laravel 的config 下
        ]);
    }
}

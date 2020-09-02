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
        $config = config('doudian');
        foreach ($config['options'] as $key => $option) {
            $this->app->bind($key == 'default' ? 'doudian' : 'doudian.' . $key, function ($app) use ($key, $option) {
                return new EasyDouDian($option['app_id'], $option['app_secret']);
            });
        }
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

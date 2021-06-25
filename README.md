# laravel-easy-doudian

抖店开放平台SDK for Laravel

> QQ：894775625



## 安装

```shell
composer require "manbinzheng/easy-doudian"
```

## laravel配置
1. 在应用根目录下的`config/app.php` 找到providers标签，注册 ServiceProvider

```php
'providers' => [
    // ...
    ManbinZheng\EasyDouDian\DouDianServiceProvider::class,
]
```

2. 控制台中，执行以下指令，创建配置文件：

```shell
php artisan vendor:publish --provider="ManbinZheng\EasyDouDian\DouDianServiceProvider"
```

## lumen配置
1. 复制配置文件至config文件夹
```shell
cp -r vendor/manbinzheng/easy-doudian/src/config ./
```


2. 在bootstrap/app.php中注册config文件和ServiceProvider

```php

...

$app->configure('doudian');
$app->register(ManbinZheng\EasyDouDian\DouDianServiceProvider::class);

return $app;
```

## 配置参数

修改应用根目录下的 `config/doudian.php` 配置开放平台的参数。建议将敏感数据放于.env中。
```php
return [
    'options' => [
        'default' => [ //默认账号
            'app_id' => env('EASY_DOUDIAN_APP_ID'),
            'app_secret' => env('EASY_DOUDIAN_APP_SECERT'),
        ],
        'defined.account' => [ //多账号配置
            'app_id' => 'YOUR_APP_ID',
            'app_secret' => 'YOUR_APP_SECERT',
        ],
    ]
];
```

## 使用
先查阅官方文档，获取接口method参数，以及业务参数，业务参数以数组对象传参。  
抖店开放平台官方文档：https://op.jinritemai.com/docs/api-docs
```php
//默认实例获取订单详情
app('doudian')->request('order.detail',['order_id'=>'894775625']);
//多账号实例获取订单详情
app('doudian.defined.account')->request('order.detail',['order_id'=>'894775625']);

//无业务参数只传method参数
app('doudian')->request('shop.brandList');
```

# laravel-easy-doudian

抖店开放平台SDK for Laravel

> QQ：894775625



## 安装

```shell
composer require "manbinzheng/easy-doudian"
```

## 配置
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

3. 修改应用根目录下的 `config/doudian.php` 配置开放平台的参数。建议将敏感数据放于.env中。
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
```php
//默认实例获取订单详情
app('doudian')->orderDetail('894775625');
//多账号实例获取订单详情
app('doudian.defined.account')->orderDetail('894775625');
```


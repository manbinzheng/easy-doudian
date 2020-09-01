# laravel-easy-doudian

抖店开放平台SDK for Laravel

> QQ：894775625



## 安装

```shell
composer require "manbinzheng/easy-doudian"
```

## 配置
1. 在 `config/app.php` 注册 ServiceProvider

```php
'providers' => [
    // ...
    \ManbinZheng\EasyDouDian\DouDianServiceProvider::class,
]
```

2. 创建配置文件：

```shell
php artisan vendor:publish --provider="ManbinZheng\EasyDouDian\DouDianServiceProvider"
```

3. 修改应用根目录下的 `config/doudian.php` 配置抖店参数。为了数据安全，建议将敏感数据放于.env中。


## 使用
```php
app('doudian')->orderDetail('894775625');//例子:获取订单详情
```


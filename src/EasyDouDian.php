<?php


namespace ManbinZheng\EasyDouDian;


class EasyDouDian extends Base
{
    public $shop;
    public $product;
    public $order;

    public function __construct($app_key, $app_secret)
    {
        if (!$app_key || !$app_secret) throw new \Exception('请先配置app_key与app_secret！');
        $this->shop = new Shop($app_key, $app_secret);
        $this->product = new Product($app_key, $app_secret);
        $this->order = new Order($app_key, $app_secret);
    }


    /**
     * @param string $method 调用的API接口名称
     * @param array $data 调用api的业务参数
     * @return mixed
     * 按照官方文档自定义发起请求
     */
    public function request($method, $data)
    {
        return $this->baseRequest($method, $data);
    }

}

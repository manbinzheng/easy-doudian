<?php


namespace ManbinZheng\EasyDouDian;


class EasyDouDian extends Base
{

    /**
     * @param string $method 调用的api接口名称
     * @param array $params 调用api的业务参数
     * @return mixed
     * 按照官方文档自定义发起请求
     */
    public function request($method, $params = [])
    {
        return $this->baseRequest($method, $params);
    }

}

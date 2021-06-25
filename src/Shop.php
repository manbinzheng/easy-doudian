<?php


namespace ManbinZheng\EasyDouDian;


class Shop extends Base
{

    /**
     * @return mixed
     * 获取店铺的已授权品牌列表
     * 调用API接口创建商品时，入参brand_id（品牌选项）依赖此接口返回的id
     */
    public function brandList()
    {
        return $this->baseRequest('shop.brandList');
    }


    /**
     * @param string $cid 父类目id，根据父id可以获取子类目，一级类目cid=0 ，获取到二级类目后再通过这个作为入参获取后面的类目
     * @return mixed
     * 获取店铺后台供商家发布商品的类目
     * 根据父类目id获取子类目
     */
    public function getShopCategory($cid)
    {
        return $this->baseRequest('shop.getShopCategory', ['cid' => $cid]);
    }


    /**
     * @param array $params 请求入参
     * @return mixed
     * 售后地址列表接口
     */
    public function addressList($params)
    {
        return $this->baseRequest('shop.addressList', $params);
    }


}
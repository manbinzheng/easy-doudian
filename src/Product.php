<?php


namespace ManbinZheng\EasyDouDian;


class Product extends Base
{
    /**
     * @param string $product_id 商品id
     * @param bool $show_draft "true"：读取草稿数据；"false"：读取上架数据
     * @return mixed
     * 获取商品的详细信息（默认读取的是线上数据，而非草稿数据；如无线上数据，则读取草稿数据）
     */
    public function detail($product_id, $show_draft = false)
    {
        return $this->baseRequest('product.detail', ['product_id' => $product_id, 'show_draft' => $show_draft]);
    }


    /**
     * @param int $page 第几页（第一页为0）
     * @param int $status 指定状态返回商品列表：0上架 1下架
     * @param int $check_status 指定审核状态返回商品列表：1未提审 2审核中 3审核通过 4审核驳回 5封禁
     * @param int $size 每页返回条数，默认10
     * @return mixed
     * 获取商品列表
     */
    public function list($page, $status, $check_status, $size = 10)
    {
        return $this->baseRequest('product.list', ['page' => $page, 'size' => $size,
            'check_status' => $check_status, 'status' => $status]);
    }


    /**
     * @param $params
     * @return mixed
     * 创建商品的接口，商品添加成功后会自动进入平台的异步机审流程，机审完成后将自动上架。
     * 注："pic"、"description"、"spec_pic"这三个字段里的传入的图片数量总和，不得超过50张
     * //TODO 参数较多，解决传参问题
     */
    public function add($params)
    {
        return $this->baseRequest('product.add', $params);
    }

    /**
     * @param $params
     * @return mixed
     * 编辑商品相关信息。编辑提交后默认自动提交审核，审核通过后，更新线上数据。
     * //TODO 参数较多，解决传参问题
     */
    public function edit($params)
    {
        return $this->baseRequest('product.edit', $params);
    }


    /**
     * @param string $product_id 商品id
     * @return mixed
     * 删除商品
     */
    public function del($product_id)
    {
        return $this->baseRequest('product.del', ['product_id' => $product_id]);
    }


    /**
     * @param string $specs 店铺通用规格，能被同类商品通用。多规格用^分隔，父规格与子规格用|分隔，子规格用,分隔
     * @param string $name 如果不填，则规格名为子规格名用 "-" 自动生成
     * @return mixed
     * 添加规格
     */
    public function specAdd($specs, $name)
    {
        return $this->baseRequest('spec.add', ['specs' => $specs, 'name' => $name]);
    }


    /**
     * @param string $id 规格id (spec_id)
     * @return mixed
     * 获取规格详情
     */
    public function specDetail($id)
    {
        return $this->baseRequest('spec.specDetail', ['id' => $id]);
    }


    /**
     * @return mixed
     * 获取规格列表
     */
    public function specList()
    {
        return $this->baseRequest('spec.list');
    }

    /**
     * @param string $id 规格id (spec_id)
     * @return mixed
     * 获取规格详情
     */
    public function specDel($id)
    {
        return $this->baseRequest('spec.del', ['id' => $id]);
    }


    /**
     * @param $params
     * @return mixed
     * 添加SKU
     * 单个规格可设置的规格值最多是20个
     * //TODO 参数较多，解决传参问题
     */
    public function skuAdd($params)
    {
        return $this->baseRequest('sku.add', $params);
    }

    /**
     * @param string $product_id 商品id
     * @return mixed
     * 根据商品id获取商品的sku列表
     */
    public function skuList($product_id)
    {
        return $this->baseRequest('sku.add', ['product_id' => $product_id]);
    }

    /**
     * @param $params
     * @return mixed
     * 批量添加商品sku
     * 每次接口调用最多添加100个
     * //TODO 参数较多，解决传参问题
     */
    public function skuAddAll($params)
    {
        return $this->baseRequest('sku.addAll', $params);
    }


    /**
     * @param string $product_id 商品id
     * @param string $sku_id sku_id
     * @param string $price 售价 (单位 分)
     * @return mixed
     * 编辑sku价格
     */
    public function skuEditPrice($product_id, $sku_id, $price)
    {
        return $this->baseRequest('sku.editPrice', ['product_id' => $product_id, 'sku_id' => $sku_id, 'price' => $price]);
    }


    /**
     * @param string $product_id 商品id
     * @param string $sku_id sku_id
     * @param string $stock_num 库存 (可以为0)
     * @return mixed
     * 修改sku库存
     * 注：同步库存时请注意sku对应商品的状态status和check_status, 下架、删除、禁封等状态的商品不予同步sku库存
     */
    public function skuSyncStock($product_id, $sku_id, $stock_num)
    {
        return $this->baseRequest('sku.syncStock', ['product_id' => $product_id, 'sku_id' => $sku_id, 'stock_num' => $stock_num]);
    }

    /**
     * @param string $product_id 商品id
     * @param string $sku_id sku_id
     * @param string $code 编码
     * @return mixed
     * 修改sku编码
     */
    public function skuEditCode($product_id, $sku_id, $code)
    {
        return $this->baseRequest('sku.editCode', ['product_id' => $product_id, 'sku_id' => $sku_id, 'code' => $code]);
    }


    /**
     * @param string $first_cid 一级分类id
     * @param string $second_cid 二级分类id
     * @param string $third_cid 三级分类id
     * @return mixed
     * 根据商品分类获取对应的属性列表
     * 调用API接口创建商品时，入参product_format（属性对）依赖此接口返回的值
     */
    public function getCateProperty($first_cid, $second_cid, $third_cid)
    {
        return $this->baseRequest('product.getCateProperty', ['first_cid' => $first_cid, 'second_cid' => $second_cid, 'third_cid' => $third_cid]);
    }


    /**
     * @param string $sku_id skuid
     * @param string $supplier_id 供应商ID
     * @return mixed
     * 修改sku对应的供应商编码ID
     */
    public function editSupplierId($sku_id, $supplier_id)
    {
        return $this->baseRequest('product.getCateProperty', ['sku_id' => $sku_id, 'supplier_id' => $supplier_id]);
    }

    /**
     * @param $params
     * @return mixed
     * 新的商品发布接口，一次性完成商品-规格-SKU的发布，更高效的上传商品方式。
     * 注意： out_sku_id可以传数字；outer_sku_id可以传数字/英文字符串 ，推荐使用outer_sku_id
     */
    public function addV2($params)
    {
        return $this->baseRequest('product.addV2', $params);
    }


    /**
     * @param $params
     * @return mixed
     * 新的编辑商品相关信息接口。
     */
    public function editV2($params)
    {
        return $this->baseRequest('product.editV2', $params);
    }


    /**
     * @param string $product_id 商品ID
     * @return mixed
     * 支持设置指定商品上架
     */
    public function setOnline($product_id)
    {
        return $this->baseRequest('product.setOnline', ['product_id' => $product_id]);
    }


    /**
     * @param string $product_id 商品ID
     * @return mixed
     * 支持针对指定商品下架处理
     */
    public function setOffline($product_id)
    {
        return $this->baseRequest('product.setOffline', ['product_id' => $product_id]);
    }


    /**
     * @param $params
     * @return mixed
     * 编辑商品限购信息。编辑提交后默认自动提交审核，审核通过后，更新线上数据。
     */
    public function editBuyerLimit($params)
    {
        return $this->baseRequest('product.editBuyerLimit', $params);
    }


    /**
     * @param $params
     * @return mixed
     * 修改sku库存
     * Tips：
     * 同步库存时请注意sku对应商品的状态status和check_status（通过/product/detail查询），删除、禁封状态的商品不予同步sku库存，其他状态的商品可以修改库存成功。
     * 即使商品的sku被删除了，依旧可以修改库存成功，只是无实际意义（sku已删除，买家无法下单该sku）
     * 仅支持针对同一商品下的多SKU支持批量更新
     */
    public function skuSyncStockBatch($params)
    {
        return $this->baseRequest('sku.syncStockBatch', $params);
    }

    /**
     * @param string $name 运费模板名称，支持模糊搜索
     * @param int $page 页数（默认为0，第一页从0开始）
     * @param int $size 每页模板数（默认为10）
     * @return mixed
     */
    public function freightTemplateList($name, $page, $size)
    {
        return $this->baseRequest('freightTemplate.list', ['name' => $name, 'page' => $page, 'size' => $size]);
    }

    /**
     * @param string $sku_id sku_id
     * @param string $code sku code
     * @return mixed
     * 根据sku id获取商品sku详情
     */
    public function skuDetail($sku_id, $code)
    {
        return $this->baseRequest('sku.detail', ['sku_id' => $sku_id, 'code' => $code]);
    }

    /**
     * @param $params
     * @return mixed
     * 查询库存
     */
    public function stockNum($params)
    {
        return $this->baseRequest('sku.stockNum', $params);
    }

    /**
     * @param int $cid 父分类id,根据父id可以获取子分类，一级分类cid=0
     * @return mixed
     * 根据父分类id获取子分类
     */
    public function getGoodsCategory($cid = 0)
    {
        return $this->baseRequest('product.getGoodsCategory', ['cid' => $cid]);
    }
}
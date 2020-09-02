<?php


namespace ManbinZheng\EasyDouDian;


class EasyDouDian extends Base
{
    public function __construct($app_key, $app_secret)
    {
        if (!$app_key || !$app_secret) throw new \Exception('请先配置app_key与app_secret！');
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
    }

    /**
     * @return mixed
     * 获取店铺的已授权品牌列表
     * 调用API接口创建商品时，入参brand_id（品牌选项）依赖此接口返回的id
     */
    public function shopBrandList()
    {
        return $this->baseRequest('shop.brandList');
    }

    /**
     * @param string $product_id 商品id
     * @param bool $show_draft "true"：读取草稿数据；"false"：读取上架数据
     * @return mixed
     * 获取商品的详细信息（默认读取的是线上数据，而非草稿数据；如无线上数据，则读取草稿数据）
     */
    public function productDetail($product_id, $show_draft = false)
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
    public function productList($page, $status, $check_status, $size = 10)
    {
        return $this->baseRequest('product.list', ['page' => $page, 'size' => $size,
            'check_status' => $check_status, 'status' => $status]);
    }

    /**
     * @param int $cid 父分类id,根据父id可以获取子分类，一级分类cid=0
     * @return mixed
     * 根据父分类id获取子分类
     */
    public function productGetGoodsCategory($cid = 0)
    {
        return $this->baseRequest('product.getGoodsCategory', ['cid' => $cid]);
    }

    /**
     * @param $data
     * @return mixed
     * 创建商品的接口，商品添加成功后会自动进入平台的异步机审流程，机审完成后将自动上架。
     * 注："pic"、"description"、"spec_pic"这三个字段里的传入的图片数量总和，不得超过50张
     * //TODO 参数较多，解决传参问题
     */
    public function productAdd($data)
    {
        return $this->baseRequest('product.add', $data);
    }

    /**
     * @param $data
     * @return mixed
     * 编辑商品相关信息。编辑提交后默认自动提交审核，审核通过后，更新线上数据。
     * //TODO 参数较多，解决传参问题
     */
    public function productEdit($data)
    {
        return $this->baseRequest('product.edit', $data);
    }

    /**
     * @param string $product_id 商品id
     * @return mixed
     * 删除商品
     */
    public function productDel($product_id)
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
     * @param $data
     * @return mixed
     * 添加SKU
     * 单个规格可设置的规格值最多是20个
     * //TODO 参数较多，解决传参问题
     */
    public function skuAdd($data)
    {
        return $this->baseRequest('sku.add', $data);
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
     * @param $data
     * @return mixed
     * 批量添加商品sku
     * 每次接口调用最多添加100个
     * //TODO 参数较多，解决传参问题
     */
    public function skuAddAll($data)
    {
        return $this->baseRequest('sku.addAll', $data);
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
    public function productGetCateProperty($first_cid, $second_cid, $third_cid)
    {
        return $this->baseRequest('product.getCateProperty', ['first_cid' => $first_cid, 'second_cid' => $second_cid, 'third_cid' => $third_cid]);
    }


    /**
     * @param string $sku_id sku_id
     * @return mixed
     * 根据sku id获取商品sku详情
     */
    public function skuDetail($sku_id)
    {
        return $this->baseRequest('sku.detail', ['sku_id' => $sku_id]);
    }


    /**
     * @param string $start_time 开始时间 示例:2018/06/03 00:00:00
     * @param string $end_time 结束时间 示例:2018/06/03 00:01:00
     * @param string $page 页数（默认为0，第一页从0开始）
     * @param string $size 每页订单数（默认为10，最大100）
     * @param string $order_by 1、默认按订单创建时间搜索 2、值为“create_time”：按订单创建时间；值为“update_time”：按订单更新时间
     * @param string $is_desc 订单排序方式：设置了此字段即为desc(最近的在前)默认为asc（最近的在后）
     * @param null $order_status 子订单状态
     * @return mixed
     * 支持按照子订单状态和订单的创建时间、更新时间来检索订单，获取订单列表
     */
    public function orderList($start_time, $end_time, $page, $size, $order_by, $is_desc, $order_status = null)
    {
        $data = [
            'start_time' => $start_time,
            'end_time' => $end_time,
            'page' => $page,
        ];
        if ($size) $data['size'] = $size;
        if ($order_by) $data['order_by'] = $order_by;
        if ($is_desc) $data['is_desc'] = $is_desc;
        if ($order_status) $data['order_status'] = $order_status;
        return $this->baseRequest('order.list', $data);
    }


    /**
     * @param string $order_id 父订单id，由orderList接口返回
     * @return mixed
     * 根据订单ID，获取单个订单的详情信息
     */
    public function orderDetail($order_id)
    {
        return $this->baseRequest('order.detail', ['order_id' => $order_id]);
    }

    /**
     * @param string $order_id 父订单id，由orderList接口返回
     * @return mixed
     * 当货到付款订单是待确认状态（final_status=1）时，可调此接口确认订单。确认后，订单状态更新为“备货中”
     */
    public function orderStockUp($order_id)
    {
        return $this->baseRequest('order.stockUp', ['order_id' => $order_id]);
    }


    /**
     * @param string $order_id 父订单ID，由orderList接口返回
     * @param string $reason 取消订单的原因
     * @return mixed
     * 取消待确认或备货中的货到付款订单（final_status=1或2）
     */
    public function orderCancel($order_id, $reason)
    {
        return $this->baseRequest('order.cancel', ['order_id' => $order_id, 'reason' => $reason]);
    }

    /**
     * @param int $start_time 开始时间时间戳
     * @param int $end_time 结束时间时间戳
     * @param int $page 页数（默认值为0，第一页从0开始）
     * @param int $supply 是否获取分销商服务申请，0获取本店铺的服务申请，1获取分销商的服务申请
     * @param int $size 每页订单数（默认为10，最大100）
     * @param mixed $status 1、不传代表获取全部服务请求 2、操作状态：0待处理，1已处理
     * @return mixed
     * 获取客服向店铺发起的服务请求列表
     */
    public function orderServiceList(int $start_time, int $end_time, $page, $supply, $size, $status = null)
    {
        $data = ['start_time' => $start_time, 'end_time' => $end_time, 'supply' => $supply];
        if ($page) $data['page'] = $page;
        if ($status != null) $data['status'] = $status;
        if ($size) $data['size'] = $size;
        return $this->baseRequest('order.serviceList', $data);
    }

    /**
     * @param string $id 服务请求列表中获取的id
     * @param string $reply 回复内容
     * @return mixed
     * 回复客服向店铺发起的服务请求
     */
    public function orderReplyService($id, $reply)
    {
        return $this->baseRequest('order.replyService', ['id' => $id, 'reply' => $reply]);
    }

    /**
     * @return mixed
     * 获取平台支持的快递公司列表
     * Tips：开发者需自行维护快递公司ID的映射关系
     */
    public function orderLogisticsCompanyList()
    {
        return $this->baseRequest('order.logisticsCompanyList');
    }

    /**
     * @param string $order_id 父订单ID，由orderList接口返回
     * @param string $logistics_id 物流公司ID，由接口/order/logisticsCompanyList返回的物流公司列表中对应的ID
     * @param string $company 物流公司名称
     * @param string $logistics_code 运单号
     * @return mixed
     * 暂时只支持整单出库，即接口调用时入参只能传父订单号
     */
    public function orderLogisticsAdd($order_id, $logistics_id, $company, $logistics_code)
    {
        return $this->baseRequest('order.logisticsAdd', [
            'order_id' => $order_id, 'logistics_id' => $logistics_id,
            'company' => $company, 'logistics_code' => $logistics_code,
        ]);
    }

    /**
     * @param string $order_id 父订单ID，由orderList接口返回
     * @param string $logistics_id 物流公司ID，由接口/order/logisticsCompanyList返回的物流公司列表中对应的ID
     * @param string $company 物流公司名称
     * @param string $logistics_code 运单号
     * @return mixed
     * 修改已发货订单（final_status=3）的发货物流信息
     */
    public function orderLogisticsEdit($order_id, $logistics_id, $company, $logistics_code)
    {
        return $this->baseRequest('order.logisticsEdit', [
            'order_id' => $order_id, 'logistics_id' => $logistics_id,
            'company' => $company, 'logistics_code' => $logistics_code,
        ]);
    }

    /**
     * @return mixed
     * 获取平台支持的省列表
     */
    public function addressProvinceList()
    {
        return $this->baseRequest('address.provinceList');
    }

    /**
     * @param string $province_id 省ID
     * @return mixed
     * 获取平台支持的市列表
     */
    public function addressCityList($province_id)
    {
        return $this->baseRequest('address.cityList', ['province_id' => $province_id]);
    }

    /**
     * @param string $city_id 市ID
     * @return mixed
     * 获取平台支持的区列表
     */
    public function addressAreaList($city_id)
    {
        return $this->baseRequest('address.areaList', ['city_id' => $city_id]);
    }


    /**
     * @param string $method 调用的API接口名称
     * @param array $data 调用api的业务参数
     * @return mixed
     * 按照官方文档自定义发起请求
     */
    public function customizeRequest($method, $data)
    {
        return $this->baseRequest($method, $data);
    }
}

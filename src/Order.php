<?php


namespace ManbinZheng\EasyDouDian;


class Order extends Base
{
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
    public function list($start_time, $end_time, $page, $size, $order_by, $is_desc, $order_status = null)
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
    public function detail($order_id)
    {
        return $this->baseRequest('order.detail', ['order_id' => $order_id]);
    }

    /**
     * @param string $order_id 父订单id，由orderList接口返回
     * @return mixed
     * 当货到付款订单是待确认状态（final_status=1）时，可调此接口确认订单。确认后，订单状态更新为“备货中”
     */
    public function stockUp($order_id)
    {
        return $this->baseRequest('order.stockUp', ['order_id' => $order_id]);
    }


    /**
     * @param string $order_id 父订单ID，由orderList接口返回
     * @param string $reason 取消订单的原因
     * @return mixed
     * 取消待确认或备货中的货到付款订单（final_status=1或2）
     */
    public function cancel($order_id, $reason)
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
    public function serviceList(int $start_time, int $end_time, $page, $supply, $size, $status = null)
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
    public function replyService($id, $reply)
    {
        return $this->baseRequest('order.replyService', ['id' => $id, 'reply' => $reply]);
    }

    /**
     * @return mixed
     * 获取平台支持的快递公司列表
     * Tips：开发者需自行维护快递公司ID的映射关系
     */
    public function logisticsCompanyList()
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
    public function logisticsAdd($order_id, $logistics_id, $company, $logistics_code)
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
    public function logisticsEdit($order_id, $logistics_id, $company, $logistics_code)
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

}
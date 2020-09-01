<?php


namespace ManbinZheng\EasyDouDian;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class EasyDouDian
{
    private $app_key = '';
    private $app_secret = '';

    public function __construct($app_key, $app_secret)
    {
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
    }

    private function baseRequest($method, $param)
    {
        $url = 'https://openapi-fxg.jinritemai.com';
        sort($param);
        $data = [
            'method' => $method,
            'app_key' => $this->app_key,
            'access_token' => $this->getAccessToken(),
            'param_json' => json_encode($param),
            'timestamp' => date('yy-m-d H:m:s', time()),
            'v' => 2,
        ];
        sort($data);
        $str = $this->app_key;
        foreach ($data as $k => $v) $str .= $k . $v;
        $str = $str . $this->app_secret;
        $data['sign'] = md5($str);

        $client = new Client();
        $response = $client->request('GET', $url, [
            'query' => $data
        ]);
        return json_decode($response->getBody(), true);
    }


    private function getAccessToken()
    {
        $key = 'easy_doudian:access_token';
        if (!Cache::has($key)) {
            $url = 'https://openapi-fxg.jinritemai.com/oauth2/access_token?app_id='
                . $this->app_key . '&app_secret=' . $this->app_secret . '&grant_type=authorization_self';

            $client = new Client();
            $response = $client->request('GET', $url);
            $ret = json_decode($response->getBody(), true);
            if ($ret && $ret['err_no'] == 0) {
                $access_token = $ret['data']['access_token'];
                Cache::put($key, $access_token, $ret['data']['expires_in']);
            } else {
                throw new \Exception(json_encode($ret));
            }
        }
        return Cache::get($key);
    }


    public function orderDetail($order_id)
    {
        return $this->baseRequest('order.detail', ['order_id' => $order_id]);
    }


    public function orderDetailFromMerchant($order_id)
    {
        return $this->baseRequest('order.detail', ['order_id' => $order_id]);
    }

}

<?php


namespace ManbinZheng\EasyDouDian;


use Illuminate\Support\Facades\Cache;
use ManbinZheng\EasyDouDian\Http\HttpService;

class Base
{
    protected $app_key = '';
    protected $app_secret = '';
    protected $access_token_key = 'easy_doudian:access_token';

    protected function baseRequest($method, $param = [])
    {
        $url = 'https://openapi-fxg.jinritemai.com' . "/" . str_replace('.', '/', $method);
        ksort($param);
        $data = [
            'method' => $method,
            'app_key' => $this->app_key,
            'access_token' => $this->getAccessToken(),
            'param_json' => $param ? json_encode($param, JSON_UNESCAPED_UNICODE) : '{}',
            'timestamp' => urlencode(date('yy-m-d H:m:s', time())),
            'v' => 2,
        ];
        ksort($data);
        $str = $this->app_secret;
        foreach ($data as $k => $v) {
            if ($k != 'access_token') $str .= $k . $v;
        }
        $str = $str . $this->app_secret;
        $data['sign'] = md5($str);
        $response = HttpService::request($url, 'POST', $data);
        $response = json_decode($response, true);
        if ($response && $response['err_no'] == 30005) {
            Cache::forget($this->access_token_key);
            return $this->baseRequest($method, $param);
        }
        return $response;
    }


    protected function getAccessToken()
    {
        if (!Cache::has($this->access_token_key)) {
            $url = 'https://openapi-fxg.jinritemai.com/oauth2/access_token?app_id='
                . $this->app_key . '&app_secret=' . $this->app_secret . '&grant_type=authorization_self';
            $response = HttpService::request($url, 'GET');
            $response = json_decode($response, true);
            if ($response && $response['err_no'] == 0) {
                if (!Cache::put($this->access_token_key,
                    $response['data']['access_token'],
                    $response['data']['expires_in'])) {
                    throw new \Exception('access_token缓存失败，请检查框架缓存配置！');
                }
            } else {
                throw new \Exception(json_encode($response));
            }
        }
        return Cache::get($this->access_token_key);
    }
}

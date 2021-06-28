<?php


namespace ManbinZheng\EasyDouDian;


use Illuminate\Support\Facades\Cache;
use ManbinZheng\EasyDouDian\Http\HttpService;

class Base
{
    protected $app_key = '';
    protected $app_secret = '';
    protected $access_token_key = 'easy_doudian:access_token:';

    /**
     * Base constructor.
     * @param string $app_key 抖店开放平台工具app_key
     * @param string $app_secret 抖店开放平台工具app_secret
     * @throws \Exception
     */
    public function __construct($app_key, $app_secret)
    {
        if (!$app_key || !$app_secret) throw new \Exception('请先配置app_key与app_secret！');
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
    }

    protected function baseRequest($method, $param = [])
    {
        $url = 'https://openapi-fxg.jinritemai.com' . "/" . str_replace('.', '/', $method);
        ksort($param);
        $data = [
            'method' => $method,
            'app_key' => $this->app_key,
            'access_token' => $this->getAccessToken(),
            'param_json' => $param ? json_encode($param, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '{}',
            'timestamp' => date('Y-m-d H:i:s', time()),
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
            Cache::forget($this->access_token_key . $this->app_key);
            return $this->baseRequest($method, $param);
        }
        return $response;
    }


    protected function getAccessToken()
    {
        $key = $this->access_token_key . $this->app_key;
        if (!Cache::has($key)) {
            $url = 'https://openapi-fxg.jinritemai.com/oauth2/access_token?app_id='
                . $this->app_key . '&app_secret=' . $this->app_secret . '&grant_type=authorization_self';
            $response = HttpService::request($url, 'GET');
            $ret = json_decode($response, true);
            if ($ret && $ret['err_no'] == 0) {
                $access_token = $ret['data']['access_token'];
                Cache::put($key, $access_token, $ret['data']['expires_in']);
            } else {
                throw new \Exception(json_encode($ret));
            }
        }
        return Cache::get($key);
    }
}

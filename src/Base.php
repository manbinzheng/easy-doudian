<?php


namespace ManbinZheng\EasyDouDian;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Base
{
    protected $app_key = '';
    protected $app_secret = '';

    protected function baseRequest($method, $param = [])
    {
        $url = 'https://openapi-fxg.jinritemai.com' . "/" . str_replace('.', '/', $method);
        ksort($param);
        $data = [
            'method' => $method,
            'app_key' => $this->app_key,
            'access_token' => $this->getAccessToken(),
            'param_json' => $param ? json_encode($param,JSON_UNESCAPED_UNICODE) : '{}',
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
        $client = new Client();
        $response = $client->request('POST', $url, [
            'form_params' => $data
        ]);
        return json_decode($response->getBody(), true);
    }


    protected function getAccessToken()
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
}

<?php

namespace App\Http\Services;

class HttpService
{
    /**
     * @param string $url
     * @param string $type
     * @param array $data
     * @param string $cookie
     * @param array $headers
     * @param string $user_agent
     * @param int $timeout
     * @param null $res_header
     * @return bool|string
     * http请求
     */
    public static function request($url, $type, $data = [], $cookie = "", $headers = [], $user_agent = "", $timeout = 20, &$res_header = null)
    {
        $type = strtoupper($type);
        if ($type == 'GET' && is_array($data)) {
            $data = http_build_query($data);
        }
        $option = [];
        if ($type == 'POST') {
            $option[CURLOPT_POST] = 1;
        }
        if ($data) {
            if ($type == 'POST') {
                if (is_array($data) && !isset($data['file'])) {
                    $post_data = http_build_query($data, null, '&');
                    if (isset($headers['Content-Type'])) {
                        if (strpos($headers['Content-Type'], 'json') !== false) {
                            $post_data = json_encode($data);
                        }
                    }
                } else {
                    $post_data = $data;
                }
                $option[CURLOPT_POSTFIELDS] = $post_data;
            } elseif ($type == 'GET') {
                $url = strpos($url, '?') !== false ? $url . '&' . $data : $url . '?' . $data;
            }
        }
        $option[CURLOPT_URL] = $url;
        $option[CURLOPT_FOLLOWLOCATION] = TRUE;
        $option[CURLOPT_MAXREDIRS] = 4;
        $option[CURLOPT_RETURNTRANSFER] = TRUE;
        $option[CURLOPT_TIMEOUT] = $timeout;

        $header = [];
        foreach ($headers as $key => $value) {
            $header[] = $key . ":" . $value;
        }
        $option[CURLOPT_HTTPHEADER] = $header;

        $option[CURLOPT_HEADER] = true;

        $ch = curl_init();

        curl_setopt_array($ch, $option);

        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }

        if ($user_agent) {
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        }

        $response = curl_exec($ch);
        //$curl_no = curl_errno($ch);
        //$curl_err = curl_error($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $res_header = substr($response, 0, $headerSize);
        $response = substr($response, $headerSize);
        return $response;
    }

}

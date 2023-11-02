<?php

error_reporting(1);
ini_set('display_errors', true);
// require_once('vendor/autoload.php');
require_once('config/app.php');
session_start();

class Api
{
    protected $endPoint;
    protected $method = 'GET';
    protected $accessToken;
    protected $response = [];
    protected $params;
    protected $apiBaseUrl = 'https://api.webflow.com/v2/';

    public function __construct()
    {
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function callApi()
    {
        try {
            if ($this->method == 'GET') {
                $result = $this->get();
                $this->response = $result;
            } else if ($this->method == 'PATCH') {
                $data = [
                    'headers' => [
                        'accept' => 'application/json',
                        'authorization' => 'Bearer ' . $this->accessToken,
                    ],
                    'body' => $this->params
                ];
            }

            // $response = $client->request($this->method, $url, $data);

            // $this->response = json_decode($response->getBody(), TRUE);
        } catch (Exception $e) {
            // $this->response = ['message' => $e->getMessage(), 'url' => $url];
        }
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function sendResponse()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->response);
    }


    public function get()
    {
        $baseUrl = $this->apiBaseUrl;
        $url = $baseUrl . $this->endPoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer e7b42aa31e71d309b0bc9bcd18ded91a4a260521bc54619d1f2cbb5890759a51';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public static function post($endpoint, $params = [], $token = true, $baseUrl = false)
    {
        $dataWithBuildQuery = false;
        $baseUrl = self::inst()->getBaseApiUrl($baseUrl);
        $url = $baseUrl . '/' . $endpoint;
        if ($token == true) {
            $token = self::inst()->getToken();
            $params['token'] = $token;
            $url = $baseUrl . '/' . $endpoint . '?token=' . $token;
        }

        self::addCurlFile($params);

        $ch = curl_init();
        // add proxy support
        $api_proxy = Settings::get('api_proxy');
        if (!empty($api_proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $api_proxy);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, self::curl_postfields_flatten($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        if (Settings::get('ssl_verify_host') == 'no') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $response = curl_exec($ch);
        return $response;
    }
}

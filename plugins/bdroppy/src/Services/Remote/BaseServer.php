<?php
namespace BDroppy\Services\Remote;

if ( ! defined( 'ABSPATH' ) ) exit;

use BDroppy\CronJob\CronJob;
use BDroppy\Init\Core;

class BaseServer
{

    protected $core;
    protected $logger;
    protected $url;
    protected $mainUrl = 'https://prod.bdroppy.com';
    protected $method = 'GET';
    protected $headerAccept = 'application/json';
    protected $headerContentType = 'application/json';
    protected $body = null;
    protected $config;

    private $params;

    public function __construct(Core $core)
    {
       $this->core      = $core;
       $this->config  = $core->getConfig();
       $this->logger  = $core->getLogger();
       $this->init();
       }
    protected function addParams($params)
    {
        if ($params != null){
            $this->params .= $this->params != null?'&': '';
            $this->params .= http_build_query($params);
        }
    }

    protected function init($prefix = null)
    {
        $prefix = is_null($prefix)? '/restful/' :$prefix;
        $this->url = $this->config->api->get('api-base-url',$this->mainUrl).$prefix;
        $this->params = null;
        $this->method = 'GET';
        $this->headerContentType = "application/json";
        $this->headerAccept = "application/json";
        $this->body = null;

    }

    protected function getArg()
    {
        return [
            'headers' => [
                "accept"        => $this->headerAccept,
                'Content-Type'  => $this->headerContentType,
                "authorization" => 'Bearer ' .$this->config->api->get('api-token')
            ],
            'sslcertificates' => false,
            'timeout' => 300,
            'method' => $this->method,
            'body' => $this->body
        ];
    }

    protected function getURL($url)
    {
        $url .= isset($this->params) ? '?'. $this->params : null;
        return isset($this->url)? $this->url . $url : null;
    }

    protected function get($url)
    {
        $this->method = "GET";
        return $this->send($url);
    }

    protected function post($url,$data,$retryToGetNewToken=true)
    {
        $this->method = "POST";
        return $this->send($url,$data,$retryToGetNewToken);
    }

    protected function getResponseBody($body)
    {
        if($this->headerAccept == 'application/json')
        {
            return json_decode($body);
        }else{
            return $body;
        }
    }

    protected function send($url,$body = null,$retryToGetNewToken = true)
    {
        if (!empty($body)) $this->body = $body;
        $response = wp_remote_get($this->getURL($url),$this->getArg());
        if (!is_wp_error($response))
        {
            if($response['response']['code'] == 401 && $retryToGetNewToken)
            {

                $email = $this->config->api->get('api-email');
                $password = $this->config->api->get('api-password');

                $secundBase = new BaseServer($this->core);
                $secundBase->init('/api/');
                $result = $secundBase->post("auth/login", json_encode([
                    'email' => $email,
                    'password' => $password
                ]),false);

                if($result['response']['code'] == 200)
                {
                    foreach ($this->config->setting->getOrderErrorEmails() as $email){
                        wp_mail($email,'Bdroppy change token','bdroppy token refresh is successful ! -- response status : '. $result['response']['code']);
                    }
                    $this->config->api->set('api-token',$result['body']->token);
                    $this->config->api->set('api-token-for-user',$result['body']->email);
                }else{
                    foreach ($this->config->setting->getOrderErrorEmails() as $email){
                        wp_mail($email,'Bdroppy change token','bdroppy token refresh isn\'t successful ! -- response status : '. $result['response']['code']);
                    }
                    $this->config->api->set('api-token',false);
                    $this->config->api->set('api-token-for-user',false);
                    CronJob::unScheduleEvents();
                }
                $response = wp_remote_get($this->getURL($url),$this->getArg());
            }
            $response =  [
                'body' => $this->getResponseBody($response['body']),
                'response' => $response['response'],
            ];
        }else{
            $response = [
                'body' => $response->errors,
                'response' => ['code' => 500,'message' => 'Wordpress errors !']
            ];
        }
        $this->init();
        return $response;

    }

}
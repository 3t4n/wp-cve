<?php
/**
 *Models a request to be passed to the context of the trigger service to allow if-so's triggers to work on current request or passed request passed via AJAX
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\PublicFace\Helpers\IfSoHttpGetRequest;

class IfSoHttpGetRequest{
    private $requestURL;

    private $params;

    private $requestType;

    private $referrer = '';

    private function __construct($url,$referrer){
        if($url!==null){
            $this->requestURL = $url;
            $this->params = $this->getParamsFromURL($url);
            $this->referrer = $referrer;
            $this->requestType = 'AJAX';
        }
        else{
            $this->requestURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $this->params = $_GET;
            $this->referrer = '';
            if (isset($_SERVER['HTTP_REFERER']))
                $this->referrer = $_SERVER['HTTP_REFERER'];
            $this->requestType = 'pageload';
        }

        $this->requestURL = urldecode($this->requestURL);
        array_walk_recursive($this->params,function (&$param){
            $param = urldecode($param);
        });
    }

    public static function create($url=null, $referrer = ''){
        return new IfSoHttpGetRequest($url,$referrer);
    }

    private function getParamsFromURL($url){
        $ret = [];
        $comp = parse_url($url);
        if(!empty($comp['query'])){
            parse_str($comp['query'],$ret);
        }
        return $ret;
    }

    public function getRequestURL(){
        return $this->requestURL;
    }


    public function getParam($paramName){
        if(isset($this->params[$paramName])){
            return $this->params[$paramName];
        }
        return null;
    }

    public function getParams(){
        return $this->params;
    }

    public function getRequestType(){
        return $this->requestType;
    }

    public function getReferrer(){
        return $this->referrer;
    }
}
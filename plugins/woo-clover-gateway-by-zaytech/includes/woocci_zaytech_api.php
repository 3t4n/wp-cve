<?php

class Woocci_zaytech_api {

    /**
     * Your unique access token to link you store with your Clover POS
     * see How it works section for more information
     * or visit smartonlineorder.com
     * @var string
     */
    private $accessToken;
    /**
     * An API service Provided by Zaytech that will create the order for you in your Clover POS
     * and will allow you accept payments directly in your Merchant account
     * @var string
     */
    public $api_url;

    /**
     * The 2nd version of API service Provided by Zaytech
     * @var string
     */
    public  $api_url_v2;

    /**
     * Set it to true to enable debug mode and see the API response
     * @var bool
     */
    public $debug = false;

    /**
     * The JWT Token to communicate with the backend
     * @var string
     */
    public $jwtToken ;


    function __construct($token) {
        if(isset( $token )) {
            $this->accessToken = $token;
        } else {
            Woocci_Logger::log("Api key required");
        }

        /**
         * Zaytech's api urls
         */
        if(strtoupper(WOOCCI_ENV) === 'DEV') {
            $this->api_url = "https://api-sandbox.smartonlineorders.com/";
            $this->api_url_v2 = "https://api-v2-sandbox.smartonlineorders.com/v2/";
        } else {
            $this->api_url = "https://api.smartonlineorders.com/";
            $this->api_url_v2 = "https://api-v2.smartonlineorders.com/v2/";
        }


    }

    /**
     * Resst the JWT Token when it's not valid or expired
     */
    public function resetJwtToken() {
        $this->jwtToken = null;
        update_option("woocci_jwt_token", "");
    }
    public function getJwtToken() {
        if($this->accessToken === ""){
            return null;
        }
        $endPoint = $this->api_url_v2 . "auth/login";
        $body = array(
            'api_key' => $this->accessToken
        );
        $response = wp_remote_post( $endPoint, array(
                'method'      => 'POST',
                'timeout'     => 60,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(
                    "Content-Type"=>"application/json",
                    "Accept"=>"application/json",
                ),
                'body'        => json_encode($body),
                'cookies'     => array()
            )
        );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            if($this->debug){
                echo "Something went wrong when getting the JWT TOKEN: $error_message";
            }
        } else {
            $http_code = wp_remote_retrieve_response_code( $response );
            $responseContent =  json_decode(wp_remote_retrieve_body( $response ));
            if( $http_code === 200 ) {
                if(isset($responseContent->access_token)){
                    $this->jwtToken =  $responseContent->access_token;
                    update_option("woocci_jwt_token", $this->jwtToken);
                    return true;
                }
            } else {
                if($this->debug){
                    echo "Something went wrong when getting jwt-token: $http_code =>". json_encode($responseContent);
                }
            }
        }
        return null;
    }

    /**
     * For future usage, this function will return the keys to crypt the card information when we will add the
     * direct payments feature
     * @return bool|array
     */
    public function getPayKey() {
        return $this->apiGet("paykey");
    }
    public function getPakmsKey() {
         $forceNewKey = get_transient( "woocci_force_pakms" ) === "yes";

         //Get the key from database
         $key = get_option("woocci_pakms_key");

         if(!$forceNewKey && !empty($key)){
             return $key;
         }
        // Key Not found on databse get it from the server and save it into database
         $endpoint = $this->api_url_v2 . "merchants/pakms";
         $res = $this->getRequest($endpoint,true);
         if(isset($res["status"]) && $res["status"] === "success"){
             update_option("woocci_pakms_key",$res["key"]);
             return $res["key"];
         } else {
             return null;
         }
    }

    /**
     * To create new order on CLover POS
     * @param $data // Order information
     * @return bool|mixed
     */
    public function createOrder($data){
        $endpoint = $this->api_url_v2 . "orders";
        return $this->postRequest($endpoint,$data, true);
    }
    public function refundOrder($order_uuid, $data){
        $endpoint = $this->api_url_v2 . "orders/" . $order_uuid . "/woo-refund";
        return $this->postRequest($endpoint, $data, true);
    }
    /**
     * Get the order details
     */
    public function getOrder( $uuid ){
        return $this->apiGet("orders/".$uuid);
    }

    /**
     * To send get request to our Zaytech API
     * @param $url
     * @return bool|array
     */
    private function apiGet($url) {
        $args = array(
            "headers"=> array(
                "Accept"=>"application/json",
                "X-Authorization"=>$this->accessToken,
            )
        );
        $url = $this->api_url.$url;
        $response = wp_remote_get($url,$args);
        if ( is_array( $response ) ) {
            if($response["response"]["code"] === 200)
                return $response['body'];
        }
        return false;
    }

    /**
     * To send post requests to Zaytech api
     * @param $url
     * @param $data
     * @return bool|mixed
     */
    private function apiPost($url, $data) {
        $args = array(
            "headers" => array(
                "Accept"=>"application/json",
                "X-Authorization"=>$this->accessToken,
            ),
            "body" => $data
        );
        $url = $this->api_url.$url;
        $response = wp_remote_post($url,$args);
        if ( is_array( $response ) ) {
            if($response["response"]["code"] === 200)
                return $response['body'];
        }
        return false;
    }

    public function getRequest($url, $withJwt = false) {
        if($withJwt) {
            if($this->jwtToken){
                $headers = array(
                    "Accept"=>"application/json",
                    "Content-Type"=>"application/json",
                    "Authorization"=>"Bearer ".$this->jwtToken,
                );
            } else {
                $this->getJwtToken();
                $headers = array(
                    "Accept"=>"application/json",
                    "Content-Type"=>"application/json",
                    "Authorization"=>"Bearer ".$this->jwtToken,
                );
            }
        } else {
            $headers = array(
                "Accept"=>"application/json",
                "X-Authorization"=>$this->accessToken,
            );
        }
        $res = $this->apiV2Get($url,$withJwt, $headers);
        if($res){
            try {
                $data = json_decode($res,true);
                return $data;
            } catch (Exception $e){
                if($this->debug){
                    echo "Something went wrong: ".$e->getMessage();
                }
            }
        }
        return false;
    }
    public function postRequest($url,$body, $withJwt = false) {
        if($withJwt) {
            if($this->jwtToken){
                $headers = array(
                    "Accept"=>"application/json",
                    "Content-Type"=>"application/json",
                    "Authorization"=>"Bearer ".$this->jwtToken,
                );
            } else {
                $this->getJwtToken();
                $headers = array(
                    "Accept"=>"application/json",
                    "Content-Type"=>"application/json",
                    "Authorization"=>"Bearer ".$this->jwtToken,
                );
            }
        } else {
            $headers = array(
                "Accept"=>"application/json",
                "X-Authorization"=>$this->accessToken,
            );
        }
        $args = array(
            "headers"=> $headers,
            "body" => json_encode($body)
        );
        $res = $this->apiV2Post($url,$args);
        if($res && is_array($res)){
            return json_decode($res["responseContent"],true);
        }
        return false;
    }
    /**
     * To send get request to our Zaytech API
     * @param $url
     * @return bool|array
     */
    private function apiV2Get($url,$withJwt, $headers) {
        $args = array(
            "headers"=> $headers
        );
        $response = $this->sendHttpRequest($url,"GET",$args);
        if($response && is_array($response)){
            if($response["httpCode"] === 200 ){
                return $response["responseContent"];
            } else {
                if($response["httpCode"] === 401 ){
                    if($withJwt){
                        $this->resetJwtToken();
                        $this->getJwtToken();
                        $response = $this->sendHttpRequest($url,"POST",$args);
                        if($response && is_array($response)){
                            if($response["httpCode"] === 200 ){
                                return $response["responseContent"];
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    private function apiV2Post($url, $args,$isSecondTry = false) {
        $defaultArgs = array(
            'timeout'     => 120,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => false,
            'blocking'    => true,
            'cookies'     => array()
        );
        $allArgs = array_merge($defaultArgs,$args);
        $response = wp_remote_post($url,$allArgs);

        if(is_wp_error( $response )){
            if( $this->debug ){
                echo "Something went wrong: ".$response->get_error_message();
            }
            return false;
        } else {
            $res = array(
                "httpCode"=> wp_remote_retrieve_response_code( $response ),
                "responseContent"=> wp_remote_retrieve_body( $response ),
            );
            if($res["httpCode"] === 200 ){
                return $res;
            } else {
                if($res["httpCode"] === 401 ){
                    $this->resetJwtToken();
                    if(!$isSecondTry){
                        if( $this->getJwtToken() ){
                            return $this->apiV2Post($url,$args,true);
                        }
                    }
                }
            }
        }
        return false;
    }
    private function sendHttpRequest($url, $method, $args) {

        $defaultArgs = array(
            'method'      => $method,
            'timeout'     => 120,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => false,
            'blocking'    => true,
            'cookies'     => array()
        );
        //Add Client IP to the requests
        if (isset($args["headers"])){
            $args["headers"] = array_merge($args["headers"],array(
                'X_CLIENT_IP' => $this->getClientIp()
            ));
        } else {
            $args["headers"] = array(
                'X_CLIENT_IP' => $this->getClientIp()
            );
        }
        $allArgs = array_merge($defaultArgs,$args);
        $response = wp_remote_request($url,$allArgs);
        if(is_wp_error( $response )){
            if( $this->debug ){
                echo "Something went wrong: ".$response->get_error_message();
            }
            return false;
        } else {
            return array(
                "httpCode"=> wp_remote_retrieve_response_code( $response ),
                "responseContent"=> wp_remote_retrieve_body( $response ),
            );
        }
    }
    private function getClientIp(){

        if( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) && !empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ){
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        if( isset( $_SERVER['HTTP_CLIENT_IP'] ) && !empty( $_SERVER['HTTP_CLIENT_IP'] ) ){
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}
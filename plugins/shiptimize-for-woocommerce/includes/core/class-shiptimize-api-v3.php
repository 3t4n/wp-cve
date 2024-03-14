<?php
/**
 * The V3 of the api
 */
class ShiptimizeApiV3
{


    /**
    * @var String servertimezone
    */
    protected static $_server_timezone = 'Europe/Amsterdam';
 
    /**
    * The single instance
    *
    * @var ShiptimizeApi
    * @since 1.0.0
    */
    protected static $_instance = null;

    /**
    * If we are testing only
    *
    * @var Boolean $_test
    * @since 1.0.0
    */
    protected $test = true;

    /**
    * the public key
    * @var Sring $public_key
    */
    protected $public_key = null;

    /**
    * the private key
    * @var String $private_key
    */
    protected $private_key = null;

    /**
    * the token expires date
    * @var date - token expires
    */
    protected $token_expires = null;

    /**
    * the temporary token
    * @var String $token
    */
    protected $token = null;

    /**
    * The pakketmail identifier for this platform
    *
    * @var number $appclass
    */
    protected $app_id = null;

    /**
    * The api url
    * @var String
    * @since 1.0.0
    */
    protected $api_url = SHIPTIMIZE_API_URL;

    /** 
     * The local dev url 
     */ 
    protected $api_url_dev = SHIPTIMIZE_API_URL; 

    /**
    * @var String
    * @since 1.0.0
    */
    protected $version = '3.0.0';

    /**
     * @var bool - dump requests and other debug info
     */
    protected $debug = false;


    private function __construct($public_key, $private_key, $app_id, $test, $token = '', $token_expires ='')
    {
        $this->private_key = $private_key;
        $this->public_key = $public_key;
        $this->token = $token;
        $this->token_expires = $token_expires;
        
        $this->app_id = $app_id;
        $this->test = $test; 

        $this->is_dev =  defined('SHIPTIMIZE_DEV'); 
    }

    /**
     * @param string token
     */
    public function set_token($token)
    {
        $this->token = $token;
    }

    /**
     * @param string token_expires
     */
    public function set_token_expires($token)
    {
        $this->token_expires = $token_expires;
    }

    /**
     * Send the orders to shiptimize
     * @since 1.0.0
     *
     * @param ShiptimizeOrder[] $orders - the array of orders to send
     */
    protected function send_to_shiptimize($orders)
    {
        die("waiting for api endpoint ");
    }

    /** 
     * @return bool test
     */ 
    public function isTest(){
        return $this->test;
    }

    /**
     * Singleton pattern ensures only one shiptimize instance
     * @since 3.0.0
     *
     * @param string $public_key
     * @param string $private_key
     * @param string $app_id
     * @param string $test
     *
     * @return ShiptimizeApi - instance.
     */
    public static function instance($public_key, $private_key, $app_id, $test, $token = '', $token_expires ='')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($public_key, $private_key, $app_id, $test, $token, $token_expires);
            if (preg_match("/\.lan/",$_SERVER['HTTP_HOST'])) {
                self::$_instance->api_url_dev = 'https://api.lan/v3';  
            } 
        }
        
        return self::$_instance;
    }

    /**
     * Create a client under the master account of the keys assigned to this plugin 
     * Client
     *  Address : {
     *   AddressType* : 1 - main address 
     *   City*: 
     *   CompanyName: 
     *   Country*: 
     *   Email*:
     *   HouseNumber: * 
     *   Name: * 
     *   Neighborhood: 
     *   NumberExtension: 
     *   Phone*: 
     *   PostalCode*: 
     *   State: 
     *   Streetname1*: 
     *   Streetname2: 
     *   Timezone: Continent/City 
     *  },
     *  Contact {
     *   Email*: 
     *   Name*:
     *   Phone*:  
     *  }
     * User {
     *  Email: 
     *  LoginName*:
     *  Name:
     *  Password*: 10-100 chars  
     * }
     */ 
    public function create_client($clientData) {
        return $this->send_to_api('POST','/clients',$clientData);
    }

    public function get_clients() {
        return $this->send_to_api('GET','/clients');
    }

    /**
     * Retrives a temporary token that replaces the public key in auth for any subsequent requests
     * Make sure we reset the token before requesting a new one, in case there is one 
     *
     * @since 3.0.0
     *
     * @param String $shop_url - a callback url the api can use to say it has updates
     * @param String $plugin_version  - this plugin's version
     * @param String $platform_version - the version of the system
     *
     * @return mixed {String Key - the token, Date Expire - YYYY-mm-dd 00:00 UTC+1}
     */
    public function get_token($shop_url, $platform_version, $plugin_version)
    {
        $data = array(
            'PluginVersion' => $plugin_version,
            'ShopUrl' =>  $shop_url,
            'PlatformVersion' => $platform_version
        );
        
        $this->token = ''; 
        $this->token_expires =''; 

        $serverResponse = $this->send_to_api('POST', '/keys', $data);
        
        $this->log(' Received Token ' . var_export($serverResponse, true) ); 

        if ($serverResponse->httpCode == 200 && isset($serverResponse->response->Key)) {
            $token = $serverResponse->response;
            $this->token = $token->Key;
            $this->token_expires = $token->Expire;
            
            return $serverResponse->response;
        } else {
            return $serverResponse;
        }
    }

    /** 
     * @return the token string 
     */ 
    public function get_token_string(){
        return $this->token; 
    }

    /** 
     * @return string - the private_key 
     */ 
    public function get_private_key(){
        return $this->private_key;
    }

    /** 
     * @return string - the public key
     */ 
    public function get_public_key(){
        return $this->public_key; 
    }

    /**
     * Determine if the token we have is still valid
     * @since 3.0.0
     *
     * @return boolean - true if the token exists and has not expired
     */
    public function is_token_valid()
    {
        if (!$this->token || !$this->token_expires) {
            return false;
        }

        $expires = new \DateTime($this->token_expires.' 00:00:00 '.self::$_server_timezone);
        $now = new \DateTime();
 
        return $expires > $now;
    }

    /**
     * Generate a base64 hmac using sha256
     * @since 3.0.0
     *
     * Note that to guarante we obtain an equivalent output across all languages used in the shiptimize ecossystem we
     * should set raw_output to true when generating the hash
     * Remember that php adds padding to encoding while perl does not 
     * 
     * @param  String $data - the data to be sent
     * @return String - a hmac_sha256_base64 of the data hashed with the private key
     */
    private function get_request_signature($data)
    {
        $hmac256 = hash_hmac('sha256', $data, $this->private_key, true);
        return base64_encode($hmac256);
    }

    /**
     * Get the carriers for this contract
     * @return mixed $carriers - an array of carriers or server response {Error:, response} if error
     */
    public function get_carriers()
    {
        if (!$this->token) {
            return null;
        }

        $serverResponse = $this->send_to_api('GET', '/carriers');

        if ('200' == $serverResponse->httpCode) {
            $carrier_list =   $serverResponse->response;
            $this->log(" get_carriers ".json_encode($carrier_list));  
            
            return !$carrier_list->Error->Id ? $carrier_list->Carrier : $carrier_list;
        }

        return $serverResponse->response;
    }

    /**
     * Return a human readable html string with the request summary
     * If we need to debug what is being sent to the server
     */
    private function print_request_data($method, $endpoint, $data, $headers,$username,$password)
    {
        $url = ($this->is_dev ? $this->api_url_dev : $this->api_url).$endpoint;
        $json_data = json_encode($data); 

        return "====  Url: $url \nMethod:$method \njson_data: $json_data \nUsername: $username  \nprivate_key: {$this->private_key} \ntoken: {$this->token}  \ntoken_expires:  {$this->token_expires} \npassword: $password  \nHeaders: ".var_export($headers, true);
    }

    /** 
     * If the carrier has been meanwhile disabled in the client settings the api will return a 403 
     * If the StreetName2 is a number the api will return an error. 
     * 
     * @param mixed $address - an object in the format described in the documenation that describes this address 
     * @param int carrier_id - the carrier id according to the API NOT the platform 
     * 
     */ 
    public function get_pickup_locations($address, $carrier_id){
        if(!$carrier_id){
            return array(
                'Error' => array(
                    'Id' => 1111, 
                    'Info' => "Invalid carrier of id $carrier_id"
                )
            );
        }
 
    	if(is_numeric($address['Streetname2'])){
    	  $address['HouseNumber'] = $address['Streetname2'];
    	  $address['Streetname2'] =''; 
    	}

        $dataToSend = [
            "City" => $address['City'],
            "Country" =>$address['Country'],
            "PostalCode" => $address['PostalCode'],
            "Streetname1" => $address['Streetname1'],
            "Lat" => $address['Lat'],
            "Long" => $address['Long']
        ];

        $data = array(
            'Address' =>  $dataToSend,
            'CarrierId' => $carrier_id
        );

        $this->log("get_pickup_locations Address " . var_export($address, true ) . ";  
            Carrier :" . $carrier_id ); 
        $curl =  $this->send_to_api('POST','/pickuppoints', $data); 
        return $curl->response;
    }

    /** 
     * POST Shipments 
     *
     * @param mixed $shiptments - an array of shipments to send to the API 
     * @param string $accept_lang - ex en_US is set messages will be localized, defaults to english  
     * 
     */
    public function post_shipments($shipments, $accept_lang = '') {
        return $this->send_shipments('POST', $shipments, $accept_lang); 
    }

    /** 
     * PATCH Shipments  
     *
     * @param mixed $shiptments - an array of shipments to send to the API 
     * @param string $accept_lang - ex en_US is set messages will be localized, defaults to english  
     * 
     */
    public function patch_shipments($shipments, $accept_lang = '') {
        return $this->send_shipments('PATCH', $shipments, $accept_lang); 
    }

    /** 
     * Request the labels  
     * @param array $clientreferences -  an array with the clientreferences to include in the label pdf
     * @param labelstart - where to start 1 is top left 
     * @param labeltype - 0 is whatever is in the client settings 
     **/
    public function post_labels_step1($clientreferences, $labelstart = 1, $labeltype = 0) {
        $data = array(
            'ClientReferenceCodeList' => $clientreferences,
            'LabelStart' => $labelstart, 
            'LabelType' => $labeltype
        ); 

        return $this->send_to_api('POST','/labels', $data); 
    }

    /** 
     *  Monitor the label request, is it finished? 
     */ 
    public function monitor_label_status($callbackurl) {
        return $this->send_to_api('GET', $callbackurl); 
    }

    /** 
     * Send Shipments to the api 
     *
     * @param mixed $shiptments - an array of shipments to send to the API 
     * @param string $accept_lang - ex en_US is set messages will be localized, defaults to english  
     * 
     */
    public function send_shipments($method, $shipments, $accept_lang) {
        $headers = array(
            'accept-language'=> $accept_lang ? $accept_lang : 'en_US', 
        ); 

        $this->log("post_shipments ".var_export( $shipments , true )); 

        $data = (object) array(
            'Shipment' => $shipments
        );
        return $this->send_to_api($method, '/shipments',$data, $headers);
    }

    /** 
     * Grant we are sending utf8 strings to the api 
     * @param String $str - the string to encode 
     * @return String an utf8 encoded string 
     */ 
    public function get_utf8($str){
        if (!function_exists('mb_detect_encoding')) {
            return $str; 
        }

        $enc =  mb_detect_encoding($str, "UTF-8,ASCII,JIS,ISO-8859-1,ISO-8859-2,ISO-8859-3,ISO-8859-4,ISO-8859-5,ISO-8859-6,ISO-8859-7,ISO-8859-8,ISO-8859-9,ISO-8859-10,ISO-8859-13,ISO-8859-14,ISO-8859-15,ISO-8859-16,EUC-JP,SJIS,Windows-1251,Windows-1252");
 
        if ($enc && $enc != 'UTF-8') {
            return iconv($enc, 'UTF-8', $str);
        } else {
            return $str;
        }
    }

    /**
     * Send the data to the api
     * HTTP 401 == ( bad hash, bad keys, bad token )
     *
     * @param String method - the http method to use for this request
     * @param String $endpoint - ex /keys
     * @param mixed $data - the data to be sent to the server
     * @param string $content_type
     * @param Array $headers - associative array ( header_name => value ) of headers to append
     * @param int $iteration - used in recursion
     * @return mixed - object {response, error}
     * @override
     */
    protected function send_to_api($method = 'GET', $endpoint = '/', $data = '', $headers = array())
    {
        $result = new \stdClass();

        $api_url = ( $this->is_dev ? $this->api_url_dev : $this->api_url );
        $endpoint = preg_replace("~$api_url~", '', $endpoint);  
        $url = $api_url . $endpoint;
        

        $json_data = $data ? $this->get_utf8(json_encode($data)) : '';
        


        $username = $this->is_token_valid() ? $this->token : $this->public_key;
        $password = $this->get_request_signature($username.$json_data);

        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = 'X-APPID: '.$this->app_id;
        
        if( $this->is_dev ){
            error_log("\n\n\n\n" . $this->print_request_data($method, $endpoint, $data, $headers,$username,$password)); 
        }

        $ch = curl_init($url);

        //Disable CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER by
        //setting them to false.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        $options = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $username . ":" . $password,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
        );
        
        curl_setopt_array($ch, $options);
 

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }

        if ($method == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }

        $response = curl_exec($ch);
        $result->response = json_decode($response);
        $result->error = curl_error($ch);
        $result->httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
        if( $this->is_dev ){
            error_log("\nRESPONSE ". json_encode($response));
        }  

        curl_close($ch);
 
        return $result;
    }

    /** 
     * Remove padding from the hashed string 
     * @param string $encoded_string
     */ 
    public function remove_base64_padding($encoded_string){
        for($i = strlen($encoded_string) -1; substr($encoded_string,$i,1) == '='; --$i );  
        return substr($encoded_string, 0, $i+1);
    }

    /** 
     * Validate a request to update 
     *  
     * @return true if the incoming request is correctly signed 
     */ 
    public function validate_update_request($status,$tracking_id,$url,$hash){
        $string = "{$status},{$tracking_id},{$url}"; 
        $confirm_hash = $this->get_request_signature($string);
        $confirm_hash_without_padding = $this->remove_base64_padding($confirm_hash);

        $this->log("Confirm Hash ".$confirm_hash_without_padding.' against received hash '.$hash); 

        return $hash == $confirm_hash_without_padding; 
    }


    private function log($msg){
        if(!$this->is_dev){
            return; 
        }
        error_log(date("Y-m-d H:i:s").'\t'.$msg); 
    }
}

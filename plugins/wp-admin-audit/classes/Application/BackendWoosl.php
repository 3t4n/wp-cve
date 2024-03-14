<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_BackendWoosl
{
    protected $apiEndpoint;
    public $lastCode = null;
    public $lastError = null;
    public $lastMessage = null;
    protected static $productUniqueId = 'WADA-FREE';

    public function __construct(){
        $apiHost = 'https://wpadminaudit.com';
        if('false' === 'true') {
            $apiHost = 'http://wpadminaudit.local';
        }
        $this->apiEndpoint = $apiHost.'/index.php';
    }

    protected static function getDomain(){
        return str_replace(array ("https://" , "http://"), "", trim(network_site_url(), '/'));
    }

    public function activateKey($key){
        $success = false;
        /*  */
        return $success;
    }


    public function deactivateKey($key){
        $success = false;
        /*  */
        return $success;
    }

    public function checkStatusOfKeyOnFile($saveToLicenseStatus = true){
        $res = new stdClass();
        /*  */
        return $res;
    }

    protected function getCallParameters($action, $key=null, $inclVersion = false){
        if(is_null($key)){
            $key = WADA_Settings::getLicenseKey();
        }
        $args = array(
            'woo_sl_action'         => $action,
            'licence_key'           => $key,
            'product_unique_id'     => self::$productUniqueId,
            'domain'                => self::getDomain()
        );
        if($inclVersion){
            $args['version'] = '1.2.9';
        }
        return $args;
    }

    protected function returnWithError($data){
        if(is_wp_error($data)){
            $this->lastError = $data->get_error_message();
            return true;
        }
        if(isset($data['response']['code']) && $data['response']['code'] != 200){
            $this->lastError = $data['response']['code'].' '.$data['response']['message'];
            return true;
        }
        return false; // no error
    }

    protected function getApiResponse($body){
        $responseBlock = json_decode($body);
        WADA_Log::debug('getApiResponse responseBlock: '.print_r($responseBlock, true));
        if(is_array($responseBlock)){
            $responseObj = $responseBlock[count($responseBlock) - 1]; // retrieve last in block
            return $this->handleResponseObject($responseObj);
        }else{
            return $this->handleResponseObject($responseBlock); // last ditch effort
        }
    }

    protected function handleResponseObject($responseObj){
        if(is_object($responseObj)){
            if(property_exists($responseObj, 'message')){
                $this->lastMessage = $responseObj->message;
                if($this->lastMessage === 'Licence Key Is Active and Valid for Domain'){
                    $this->lastMessage = __('License key is active and valid for your domain', 'wp-admin-audit'); // translate the (known) text
                }
            }
            if(!property_exists($responseObj, 'status_code')){
                return array(null, null); // if there is no application level status code, this is no good
            }
            $this->lastCode = $responseObj->status_code;
            WADA_Log::debug('handleResponseObject responseObj: '.print_r($responseObj, true));
            return array($responseObj->status_code, $responseObj);
        }
        return array(null, null);
    }

}
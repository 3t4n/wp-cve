<?php

/**
 * @author Christoffer
 * @copyright Copyright (C) 2015, IEX (https://iex.dk)
 * @package IEXApi
 */
define('IEX_API_URL', 'https://api.iex.dk/');

define('IEX_PRODUCTS', 'product');
define('IEX_ORDERS', 'order');
define('IEX_CUSTOMERS', 'customer');
define('IEX_CATEGORIES', 'category');

class IEX_APIClient{
    /********************************
    *           Contruct            *
    ********************************/
    public function __construct($api_key, $api_key_2nd) {
		$auth = array();
		
		if ($api_key) {
			$auth[] = array('api_key' => $api_key);
		}
		if ($api_key_2nd) {
			$auth[] = array('api_key' => $api_key_2nd);
		}

        $this->auth = $auth;

        // Make sure that Customer info is ready
        $this->customer_info = get_option('iex_customer_information');

        // Make sure that ERP system is ready
        $this->system = get_option('iex_customer_system');
    }

    /********************************
    *           Open Curl           *
    ********************************/
    private function open() {
        $cl = curl_init();

        curl_setopt($cl, CURLOPT_HEADER, 'Content-Type: application/json');
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($cl, CURLOPT_POST, TRUE);

        $this->cl = $cl;
    }

    /********************************
    *  Check key, get customer info *
    *        and find system        *
    ********************************/
    public function get_info(){
        $this->open();
	
		// Currently only wokring on main api key
        curl_setopt($this->cl, CURLOPT_URL, IEX_API_URL.$this->auth[0]['api_key']);

        curl_setopt($this->cl, CURLOPT_POSTFIELDS, array());
        $result = curl_exec($this->cl);
        $returned = json_decode($result);

        $return = new stdClass();

        //Return everything
        $return->returned = $returned;

        //Return customer info
        if(isset($returned->customer)){
            $return->customer = $returned->customer;
        }

        //Find the system
        if(isset($returned->systems) && $returned->systems){
            foreach ($returned->systems as $key => $value) {
                if($key !== 'woocommerce'){
                    $system['erp'] = $key;
                    $system['url'] = $value;
                    break;
                }
            }
        }
        //Return system
        if(isset($system)){
            $return->system = $system;
        }

        //Close the curl connection
        $this->close();

        return $return;
    }

    /********************************
    *  Hold the things to transfer  *
    ********************************/
    public function addTransfer($type, $data){
        $transfer['type'] = $type;
        $transfer['data'] = $data;

        $this->transfers[] = $transfer;
    }

    /********************************
    *   Let's transfer the stuff    *
    ********************************/
    public function doTransfer($return_result = false){
        //Only transfer if customer status is active or onhold
        if($this->customer_info->status != 'active' && $this->customer_info->status != 'onhold'){
            return $responses['error'][]['status'] = $this->customer_info->status;
        }

        $this->open();

        $found_system = $this->system;

        // Found a system?
        if(!is_array($found_system)){
            $responses['error'][] = __('No system found for this key!','iex_integration');
            $this->close();
            return $responses;
        }

		foreach ($this->auth as $key => $this_auth) {
        	foreach ($this->transfers as $transfer) {
            	$call_url = IEX_API_URL.$this_auth['api_key'].'/woocommerce/'.$transfer['type'];

            	curl_setopt($this->cl, CURLOPT_URL, $call_url);

            	curl_setopt($this->cl, CURLOPT_POSTFIELDS, json_encode($transfer['data']) );
            	$result = curl_exec($this->cl);
            	$responses[$call_url][] = $return_result ? $result : curl_getinfo($this->ch);
        	}
		}

        //Close the curl connection
        $this->close();

        return $responses;
    }

    /********************************
    *           Close Curl          *
    ********************************/
    public function close() {
        curl_close($this->cl);
    }
}

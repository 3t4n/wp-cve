<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");

class CallURL{
	
	private $callOptions;
	private $url;
	private $query;
	private $method;
	private $timeout;
	private $lastResult;
	private $jsonPost;
	private $headers;
	
	function __construct(){
		$this->setDefaults();
	}
	
	private function setDefaults(){
		$this->callOptions = array();
		$this->timeout = 30;
		$this->jsonPost = false;		
	}
	
	public function setMethod($method){
		if (strtoupper($method) === 'POST'){
			$this->method = 'POST';
		}elseif (strtoupper($method) === 'JSON_POST'){
			$this->method = 'POST';
			$this->jsonPost = true;
		}else {
			$this->method = 'GET';
		}
	}
	
	public function setURL($url){
			$this->url = $url;
	}

	public function setQuery($query){
			$this->query = $query;
	}
		
	public function setTimeout($timeout) {
		if (is_int($timeout)){
			$this->timeout = $timeout;
		}
	}
	
	public function getLastResult() {
		return $this->lastResult;
	}
		
    public function doCall(){
    	
    	## Preparing callOptions Array
    	$this->callOptions[CURLOPT_TIMEOUT] = $this->timeout;
    	$this->callOptions[CURLOPT_RETURNTRANSFER] = true;
        $this->callOptions[CURLOPT_SSL_VERIFYPEER] = false;
        
        if ($this->method === "POST"){
        	$this->callOptions[CURLOPT_URL] = $this->url;
        	$this->callOptions[CURLOPT_POST] = true;			
			if ($this->jsonPost === true){
				$this->headers[] = "Content-Type:  application/json; charset=UTF-8";
				$this->callOptions[CURLOPT_POSTFIELDS] = json_encode($this->query, true);
			}else{
				 $this->callOptions[CURLOPT_POSTFIELDS] = http_build_query($this->query, '', '&');
			}
        }else{
			$query = "";
			if (is_array($this->query) && !empty($this->query)) {
				$query = "?".http_build_query($this->query, '', '&');
			}
        	$this->callOptions[CURLOPT_URL] = $this->url.$query;
        }
		
		if(!empty($this->headers)){
			$this->callOptions[CURLOPT_HTTPHEADER] = $this->headers;
		}	

		## Check for cURL
		if (!extension_loaded('curl')) {
			ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('cURL is not installed/enabled on your server. Please contact your server administrator or hosting provider for assistance with installing/enabling cURL extension for PHP.', 'lara-google-analytics'));
		}		
        
        ## Do Call
        $handle = curl_init();
        curl_setopt_array($handle, $this->callOptions);
        $returnedResp = curl_exec($handle);
        $serverRespCodes = curl_getinfo($handle);
        $returnedErrors = curl_error($handle);
        
		
		## Check for fatal errors
		if(curl_errno($handle)){
			ErrorHandler::FatalError("curl_error",curl_error($handle), curl_errno($handle),$this->callOptions);
		}
	    curl_close($handle);
		
        $this->lastResult = array(
		        'curlRequest'       => $this->callOptions,
        		'HTTP_Status_Code'  => $serverRespCodes['http_code'],
        		'Response'          => $returnedResp,
        		'Error_Description' => $returnedErrors 
        		);        
		return $this->lastResult;
    }

    public function doQuickCall($url, $query=array(), $method="GET", $headers = array()){
		$this->setDefaults();
    	$this->setMethod($method);
    	$this->setURL($url);
    	$this->setQuery($query);
		$this->headers = $headers;
    	return $this->doCall();
    }
    
    public function doGET($url, $query=array()) {
    	return $this->doQuickCall($url, $query, 'GET');
    }

    public function doPOST($url, $query=array()) {
    	return $this->doQuickCall($url, $query, 'POST');
    }
    
}
?>
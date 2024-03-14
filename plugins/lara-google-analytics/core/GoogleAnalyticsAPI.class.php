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

class GoogleAnalyticsAPI{
	
 	const TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
	const AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
	const REDIRECT_URI = lrgawidget_plugin_redirect_uri;
	const SCOPE_URL = 'https://www.googleapis.com/auth/analytics.readonly';
    const ACCOUNTS_SUMMARY_URL = "https://analyticsadmin.googleapis.com/v1beta/accountSummaries";
	const PROPERTIES_SUMMARY_URL = "https://analyticsadmin.googleapis.com/v1beta/properties";
	const API_URL = "https://analyticsdata.googleapis.com/v1beta/properties";

	
    private $client_id;
	private $client_secret;
	private $access_token;
	private $refresh_token;
	private $code;
	private $property_id;
	private $start_date;
	private $end_date;
	private $dateRange = array();
	private $redirect_uri;
	private $httpRequest;
	private $queryParams;
	private $errorMessages = array();

	public function __construct(){
		$this->httpRequest = new CallURL();
		$this->redirect_uri = self::REDIRECT_URI;
		$this->start_date = date('Y-m-d', strtotime('-1 month'));
		$this->end_date = date('Y-m-d');
		$this->errorMessages['missingWebmasterSiteUrl'] = __('Missing Search Console Property URL - Please use the <b>Settings</b> tab to choose a valid <b>Search Console Property URL</b>.', 'lara-google-analytics');
	}

	public function authURL($params=array()) {
		$defaults = array( 'response_type' => 'code',
						   'client_id' => $this->client_id,
						   'redirect_uri' => $this->redirect_uri,
						   'scope' => self::SCOPE_URL,
						   'access_type' => 'offline',
						   'approval_prompt' => 'force');
						   
		$params = array_merge($defaults, $params);
		return self::AUTH_URL . '?' . http_build_query($params);
	}

    public function getAccounts() {
        return $this->httpRequest(self::ACCOUNTS_SUMMARY_URL, array('access_token' => $this->access_token, 'pageSize' => 200), "GET");
    }	
	
	public function getProperty($property_id) {
		return $this->httpRequest(self::PROPERTIES_SUMMARY_URL . "/" . $property_id , array('access_token' => $this->access_token), "GET");
	}
	
	public function getDataStreams($property_id) {
		return $this->httpRequest(self::PROPERTIES_SUMMARY_URL . "/" . $property_id . "/dataStreams" , array('access_token' => $this->access_token), "GET");
	}	
	
	public function buildQuery($params=array()){
		$this->dateRange = array("dateRanges" => array('startDate' => $this->start_date, 'endDate' => $this->end_date));
		$this->queryParams = array_merge($this->dateRange, $params);
	}

	public function doQuery(){
		return $this->httpRequest(self::API_URL . "/" . $this->property_id . ":runReport" , $this->queryParams, "JSON_POST", array("Authorization: Bearer " . $this->access_token)); 
	}
	
	public function getAccessToken() {
		$params = array( 'code'          => $this->code,
		                 'client_id'     => $this->client_id,
						 'client_secret' => $this->client_secret,
						 'redirect_uri'  => $this->redirect_uri,
						 'grant_type'    => 'authorization_code');
		
        $results = $this->httpRequest(self::TOKEN_URL, $params, "POST"); 
		if ( !empty($results['access_token']) && !empty($results['token_type']) && !empty($results['expires_in']) && !empty($results['refresh_token']) ){
			return $results;
		}else{ ErrorHandler::FatalError(__('Invalid Reply', 'lara-google-analytics'),__('Google Replied with unexpected replay, enable debugging to check the reply', 'lara-google-analytics'),100,$results); }
	}	
	
	public function refreshAccessToken() {
		$params = array( 'client_id'     => $this->client_id,
		                 'client_secret' => $this->client_secret,
						 'refresh_token' => $this->refresh_token,
						 'grant_type'    => 'refresh_token');
						 
        $results = $this->httpRequest(self::TOKEN_URL, $params, "POST"); 
		if ( !empty($results['access_token']) && !empty($results['token_type']) && !empty($results['expires_in']) ){
			return $results;
		}else{ ErrorHandler::FatalError(__('Invalid Reply', 'lara-google-analytics'),__('Google Replied with unexpected replay, enable debugging to check the reply', 'lara-google-analytics'),101,$results); }
	}
	
	private function httpRequest ($url, $query, $method, $headers = array()){
		//if (defined("_CURRENT_QUOTAUSER_")){ $query["quotaUser"] = _CURRENT_QUOTAUSER_;}
		$doCall = $this->httpRequest->doQuickCall($url, $query, $method, $headers);
		$response = json_decode($doCall['Response'], true);
		if ($doCall['HTTP_Status_Code'] === 200){
			return $response;
		}else{
			$debugReply = json_decode($doCall['Response'], true);
			if (is_array($response['error']) && !empty($response['error']['message'])){ $response['error'] = $response['error']['message'];}
			if(isset($query["client_secret"])){unset($query["client_secret"]);}
			ErrorHandler::FatalError($response['error'],@$response['error_description'], $doCall['HTTP_Status_Code'], array("url" => $url, "request" => $query, "reply" => $debugReply)); 
		}
	}
	
	public function getQueryParams(){
		return $this->queryParams;
	}	
	
	public function __set($property, $value) {
		switch($property){
			case 'client_id';
			case 'client_secret';
			case 'access_token';
			case 'refresh_token';
			case 'code';
			case 'property_id';
			case 'start_date';
			case 'end_date';
			     $this->$property = $value;
				 break;
			default;
			     ErrorHandler::FatalError(__('Invalid Property', 'lara-google-analytics')." : ".$property);
				 break;
		}		
	}
}

?>
<?php

/**
 * The active trail API connector class.
 *
 * @link	   http://activetrail.com
 * @since	  1.0.0
 *
 * @package	Activetrail_Cf7
 * @subpackage Activetrail_Cf7/admin
 */

/**
 * The active trail API connector class.
 *
 * Defines the plugin name, version, 
 *
 * @package	Activetrail_Cf7
 * @subpackage Activetrail_Cf7/admin
 * @author	 ActiveTrail <contact@activetrail.com>
 */
class Activetrail_Api {

	private $credentials = null;
	
	public function __construct($credentials = null) {
		if (!$credentials) {
			wp_die('Error Initializing ActiveTrail API Credentials');
		}
		
		$this->credentials = $credentials;
	}
	
	public function get_contact_fields() {
		$url = ACTIVETRAIL_API_URL . AT_ENDPOINT_GET_CONTACT_FIELDS;
		
		$args = array(
			'headers' => array(
				'Authorization' => 'Basic '. $this->credentials['app_token_id'],
				'Content-Type' => 'application/json'                                                                                
			)
		);
		
		$response = wp_remote_get($url, $args);
		$body = wp_remote_retrieve_body($response);
		
		return $body;
	}
	
	public function get_contact($data = array()) {
		$url = ACTIVETRAIL_API_URL . AT_ENDPOINT_CONTACTS;
		
		$url = sprintf("%s?%s", $url, http_build_query($data));
		
		$args = array(
			'headers' => array(
				'Authorization' => 'Basic '. $this->credentials['app_token_id'],
				'Content-Type' => 'application/json'                                                                                
			)
		);
		
		$response = wp_remote_get( $url, $args );
		$body = wp_remote_retrieve_body( $response );
		
		return $body;
	}
	
	
	public function import_contacts($data) {
		$url = ACTIVETRAIL_API_URL . AT_ENDPOINT_CONTACTS_IMPORT;
		
		$data_string = '';
		if ($data) {
			$data_string = json_encode($data);
		}
		
		$args = array(
			'headers' => array(
				'Authorization' => 'Basic '. $this->credentials['app_token_id'],
				'Content-Type' => 'application/json; charset=utf-8',
				'Content-Length' => strlen($data_string)
			),
			'method' => 'POST',
			'body' => $data_string,
			'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'cookies' => array()
		);
				
		$response = wp_remote_post($url, $args);
		$body = wp_remote_retrieve_body($response);
		
		return $body;
	}
	
}
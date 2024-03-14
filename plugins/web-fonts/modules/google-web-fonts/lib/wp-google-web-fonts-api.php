<?php

if(!class_exists('WP_Google_Web_Fonts_API')) {
	class WP_Google_Web_Fonts_API {
		const API_BASE_URL = 'https://www.googleapis.com/webfonts/v1/webfonts';
		
		private $_api_key = null;
	
		public function __construct($api_key) {
			$this->_api_key = $api_key;
		}
		
		public function get_fonts($sort) {
			$request_url = add_query_arg(array('key' => $this->_api_key, 'sort' => $sort), self::API_BASE_URL);
			$response = wp_remote_get($request_url, array('sslverify' => false));
			
			if(is_wp_error($response)) {
				$result = $response;
			} else {
				$body = wp_remote_retrieve_body($response);
				
				$object = json_decode($body);
				
				if(is_object($object)) {
					if(isset($object->error)) {
						$error = array_shift($object->error->errors);
						$result = new WP_Error($error->reason, $error->message);
					} else if(is_array($object->items)) {
						$result = $object->items;
					} else {
						$result = new WP_Error('unexpected-response', __('The Google Web Fonts API returned an unexpected response.'));
					}
				} else {
					$result = new WP_Error('could-not-decode', __('The system was unable to decode the response fromt he Google Web Fonts API.'));
				}
			}
			
			return $result;
		}
	}
}

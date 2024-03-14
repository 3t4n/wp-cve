<?php

if(!class_exists('WP_Web_Fonts_Service')) {
	class WP_Web_Fonts_Service {
		
		const API_HOST = 'api.fonts.com';
		const API_BASE_URI = 'rest';
		const API_FORMAT = 'json';
		
		const ACCOUNTS_URI = 'Accounts';
		const GET_TOKEN_URI = 'GetToken';
		
		const DOMAINS_URI = 'Domains';
		
		const FILTER_VALUES_URI = 'FilterValues';
		const FILTER_VALUES_ALL_URI = 'AllFilterValues';
		
		const FONTS_URI = 'Fonts';
		const FONTS_ALL_URI = 'AllFonts';
		
		const PROJECTS_URI = 'Projects';
		
		const PUBLISH_URI = 'Publish';
		
		const PROJECT_STYLES_URI = 'ProjectStyles';
		const PROJECT_STYLES_EXPORT_URI = 'ProjectStylesExport';
		const PROJECT_STYLES_Import_URI = 'ProjectStylesImport';
		
		const SELECTORS_URI = 'Selectors';
		
		private static $paths_exempt_from_authorization = array('Accounts', 'GetToken');
		
		private static $paths_supporting_pagination = array(
			'Domains' => array('POST', 'DELETE', 'PUT', 'GET'),
			'Fonts' => array('POST', 'DELETE', 'GET'),
			'AllFonts' => array('GET'),
			'Projects' => array('POST', 'DELETE', 'PUT', 'GET'),
			'Selectors' => array('POST', 'DELETE', 'PUT', 'GET'),
			'ProjectStyles' => array('POST'),
			'ProjectStylesExport' => array('GET'),
			'ProjectStylesImport' => array('GET'),
		);
		
		private static $paths_supporting_publication = array(
			'Domains' => array('POST',  'DELETE', 'PUT'),
			'Fonts' => array('POST', 'DELETE'),
			'Selectors' => array('POST', 'DELETE', 'PUT'),
		);
		
		private static $request_path_to_property_map = array(
			'Accounts' => 'Accounts',
			'GetToken' => 'Accounts',
			'Domains' => 'Domains',
			'Filters' => 'Filters',
			'FilterValues' => 'FilterValues',
			'AllFilterValues' => 'FilterValues',
			'Fonts' => 'Fonts',
			'AllFonts' => 'AllFonts',
			'Projects' => 'Projects',
			'Publish' => 'Publish',
			'Selectors' => 'Selectors',
			'ProjectStyles' => 'ProjectStyles',
			'ProjectStylesExport' => 'ProjectStyles',
			'ProjectStylesImport' => 'ProjectStyles',
		);
		
		private static $request_path_to_collection_property_map = array(
			'Domains' => 'Domain',
			'Projects' => 'Project',
			'Fonts' => 'Font',
			'AllFonts' => 'Font',
			'Selectors' => 'Selector',
		);
			
		/**
		 * For paginating API calls, the value of this parameter determines how many records
		 * to return per request.
		 */
		protected $record_limit = 50;
		
		/**
		 * For paginating API calls, the value of this parameter determines which zero-indexed
		 * record to start at when returning a response.
		 */
		protected $record_start = 0;
		
		/**
		 * For API calls which can be told to not publish immediately, the value of this parameter
		 * indicates whether an immediate publish should happen at the end of the API call.
		 */
		protected $immediate_publish = false;
		
		/**
		 * If you wish to record all requests and response objects for later debugging, simply 
		 * enable this and everything will be logged appropriately for inspection.
		 */
		protected $is_logging_enabled = false;
		
		/**
		 * For logging all requests, we use an index that is increment when calling the start_request_log() 
		 * method.
		 */
		protected $current_log_index = 0;
		
		/**
		 * All log data is stored in this array.
		 */
		protected $log_data = array();
		
		/**
		 * If this is true, then responses will be returned directly from the API with no additional parsing. If it is 
		 * false, as it is by default, then the responses will be parsed before returning the results.
		 */
		protected $raw_response = false;
		
		/**
		 * Each application that accesses the Web Fonts service requires an API key. This paramter must
		 * be filled before making any requests. The value should be set by constructing the WP_Web_Font_Services
		 * object with the value of the appropriate API key.
		 */
		protected $api_key = null;
		
		/**
		 * For all requests except those related to user accounts, the $public_key and $private_key values must be
		 * valid user identifiers.
		 */
		protected $public_key = null;
		protected $private_key = null;
		
		/**
		 * This method is a temporary fix for the bug referenced at http://core.trac.wordpress.org/ticket/18589
		 * 
		 * It will be fixed in the next version of WordPress but doesn't currently work so we need this filter.
		 */
		public static function set_delete_custom_method($curl_handle) {
			curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		
		/**
		 * Create a new API services object by passing a valid API key that will be used in all requests.
		 * 
		 * @param string $api_key A valid API key as requested from http://webfonts.fonts.com/en-US/Account/CreateAnApp
		 */
		public function __construct($api_key) {
			$this->api_key = $api_key;
		}
		
		/**
		 * Set the user's public and private key for access to a user's account.
		 * 
		 * @param string $public_key The public key for a user's account.
		 * @param string $private_key The private key for a user's account.
		 */
		public function set_credentials($public_key, $private_key) {
			$this->public_key = $public_key;
			$this->private_key = $private_key;
		}
		
		/**
		 * Indicate whether a publish should happen immediately upon success of an API call. Only valid
		 * for some API calls. If false (as it is by default) you need to call the publish method
		 * after all your other actions are taken.
		 * 
		 * @param bool $should_publish_immediately If true, this services object will cause all
		 * actions to publish changes immediately upon completion. Otherwise, the publish method
		 * must be called explicitly.
		 */
		public function set_immediate_publish($should_publish_immediately) {
			$this->immediate_publish = $should_publish_immediately;
		}
		
		/**
		 * Indicate whether logging should be enabled for all requests. If 
		 * 
		 * @param bool $should_enable_logging If true, logging will be enabled for all requests
		 * that happen in this use of this plugin.
		 */
		public function set_logging_enabled($should_enable_logging) {
			$this->is_logging_enabled = (bool)$should_enable_logging;
		}
		
		/**
		 * Indicate how many and which page of results to return
		 * 
		 * @param int The page of results to return.
		 * @param int The number of results per page.
		 */
		public function set_pagination_parameters($page, $per_page) {
			$this->record_limit = $per_page;
			$this->record_start = $page;
		}
		
		/**
		 * Indicate whether this services object should return raw JSON responses from the API
		 * or if responses should be parsed before being returned.
		 * 
		 * @param bool $should_return_raw_responses If true, this services object will return raw 
		 * JSON responses as they are returned from the API with no additional parsing.
		 */
		public function set_raw_responses($should_return_raw_responses) {
			$this->raw_responses = (bool)$should_return_raw_responses;
		}
		
		/// === LOGGING ===
		
		public function dump_log() {
			if($this->is_logging_enabled) {
				error_log(print_r($this->log_data,true));
				
				$this->current_log_index = 0;
				$this->log_data = array();
			}
		}
		
		private function log_request_component($request_component, $request_component_data) {
			if($this->is_logging_enabled) {
				$this->log_data[$this->current_log_index][$request_component] = $request_component_data;			
			}
		}
		
		private function start_logging_request() {
			if($this->is_logging_enabled) {
				$this->current_log_index++;
				$this->log_data[$this->current_log_index] = array();			
			}
		}
		
		//// API REQUESTS
		
		/// === UTILITY ===
		
		private function construct_headers($request_path, $request_uri, $extra_headers) {
			if(!is_array($extra_headers)) {
				$extra_headers = array();
			}
			
			$headers = array('AppKey' => $this->api_key);
			if(!$this->exempt_from_authorization($request_path)) {
				$headers['Authorization'] = urlencode($this->public_key . ':' . $this->sign_request(substr($request_uri, strpos($request_uri, self::API_BASE_URI) - 1)));
			}
			
			return array_merge($extra_headers, $headers);;
		}
		
		private function construct_request_uri($request_path, $query_data, $use_ssl) {
			$uri = ((bool)$use_ssl ? 'https' : 'http') . '://' . self::API_HOST . '/' . self::API_BASE_URI . '/' . self::API_FORMAT . '/' . $request_path . '/';
			$uri = add_query_arg($query_data, $uri);
			
			return $uri;
		}
		
		private function ensure_correct_response_structure($request_path, $response_object) {
			$collection_property = $this->get_collection_property_for_request_path($request_path);
			
			if(!empty($collection_property) && !is_array($response_object->{$collection_property})) {
				$response_object->{$collection_property} = array($response_object->{$collection_property});
			} 
			
			return $response_object;
		}
		
		private function exempt_from_authorization($request_path) {
			return in_array($request_path, self::$paths_exempt_from_authorization);
		}
		
		private function get_collection_property_for_request_path($request_path) {
			return self::$request_path_to_collection_property_map[$request_path];
		}
		
		private function get_property_from_request_path($request_path) {
			return self::$request_path_to_property_map[$request_path];
		}
		
		/**
		 * Constructs and performs the appropriate request to the Fonts.com API service and returns results based
		 * on whether raw results or parsed results should be returned.
		 * 
		 * @param string $request_path
		 * @param string $request_method One of 'GET', 'POST', 'PUT', 'DELETE'
		 * @param array $query_data
		 * @param array $body_data
		 * @param array $extra_headers
		 * @param bool $use_ssl
		 */
		private function make_request($request_path, $request_method, $query_data = array(), $body_data = array(), $extra_headers = array(), $use_ssl = false) {
			$this->start_logging_request();
			
			$request_method = in_array($request_method, array('GET', 'POST', 'PUT', 'DELETE')) ? $request_method : 'GET';
			
			if(!is_array($query_data)) {
				$query_data = array();
			}
			
			if(!is_array($body_data)) {
				$body_data = array();
			}
			
			if($this->supports_pagination($request_path, $request_method)) {
				$query_data['wfsplimit'] = $this->record_limit;
				$query_data['wfspstart'] = $this->record_start;
			}
			
			if($this->supports_publication($request_path, $request_method) && !$this->immediate_publish) {
				$query_data['wfsnopublish'] = 1; 
			}
			
			if('DELETE' == $request_method) {
				add_action('http_api_curl', array(__CLASS__, 'set_delete_custom_method'), 11);
			}
			
			$request_uri = $this->construct_request_uri($request_path, $query_data, $use_ssl);
			$request_headers = $this->construct_headers($request_path, $request_uri, $extra_headers);
			$request_args = array(
				'timeout' => 15,
				'method' => $request_method,
				'user-agent' => __('WordPress Web Fonts'),
				'headers' => $request_headers,
				'body' => ('GET' != $request_method && !empty($body_data)) ? $body_data : '',
				'sslverify' => false,
			);
			
			$this->log_request_component('URI', $request_uri);
			$this->log_request_component('HEADERS', $request_headers);
			$this->log_request_component('ARGS', $request_args);
			$this->log_request_component('QUERY_DATA', $query_data);
			$this->log_request_component('BODY_DATA', $body_data);
			
			$request_result = wp_remote_request($request_uri, $request_args);
			
			if('DELETE' == $request_method) {
				remove_action('http_api_curl', array(__CLASS__, 'set_delete_custom_method'), 11);
			}
			
			$this->log_request_component('RAW_RESPONSE', $request_result);
			
			if(is_wp_error($request_result)) {
				$decoded = new stdClass;
				$decoded->Message = $request_result->get_error_message();
				$request_result_body = json_encode($decoded);
			} else {
				$request_result_body = wp_remote_retrieve_body($request_result);
			}
			
			$this->log_request_component('RESPONSE_BODY', $request_result_body);
				
			$response_object = json_decode($request_result_body);
			if(!isset($response_object->Message)) {
				$property_name = $this->get_property_from_request_path($request_path);
				$response_object = $this->ensure_correct_response_structure($request_path, $response_object->{$property_name});
			}
			
			$this->log_request_component('RESPONSE_OBJECT', $response_object);
			
			
			return $this->raw_response ? $request_result_body : $response_object;
		}
		
		private function sign_request($message){
			return base64_encode(hash_hmac('md5', "{$this->public_key}|{$message}", $this->private_key, true));
		}
		
		private function supports_pagination($request_path, $request_method) {
			return is_array(self::$paths_supporting_pagination[$request_path]) && in_array($request_method, self::$paths_supporting_pagination[$request_path]);
		}
		
		private function supports_publication($request_path, $request_method) {
			return is_array(self::$paths_supporting_publication[$request_path]) && in_array($request_method, self::$paths_supporting_publication[$request_path]);
		}
		
		/// === ACCOUNTS ===
		
		public function create_account($first_name, $last_name, $email) {
			return $this->make_request(self::ACCOUNTS_URI, 'POST', array(), array('wfsfirst_name' => $first_name, 'wfslast_name' => $last_name, 'wfsemail' => $email), array(), true);
		}
		
		public function generate_token($email, $password) {
			return $this->make_request(self::ACCOUNTS_URI, 'GET', array('wfsemail' => $email), array(), array('Password' => $password), true);
		}
		
		public function get_token($email, $password) {
			return $this->make_request(self::GET_TOKEN_URI, 'GET', array('wfsemail' => $email), array(), array('Password' => $password), true);
		}
		
		/// === DOMAINS ===
		
		public function add_domain($project_id, $domain_name) {
			return $this->make_request(self::DOMAINS_URI, 'POST', array('wfspid' => $project_id), array('wfsdomain_name' => $domain_name));
		}
		
		public function delete_domain($project_id, $domain_id) {
			return $this->make_request(self::DOMAINS_URI, 'DELETE', array('wfspid' => $project_id, 'wfsdomain_id' => $domain_id));
		}
		
		public function edit_domain($project_id, $domain_id, $domain_name) {
			return $this->make_request(self::DOMAINS_URI, 'PUT', array('wfspid' => $project_id, 'wfsdomain_id' => $domain_id), array('wfsdomain_name' => $domain_name));
		}
		
		public function list_domains($project_id) {
			return $this->make_request(self::DOMAINS_URI, 'GET', array('wfspid' => urlencode($project_id)));
		}
		
		/// === FILTERS ===
		
		public function list_filters() {
			
		}
		
		/// === FILTER VALUES ===
		
		public function list_filter_values($filter_type, $classification_id = null, $designer_id = null, $foundry_id = null, $language_id = null) {
			
		}
		
		public function list_all_filter_values($free_or_all = null, $classification_id = null, $designer_id = null, $foundry_id = null, $language_id = null, $alphabet_characters = null) {
			$query_data = array(
				'wfsClassId' => $classification_id,
				'wfsDesignerId' => $designer_id,
				'wfsFoundryId' => $foundry_id,
				'wfsLangId' => $language_id,
				'wfsAlphabet' => $alphabet_id,
				'wfsFree' => $free_or_all == 0 ? 0 : -1,
				'wfsKeyword' => $keywords
			);
			
			return $this->make_request(self::FILTER_VALUES_ALL_URI, 'GET', $query_data);
		}
		
		/// === FONTS ===
		
		public function add_font($project_id, $font_id) {
			return $this->make_request(self::FONTS_URI, 'POST', array('wfspid' => $project_id), array('wfsfid' => $font_id));
		}
		
		public function delete_font($project_id, $font_id) {
			return $this->make_request(self::FONTS_URI, 'DELETE', array('wfspid' => $project_id, 'wfsfid' => $font_id));
		}
		
		/**
		 * 
		 * @param string $keywords
		 * @param int $free_or_all
		 * @param string $classification_id
		 * @param string $designer_id
		 * @param string $foundry_id
		 * @param string $alphabet_id
		 */
		public function list_fonts($keywords = null, $free_or_all = null, $classification_id = null, $designer_id = null, $foundry_id = null, $language_id = null, $alphabet_id = null) {
			$query_data = array(
				'wfsClassId' => $classification_id,
				'wfsDesignerId' => $designer_id,
				'wfsFoundryId' => $foundry_id,
				'wfsLangId' => $language_id,
				'wfsAlphabet' => $alphabet_id,
				'wfsFree' => $free_or_all == 0 ? 0 : -1,
				'wfsKeyword' => $keywords
			);
			
			return $this->make_request(self::FONTS_ALL_URI, 'GET', $query_data);
		}
		
		public function list_project_fonts($project_id) {
			return $this->make_request(self::FONTS_URI, 'GET', array('wfspid' => $project_id));
		}
		
		/// === PROJECTS ===
		
		public function add_project($project_name) {
			return $this->make_request(self::PROJECTS_URI, 'POST', array(), array('wfsproject_name' => $project_name));
		}
		
		public function delete_project($project_id) {
			return $this->make_request(self::PROJECTS_URI, 'DELETE', array('wfspid' => $project_id));
		}
		
		public function edit_project($project_id, $project_name) {
			return $this->make_request(self::PROJECTS_URI, 'PUT', array('wfspid' => $project_id), array('wfsproject_name' => $project_name));
		}
		
		public function list_projects() {
			return $this->make_request(self::PROJECTS_URI, 'GET');
		}
		
		/// === PUBLISH ===
		
		public function publish() {
			return $this->make_request(self::PUBLISH_URI, 'GET');
		}
		
		/// === SELECTORS ===
		
		public function add_selector($project_id, $selector_tag) {
			return $this->make_request(self::SELECTORS_URI, 'POST', array('wfspid' => $project_id), array('wfsselector_tag' => $selector_tag));
		}
		
		public function assign_to_selector($project_id, $font_ids, $selector_ids) {
			$font_ids_string = implode(',', $font_ids);
			$selector_ids_string = implode(',', $selector_ids);
			
			return $this->make_request(self::SELECTORS_URI, 'PUT', array('wfspid' => $project_id), array('wfsfont_ids' => $font_ids_string, 'wfsselector_ids' => $selector_ids_string));
		}
		
		public function delete_selector($project_id, $selector_id) {
			return $this->make_request(self::SELECTORS_URI, 'DELETE', array('wfspid' => $project_id, 'wfsselector_id' => $selector_id));
		}
		
		public function list_selectors($project_id) {
			return $this->make_request(self::SELECTORS_URI, 'GET', array('wfspid' => $project_id));
		}
		
		/// === STYLESHEETS ===
		
		public function add_stylesheet($project_id, $project_token, $selector_ids) {
			
		}
		
		public function export_stylesheet($project_id) {
			
		}
		
		public function import_stylesheet($project_id, $project_token) {
			
		}
	}
}
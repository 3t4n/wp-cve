<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access.');

// Purpose: Interface with the HMRC VAT number lookup service

if (!class_exists('WC_VAT_Number_Lookup_Service')) require_once(WC_VAT_COMPLIANCE_DIR.'/number-lookups/lookup-service.php');

// Conditional execution to deal with bugs on some old PHP versions with classes that extend classes not known until execution time
if (1==1):
class WC_VAT_Number_Lookup_Service_hmrc extends WC_VAT_Number_Lookup_Service {

	// Application public client identifier
	private $client_id = 'Gftxu0nm7wHphJH7mA6drTnT66tm';

	private $hmrc_token_option_name = 'wceuvat_uk_vat_compliance_hmrc_token';
	
	private $service_urls = array(
		'test' => array(
			'base_auth_url' => 'https://test-www.tax.service.gov.uk/',
			'base_api_url' => 'https://test-api.service.hmrc.gov.uk/',
		),
		'live' => array(
			'base_auth_url' => 'https://www.tax.service.gov.uk/',
			'base_api_url' => 'https://api.service.hmrc.gov.uk/',
		)
	);
	
	private $auth_url = 'oauth/authorize';

	private $lookup_vat_endpoint = 'organisations/vat/check-vat-number/lookup/';

	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		// No longer needed as HMRC does not require OAuth for the lookup endpoints since 19/03/21
		// add_action('init', array($this, 'handle_url_actions'));
	}

	/**
	 * Return the name of this VAT-number checking service
	 *
	 * @return String
	 */
	public function get_service_name() {
		return 'HMRC';
	}
	
	/**
	 * Get a list of regions that this service can look-up numbers in
	 *
	 * @return Array - A list of region codes as used by the plugin
	 */
	public function get_supported_region_codes() {
		return array('uk');
	}
	
	/**
	 * This function handles processing the URL Actions
	 *
	 * @return void
	 */
	public function handle_url_actions() {
		if (isset($_SERVER['REQUEST_METHOD']) && ('GET' == $_SERVER['REQUEST_METHOD'] || 'POST' == $_SERVER['REQUEST_METHOD']) && isset($_GET['action'])) {
			if ('uk-vat-auth' == $_GET['action']) {
				$this->action_auth();
			}
		}
	}
	
	/**
	 * Output settings HTML for the control centre
	 */
	public function do_settings_output() {

		// HMRC does not require OAuth for the lookup endpoints, since 19/03/21

		_e('HMRC (the official UK service for verifying UK VAT numbers) does not require any authentication (i.e. their API is publicly available).', 'woocommerce-eu-vat-compliance');

	}
	
	/**
	 * Handles various URL actions, as indicated by the uk-vat-auth URL parameter or the contents of the $_REQUEST array
	 *
	 * @return null
	 */
	private function action_auth() {
		
		if (isset($_GET['uk-vat-auth'])) {
			if ('doit' == $_GET['uk-vat-auth']) {
				$this->auth();
				return;
			} elseif ('deauth' == $_GET['uk-vat-auth']) {
				
				$nonce = empty($_GET['uk_vat_deauth_nonce']) ? '' : $_GET['uk_vat_deauth_nonce'];
				if (!wp_verify_nonce($nonce, 'uk_vat_deauth_nonce')) die('Security check');

				delete_option($this->hmrc_token_option_name);
				return;
			}
		} elseif (isset($_REQUEST['state'])) {

			$raw_state = urldecode($_POST['state']);
			if (isset($_POST['code'])) $raw_code = urldecode($_POST['code']);

			// Get the CSRF from setting and check it matches the one returned if it does no CSRF attack has happened
			$csrf = get_option('wc_uk_vat_hmrc_csrf', '');
			$state = stripslashes($raw_state);

			if (strcmp($csrf, $state) == 0) {
				update_option('wc_uk_vat_hmrc_csrf', '');
				if (isset($raw_code)) {
					$token = json_decode(base64_decode($raw_code), true);
					$token['expires_in'] = time() + $token['expires_in'];
					$token['authorised_at'] = time();
					update_option($this->hmrc_token_option_name, $token);
				}
			} else {
				error_log("UK VAT: CSRF comparison failure: $csrf != $state");
			}
		}
	}

	/**
	 * This builds the authorise url
	 *
	 * @return void
	 */
	private function get_authorise_url() {

		$CSRF = $this->generate_random_string(24);
		$callbackhome = admin_url('admin.php?page=wc_eu_vat_compliance_cc&action=uk-vat-auth');
		$callback = defined('WCEUVAT_UK_VAT_AUTH_RETURN_URL') ? WCEUVAT_UK_VAT_AUTH_RETURN_URL : 'https://auth.updraftplus.com/auth/euvat/';
		$client_id = $this->get_client_id();

		update_option('wc_uk_vat_hmrc_csrf', $CSRF);

		// We encode the entire state because when we return to the auth server HMRC has stripped away part of our callback home URL
		$state = base64_encode($CSRF.$callbackhome);
		
		$params = array(
			'client_id' => $client_id,
			'response_type' => 'code',
			'redirect_uri' => $callback,
			'state' => $state,
			'scope' => 'read:vat',
		);
	
		$base_auth_url = $this->service_urls[$this->get_hmrc_mode()]['base_auth_url'];
	
		// Build the URL and redirect the user
		$query = '?' . http_build_query($params, '', '&');
		$url = $base_auth_url . $this->auth_url . $query;
		return $url;
	}

	/**
	 * Returns 'test' or 'live'
	 *
	 * @return String
	 */
	private function get_hmrc_mode() {
		return apply_filters('wc_eu_vat_hmrc_mode', 'live');
	}

	/**
	 * Returns the client id
	 *
	 * @return string
	 */
	private function get_client_id() {
		return apply_filters('wc_eu_vat_client_id', $this->client_id);
	}
	
	/**
	 * This function will make the request to lookup a vat number
	 *
	 * @param String $vat_number		   - the VAT number (already canonicalised), minus any country prefix
	 * @param String $requester_vat_number - 
	 *
	 * @return WP_Error|Array - returns a WP_Error object (which indicates that validation did not happen), or an Array (keys: http_code, response_body)
	 */
	private function lookup_vat_number($vat_number, $requester_vat_number = null) {
		
		$base_api_url = $this->service_urls[$this->get_hmrc_mode()]['base_api_url'];
		
		$url = $base_api_url . $this->lookup_vat_endpoint . $vat_number;
		
		if (null !== $requester_vat_number) $url .= '/' . $requester_vat_number;

		return $this->fetch($url);
	}

	/**
	 * This function performs a Curl call using the passed in parameters
	 *
	 * @param String $url        - the url to call
	 *
	 * @return WP_Error|Array - returns an error object (which indicates that validation did not take place) or the HTTP code and JSON response from the request
	 */
	private function fetch($url) {

		// HMRC does not require OAuth for the lookup endpoints since 19/03/21
		$headers = array(
			'Accept' => 'application/vnd.hmrc.1.0+json',
			// 'Authorization' => 'Bearer '.$access_token,
			// HMRC block the default WordPress user agent
			'User-Agent' => 'WooCommerce VAT Compliance by Simba/'.WooCommerce_EU_VAT_Compliance()->get_version()
		);
		
		$options = array('headers' => $headers, 'timeout' => 15);

		$response = wp_remote_get($url, $options);
		
		if (is_wp_error($response)) return $response;
		
		$http_response_code = wp_remote_retrieve_response_code($response);
		$http_response_body = wp_remote_retrieve_body($response);
		
		return array('http_code' => $http_response_code, 'response_body' => $http_response_body);

	}

	/**
	 * Return whether or not the service is configured
	 *
	 * @return Boolean
	 */
	public function is_configured() {
		$access_token = $this->get_access_token();
		$is_configured = !empty($access_token);
		return apply_filters('wc_vat_compliance_hmrc_lookup_provider_is_configured', $is_configured);
	}
	
	/**
	 * This function will get and return the access token from the database, check if it's near or expired and if so request a new one
	 *
	 * @return String - the access token (will be the empty string if there isn't one)
	 */
	private function get_access_token() {

		$hmrc_token = get_option($this->hmrc_token_option_name, array());

		if (empty($hmrc_token)) return '';

		if (time() + 1800 > $hmrc_token['expires_in']) {
			$result = $this->refresh_access_token($hmrc_token['refresh_token']);
			if (is_wp_error($result)) {
				error_log("get_access_token() error (".$result->get_error_code()."): ".$result->get_error_message());
				return '';
			}
			$hmrc_token = get_option($this->hmrc_token_option_name, array());
			if (empty($hmrc_token)) return '';
		}

		return $hmrc_token['access_token'];
	}

	/**
	 * This function will take the passed in refresh token and request a new access token
	 *
	 * @param string $refresh_token - the refresh token needed to request a new access token
	 *
	 * @return Boolean|WP_Error - returns true on success; otherwise a WP_Error 
	 */
	private function refresh_access_token($refresh_token) {
		
		$callback = defined('WCEUVAT_UK_VAT_AUTH_RETURN_URL') ? WCEUVAT_UK_VAT_AUTH_RETURN_URL : 'https://auth.updraftplus.com/auth/euvat/';

		$args = array(
			'code' => 'wceuvat_uk_code',
			'refresh_token' => $refresh_token,
		);

		$result = wp_remote_post($callback, array(
			'timeout' => 20,
			'headers' => apply_filters('authentication_headers', ''),
			'body' => $args
		));
		
		if (is_wp_error($result)) {
			$body = array('result' => 'error', 'error' => $result->get_error_code(), 'error_description' => $result->get_error_message());
		} else {
			$body_json = wp_remote_retrieve_body($result);
			$body = json_decode($body_json, true);
		}
		
		if (isset($body['error'])) {
			return new WP_Error($body['error'], empty($body['error_description']) ? 'Have not yet obtained an access token from the HMRC API - you need to authorise or re-authorise your connection.' : $body['error_description']);
		} else {
		
			$result_body_json = base64_decode($body[0]);
			$result_body = json_decode($result_body_json);
			
			if (isset($result_body->access_token)) {
			
				$prior_token = get_option($this->hmrc_token_option_name);
			
				$access_token = array(
					'access_token' => $result_body->access_token,
					'refresh_token' => $result_body->refresh_token,
					'expires_in' => time() + $result_body->expires_in,
					'scope' => $result_body->scope,
					'token_type' => $result_body->token_type,
				);
				
				if (isset($prior_token['authorised_at'])) $access_token['authorised_at'] = $prior_token['authorised_at'];

				update_option($this->hmrc_token_option_name, $access_token);

				return true;
			} else {
				return new WP_Error('unexpected_result', 'No access token returned; try again but if the error persists, contact support.');
			}
		}
	}

	/**
	 * This function will generate a random string
	 *
	 * @param integer $length - the size of the random string
	 *
	 * @return string - a random string
	 */
	private function generate_random_string($length = 10) {
		return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
	}
	
	/**
	 * @param String  $vat_prefix	- the country prefix to the VAT number
	 * @param String  $vat_number	- the VAT number (already canonicalised), minus any country prefix
	 * @param Boolean $force_simple	- force a non-extended lookup, even if in the saved options there is a VAT ID for the store
	 *
	 * N.B. The return format has to be kept in sync with that for WC_EU_VAT_Compliance
	 *
	 * @return Array - keys are:
	 * (boolean) 'validated'  - whether a definitive result was obtained
	 * (boolean) 'valid'	  - if 'validated' is true, then this contains the validation result (otherwise, undefined)
	 * (string)	 'error_code' - if 'validated' is false, this contains an error code
	 * (string)	 'error_message' - is set if, and only if, there was an error_code
	 * (mixed)	 'data'		  - data - usually the raw result from the network
	 */
	public function get_validation_result_from_network($vat_prefix, $vat_number, $force_simple = false) {

		// No longer needed as HMRC does not require OAuth for the lookup endpoints since 19/03/21
		// if (!$this->is_configured()) return $this->not_configured_response();
	
		$store_vat_id = WooCommerce_EU_VAT_Compliance()->get_store_vat_number('uk');
	
		// Perform an extended check, unless it was forbidden by the parameter or prevented by lack of configuration
		if (!$force_simple && '' != $store_vat_id) {

			if (preg_match('/^([A-Z][A-Z])?([0-9A-Z]+)/i', str_replace(' ', '', $store_vat_id), $matches)) {

				if (empty($matches[1]) || 'GB' == strtoupper($matches[1]) || 'IM' == strtoupper($matches[1])) {
					$store_vat_id = $matches[2];
				}

			}
		} else {
			$store_vat_id = null;
		}

		$response = $this->lookup_vat_number($vat_number, $store_vat_id);
		
		if (is_wp_error($response)) {
			return array('validated' => false, 'error_code' => $response->get_error_code(), 'error_message' => $response->get_error_message(), 'data' => $response->get_error_data());
		}

		$http_code = $response['http_code'];
		$response_body = $response['response_body'];
		
		$decoded_response = json_decode($response_body, true);

		if ($decoded_response === null && json_last_error() !== JSON_ERROR_NONE) {
			if (is_string($response_body)) {
				if (preg_match("/The requested URL returned error: (\d+) ([A-Za-z ]+)/i", $response_body, $matches)) {
					return array('validated' => false, 'error_code' => $matches[1], 'error_message' => $matches[2], 'data' => $response);
				} else {
					return array('validated' => false, 'error_code' => 'UNKNOWN_ERROR_MESSAGE', 'error_message' => 'Unknown error message', 'data' => $response);
				}
			}
			return array('validated' => false, 'error_code' => 'UNKNOWN_ERROR', 'error_message' => 'Unknown response type', 'data' => $response);
		}
		
		// Validated but VAT number in invalid format: 400: {"code":"INVALID_REQUEST","message":"Invalid targetVrn - Vrn parameters should be 9 or 12 digits"}

		// Not validated (bad auth): 403: { "code": "DENIED", "message": "Request denied", "reference": "0.(snip)" }
		
		// Validated but unrecognised number: 404: {"code":"NOT_FOUND","message":"targetVrn does not match a registered company"}
		
		if ($http_code >= 200 && $http_code < 300) {
			return array('validated' => true, 'valid' => true, 'data' => $decoded_response);
		}
		
		if (isset($decoded_response['code'])) {
			if ('INVALID_REQUEST' == $decoded_response['code'] || 'NOT_FOUND' == $decoded_response['code']) {
				return array('validated' => true, 'valid' => false, 'data' => $decoded_response, 'error_code' => $decoded_response['code'], 'error_message' => $decoded_response['message']);
			} else {
				return array('validated' => false, 'data' => $decoded_response, 'error_code' => $decoded_response['code'], 'error_message' => $decoded_response['message']);
			}
		}
		
		return array('validated' => false, 'error_code' => 'unrecognised_response', 'error_message' => 'The response from the HMRC API was not recognised.', 'data' => array('http_code' => $http_code, 'response_body' => $decoded_response));

	}
	
	/**
	 * This function will get the auth url and forward the user to it for authentication
	 *
	 * @return void
	 */
	private function auth() {
		// Only redirect if not using CLI
		if (PHP_SAPI !== 'cli' && (!defined('DOING_CRON') || !DOING_CRON) && (!defined('DOING_AJAX') || !DOING_AJAX)) {
			$url = $this->get_authorise_url();
			if (!headers_sent()) {
				header('Location: '.$url);
				exit;
			} else {
				throw new Exception(sprintf(__('The %s authentication could not go ahead, because something else on your site is breaking it. Try disabling your other plugins and switching to a default theme. (Specifically, you are looking for the component that sends output (most likely PHP warnings/errors) before the page begins. Turning off any debugging settings may also help).', 'woocommerce-eu-vat-compliance'), 'HMRC UK VAT'));
			}
			return false;
		}
	}

}
endif;

<?php

/**
 * Nouvello WeManage Worker OAuth Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('Nouvello_WeManage_Worker_OAuth')) :

	/**
	 * Nouvello WeManage Worker OAuth Class
	 */
	final class Nouvello_WeManage_Worker_OAuth
	{

		/**
		 * Constructor
		 */
		public function __construct()
		{
		}

		/**
		 * Authenticate API Request.
		 *
		 * @return int|false
		 */
		public function authenticate()
		{
			if (is_ssl()) {
				return $this->perform_basic_authentication();
			} else {
				return $this->perform_oauth_authentication();
			}
		}

		/**
		 * Basic Authentication.
		 *
		 * SSL-encrypted requests are not subject to sniffing or man-in-the-middle
		 * attacks, so the request can be authenticated by simply looking up the user
		 * associated with the given consumer key and confirming the consumer secret
		 * provided is valid.
		 *
		 * @return int|bool
		 */
		private function perform_basic_authentication()
		{
			$this->auth_method = 'basic_auth';
			$consumer_key      = '';
			$consumer_secret   = '';

			// If the $_GET parameters are present, use those first.
			if (!empty($_GET['consumer_key']) && !empty($_GET['consumer_secret'])) {
				$consumer_key    = sanitize_text_field(wp_unslash($_GET['consumer_key']));
				$consumer_secret = sanitize_text_field(wp_unslash($_GET['consumer_secret']));
			}

			// If the above is not present, we will do full basic auth.
			if (!$consumer_key && !empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
				$consumer_key    = sanitize_text_field(wp_unslash($_SERVER['PHP_AUTH_USER']));
				$consumer_secret = sanitize_text_field(wp_unslash($_SERVER['PHP_AUTH_PW']));
			}

			// Stop if don't have any key.
			if (!$consumer_key || !$consumer_secret) {
				return false;
			}

			// Get user data.
			$this->user = $this->get_user_data_by_consumer_key($consumer_key);
			if (empty($this->user)) {
				return false;
			}

			// Validate user secret.
			if (!hash_equals($this->user->consumer_secret, $consumer_secret)) { // @codingStandardsIgnoreLine
				$this->set_error(new WP_Error('woocommerce_rest_authentication_error', __('Consumer secret is invalid.', 'ns-wmw'), array('status' => 401)));

				return false;
			}

			return $this->user->user_id;
		}


		/**
		 * Perform OAuth 1.0a "one-legged" (http://oauthbible.com/#oauth-10a-one-legged) authentication for non-SSL requests.
		 *
		 * This is required so API credentials cannot be sniffed or intercepted when making API requests over plain HTTP.
		 *
		 * This follows the spec for simple OAuth 1.0a authentication (RFC 5849) as closely as possible, with two exceptions:
		 *
		 * 1) There is no token associated with request/responses, only consumer keys/secrets are used.
		 *
		 * 2) The OAuth parameters are included as part of the request query string instead of part of the Authorization header,
		 *    This is because there is no cross-OS function within PHP to get the raw Authorization header.
		 *
		 * @link http://tools.ietf.org/html/rfc5849 for the full spec.
		 *
		 * @return int|bool
		 */
		public function perform_oauth_authentication()
		{
			$this->auth_method = 'oauth1';

			$params = $this->get_oauth_parameters();

			if (empty($params)) {
				return false;
			}

			// Fetch WP user by consumer key.
			$this->user = $this->get_user_data_by_consumer_key($params['oauth_consumer_key']);
			if (empty($this->user)) {
				$this->set_error(new WP_Error('nouvello_rest_authentication_error', __('Consumer key is invalid.', 'ns-wmw'), array('status' => 401)));
				return false;
				return $this->get_error(); // to display actuall error.
			}

			// Perform OAuth validation.
			$signature = $this->check_oauth_signature($this->user, $params);

			if (is_wp_error($signature)) {
				$this->set_error($signature);
				return false;
				return $this->get_error(); // to display actuall error.
			}

			$timestamp_and_nonce = $this->check_oauth_timestamp_and_nonce($this->user, $params['oauth_timestamp'], $params['oauth_nonce']);
			if (is_wp_error($timestamp_and_nonce)) {
				$this->set_error($timestamp_and_nonce);
				return false;
				return $this->get_error(); // to display actuall error.
			}

			// return $this->user->user_id; // changed by zak.
			return true;
		}


		/**
		 * Get oAuth parameters from $_GET, $_POST or request header.
		 *
		 * @since 3.0.0
		 *
		 * @return array|WP_Error
		 */
		public function get_oauth_parameters()
		{

			$get_array = $_GET;
			foreach ($get_array as $get) {
				$get = sanitize_text_field(wp_unslash($get));
			}

			$post_array = $_POST;
			foreach ($post_array as $post) {
				$post = sanitize_text_field(wp_unslash($post));
			}

			$params = array_merge($get_array, $post_array);
			$params = wp_unslash($params);
			$header = $this->get_authorization_header();

			if (!empty($header)) {
				// Trim leading spaces.
				$header        = trim($header);
				$header_params = $this->parse_header($header);

				if (!empty($header_params)) {
					$params = array_merge($params, $header_params);
				}
			}

			$param_names = array(
				'oauth_consumer_key',
				'oauth_timestamp',
				'oauth_nonce',
				'oauth_signature',
				'oauth_signature_method',
			);

			$errors   = array();
			$have_one = false;

			// Check for required OAuth parameters.
			foreach ($param_names as $param_name) {
				if (empty($params[$param_name])) {
					$errors[] = $param_name;
				} else {
					$have_one = true;
				}
			}

			// All keys are missing, so we're probably not even trying to use OAuth.
			if (!$have_one) {
				return array();
			}

			// If we have at least one supplied piece of data, and we have an error,
			// then it's a failed authentication.
			if (!empty($errors)) {
				$message = sprintf(
					/* translators: %s: amount of errors */
					_n('Missing OAuth parameter %s', 'Missing OAuth parameters %s', count($errors), 'ns-wmw'),
					implode(', ', $errors)
				);

				$this->set_error(new WP_Error('nouvello_rest_authentication_missing_parameter', $message, array('status' => 401)));

				return array();
			}

			return $params;
		}


		/**
		 * Return the user data for the given consumer_key.
		 *
		 * @param string $consumer_key Consumer key.
		 * @return array
		 */
		private function get_user_data_by_consumer_key($consumer_key)
		{
			global $wpdb;

			$consumer_key = $this->nouvello_api_hash(sanitize_text_field($consumer_key));
			$user         = $wpdb->get_row(
				$wpdb->prepare(
					"
				SELECT key_id, user_id, permissions, consumer_key, consumer_secret, nonces
				FROM {$wpdb->prefix}nouvello_api_keys
				WHERE consumer_key = %s
			",
					$consumer_key
				)
			);

			return $user;
		}

		/**
		 * Nouvello API - Hash.
		 *
		 * @since  2.4.0
		 * @param  string $data Message to be hashed.
		 * @return string
		 */
		private function nouvello_api_hash($data)
		{
			return hash_hmac('sha256', $data, 'wc-api'); // keep as wc-api.
		}

		/**
		 * Get the authorization header.
		 *
		 * On certain systems and configurations, the Authorization header will be
		 * stripped out by the server or PHP. Typically this is then used to
		 * generate `PHP_AUTH_USER`/`PHP_AUTH_PASS` but not passed on. We use
		 * `getallheaders` here to try and grab it out instead.
		 *
		 * @since 3.0.0
		 *
		 * @return string Authorization header if set.
		 */
		public function get_authorization_header()
		{
			if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
				return sanitize_text_field(wp_unslash($_SERVER['HTTP_AUTHORIZATION']));
			}

			if (function_exists('getallheaders')) {
				$headers = getallheaders();
				// Check for the authoization header case-insensitively.
				foreach ($headers as $key => $value) {
					if ('authorization' === strtolower($key)) {
						return $value;
					}
				}
			}

			return '';
		}

		/**
		 * Parse the Authorization header into parameters.
		 *
		 * @since 3.0.0
		 *
		 * @param string $header Authorization header value (not including "Authorization: " prefix).
		 *
		 * @return array Map of parameter values.
		 */
		public function parse_header($header)
		{
			if ('OAuth ' !== substr($header, 0, 6)) {
				return array();
			}

			// From OAuth PHP library, used under MIT license.
			$params = array();
			if (preg_match_all('/(oauth_[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $header, $matches)) {
				foreach ($matches[1] as $i => $h) {
					$params[$h] = urldecode(empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]);
				}
				if (isset($params['realm'])) {
					unset($params['realm']);
				}
			}

			return $params;
		}

		/**
		 * Verify that the consumer-provided request signature matches our generated signature,
		 * this ensures the consumer has a valid key/secret.
		 *
		 * @param stdClass $user   User data.
		 * @param array    $params The request parameters.
		 * @return true|WP_Error
		 */
		private function check_oauth_signature($user, $params)
		{

			$http_method  = isset($_SERVER['REQUEST_METHOD']) ? strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD']))) : '';
			$request_path = isset($_SERVER['REQUEST_URI']) ? wp_parse_url(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), PHP_URL_PATH) : '';
			$wp_base      = get_home_url(null, '/', 'relative');
			if (substr($request_path, 0, strlen($wp_base)) === $wp_base) {
				$request_path = substr($request_path, strlen($wp_base));
			}
			$base_request_uri = rawurlencode(get_home_url(null, $request_path, is_ssl() ? 'https' : 'http'));

			// Get the signature provided by the consumer and remove it from the parameters prior to checking the signature.
			$consumer_signature = rawurldecode(str_replace(' ', '+', $params['oauth_signature']));
			unset($params['oauth_signature']);

			// Sort parameters.
			if (!uksort($params, 'strcmp')) {
				return new WP_Error('nouvello_rest_authentication_error', __('Invalid signature - failed to sort parameters.', 'ns-wmw'), array('status' => 401));
			}

			// Normalize parameter key/values.

			$params         = $this->normalize_parameters($params);
			$query_string   = implode('%26', $this->join_with_equals_sign($params)); // Join with ampersand.
			$string_to_sign = $http_method . '&' . $base_request_uri . '&' . $query_string;

			if ('HMAC-SHA1' !== $params['oauth_signature_method'] && 'HMAC-SHA256' !== $params['oauth_signature_method']) {
				return new WP_Error('nouvello_rest_authentication_error', __('Invalid signature - signature method is invalid.', 'ns-wmw'), array('status' => 401));
			}

			$hash_algorithm = strtolower(str_replace('HMAC-', '', $params['oauth_signature_method']));
			$secret         = $user->consumer_secret . '&';
			$signature      = base64_encode(hash_hmac($hash_algorithm, $string_to_sign, $secret, true));

			if (!hash_equals($signature, $consumer_signature)) { // @codingStandardsIgnoreLine
				return new WP_Error('nouvello_rest_authentication_error', __('Invalid signature - provided signature does not match.', 'ns-wmw'), array('status' => 401));
			}

			return true;
		}

		/**
		 * Normalize each parameter by assuming each parameter may have already been
		 * encoded, so attempt to decode, and then re-encode according to RFC 3986.
		 *
		 * Note both the key and value is normalized so a filter param like:
		 *
		 * 'filter[period]' => 'week'
		 *
		 * is encoded to:
		 *
		 * 'filter%255Bperiod%255D' => 'week'
		 *
		 * This conforms to the OAuth 1.0a spec which indicates the entire query string
		 * should be URL encoded.
		 *
		 * @see rawurlencode()
		 * @param array $parameters Un-normalized parameters.
		 * @return array Normalized parameters.
		 */
		private function normalize_parameters($parameters)
		{
			$keys       = $this->nouvello_rest_urlencode_rfc3986(array_keys($parameters));
			$values     = $this->nouvello_rest_urlencode_rfc3986(array_values($parameters));
			$parameters = array_combine($keys, $values);

			return $parameters;
		}


		/**
		 * Encodes a value according to RFC 3986.
		 * Supports multidimensional arrays.
		 *
		 * @since 2.6.0
		 * @param string|array $value The value to encode.
		 * @return string|array       Encoded values.
		 */
		private function nouvello_rest_urlencode_rfc3986($value)
		{
			if (is_array($value)) {
				return array_map(array($this, 'nouvello_rest_urlencode_rfc3986'), $value);
			}

			return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($value));
		}

		/**
		 * Creates an array of urlencoded strings out of each array key/value pairs.
		 *
		 * @param  array  $params       Array of parameters to convert.
		 * @param  array  $query_params Array to extend.
		 * @param  string $key          Optional Array key to append.
		 * @return string               Array of urlencoded strings.
		 */
		private function join_with_equals_sign($params, $query_params = array(), $key = '')
		{
			foreach ($params as $param_key => $param_value) {
				if ($key) {
					$param_key = $key . '%5B' . $param_key . '%5D'; // Handle multi-dimensional array.
				}

				if (is_array($param_value)) {
					$query_params = $this->join_with_equals_sign($param_value, $query_params, $param_key);
				} else {
					$string         = $param_key . '=' . $param_value; // Join with equals sign.
					$query_params[] = $this->nouvello_rest_urlencode_rfc3986($string);
				}
			}

			return $query_params;
		}


		/**
		 * Verify that the timestamp and nonce provided with the request are valid. This prevents replay attacks where
		 * an attacker could attempt to re-send an intercepted request at a later time.
		 *
		 * - A timestamp is valid if it is within 15 minutes of now.
		 * - A nonce is valid if it has not been used within the last 15 minutes.
		 *
		 * @param stdClass $user      User data.
		 * @param int      $timestamp The unix timestamp for when the request was made.
		 * @param string   $nonce     A unique (for the given user) 32 alphanumeric string, consumer-generated.
		 * @return bool|WP_Error
		 */
		private function check_oauth_timestamp_and_nonce($user, $timestamp, $nonce)
		{
			global $wpdb;

			$valid_window = 15 * 60; // 15 minute window.

			if (($timestamp < time() - $valid_window) || ($timestamp > time() + $valid_window)) {
				return new WP_Error('nouvello_rest_authentication_error', __('Invalid timestamp.', 'ns-wmw'), array('status' => 401));
			}

			$used_nonces = maybe_unserialize($user->nonces);

			if (empty($used_nonces)) {
				$used_nonces = array();
			}

			if (in_array($nonce, $used_nonces, true)) {
				return new WP_Error('nouvello_rest_authentication_error', __('Invalid nonce - nonce has already been used.', 'ns-wmw'), array('status' => 401));
			}

			$used_nonces[$timestamp] = $nonce;

			// Remove expired nonces.
			foreach ($used_nonces as $nonce_timestamp => $nonce) {
				if ($nonce_timestamp < (time() - $valid_window)) {
					unset($used_nonces[$nonce_timestamp]);
				}
			}

			$used_nonces = maybe_serialize($used_nonces);

			$wpdb->update(
				$wpdb->prefix . 'nouvello_api_keys',
				array('nonces' => $used_nonces),
				array('key_id' => $user->key_id),
				array('%s'),
				array('%d')
			);

			return true;
		}


		/**
		 * Check for authentication error.
		 *
		 * @param WP_Error|null|bool $error Error data.
		 * @return WP_Error|null|bool
		 */
		public function check_authentication_error($error)
		{
			// Pass through other errors.
			if (!empty($error)) {
				return $error;
			}

			return $this->get_error();
		}

		/**
		 * Set authentication error.
		 *
		 * @param WP_Error $error Authentication error data.
		 */
		protected function set_error($error)
		{
			// Reset user.
			$this->user = null;

			$this->error = $error;
		}

		/**
		 * Get authentication error.
		 *
		 * @return WP_Error|null.
		 */
		protected function get_error()
		{
			return $this->error;
		}
	} // end of class

endif; // end if class exist.

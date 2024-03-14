<?php

namespace PargoWp\PargoAdmin;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PargoWp\Includes\Analytics;
use PargoWp\Includes\Pargo_Wp_Shipping_Method;
use WP_REST_Response;

class Pargo_Admin_API
{

	public const PARGO_API_ENDPOINTS = [
		"staging" => "https://api.staging.pargo.co.za",
		"production" => "https://api.live.pargo.co.za"
	];

	public const PARGO_DEFAULT_TOKENS = [
		"staging" => "jCrAors2y3MmidOkKjn1xxsyLaPnrkCXAFwH8zrdczo6kjjt",
		"production" => "Vs2Vj14H4gNZLg1OaQKPsNhUHDXgE5eglX16eaeSlUInY53q"
	];
	protected $plugin_version;
	private $plugin_name;

	public function __construct($plugin_name, $plugin_version)
	{
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
		// If there is no token yet, generate one.
		if ( !get_option($this->plugin_name.'_token') ) {
			$this->generate_api_token();
		}
	}

	public function register_routes()
	{
		register_rest_route($this->plugin_name . '/v1', '/get-signup-click-event', [
			'methods' => 'GET',
			'callback' => [$this, 'get_signup_click_event'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route($this->plugin_name . '/v1', '/get-credentials', [
			'methods' => 'GET',
			'callback' => [$this, 'get_credentials'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route($this->plugin_name . '/v1', '/store-credentials', [
			'methods' => 'POST',
			'callback' => [$this, 'store_credentials'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route($this->plugin_name . '/v1', '/regenerate-token', [
			'methods' => 'POST',
			'callback' => [$this, 'new_api_token_request'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route($this->plugin_name . '/v1', '/verify-credentials', [
			'methods' => 'POST',
			'callback' => [$this, 'verify_credentials'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route($this->plugin_name . '/v1', '/get-setting-styling', [
			'methods' => 'GET',
			'callback' => [$this, 'get_setting_styling'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route($this->plugin_name . '/v1', '/store-setting-styling', [
			'methods' => 'POST',
			'callback' => [$this, 'store_setting_styling'],
			'permission_callback' => function () {
				return current_user_can('manage_options');
			}
		]);

		register_rest_route( $this->plugin_name . '/v1', '/write-back', [
			'methods' => 'POST',
			'callback' => [$this, 'perform_write_back'],
			'permission_callback' => function () {
				return $this->check_token();
			}
		]);

	}

	/**
	 * Perform the analytics event for clicking the sign-up button
	 *
	 * @return WP_REST_Response
	 */
	public function get_signup_click_event()
	{
		$event = Analytics::submit('client_admin', 'click', 'mypargo_link');
		if ($event) {
			return new WP_REST_Response(['code' => 'success', 'message' => 'Sign Up event sent']);
		}
		return new WP_REST_Response(['code' => 'error', 'message' => 'Event not sent. Not allowed?'], 405);
	}

    /**
     * Retrieve the stored credentials and map token
     *
     * @return WP_REST_Response
     */
    public function get_credentials()
    {
        try {
            $pargo_shipping_method = new Pargo_Wp_Shipping_Method();
            $pargo_username = $pargo_shipping_method->get_option('pargo_username');
            $pargo_password = $pargo_shipping_method->get_option('pargo_password');
            $pargo_url = $pargo_shipping_method->get_option('pargo_url'); // Legacy method to get API endpoint on v2.5.*
            $pargo_url_endpoint = $pargo_shipping_method->get_option('pargo_url_endpoint');
            $pargo_map_token = $pargo_shipping_method->get_option('pargo_map_token');
            $usage_tracking_enabled = $pargo_shipping_method->get_option('pargo_usage_tracking_enabled') ?? 'true';
	        $api_token = get_option($this->plugin_name . '_token' );
			$supplier_id = $pargo_shipping_method->get_option('pargo_supplier_id');
	        $store_country = WC()->countries->get_base_country();

            if (!$pargo_url_endpoint) {
                if (strpos($pargo_url, 'staging') !== false) {
                    $pargo_url_endpoint = 'staging';
                } else {
                    $pargo_url_endpoint = 'production';
                }
            }
        } catch (\Exception $e) {
            return new WP_REST_Response(['code' => 'error', 'message' => $e], 500);
        }
        if (!isset($pargo_username) && !isset($pargo_password) && !isset($pargo_url_endpoint)) {
            return new WP_REST_Response(['code' => 'success', 'data' => [
                'pargo_username' => "",
                'pargo_password' => "",
                'pargo_url' => "",
                'pargo_url_endpoint' => "staging",
                'pargo_map_token' => self::PARGO_DEFAULT_TOKENS['staging'],
                'pargo_usage_tracking_enabled' => 'true',
                'api_token' => '',
				'supplier_id' => '',
                'pargo_store_country_code' => $store_country,
            ]], 200);
        }
        if (empty($pargo_map_token)) {
            $pargo_map_token = self::PARGO_DEFAULT_TOKENS[$pargo_url_endpoint];
        }

        return new WP_REST_Response(['code' => 'success', 'data' => [
            'pargo_username' => $pargo_username,
            'pargo_password' => $pargo_password,
            'pargo_url' => $pargo_url,
            'pargo_url_endpoint' => $pargo_url_endpoint,
            'pargo_map_token' => $pargo_map_token,
            'pargo_usage_tracking_enabled' => $usage_tracking_enabled,
            'api_token' => $api_token,
			'supplier_id' => $supplier_id,
            'pargo_store_country_code' => $store_country,
        ]], 200);
    }

	/**
	 * Store the user credentials and map token
	 *
	 * @return WP_REST_Response
	 */
	public function store_credentials()
	{
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		if (!isset($_POST['pargo_username']) || !isset($_POST['pargo_password']) || !isset($_POST['pargo_url_endpoint'])) {
			return new WP_REST_Response(['code' => 'error', 'message' => 'An error occurred. Parameter missing?'], 500);
		}
		Analytics::submit('client_admin', 'click', 'save_credentials');

		//write event to analytics if usage tracking is disabled
		if ($_POST['pargo_usage_tracking_enabled'] == 'false') {
			Analytics::submit('client_admin', 'click', 'disable_usage_tracking');
		}

		// store the username and password
		if (!empty($_POST['pargo_username']) && !empty($_POST['pargo_password']) && !empty($_POST['pargo_url_endpoint'])) {
			$pargo_shipping_method->update_option('pargo_username', $_POST['pargo_username']);
			$pargo_shipping_method->update_option('pargo_password', $_POST['pargo_password']);
			$pargo_shipping_method->update_option('pargo_url_endpoint', $_POST['pargo_url_endpoint']);
			$pargo_shipping_method->update_option('pargo_usage_tracking_enabled', $_POST['pargo_usage_tracking_enabled']);
			$pargo_shipping_method->update_option('pargo_supplier_id', $_POST['pargo_supplier_id']);
			if (empty($_POST["pargo_map_token"])) {
				$pargo_shipping_method->update_option('pargo_map_token', self::PARGO_DEFAULT_TOKENS[$_POST['pargo_url_endpoint']]);
			} else {
				$pargo_shipping_method->update_option('pargo_map_token', $_POST['pargo_map_token']);
			}

			return new WP_REST_Response(['code' => 'success', 'message' => 'Pargo Credentials Updated'], 200);
		}

		return new WP_REST_Response(['code' => 'error', 'message' => 'Parameters empty?'], 500);
	}

	/**
	 * Helper to get the Auth Token
	 */
	public function get_auth_token()
	{
		if (isset(WC()->session)) {
			if (!empty(WC()->session->get('access_token')) && time() < WC()->session->get('access_token_expiry')) {
				return WC()->session->get('access_token');
			}
		}

		$api_url = self::get_api_url() . 'auth';

		/**
		 * Get the pargo username and password
		 */
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		$pargo_username = $pargo_shipping_method->get_option('pargo_username');
		$pargo_password = $pargo_shipping_method->get_option('pargo_password');
		$data = [
			'username' => $pargo_username,
			'password' => $pargo_password
		];


		/**
		 * Get Auth data from the API
		 */
		$returnData = $this->post_api($api_url, $data);

		/**
		 * Check if an error is returned else return the authToken
		 */
		if (is_wp_error($returnData)) {
			$error_message = $returnData->get_error_message();
			return "Something went wrong: $error_message";
		} else {
			$response = wp_remote_retrieve_body($returnData);

			$accessToken = json_decode($response)->access_token;
			$expiresIn = json_decode($response)->expires_in;

			if (isset(WC()->session)) {
				WC()->session->set('access_token', $accessToken);
				WC()->session->set('access_token_expiry', $expiresIn);
			}

			return $accessToken;
		}
	}

	/**
	 * Helper to get the API url
	 */
	public static function get_api_url($pargo_url_endpoint = '')
	{
		if (empty($pargo_url_endpoint)) {
			$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
			$pargo_url_endpoint = $pargo_shipping_method->get_option('pargo_url_endpoint');
		}
		$pargo_url = self::PARGO_API_ENDPOINTS[$pargo_url_endpoint];
		return trailingslashit($pargo_url);
	}

	/**
	 * Helper to perform the API request
	 * @param $url
	 * @param string $data
	 * @param array $headers
	 * @param string $method
	 * @return mixed|WP_Error
	 */
	public function post_api($url, $data = '', $headers = [], $method = 'POST')
	{
		if (!$url || empty($url)) {
			return false;
		}
		$response = wp_remote_post(
			$url,
			array(
				'method' => $method,
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => $headers,
				'body' => $data,
				'cookies' => array()
			)
		);

		return $response;
	}

	/**
	 * Perfom API Call to Pargo to verify the credentials
	 *
	 * @return WP_REST_Response
	 */
	public function verify_credentials()
	{
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		$pargo_username = $pargo_shipping_method->get_option('pargo_username');
		$pargo_password = $pargo_shipping_method->get_option('pargo_password');
		$pargo_url_endpoint = $pargo_shipping_method->get_option('pargo_url_endpoint');
		$pargo_url = self::PARGO_API_ENDPOINTS[$pargo_url_endpoint];
		$url = trailingslashit($pargo_url) . 'auth';
		$body = [
			'username' => $pargo_username,
			'password' => $pargo_password,
		];

		$response = $this->post_api($url, $body);
		$status_code = $response['response']['code'];
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();

			return new WP_REST_Response(['code' => 'error', 'message' => $error_message], $status_code);
		}
		$response = wp_remote_retrieve_body($response);
		if (isset(json_decode($response)->access_token)) {
			return new WP_REST_Response(['code' => 'success', 'message' => 'Pargo Credentials Verified!', 'access_token' => json_decode($response)->access_token], 200);
		}
		return new WP_REST_Response(['code' => 'error', 'message' => 'Could not verify your credentials, please try again or contact support.', 'url' => $url], $status_code);
	}

	/**
	 * Retrieve the styling CSS for the Pargo Frontend
	 *
	 * @return WP_REST_Response
	 */
	public function get_setting_styling()
	{
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		$styling = "";
		if ($pargo_shipping_method->get_option("pargo_custom_styling")) {
			$styling = $pargo_shipping_method->get_option("pargo_custom_styling");
			return new WP_REST_Response(['code' => 'success', 'styling' => $styling], 200);
		}

		$styling = $pargo_shipping_method->default_styling();

		if (!empty($styling)) {
			$styling .= "\n";
			return new WP_REST_Response(['code' => 'success', 'styling' => $styling], 200);
		}

		return new WP_REST_Response(['code' => 'error', 'message' => 'An unexpected error occurred loading settings'], 500);
	}

	/**
	 * Store the custom styling
	 *
	 * @returns WP_REST_Response
	 */
	public function store_setting_styling()
	{
		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		Analytics::submit('client_admin', 'click', 'save_styles');
		if (!isset($_POST['pargo_setting_styling'])) {
			return new WP_REST_Response(['code' => 'error', 'message' => 'An error occurred. Parameter missing?'], 500);
		}

		// store the username and password
		if (!empty($_POST['pargo_setting_styling'])) {
			$pargo_shipping_method->update_option('pargo_custom_styling', sanitize_text_field($_POST['pargo_setting_styling']));
			// remove v2 styling
			delete_option("pargo_style_button");
			delete_option("pargo_style_title");
			delete_option("pargo_style_desc");
			delete_option("pargo_style_image");
			$css_file_path = plugin_dir_path(__FILE__) . "../../assets/css/pargo_wp.css";
			file_put_contents($css_file_path, sanitize_text_field($_POST['pargo_setting_styling']));
			return new WP_REST_Response(['code' => 'success', 'message' => 'Pargo Settings Updated'], 200);
		}

		return new WP_REST_Response(['code' => 'error', 'message' => 'Parameters empty?'], 500);
	}

	/**
	 * Tests the JWT and allows access
	 *
	 * @return false|string
	 */
	private function check_token() {
		if (!defined('AUTH_KEY')) {
			error_log('Could not validate Pargo API token, AUTH_KEY is missing from your WordPress installation. More information on this is here: https://developer.wordpress.org/reference/functions/wp_salt/');
			return false;
		}

		if ( get_option( $this->plugin_name . '_token' ) !== false && isset($_SERVER["HTTP_AUTHORIZATION"]) && 0 === stripos($_SERVER["HTTP_AUTHORIZATION"], 'Bearer ')) {
			$jwt = get_option( $this->plugin_name . '_token' );
			$auth_array = explode(" ", $_SERVER["HTTP_AUTHORIZATION"]);
			// Check if the tokens match
			if ($jwt == $auth_array[1]) {
				$key = AUTH_KEY;
				// check if the token has expired
				$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
				if ($decoded->exp < time() ){
					error_log('Could not validate Pargo API token, token has expired');
					return false;
				}
				return '__return_true';
			}
		}
		return false;
	}

	/**
	 * Handles a request for a new token from the admin API
	 *
	 * @returns WP_REST_Response
	 */
	public function new_api_token_request() {
		if ($this->generate_api_token()) {
			return new WP_REST_Response(['code' => 'success', 'message' => 'Write-Back token updated', 'api_token' => get_option($this->plugin_name . "_token")], 200);
		}
		return new WP_REST_Response(['code' => 'error', 'message' => "Uh-oh, we couldn't generate a token - not all is lost! Check your debug log, or contact support@pargo.co.za"], 500);
	}
	/**
	 * Generates the API token for use outside the WordPress environment.
	 *
	 * @return bool;
	 */
	private function generate_api_token()
	{
		// Using the WordPress Auth key as this is unique for each website
		// Return false if no AUTH_KEY is present, user should be notified.
		if (!defined('AUTH_KEY')) {
			error_log('Could not generate Pargo API token, AUTH_KEY is missing from your WordPress installation. More information on this is here: https://developer.wordpress.org/reference/functions/wp_salt/');
			return false;
		}

		$key = AUTH_KEY;

		// Setting expiration to 1 year from now
		$payload = [
			'iss' => get_site_url(),
			'exp' => strtotime('+1 year'),
			'rand' => rand(0,100000)
		];
		try {
			$jwt = JWT::encode( $payload, $key, 'HS256' );
			update_option($this->plugin_name . '_token', $jwt);
			return true;
		} catch (\Exception $error) {
			error_log($error);
			return false;
		}

	}

	/**
	 * Handle the write back and update the order from the request
	 *
	 * @param $payload WP_REST_Request
	 * @return WP_REST_Response
	 */
	public function perform_write_back($payload) {
		if ($payload->is_json_content_type()) {
			$request = $payload->get_json_params();
			$event = $request["data"]["event"];
			$event = explode('.', $event);
			// Make sure it's a valid event
			if (count($event) <= 1) {
				return new WP_REST_Response( [ 'code'    => 'failed',
				                               'message' => 'Unknown event'
				], 400 );
			}
			$allowed_event_statuses = [
				'pending',
				'confirmed',
				'completed',
				'atCourierForDelivery',
				'atCollectionPoint',
			];
			$event = array_pop($event);
			if (!in_array($event, $allowed_event_statuses)) {
				return new WP_REST_Response( [ 'code'    => 'failed',
				                               'message' => 'Event: ' . $request["data"]["event"] . " cannot be handled"
				], 400 );
			}
			$order_reference = $request["data"]["reference1"];
			// Get the order
			$orders = wc_get_orders([ 'pargo_waybill' => $order_reference ]);
			if (!$orders) {
				return new WP_REST_Response(['code' => 'error', 'message' => __('Order: ' . $order_reference .' could not be found.')], 404);
			}
			$order = $orders[0];
			if (in_array($event, ['pending', 'confirmed', 'atCourierForDelivery', 'atCollectionPoint'])) {
				$order->set_status('processing');
				$note = "";
				switch($event) {
					case "pending":
						$note = __( "Pargo Update: order status updated to Pending in myPargo.", $this->plugin_name );
					break;
					case "confirmed":
						$note = __( "Pargo Update: order status updated to Confirmed in myPargo.", $this->plugin_name );
					break;
					case "atCourierForDelivery":
						$note = __( "Pargo Update: order status updated to At Courier in myPargo.", $this->plugin_name );
					break;
					case "atCollectionPoint":
						$note = __( "Pargo Update: order status updated to At Pargo Point in myPargo.", $this->plugin_name );
					break;
				}
				if (!empty($note)) {
					$order->add_order_note( $note );
				}
				$order->save();
			}
			if ($event == 'completed') {
				$order->set_status('completed');
				$note = __("Pargo Update: order status updated to Completed in myPargo.", $this->plugin_name );
				$order->add_order_note($note);
				$order->save();
			}
			return new WP_REST_Response( [ 'code'    => 'success',
			                               'message' => 'Write-back performed'
			] );
		}
		return new WP_REST_Response(['code' => 'error', 'message' => 'Could not handle the request.'], 500);
	}

}

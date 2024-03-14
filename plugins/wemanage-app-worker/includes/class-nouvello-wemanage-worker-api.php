<?php

/**
 * Nouvello WeManage Worker Api Class
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

/**
 * Nouvello WeManage Worker Api Class
 */
class Nouvello_WeManage_Worker_Api
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// register custom api end points.
		add_action('rest_api_init', array($this, 'nouvello_custom_api_check_route_get')); // GET.
		add_action('rest_api_init', array($this, 'nouvello_custom_api_check_route_post')); // POST.
		add_action('rest_api_init', array($this, 'nouvello_custom_api_nouvello_wl')); // POST.
		add_action('rest_api_init', array($this, 'nouvello_custom_api_remove_keys')); // POST.
		add_action('rest_api_init', array($this, 'nouvello_custom_api_nouvello_mi')); // POST.

		// Protect Api with OAuth.
		require_once NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-oauth.php';
		nouvello_wemanage_worker()->oauth = new Nouvello_WeManage_Worker_OAuth();
	}

	/**
	 * Register custom GET api endpoint.
	 */
	public function nouvello_custom_api_check_route_get()
	{

		register_rest_route(
			'nouvello-api',
			'check',
			array(
				'methods'  => 'GET',
				'callback' => array($this, 'test_oauth1_get'),
				'permission_callback' => function ($request) {
					return nouvello_wemanage_worker()->oauth->authenticate();
				},
			)
		);
	}

	/**
	 * Register custom POST api endpoint.
	 */
	public function nouvello_custom_api_check_route_post()
	{
		register_rest_route(
			'nouvello-api',
			'do',
			array(
				'methods'  => 'POST',
				'callback' => array($this, 'test_oauth1_post'),
				'permission_callback' => function ($request) {
					return nouvello_wemanage_worker()->oauth->authenticate();
				},
			)
		);
	}

	/**
	 * Register custom POST api endpoint.
	 */
	public function nouvello_custom_api_nouvello_wl()
	{
		register_rest_route(
			'nouvello-api',
			'nouvello-wl',
			array(
				'methods'  => 'POST',
				'callback' => array($this, 'nouvello_setup_wl'),
				'permission_callback' => function ($request) {
					return nouvello_wemanage_worker()->oauth->authenticate();
				},
			)
		);
	}

	/**
	 * Register custom POST api endpoint.
	 */
	public function nouvello_custom_api_remove_keys()
	{
		register_rest_route(
			'nouvello-api',
			'wemanage-remove-keys',
			array(
				'methods'  => 'POST',
				'callback' => array($this, 'nouvello_wemanage_remove_keys'),
				'permission_callback' => function ($request) {
					return nouvello_wemanage_worker()->oauth->authenticate();
				},
			)
		);
	}

	/**
	 * Register custom POST api endpoint.
	 */
	public function nouvello_custom_api_nouvello_mi()
	{
		register_rest_route(
			'nouvello-api',
			'nouvello-mi',
			array(
				'methods'  => 'POST',
				'callback' => array($this, 'nouvello_setup_mi'),
				'permission_callback' => function ($request) {
					return $this->check_manual_mode();
				},
			)
		);
	}

	/**
	 *  Route test GET.
	 *
	 * @param  [type] $data [description].
	 * @return [type]       [description]
	 */
	public function test_oauth1_get($data)
	{

		// build array of get params (optional - dev only).
		$get_params = array();
		$params = $data->get_params();
		if (isset($params) && is_array($params) && !empty($params)) {
			unset($params['oauth_consumer_key']);
			unset($params['oauth_nonce']);
			unset($params['oauth_signature']);
			unset($params['oauth_signature_method']);
			unset($params['oauth_timestamp']);
			foreach ($params as $param => $value) {
				$get_params[$param] = $data->get_param($param);
			}
		}

		return array(
			'success' => true,
			'message' => 'OAuth1 Working GET. Thank you.',
			'data' => $get_params, // optional - dev only.
		);
	}

	/**
	 * [test_oauth1_post description]
	 *
	 * @param  WP_REST_Request $request [description].
	 * @return array                    [description]
	 */
	public function test_oauth1_post(WP_REST_Request $request)
	{
		// get request data.
		$arr_request = json_decode($request->get_body());

		return array(
			'success' => true,
			'message' => 'OAuth1 Working POST. Thank you.',
			'data' => $arr_request,
		);
	}

	/**
	 * Setup Nouvello WL.
	 *
	 * @param  WP_REST_Request $request [description].
	 * @return [type]                   [description]
	 */
	public function nouvello_setup_wl(WP_REST_Request $request)
	{
		// get request data.
		$arr_request = json_decode($request->get_body(), true);
		if (isset($arr_request['name'])) {
			update_option('nvl_wl_n', $arr_request['name']);
		} else {
			delete_option('nvl_wl_n');
		}
		if (isset($arr_request['description'])) {
			update_option('nvl_wl_d', $arr_request['description']);
		} else {
			delete_option('nvl_wl_d');
		}
		if (isset($arr_request['author'])) {
			update_option('nvl_wl_a', $arr_request['author']);
		} else {
			delete_option('nvl_wl_a');
		}
		return array(
			'success' => true,
		);
	}

	/**
	 * Remove plugin API keys
	 *
	 * @param  WP_REST_Request $request [description].
	 * @return [type]                   [description]
	 */
	public function nouvello_wemanage_remove_keys(WP_REST_Request $request)
	{

		// remove API keys.
		nouvello_wemanage_worker()->init->remove_all_nouvello_keys('nouvello_api_keys');
		nouvello_wemanage_worker()->init->remove_all_nouvello_keys('woocommerce_api_keys');
		// remove activation key.
		delete_option('nouvello-worker-activation-key');

		// disable plugin.
		$active_plugins = get_option('active_plugins');
		foreach ($active_plugins as $key => $plugin) {
			if (NSWMW_BASE_NAME == $plugin) {
				unset($active_plugins[$key]);
			}
		}
		update_option('active_plugins', $active_plugins);

		// remove reintall restrictions.
		delete_option('nvl_wemanage_worker_wp');
		delete_option('nvl_wemanage_worker_wc');

		return array(
			'success' => true,
		);
	}

	/**
	 * [check_manual_mode description]
	 *
	 * @return [type] [description]
	 */
	public function check_manual_mode()
	{
		// to do check if manual mode has been enabled by used.
		// transient will expire in 60 seconds.
		return true;
	}


	/**
	 * Manual Installation
	 *
	 * @param  WP_REST_Request $request [description].
	 * @return [type]                   [description]
	 */
	public function nouvello_setup_mi(WP_REST_Request $request)
	{
		// get request data.
		$arr_request = json_decode($request->get_body(), true);

		$user_enabled_manual_mode = get_transient('nvl_wemanage_manual');
		if (!$user_enabled_manual_mode) {
			$error = array(
				'status' => 'error',
				'msg' => 'Manual mode not enabled', // manual mode must be enabled by user and is valid for 60 seconds.
			);
			return $error;
		}

		if ((!isset($arr_request['connection_key']) || '' == $arr_request['connection_key']) || nouvello_wemanage_worker()->init->return_activation_key() !== $arr_request['connection_key']) {
			$error = array(
				'status' => 'error',
				'msg' => 'A valid connection key is required',
			);
			return $error;
		}

		// remove restrictions.
		delete_option('nvl_wemanage_worker_wp');
		delete_option('nvl_wemanage_worker_wc');

		nouvello_wemanage_worker()->init->nouvello_setup_wp_worker();
		nouvello_wemanage_worker()->init->nouvello_setup_wc_worker();

		// reinforce restrictions.
		update_option('nvl_wemanage_worker_wp', time()); // prevent running again.
		update_option('nvl_wemanage_worker_wc', time()); // prevent running again.

		$key = nouvello_wemanage_worker()->init->return_activation_key();
		$website_url = get_home_url();
		$website_name = get_bloginfo('name');
		$website_tagline = get_bloginfo('description', 'display');
		$ns_wmw_key = get_transient('ns-wmw-key');
		$ns_wmw_secret = get_transient('ns-wmw-secret');
		$ns_wmw_wc_key = get_transient('ns-wmw-wc-key');
		$ns_wmw_wc_secret = get_transient('ns-wmw-wc-secret');

		$data = array(
			'status' => 'ok',
			'key' => $key,
			'url' => $website_url,
			'name' => $website_name,
			'tagline' => $website_tagline,
			'wp_key' => $ns_wmw_key,
			'wp_secret' => $ns_wmw_secret,
			'wc_key' => $ns_wmw_wc_key,
			'wc_secret' => $ns_wmw_wc_secret,
			'wp_plugin_version' => NSWMW_VER,
		);

		return $data;
	}
}

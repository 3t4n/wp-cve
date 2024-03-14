<?php
/**
 * @package     blazing-shipment-tracking
 * @category    API
 * @since       1.0
 *
 * Handles BST-Tracking-API endpoint requests
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class BS_Shipment_Tracking_API
{

	/** This is the major version for the REST API and takes
	 * first-order position in endpoint URLs
	 */
	const VERSION = 1;

	/** @var WC_API_Server the REST API server */
	public $server;

	/**
	 * Setup class
	 *
	 * @access public
	 * @since 2.0
	 * @return WC_API
	 */
	public function __construct()
	{

		// add query vars
		add_filter('query_vars', array($this, 'add_query_vars'), 0);

		// register API endpoints
		add_action('init', array($this, 'add_endpoint'), 0);

		// handle REST/legacy API request
		add_action('parse_request', array($this, 'handle_api_requests'), 0);
	}

	/**
	 * add_query_vars function.
	 *
	 * @access public
	 * @since 2.0
	 * @param $vars
	 * @return array
	 */
	public function add_query_vars($vars)
	{
		$vars[] = 'bs_ship_track-api';
		$vars[] = 'bs_ship_track-api-route';
		return $vars;
	}

	/**
	 * add_endpoint function.
	 *
	 * @access public
	 * @since 2.0
	 * @return void
	 */
	public function add_endpoint()
	{

		// REST API
		add_rewrite_rule('^bs_ship_track-api\/v' . self::VERSION . '/?$', 'index.php?bs_ship_track-api-route=/', 'top');
		add_rewrite_rule('^bs_ship_track-api\/v' . self::VERSION . '(.*)?', 'index.php?bs_ship_track-api-route=$matches[1]', 'top');

		// legacy API for payment gateway IPNs
		add_rewrite_endpoint('bs_ship_track-api', EP_ALL);
	}


	/**
	 * API request - Trigger any API requests
	 *
	 * @access public
	 * @since 2.0
	 * @return void
	 */
	public function handle_api_requests()
	{
		global $wp;

		if (!empty($_GET['bs_ship_track-api']))
			$wp->query_vars['bs_ship_track-api'] = $_GET['bs_ship_track-api'];

		if (!empty($_GET['bs_ship_track-api-route']))
			$wp->query_vars['bs_ship_track-api-route'] = $_GET['bs_ship_track-api-route'];

		// REST API request
		if (!empty($wp->query_vars['bs_ship_track-api-route'])) {

			define('BS_SHIP_TRACK_API_REQUEST', true);

			// load required files
			$this->includes();

			$this->server = new BS_Shipment_Tracking_API_Server($wp->query_vars['bs_ship_track-api-route']);

			// load API resource classes
			$this->register_resources($this->server);

			// Fire off the request
			$this->server->serve_request();

			exit;
		}

		// legacy API requests
		if (!empty($wp->query_vars['bs_ship_track-api'])) {

			// Buffer, we won't want any output here
			ob_start();

			// Get API trigger
			$api = strtolower(esc_attr($wp->query_vars['bs_ship_track-api']));

			// Load class if exists
			if (class_exists($api))
				$api_class = new $api();

			// Trigger actions
			do_action('woocommerce_api_' . $api);

			// Done, clear buffer and exit
			ob_end_clean();
			die('1');
		}
	}


	/**
	 * Include required files for REST API request
	 *
	 * @since 2.1
	 */
	private function includes()
	{

		// authentication
		include_once('api/class-bst-tracking-api-authentication.php');

		include_once('api/class-bst-tracking-api-resource.php');

		// self api
		//include_once('api/class-bst-tracking-api-orders.php');

		// allow plugins to load other response handlers or resource classes
		do_action('woocommerce_api_loaded');
	}

	/**
	 * Register available API resources
	 *
	 * @since 2.1
	 * @param object $server the REST server
	 */
	public function register_resources($server)
	{

		$api_classes = apply_filters('bst_api_classes',
			array(
				'BS_Shipment_Tracking_API_Orders',
			)
		);

		foreach ($api_classes as $api_class) {
			$this->$api_class = new $api_class($server);
		}
	}

}

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Trackship_Analytics {
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		
		//start adding hooks
		$this->init();
	}
	
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Advanced_Shipment_Tracking_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init when class loaded
	*/
	public function init() {
		
		add_action( 'admin_enqueue_scripts', array( $this, 'analytics_script' ) );
		add_filter( 'woocommerce_analytics_report_menu_items', array( $this, 'add_ts_analytics_menu' ) );
		add_action( 'rest_api_init', array( $this, 'ts_analytics_rest_api_register_routes' ) );
	}
	
	public function add_ts_analytics_menu( $report_pages ) {
		$report_pages[] = array(
			'id' => 'trackship-analytics',
			'title' => '<trackship-icon aria-label="TrackShip"></trackship-icon>' . __('Shipping & Delivery', 'trackship-for-woocommerce'),
			'parent' => 'woocommerce-analytics',
			'path' => '/analytics/trackship-analytics',
		);
		return $report_pages;
	}
	
	/**
	* Register TrackShip Analytics Routes
	*/
	public function ts_analytics_rest_api_register_routes() {
		
		if ( ! is_a( WC()->api, 'WC_API' ) ) {
			return;
		}
		
		require_once trackship_for_woocommerce()->get_plugin_path() . '/includes/analytics/class-trackship-analytics-rest-api-controller.php';
		
		// Register route with default namespace wc/v3.
		$ts_analytics_api_controller = new WC_Ts_Analytics_REST_API_Controller();
		$ts_analytics_api_controller->register_routes();					
	}

	public function analytics_script() {
		
		if ( version_compare( WC_VERSION, 6.5, '>=' ) ) {
			if ( ! class_exists( 'Automattic\WooCommerce\Admin\PageController' ) || ! \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page() ) {
				return;
			}
		} else {
			if ( ! class_exists( 'Automattic\WooCommerce\Admin\Loader' ) || ! \Automattic\WooCommerce\Admin\Loader::is_admin_or_embed_page() ) {
				return;
			}
		}
		
		$script_asset_path = trackship_for_woocommerce()->get_plugin_path() . '/includes/analytics/assets/index.asset.php';
		$script_asset = file_exists( $script_asset_path ) ? require( $script_asset_path ) : array( 'dependencies' => array() );		
	
		wp_register_script( 'trackship-analytics', trackship_for_woocommerce()->plugin_dir_url() . 'includes/analytics/assets/index.js', $script_asset['dependencies'], trackship_for_woocommerce()->version, true );
		wp_register_style( 'trackship-analytics', trackship_for_woocommerce()->plugin_dir_url() . 'includes/analytics/assets/index.css', array(), trackship_for_woocommerce()->version );
		
		wp_enqueue_script( 'trackship-analytics' );
		wp_enqueue_style( 'trackship-analytics' );
	}	
}

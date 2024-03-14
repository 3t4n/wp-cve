<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Admin {
  /**
   * Constructor
   */
  public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15, 0 );

		// Version check
		add_action( 'init', array( $this, 'version_check' ), 10, 0 );

		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_settings_page' ), 10, 1 );

		// Add links to the plugins page
		if ( defined ( 'WOO_STOCK_SYNC_BASENAME' ) ) {
			add_filter( 'plugin_action_links_' . WOO_STOCK_SYNC_BASENAME, array( $this, 'add_plugin_links' ), 10, 1 );
		}

		// AJAX page for checking API access
		// /wp-admin/admin-ajax.php?action=wss_api_check
		add_action( 'wp_ajax_wss_api_check', [ $this, 'check_api_access' ] );

		// AJAX page for running sync update
		add_action( 'wp_ajax_woo_stock_sync_update', array( $this, 'update' ) );

		// AJAX page for pushing stock quantities to all sites 
		add_action( 'wp_ajax_woo_stock_sync_push_all', array( $this, 'push_all' ) );

		// Push stock quantity to external sites
		add_action( 'wp_ajax_woo_stock_sync_push', array( $this, 'push' ) );
		
		// View last response from credentials check
		add_action( 'wp_ajax_wss_view_last_response', [ $this, 'view_last_response' ] );
  }

	/**
	 * Version check
	 */
	public function version_check() {
		if ( ! woo_stock_sync_version_check() ) {
			queue_flash_message( __( 'Stock Sync for WooCommerce requires WooCommerce 4.0 or higher. Please update WooCommerce.', 'woo-stock-sync' ), 'error' );
		}
	}

	/**
	 * Plugin links
	 */
	public function add_plugin_links( $links ) {
		$url = admin_url( 'admin.php?page=wc-settings&tab=woo_stock_sync' );
		$link = '<a href="' . $url . '">' . __( 'Settings' ) . '</a>';
		$links = array_merge( array( $link ), $links );

		if ( ! class_exists( 'Woo_Stock_Sync_Pro' ) ) {
			$link = '<span style="font-weight:bold;"><a href="https://wptrio.com/products/stock-sync-pro" style="color:#46b450;" target="_blank">' . __( 'Go Pro' ) . '</a></span>';

			$links = array_merge( array( $link ), $links );
		}

	  return $links;
	}

	/**
	 * Check API access
	 */
	public function check_api_access() {
		check_ajax_referer( 'wss-api-check', 'security' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			http_response_code( 403 );
			die( 'Permission denied' );
		}

		$type = isset( $_POST['type'] ) ? trim( $_POST['type'] ) : '';
		$url = isset( $_POST['url'] ) ? trim( $_POST['url'] ) : '';
		$key = isset( $_POST['key'] ) ? trim( $_POST['key'] ) : '';
		$secret = isset( $_POST['secret'] ) ? trim( $_POST['secret'] ) : '';

		$check = new Woo_Stock_Sync_Api_Check($url, $key, $secret);
		$check->check($type);
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings ) {
		$settings[] = include_once( WOO_STOCK_SYNC_DIR_PATH . 'includes/admin/class-wc-settings-woo-stock-sync.php' );

		return $settings;
	}

	/**
	 * Enqueue admin scripts and styles
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'woo-stock-sync-admin-css', WOO_STOCK_SYNC_DIR_URL . 'public/admin/css/woo-stock-sync-admin.css', [ 'woocommerce_admin_styles', 'wp-jquery-ui-dialog' ], WOO_STOCK_SYNC_VERSION );

		wp_enqueue_script( 'woo-stock-sync-admin-js', WOO_STOCK_SYNC_DIR_URL . 'public/admin/js/woo-stock-sync-admin.js', [ 'jquery', 'wc-enhanced-select', 'jquery-tiptip', 'jquery-ui-dialog' ], WOO_STOCK_SYNC_VERSION );

		$is_report_page = isset( $_GET['page'] ) && $_GET['page'] === 'woo-stock-sync-report';
		$is_settings_page = isset( $_GET['page'], $_GET['tab'] ) && $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'woo_stock_sync';
		$is_log_page = isset( $_GET['page'], $_GET['action'] ) && $_GET['page'] === 'woo-stock-sync-report' && $_GET['action'] === 'log';

		if ( $is_log_page ) {
			wp_enqueue_style( 'wc-admin-layout' );
		}

		if ( $is_report_page || $is_settings_page ) {
			// Enqueue Vue.js only here to avoid clashing with other plugins using Vue.js
			wp_enqueue_script( 'vue-js', WOO_STOCK_SYNC_DIR_URL . 'public/admin/js/vue.js', [ 'underscore' ], WOO_STOCK_SYNC_VERSION );
		}

		wp_localize_script( 'woo-stock-sync-admin-js', 'woo_stock_sync', [
			'ajax_urls' => $this->ajax_urls(),
			'nonces' => [
				'push' => wp_create_nonce( 'wss-push' ),
				'update_qty' => wp_create_nonce( 'wss-update-qty' ),
				'push_all' => wp_create_nonce( 'wss-push-all' ),
				'update' => wp_create_nonce( 'wss-update' ),
				'api_check' => wp_create_nonce( 'wss-api-check' ),
			],
		] );
	}

	/**
	 * Get AJAX action URLs
	 */
	private function ajax_urls() {
		$urls = [];

		$urls['update'] = add_query_arg( [
			'action' => 'woo_stock_sync_update',
		], admin_url( 'admin-ajax.php' ) );

		$urls['update_qty'] = add_query_arg( [
			'action' => 'woo_stock_sync_update_qty',
		], admin_url( 'admin-ajax.php' ) );

		$urls['push'] = add_query_arg( [
			'action' => 'woo_stock_sync_push',
		], admin_url( 'admin-ajax.php' ) );

		$urls['push_all'] = add_query_arg( [
			'action' => 'woo_stock_sync_push_all',
		], admin_url( 'admin-ajax.php' ) );

		return $urls;
	}

	/**
	 * AJAX action for running sync
	 */
	public function update() {
		check_ajax_referer( 'wss-update', 'security' );

    if ( ! current_user_can( 'manage_woocommerce' ) ) {
      http_response_code( 403 );
      die( 'Permission denied' );
    }

		// If we are completing whole update (all sites has been processed),
		// just update timestamp
		if ( isset( $_POST['complete'] ) && $_POST['complete'] ) {
			update_option( 'woo_stock_sync_last_updated', time() );

			wp_send_json( null, 200 );
		}

		$page = intval( $_POST['page'] );
		$limit = intval( $_POST['limit'] );
		$site = wss_site_by_key( $_POST['site_key'] );

		if ( ! $site ) {
			http_response_code( 422 );
			die( 'Site not found' );
		}

		$query = wss_product_query( [
			'limit' => $limit,
			'page' => $page,
			'paginate' => true,
			'type' => wss_product_types( [ 'variation' ] ),
		] );

		$results = $query->get_products();

		$request = new Woo_Stock_Sync_Api_Request();

		if ( $request->update( $site, $results->products ) ) {
			wp_send_json( [
				'status' => 'processed',
				'total' => $results->total,
				'pages' => $results->max_num_pages,
				'page' => $page,
				'last_page' => $results->max_num_pages == $page,
				'count' => count( $results->products ),
			], 200 );
		} else if ( ! empty( $request->errors ) ) {
			wp_send_json( [
				'status' => 'error',
				'errors' => array_values( $request->errors )
			], 200 );
		}

		wp_send_json( [
			'status' => 'error',
		], 422 );
	}

	/**
	 * Push stock quantity of a single product to external site
	 */
	public function push() {
		check_ajax_referer( 'wss-push', 'security' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
      http_response_code( 403 );
      die( 'Permission denied' );
		}

		$product = wc_get_product( $_POST['product_id'] );
		$site = wss_site_by_key( $_POST['site_key'] );

		if ( ! $site || ! $product ) {
			http_response_code( 422 );
      die( 'Site or product not found' );
		}

		$request = new Woo_Stock_Sync_Api_Request();

		$response = $request->push_multiple( [
			[
				'product' => $product,
				'operation' => 'set',
				'value' => $product->get_stock_quantity( 'edit' ),
			]
		], $site );

		if ( $response ) {
			wp_send_json( wss_product_to_json( $product, true ), 200 );
		}

		wp_send_json( [
			'errors' => $request->errors,
		], 422 );
	}

	/**
	 * Push all stock quantities to external site
	 */
	public function push_all() {
		check_ajax_referer( 'wss-push-all', 'security' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
      http_response_code( 403 );
      die( 'Permission denied' );
		}
		
		$site = wss_site_by_key( $_POST['site_key'] );
		$page = intval( $_POST['page'] );
		$limit = intval( $_POST['limit'] );

		if ( ! $site ) {
			http_response_code( 422 );
      die( 'Site not found' );
		}

		$request = new Woo_Stock_Sync_Api_Request();

		$query = wss_product_query( [
			'limit' => $limit,
			'page' => $page,
			'paginate' => true,
			'type' => wss_product_types( [ 'variation' ] ),
		] );

		$results = $query->get_products();

		$data = array_map( function( $product ) {
			return [
				'product' => $product,
				'operation' => 'set',
				'value' => $product->get_stock_quantity( 'edit' ),
			];
		}, $results->products );

		if ( $request->push_multiple( $data, $site ) ) {
			echo json_encode( [
				'status' => 'processed',
				'total' => $results->total,
				'pages' => $results->max_num_pages,
				'page' => $page,
				'last_page' => $results->max_num_pages == $page,
				'count' => count( $results->products ),
			] );

			die;
		} else if ( ! empty( $request->errors ) ) {
			$error_data = [
				'code' => __( 'N/A', 'woo-stock-sync' ),
				'headers' => [],
				'body' => __( 'N/A', 'woo-stock-sync' ),
			];
			if ( ! empty( $request->errors ) && $request->response ) {
				$error_data = wp_parse_args( [
					'code' => $request->response->getCode(),
					'headers' => $request->response->getHeaders(),
					'body' => $request->response->getBody(),
				], $error_data );
			}	

			echo json_encode( [
				'status' => 'error',
				'errors' => array_values( $request->errors ),
				'error_data' => $error_data,
			] );

			die;
		}

		http_response_code( 422 );

		echo json_encode( [
			'status' => 'error',
		] );
		die;
	}

	/**
	 * View last response for failed API check
	 */
	public function view_last_response() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			http_response_code( 403 );
			die( 'Permission denied' );
		}

		$data = get_option( 'wss_last_response', [] );
		$code = isset( $data['code'] ) ? $data['code'] : __( 'N/A', 'woo-stock-sync' );
		$body = isset( $data['body'] ) ? $data['body'] : __( 'N/A', 'woo-stock-sync' );
		$headers = isset( $data['headers'] ) ? $data['headers'] : false;

		include 'views/last-response.html.php';
		die;
	}
}

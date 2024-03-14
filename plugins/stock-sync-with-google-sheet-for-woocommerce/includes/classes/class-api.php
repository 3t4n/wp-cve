<?php
/**
 * Handles API requests for Stock Sync With Google Sheet For WooCommerce.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\API') ) {

	/**
	 * Handles API requests for Stock Sync With Google Sheet For WooCommerce.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class API extends Base {

		/**
		 * Contains an instance of this class, if available.
		 *
		 * @var API
		 */
		public static $instance = null;

		/**
		 * API init
		 */
		public static function init() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			self::$instance->register_routes();
		}

		/**
		 * Register API  routes
		 */
		public function register_routes() {
			add_action('rest_api_init', [ $this, 'add_api_routes' ]);
		}


		/**
		 * Add API routes
		 */
		public function add_api_routes() {

			$routes = [
				'action' => [
					'methods'             => [ 'GET', 'POST' ],
					'callback'            => [ $this, 'action_api_callback' ],
					'permission_callback' => [ $this, 'permission_callback' ],
				],

				'update' => [
					'methods'             => [ 'GET', 'POST' ],
					'callback'            => [ $this, 'update_api_callback' ],
					'permission_callback' => [ $this, 'permission_callback' ],
				],
			];

			foreach ( $routes as $route => $args ) {
				register_rest_route('ssgsw/v1', $route, $args);
			}
		}


		/**
		 * Check permission for API.
		 *
		 * @return bool
		 */
		public function permission_callback() {
			if ( defined('SSGSW_DEBUG') && SSGSW_DEBUG ) {
				return true;
			}

			$bearer2 = isset($_SERVER['HTTP_AUTHORIZATION']) ? sanitize_text_field( wp_unslash($_SERVER['HTTP_AUTHORIZATION'] ) ) : '';
			$bearer3 = isset($_SERVER['HTTP_SSGSWKEY']) ? sanitize_text_field( wp_unslash($_SERVER['HTTP_SSGSWKEY'] ) ) : '';
			$bearer = '';
			if ( empty($bearer3) ) {
				$bearer = $bearer2;
			}
			if ( empty($bearer2) ) {
				$bearer = $bearer3;
			}
			if ( empty($bearer) ) {
				return false;
			}

			$bearer      = str_replace('Bearer ', '', $bearer);
			$saved_token = ssgsw_get_option('token');

			if ( empty($saved_token) ) {
				return false;
			}

			if ( $bearer !== $saved_token ) {
				return false;
			}

			return true;
		}

		/**
		 * Responses for API
		 *
		 * @param bool  $success Success status.
		 * @param mixed $data Data to return.
		 * @return \WP_REST_Response
		 */
		public function response( $success = true, $data = null ) {
			$response = [
				'success' => $success,
			];

			if ( $data ) {
				if ( is_object($success) || is_array($success) ) {
					$response['data'] = $data;
				} else {
					$response['message'] = $data;
				}
			}

			return new \WP_REST_Response($response);
		}

		/**
		 * Action API callback. Which will call the action method.
		 *
		 * @param \WP_REST_Request $request Request.
		 * @return mixed Response.
		 */
		public function action_api_callback( $request ) {
			$params = $request->get_params();
			$action = $params['action'] ?? null;

			if ( ! $action ) {
				return $this->response( false, __('No action specified', 'stock-sync-with-google-sheet-for-woocommerce') );
			}

			$action = strtolower($action);

			if ( ! method_exists($this, 'action_' . $action) ) {
				return $this->response(
					false,
					__('Action not found', 'stock-sync-with-google-sheet-for-woocommerce')
				);
			}

			try {
				return $this->{'action_' . $action}($request);
			} catch ( \Exception $e ) {
				return $this->response(false, $e->getMessage());
			}
		}

		/**
		 * Callback for action sync.
		 *
		 * @param \WP_REST_Request $request Request.
		 * @return mixed Response.
		 */
		public function action_sync( $request ) {
			$params  = $request->get_params();
			$message = $params['message'] ?? __('Products synced successfully', 'stock-sync-with-google-sheet-for-woocommerce');

			try {
				$product = new Product();

				$response = $product->sync_all();

				if ( $response ) {
					return $this->response(
						true,
						$message
					);
				} else {
					return $this->response(
						false,
						__('Something went wrong', 'stock-sync-with-google-sheet-for-woocommerce')
					);
				}
			} catch ( \Exception $e ) {
				return $this->response(false, $e->getMessage());
			}
		}

		/**
		 * Callback for action update.
		 *
		 * @param \WP_REST_Request $request Request.
		 * @return mixed Response.
		 */
		public function update_api_callback( $request ) {
			$body     = $request->get_params();
			$products = $body['products'] ?? null;
			$message = $body['message'] ?? __('Product Create successfully', 'stock-sync-with-google-sheet-for-woocommerce');
			if ( $products && ( is_array($products) || is_object($products) ) && ! empty($products) ) {
				try {
					$product  = new Product();
					$response = $product->bulk_update($products);
					if ( $response ) {
						return $this->response( true, $message );
					} else {
						$new_message = __("You couldn't create a new product because the Add new products from Google Sheet feature is not enabled in your settings",'stock-sync-with-google-sheet-for-woocommerce');//phpcs:ignore
						return $this->response( true, $new_message );
					}
				} catch ( \Exception $e ) {
					$product = new Product();
					$response = $product->sync_all();
					return $this->response(false, $e->getMessage());
				}
			}
			return $this->response( false, __('No products specified', 'stock-sync-with-google-sheet-for-woocommerce') );
		}
	}
	/**
	 * API init
	 */
	API::init();
}

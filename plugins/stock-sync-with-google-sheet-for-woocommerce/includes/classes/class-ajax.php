<?php
/**
 * Handles ajax requests for Stock Sync With Google Sheet For WooCommerce admin.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */
// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( __NAMESPACE__ . '\Ajax') ) {

	/**
	 * Class Ajax.
	 * Handles ajax requests for Stock Sync With Google Sheet For WooCommerce admin.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class Ajax extends Base {

		/**
		 * Contains an instance of this class, if available.
		 *
		 * @var Ajax
		 */
		public static $instance;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since 1.0.0
		 */
		public static function init() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			self::$instance->add_ajax_actions();
		}


		/**
		 * Add ajax actions.
		 *
		 * @since 1.0.0
		 */
		public function add_ajax_actions() {
			$actions = [
				'update_options'       => [ $this, 'update_options_callback' ],
				'reset_options'        => [ $this, 'reset_options_callback' ],
				'init_sheet'           => [ $this, 'init_sheet_callback' ],
				'sync_sheet'           => [ $this, 'sync_sheet_callback' ],
				'reset_sheet'          => [ $this, 'reset_sheet_callback' ],
				'activate_woocommerce' => [ $this, 'activate_woocommerce_callback' ],
				'hide_upgrade_notice'  => [ $this, 'hide_notice_callback' ],
			];

			foreach ( $actions as $action => $callback ) {
				add_action('wp_ajax_' . SSGSW_PREFIX . $action, $callback);
			}
		}

		/**
		 * Save options callback
		 */
		public function update_options_callback() {

			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			/**
			 * Get body from request
			 */
			if ( ! isset($body->options) ) {
				$this->send_json(false, __('Options not set', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			$option_keys = array_keys($this->app->get_default_options());

			foreach ( $option_keys as $key ) {
				$value = $body->options[ $key ] ?? null;

				if ( isset($value) ) {
					ssgsw_update_option($key, $value);
				}
			}

			$this->send_json(true, __('Options saved', 'stock-sync-with-google-sheet-for-woocommerce'));
		}

		/**
		 * Reset options callback
		 */
		public function reset_options_callback() {
			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			$this->app->reset_options(true);

			$this->send_json(true, __('Options reset', 'stock-sync-with-google-sheet-for-woocommerce'));
		}

		/**
		 * Check sheet access callback
		 */
		public function init_sheet_callback() {
			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			try {

				$sheet = new Sheet();

				$sheet_initialized = $sheet->initialize();

				if ( $sheet_initialized ) {
					$this->send_json(true, __('Sheet initialized', 'stock-sync-with-google-sheet-for-woocommerce'));
				} else {
					ssgsw_update_option('setup_step', 3);
					$this->send_json(false, $sheet_initialized);
				}
			} catch ( \Throwable $e ) {
				error_log( $e->getMessage() );
				ssgsw_update_option('setup_step', 3);
				$this->send_json(false, $e->getMessage());
			}
		}

		/**
		 * Update sheet callback
		 */
		public function sync_sheet_callback() {
			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			$product = new Product();

			try {
				$update = $product->sync_all();

				if ( true === $update ) {
					$this->send_json(true, __('Sheet updated', 'stock-sync-with-google-sheet-for-woocommerce'));
				} else {
					$this->send_json(false, $update);
				}
			} catch ( \Exception $e ) {
				$this->send_json(false, $e->getMessage());
			}
		}

		/**
		 * Reset sheet callback
		 */
		public function reset_sheet_callback() {
			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			$sheet = new Sheet();
			$reset = $sheet->reset_sheet();

			$this->send_json(true, $reset);

			if ( true === $reset ) {
				$this->send_json(true, __('Sheet reset', 'stock-sync-with-google-sheet-for-woocommerce'));
			} else {
				$this->send_json(false, $reset);
			}
		}

		/**
		 * Activate WooCommerce callback
		 */
		public function activate_woocommerce_callback() {
			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			$this->app->activate_woocommerce();

			$this->send_json(true, __('WooCommerce activated', 'stock-sync-with-google-sheet-for-woocommerce'));
		}

		/**
		 * Hide notice callback
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function hide_notice_callback() {
			// Check nonce.
			$body = $this->get_body();
			if ( ! isset($body->nonce) || ! wp_verify_nonce($body->nonce, 'ssgsw_nonce') ) {
				$this->send_json(false, __('Invalid nonce', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			// Check permission.
			if ( ! current_user_can('manage_options') ) {
				$this->send_json(false, __('You do not have permission to do this', 'stock-sync-with-google-sheet-for-woocommerce'));
			}

			ssgsw_update_option('hide_upgrade_notice', true);

			$this->send_json(true, __('Notice hidden', 'stock-sync-with-google-sheet-for-woocommerce'));
		}
	}

	/**
	 * Initialize Ajax
	 */

	Ajax::init();
}

<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YITH_Pre_Order' ) ) {
	/**
	 * Class YITH_Pre_Order
	 * Plugin's main class
	 */
	class YITH_Pre_Order {

		/**
		 * Main Instance
		 *
		 * @var YITH_Pre_Order
		 * @since  1.0
		 */
		protected static $instance;

		/**
		 * Main Admin Instance. This is kept for code snippets compatibility.
		 *
		 * @var YITH_Pre_Order_Admin
		 * @since 1.0
		 */
		public $admin;

		/**
		 * Main Frontpage Instance. This is kept for code snippets compatibility.
		 *
		 * @var YITH_Pre_Order_Frontend
		 * @since 1.0
		 */
		public $frontend;

		/**
		 * Main My Account Instance. This is kept for code snippets compatibility.
		 *
		 * @var YITH_Pre_Order_My_Account
		 * @since 1.0
		 */
		public $myaccount;

		/**
		 * Main Download Links manager Instance. This is kept for code snippets compatibility.
		 *
		 * @var YITH_Pre_Order_Download_Links
		 * @since 1.3.0
		 */
		public $download_links;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_Pre_Order
		 * @since 2.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Calls get_instance() for backward compatibility
		 *
		 * @since 1.0.0
		 */
		public static function instance() {
			return self::get_instance();
		}

		/**
		 * Construct
		 *
		 * @since  1.0
		 */
		protected function __construct() {
			$this->init_includes();
			$this->init();

			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			add_action( 'init', array( $this, 'add_endpoints' ), 1 );
			add_action( 'init', array( $this, 'rewrite_rules' ), 22 );

			add_filter( 'woocommerce_email_classes', array( $this, 'register_email_classes' ) );
			add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', array( $this, 'orders_custom_query_var' ), 10, 2 );
		}

		/**
		 * Include main classes
		 */
		public function init_includes() {
			require_once YITH_WCPO_PATH . 'includes/functions-pre-order.php';

			if ( ywpo_is_admin() || defined( 'YITH_WCFM_PREMIUM' ) ) {
				require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order-admin.php';
				require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order-edit-product-page.php';
			}

			if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order-frontend.php';
				require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order-my-account.php';
			}

			require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order-product.php';
			require_once YITH_WCPO_PATH . 'includes/class-yith-pre-order-download-links.php';
		}

		/**
		 * Class Initialization
		 *
		 * Instance the admin or frontend classes
		 *
		 * @since  1.0
		 * @return void
		 * @access protected
		 */
		public function init() {
			if ( ywpo_is_admin() || defined( 'YITH_WCFM_PREMIUM' ) ) {
				$this->admin = YITH_Pre_Order_Admin();
				YITH_Pre_Order_Edit_Product_Page();
			}

			if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				$this->frontend  = YITH_Pre_Order_Frontend();
				$this->myaccount = YITH_Pre_Order_My_Account();
			}

			$this->download_links = YITH_Pre_Order_Download_Links();
		}

		/**
		 * Add waiting list account endpoints for WC 2.6
		 *
		 * @access public
		 */
		public function add_endpoints() {
			add_rewrite_endpoint( 'my-pre-orders', EP_ROOT | EP_PAGES );
		}

		/**
		 * Load plugin framework
		 *
		 * @since  1.0
		 * @return void
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Flush rewrite rules once.
		 */
		public function rewrite_rules() {
			$do_flush = get_option( 'yith-ywpo-flush-rewrite-rules', 1 );

			if ( $do_flush ) {
				update_option( 'yith-ywpo-flush-rewrite-rules', 0 );
				flush_rewrite_rules();
			}
		}

		/**
		 * Register the email classes included in the plugin.
		 *
		 * @param array $email_classes Array of available email classes.
		 *
		 * @return array
		 */
		public function register_email_classes( $email_classes ) {
			$email_classes['YITH_Pre_Order_Confirmed_Email']     = include YITH_WCPO_PATH . 'includes/emails/class-yith-pre-order-confirmed-email.php';
			$email_classes['YITH_Pre_Order_New_Pre_Order_Email'] = include YITH_WCPO_PATH . 'includes/emails/class-yith-pre-order-new-pre-order-email.php';

			return $email_classes;
		}

		/**
		 * Add custom order meta query.
		 *
		 * @param array           $query      WP_Query args.
		 * @param WC_Object_Query $query_vars The WC_Object_Query object.
		 *
		 * @return array
		 */
		public function orders_custom_query_var( $query, $query_vars ) {
			if ( ! empty( $query_vars['order_has_preorder'] ) ) {
				$query['meta_query'][] = array(
					'key'   => '_order_has_preorder',
					'value' => esc_attr( $query_vars['order_has_preorder'] ),
				);
			}

			if ( ! empty( $query_vars['_ywpo_status'] ) ) {
				$query['meta_query'][] = array(
					'key'   => '_ywpo_status',
					'value' => esc_attr( $query_vars['_ywpo_status'] ),
				);
			}

			return $query;
		}

		/**
		 * Check if a product has the pre-order status
		 *
		 * @since  2.0.0
		 *
		 * @param WC_Product|int $product The WC_Product object or product ID.
		 * @return bool
		 */
		public static function is_pre_order_product( $product ) {
			$return = false;

			// Use the WC product factory function to be sure $product is a WC_Product object.
			$product = wc_get_product( $product );

			if ( $product instanceof WC_Product ) {
				if ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) ) {
					if ( 'yes' === $product->get_meta( '_ywpo_preorder' ) ) {
						$return = true;
					}
				}
			}

			return apply_filters( 'ywpo_is_pre_order_product', $return, $product );
		}

		/**
		 * Check if a product can be displayed as pre-order in the shop.
		 *
		 * @since  2.0.0
		 *
		 * @param WC_Product|int $product The WC_Product object or product ID.
		 * @return bool
		 */
		public static function is_pre_order_active( $product ) {
			$return = false;

			// Use the WC product factory function to ensure that $product is a WC_Product object.
			$product = wc_get_product( $product );

			if ( $product instanceof WC_Product ) {
				if ( self::is_pre_order_product( $product ) && 'outofstock' !== $product->get_stock_status( 'edit' ) ) {
					$pre_order = ywpo_get_pre_order( $product );
					$timestamp = $pre_order->get_for_sale_date_timestamp();
					if (
						'yes' === get_option( 'yith_wcpo_enable_pre_order_purchasable', 'yes' ) &&
						'date' === $pre_order->get_availability_date_mode() &&
						$timestamp && $timestamp > 0 && time() > $pre_order->get_for_sale_date_timestamp()
					) {
						ywpo_reset_pre_order( $product );
						$return = false;
					} else {
						$return = true;
					}
				}
			}

			return apply_filters( 'ywpo_is_pre_order_active', $return, $product );
		}
	}
}

/**
 * Unique access to instance of YITH_Pre_Order class
 *
 * @return YITH_Pre_Order
 */
function YITH_Pre_Order() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_Pre_Order::get_instance();
}

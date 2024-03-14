<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


if ( ! class_exists( 'YITH_PayPal_EC_Admin' ) ) {
	/**
	 * Class YITH_PayPal_EC_Admin
	 */
	class YITH_PayPal_EC_Admin {

		/**
		 * Panel Object.
		 *
		 * @var $_panel
		 */
		protected $_panel; //phpcs:ignore

		/**
		 * Panel page slug.
		 *
		 * @var string
		 */
		protected $_panel_page = 'yith_paypal_ec_panel'; //phpcs:ignore

		/**
		 * Single instance of the class.
		 *
		 * @var \YITH_PayPal_EC_Admin
		 */
		protected static $instance;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_PayPal_EC_Admin
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
			add_action( 'yith_paypal_ec_settings_tab', array( $this, 'settings_tab' ) );

			// Custom styles and javascripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 20 );
			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_PAYPAL_EC_DIR . '/' . basename( YITH_PAYPAL_EC_FILE ) ), array( $this, 'action_links' ) );

			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

		}

		/**
		 * Enqueue Script and Styles.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_styles_scripts() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			if ( ! in_array( $screen_id, array( 'yith-plugins_page_' . $this->_panel_page, 'woocommerce_page_wc-settings' ), true ) ) {
				return;
			}

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'yith_paypal-ec_admin', YITH_PAYPAL_EC_ASSETS_URL . '/js/yith-paypal-ec-admin' . $suffix . '.js', array( 'jquery' ), YITH_PAYPAL_EC_VERSION, true );
		}

		/**
		 * Register subpanel for YITH PayPal EC into YI Plugins panel
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function register_panel() {
			$admin_tabs = apply_filters(
				'yith_paypal_ec_admin_panels',
				array(
					'settings' => __( 'Settings', 'yith-paypal-express-checkout-for-woocommerce' ),
				)
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => 'YITH PayPal Express Checkout for WooCommerce',
				'menu_title'       => 'PayPal Express Checkout',
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YITH_PAYPAL_EC_DIR . 'plugin-options',
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_PAYPAL_EC_DIR . 'plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Print custom tab of settings for Stripe subpanel
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function settings_tab() {
			$panel_template = YITH_PAYPAL_EC_DIR . '/templates/admin/settings-tab.php';

			if ( ! file_exists( $panel_template ) ) {
				return;
			}

			global $current_section;
			$current_section = 'yith-paypal-ec';

			WC_Admin_Settings::get_settings_pages();

			if ( ! empty( $_POST ) ) { //phpcs:ignore
				$gateways = WC()->payment_gateways()->payment_gateways();
				$gateways[ YITH_PayPal_EC::$gateway_id ]->process_admin_options();
			}

			include_once $panel_template;
		}

		/**
		 * Return the URL of setting panel.
		 *
		 * @return string
		 */
		public function get_setting_url() {
			return admin_url( 'admin.php?page=' . $this->_panel_page );
		}

		/**
		 * Action Links
		 *
		 * Add the action links to plugin admin page.
		 *
		 * @param array $links links plugin array.
		 *
		 * @return mixed
		 * @use      plugin_action_links_{$plugin_file_name}
		 * @since    1.0.0
		 */
		public function action_links( $links ) {

			if ( function_exists( 'yith_add_action_links' ) ) {
				$links = yith_add_action_links( $links, $this->_panel_page, false );
			}

			return $links;
		}

		/**
		 * Plugin_row_meta
		 *
		 * Add the action links to plugin admin page.
		 *
		 * @param array  $new_row_meta_args .
		 * @param array  $plugin_meta .
		 * @param string $plugin_file .
		 * @param array  $plugin_data .
		 * @param string $status .
		 * @param string $init_file .
		 *
		 * @return   array
		 * @since 1.0.0
		 * @use      plugin_row_meta
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_PAYPAL_EC_INIT' ) {
			if ( defined( 'YITH_PAYPAL_PAYMENTS_INIT' ) && YITH_PAYPAL_EC_INIT === $plugin_file ) {

				foreach ( $new_row_meta_args['to_show'] as $key => $value ) {
					if ( in_array( $value, array( 'support', 'live_demo', 'premium_version' ), true ) ) {
						unset( $new_row_meta_args['to_show'][ $key ] );
					}
				}
				$new_row_meta_args['slug']       = YITH_PAYPAL_EC_SLUG;
				$new_row_meta_args['is_premium'] = false;
			}

			return $new_row_meta_args;
		}
	}

	/**
	 * Unique access to instance of YITH_PayPal_EC_Admin class
	 *
	 * @return \YITH_PayPal_EC_Admin
	 */
	function YITH_PayPal_EC_Admin() { //phpcs:ignore
		return YITH_PayPal_EC_Admin::get_instance();
	}
}

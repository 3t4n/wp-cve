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

if ( ! class_exists( 'YITH_Pre_Order_Admin' ) ) {
	/**
	 * Class YITH_Pre_Order_Admin
	 */
	class YITH_Pre_Order_Admin {

		/**
		 * YIT_Plugin_Panel_WooCommerce instance.
		 *
		 * @var YIT_Plugin_Panel_WooCommerce object
		 */
		protected $panel = null;

		/**
		 * Panel page slug.
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_wcpo_panel';

		/**
		 * Show the premium landing page.
		 *
		 * @var bool
		 */
		public $show_premium_landing = true;

		/**
		 * Official plugin documentation URL.
		 *
		 * @var string
		 */
		protected $official_documentation = 'https://docs.yithemes.com/yith-woocommerce-pre-order/';

		/**
		 * Official plugin landing page URL.
		 *
		 * @var string
		 */
		protected $premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-pre-order/';

		/**
		 * Official plugin live demo page URL.
		 *
		 * @var string
		 */
		protected $premium_live = 'https://plugins.yithemes.com/yith-woocommerce-pre-order/';

		/**
		 * Single instance of the class YITH_Pre_Order_Admin.
		 *
		 * @var YITH_Pre_Order_Admin
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Single instance of the class YITH_Pre_Order_Edit_Product_Page for backward compatibility.
		 *
		 * @var YITH_Pre_Order_Edit_Product_Page $edit_product_page Edit product page object.
		 */
		public $edit_product_page;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_Pre_Order_Admin
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Construct
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->edit_product_page = YITH_Pre_Order_Edit_Product_Page();

			/* === Register Panel Settings === */
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
			/* === Premium Tab === */
			add_action( 'yith_ywpo_pre_order_premium_tab', array( $this, 'show_premium_landing' ) );

			/* === Show Plugin Information === */
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_WCPO_PATH . '/' . basename( YITH_WCPO_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );
			add_filter( 'woocommerce_locate_core_template', array( $this, 'locate_core_template' ), 10, 2 );
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$menu_title = 'Pre-Order';

			$admin_tabs = apply_filters(
				'yith_wcpo_admin_tabs',
				array(
					'general' => __( 'General options', 'yith-pre-order-for-woocommerce' ),
					'style'   => __( 'Style', 'yith-pre-order-for-woocommerce' ),
				)
			);

			$premium_tab = array(
				'landing_page_url' => $this->get_premium_landing_uri(),
				'premium_features' => array(
					// Translators: %1$s '<b>', %2$s '</b>'.
					sprintf( __( 'Automatically enable pre-order mode in %1$s out of stock products %2$s', 'yith-pre-order-for-woocommerce' ), '<b>', '</b>' ),
					// Translators: %1$s '<b>', %2$s '</b>'.
					sprintf( __( 'Ask users to %1$s pay a fee %2$s for each pre-order', 'yith-pre-order-for-woocommerce' ), '<b>', '</b>' ),
					__( 'Schedule the pre-order mode in specific products', 'yith-pre-order-for-woocommerce' ),
					// Translators: %1$s '<b>', %2$s '</b>'.
					sprintf( __( 'Set a %1$s dynamic availability date: %2$s the product becomes available X days after the pre-order', 'yith-pre-order-for-woocommerce' ), '<b>', '</b>' ),
					__( 'Manually charge pre-orders upon release, through the “Pay Later” option', 'yith-pre-order-for-woocommerce' ),
					// Translators: %1$s '<b>', %2$s '</b>'.
					sprintf( __( '%1$s Automatically charge customer\'s credit card %2$s upon pre-orders release (a supported payment gateway is required)', 'yith-pre-order-for-woocommerce' ), '<b>', '</b>' ),
					// Translators: %1$s '<b>', %2$s '</b>'.
					sprintf( __( 'Set the %1$s maximum quantity %2$s that can be ordered by the user per each pre-order product', 'yith-pre-order-for-woocommerce' ), '<b>', '</b>' ),
					__( 'Offer free shipping for pre-order products ', 'yith-pre-order-for-woocommerce' ),
					'<b>' . __( 'Regular updates, translations and premium support', 'yith-pre-order-for-woocommerce' ) . '</b>',
				),
				'main_image_url'   => YITH_WCPO_ASSETS_URL . 'images/get-premium-preorder.jpg',
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'plugin_slug'      => YITH_WCPO_SLUG,
				'premium_tab'      => $premium_tab,
				'page_title'       => 'YITH Pre-Order for WooCommerce',
				'menu_title'       => $menu_title,
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yith_plugin_panel',
				'page'             => $this->panel_page,
				'admin-tabs'       => $admin_tabs,
				'class'            => yith_set_wrapper_class(),
				'options-path'     => YITH_WCPO_OPTIONS_PATH,
				'is_free'          => defined( 'YITH_WCPO_FREE_INIT' ),
				'is_premium'       => defined( 'YITH_WCPO_PREMIUM' ),
				'is_extended'      => false,
			);

			/* === Fixed: not updated theme/old plugin framework  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_WCPO_PATH . '/plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @since    1.0
		 * @return void
		 */
		public function show_premium_landing() {
			if ( file_exists( YITH_WCPO_TEMPLATE_PATH . 'admin/premium_tab.php' ) && $this->show_premium_landing ) {
				require_once YITH_WCPO_TEMPLATE_PATH . 'admin/premium_tab.php';
			}
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return $this->premium_landing;
		}

		/**
		 * Action Links
		 *
		 * Add the action links to plugin admin page.
		 *
		 * @param array $links .
		 *
		 * @return mixed
		 * @use      plugin_action_links_{$plugin_file_name}
		 * @since    1.0
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, false, YITH_WCPO_SLUG );
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
		 * @use      plugin_row_meta
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WCPO_FREE_INIT' ) {
			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_WCPO_SLUG;
			}

			return $new_row_meta_args;
		}

		/**
		 * Locate core template file
		 *
		 * @param string $core_file Template full path.
		 * @param string $template Template in use.
		 *
		 * @return string
		 * @since  1.0.0
		 */
		public function locate_core_template( $core_file, $template ) {
			$custom_template = array(
				'emails/ywpo-email-admin-new-pre-order',
				'emails/ywpo-email-user-pre-order-confirmed.php',
			);

			if ( in_array( $template, $custom_template, true ) ) {
				$core_file = YITH_WCPO_TEMPLATE_PATH . $template;
			}

			return $core_file;
		}
	}
}

/**
 * Unique access to instance of YITH_Pre_Order_Admin class
 *
 * @return YITH_Pre_Order_Admin
 */
function YITH_Pre_Order_Admin() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_Pre_Order_Admin::get_instance();
}

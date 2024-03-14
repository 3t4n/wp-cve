<?php
/**
 * Plugin Name:       Woo Empty Cart Button
 * Plugin URI:        http://www.wpcodelibrary.com
 * Description:       This plugin is use for empty whole cart using single click.
 * Version:           1.4.0
 * Author:            WPCodelibrary
 * Author URI:        http://www.wpcodelibrary.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-empty-cart-button
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! class_exists( 'Woo_Empty_Cart_Button' ) ) {

	/**
	 * Plugin main class.
	 *
	 * @package Woo_Empty_Cart_Button
	 */
	class Woo_Empty_Cart_Button {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.3.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin public actions.
		 */
		private function __construct() {
			add_action( 'init', array( $this, 'wecb_load_plugin_textdomain' ) );
			add_action( 'woocommerce_after_cart_contents', array( $this, 'woo_empty_cart_button' ) );
			add_shortcode( 'wec_button', array( $this, 'wec_create_button_shortcode' ) );
			add_filter( 'widget_text', 'do_shortcode' );

			add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::wecb_add_settings_tab', 50 );
			add_action( 'woocommerce_settings_tabs_wecb_settings', __CLASS__ . '::wecb_settings_tab' );
			add_action( 'woocommerce_update_options_wecb_settings', __CLASS__ . '::wecb_update_settings' );
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public static function wecb_add_settings_tab( $settings_tabs ) {
			$settings_tabs['wecb_settings'] = __( 'Empty Cart Settings', 'woo-empty-cart-button' );

			return $settings_tabs;
		}

		public static function wecb_settings_tab() {
			woocommerce_admin_fields( self::wecb_get_settings() );
		}

		public static function wecb_update_settings() {
			woocommerce_update_options( self::wecb_get_settings() );
		}

		public static function wecb_get_settings() {
			$settings = array(
				'wecb_text' => array(
					'name'    => __( 'Empty Cart Text', 'woo-empty-cart-button' ),
					'type'    => 'text',
					'desc'    => __( 'Set your empty cart button text', 'woo-empty-cart-button' ),
					'id'      => 'wecb_text',
					'default' => 'Empty Cart',
				),

			);

			return apply_filters( 'wecb_get_settings', $settings );
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function wecb_load_plugin_textdomain() {
			load_plugin_textdomain( 'woo-empty-cart-button', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			global $woocommerce;
			if ( isset( $_REQUEST['empty-cart'] ) && $_REQUEST['empty-cart'] == 'clearcart' ) {
				$woocommerce->cart->empty_cart();
			}
		}

		/**
		 * Create empty cart button on cart page
		 */
		public function woo_empty_cart_button() {
			global $woocommerce;

			// Check for deprecation notices
			if ( version_compare( $woocommerce->version, '2.5', "<=" ) ) {
				$cart_url = $woocommerce->cart->get_cart_url();

			} else {
				$cart_url = wc_get_cart_url();
			}

			if (isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"])) {
				$cart_url = wc_get_cart_url().'?'.$_SERVER["QUERY_STRING"];
			}

			$getEmptytext = get_option( 'wecb_text' );
			$emptyTxt     = ! empty( $getEmptytext ) ? get_option( 'wecb_text' ) : 'Empty Cart';
			?>
			<tr>
				<td colspan="6" class="actions">
					<?php if ( empty( $_GET )  || $_SERVER["QUERY_STRING"] == null || empty($_SERVER["QUERY_STRING"]) ) { ?>
						<a class="button wecb_emptycart" href="<?php echo $cart_url; ?>?empty-cart=clearcart"><?php echo sprintf( __( '%s', 'woo-empty-cart-button' ), $emptyTxt ); ?></a>
					<?php } else { ?>
						<a class="button wecb_emptycart" href="<?php echo $cart_url; ?>&empty-cart=clearcart"><?php echo sprintf( __( '%s', 'woo-empty-cart-button' ), $emptyTxt ); ?></a>
					<?php } ?>
				</td>
			</tr>
			<?php
		}

		public function wec_create_button_shortcode() {
			global $woocommerce;

			// Check for deprecation notices
			if ( version_compare( $woocommerce->version, '2.5', "<=" ) ) {
				$cart_url = $woocommerce->cart->get_cart_url();

			} else {
				$cart_url = wc_get_cart_url();
			}

			$getEmptytext = get_option( 'wecb_text' );
			$emptyTxt     = ! empty( $getEmptytext ) ? $getEmptytext : 'Empty Cart';

			if ( empty( $_GET ) ) {
				return '<a class="button wecb_emptycart" href="' . $cart_url . '?empty-cart=clearcart">' . sprintf( __( '%s', 'woo-empty-cart-button' ), $emptyTxt ) . '</a>';
			} else {
				return '<a class="button wecb_emptycart" href="' . $cart_url . '?empty-cart=clearcart">' . sprintf( __( '%s', 'woo-empty-cart-button' ), $emptyTxt ) . '</a>';
			}
		}

	}

	add_action( 'plugins_loaded', array( 'Woo_Empty_Cart_Button', 'get_instance' ) );
}
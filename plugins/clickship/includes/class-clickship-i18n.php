<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://clickship.com
 * @since      1.0.0
 *
 * @package    Clickship
 * @subpackage Clickship/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Clickship
 * @subpackage Clickship/includes
 * @author     ClickShip <info@clickship.com>
 */
class Clickship_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'clickship',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
		/**
		 * Hook plugin classes into WP/WC core.
		 */
		/**
		 * Check if WooCommerce is active 
		 */
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action(
				'admin_notices',
				function() {
					/* translators: %s WC download URL link. */
					echo '<div class="error"><p><strong>' . sprintf( esc_html__('ClickShip requires the WooCommerce plugin to be installed and active. You can download %s here.', 'clickship' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
				}
			);
			return;
		}
		load_textdomain('clickship', dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'. 'clickship.pot');
	}
}
<?php
namespace PargoWp\Includes;


use PargoWp\Includes\Analytics;
use PargoWp\Includes\Pargo_Wp_Shipping_Method;

/**
 * Fired during plugin activation
 *
 * @link       pargo.co.za
 * @since      1.0.0
 *
 * @package    Pargo
 * @subpackage Pargo/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pargo
 * @subpackage Pargo/includes
 * @author     Pargo <support@pargo.co.za>
 */
class Pargo_Activator {

	/**
	 * Activate the plugin and do some housekeeping.
	 *
	 * Check if WooCommerce is installed otherwise notify the user.
     * Create new metadata for the shipping option endpoint based on the old API url.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$need = false;

        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_multisite() && is_plugin_active_for_network( plugin_basename(__FILE__) )  ) {
			$need = is_plugin_active_for_network('woocommerce/woocommerce.php') ? false : true;
		} else {
			$need =  is_plugin_active( 'woocommerce/woocommerce.php') ? false : true;
		}

		if ($need === true) {
			die(_e( 'Please install WooCommerce, it is required by the Pargo Plugin!', 'pargo' ));
		}

		$pargo_shipping_method = new Pargo_Wp_Shipping_Method();
		// Test to see if the user end point was staging or production and add the new endpoint
		$pargo_url = $pargo_shipping_method->get_option('pargo_url'); // Legacy method to get API endpoint on v2.5.*
		$pargo_url_endpoint = $pargo_shipping_method->get_option('pargo_url_endpoint');
		if (!$pargo_url_endpoint) {
			if (strpos($pargo_url, 'staging') !== false) {
				$pargo_shipping_method->update_option('pargo_url_endpoint', 'staging');
			} else {
				$pargo_shipping_method->update_option('pargo_url_endpoint', 'production');
			}
		}
		// Output the custom CSS if it exists in the database
		$styling = $pargo_shipping_method->get_option('pargo_custom_styling');
		if (!$styling) {
			$styling = $pargo_shipping_method->default_styling();
		}
		if ($styling) {
			$assets_dir = plugin_dir_path( __FILE__ ) . "../../assets";
			if (!file_exists($assets_dir . '/css')) {
				try {
					mkdir($assets_dir . '/css', 0755, true);
				} catch (Exception $e) {
					error_log($e);
					die(_e( 'Could not create assets css directory in Pargo plugin', 'pargo' ));
				}
			}
			$css_file_path = $assets_dir . "/css/pargo_wp.css";
			try {
				file_put_contents( $css_file_path, sanitize_text_field( $styling ) );
			} catch (Exception $e) {
				error_log($e);
				die(_e( 'Could not output existing stylesheet in Pargo plugin', 'pargo' ));
			}
		}
        Analytics::submit('client', 'click', 'plugin_enable');
    }
}

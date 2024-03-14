<?php
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 * @author     theDotstore <wordpress@multidots.in>
 */
class Woo_Hide_Shipping_Methods_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        if( get_transient( 'whsm-admin-notice' ) ) {
            delete_transient('whsm-admin-notice');
        }
	}

}

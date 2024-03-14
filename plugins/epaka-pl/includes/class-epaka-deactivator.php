<?php
if (!defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Fired during plugin deactivation
 *
 * @link       Epaka.pl
 * @since      1.0.0
 *
 * @package    Epaka
 * @subpackage Epaka/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Epaka
 * @subpackage Epaka/includes
 * @author     Epaka <bok@epaka.pl>
 */


class Epaka_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('epakaShippingCourierMapping');
		delete_option('epakaAdminToken');
		delete_option('epakaSession');
		delete_option('epakaE');
		delete_option('epakaP');

		$zones = WC_Shipping_Zones::get_zones();
		$shipping_zone = array_filter($zones, function($val){
			return $val['zone_name'] == "epaka.pl - Polska";
		});
		WC_Shipping_Zones::delete_zone(end($shipping_zone)['zone_id']);
	}

}

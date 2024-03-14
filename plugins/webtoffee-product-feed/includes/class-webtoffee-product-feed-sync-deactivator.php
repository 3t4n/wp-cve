<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/includes
 * @author     WebToffee <info@webtoffee.com>
 */
class Webtoffee_Product_Feed_Sync_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function deactivate() {
		delete_option('wt_pf_is_active');
	}
}

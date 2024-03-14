<?php

use platy\etsy\EtsySyncer;
use platy\etsy\EtsyDataService;
/**
 * Fired during plugin deactivation
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$data_service = EtsyDataService::get_instance();
		$shops = $data_service->get_shops();
		foreach($shops as $shop) {
			wp_clear_scheduled_hook(  'platy_etsy_orders_cron_hook', [$shop['id']]);
			wp_clear_scheduled_hook(  'platy_etsy_stock_cron_hook', [$shop['id']] );
		}	
		wp_clear_scheduled_hook(  'platy_etsy_clean_logs' );
	}

}

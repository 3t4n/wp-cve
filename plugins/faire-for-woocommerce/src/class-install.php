<?php
/**
 * Faire Install.
 *
 * @package  FAIRE
 */

namespace Faire\Wc;

use Faire\Wc\Admin\Settings;
use Faire\Wc\Sync\Sync_Order_Status;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Faire Install.
 */
class Install {

	/**
	 * Handler for activate plugin event.
	 */
	public static function on_plugin_activation() {
		// Sets the initial value for the last orders sync date to the date the
		// plugin was activated.
		$sync_order_status = new Sync_Order_Status( new Settings() );
		$sync_order_status->init_orders_last_sync_date();
	}

}

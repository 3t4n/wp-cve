<?php
/**
 * Setup our various cron events.
 *
 * Author:          Uriahs Victor
 * Created on:      30/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Bootstrap
 */

namespace Lpac_DPS\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SetupCron.
 *
 * @since 1.0.0
 */
class SetupCron {

	/**
	 * Create cron tasks that should be running on the website.
	 *
	 * Runs on admin init
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function set_cron_tasks() {
		// Remove passed dates from our list of maxed out dates. This is to prevent the option from getting too big.
		if ( ! wp_next_scheduled( 'lpac_dps_remove_passed_maxed_dates', array() ) ) {
			wp_schedule_event( time(), 'daily', 'lpac_dps_remove_passed_maxed_dates' );
		}
	}
}

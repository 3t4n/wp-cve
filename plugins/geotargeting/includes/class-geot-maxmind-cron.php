<?php

/**
 * Register Cron.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */
class GeotMaxmindCron {

	/**
	 * GeotMaxmindCron constructor.
	 */
	public function __construct() {
		add_filter( 'cron_schedules', [ self::class, 'register_schedules' ], 10, 1 );
	}

	/**
	 * Register our time schedule
	 *
	 * @param $schedules
	 *
	 * @return mixed
	 */
	static function register_schedules( $schedules ) {
		$schedules['geot_every_month'] = [
			'interval' => MONTH_IN_SECONDS,
			'display'  => __( 'Every Month', 'geot' )
		];

		return $schedules;
	}

}

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PMCS_Crons {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function __construct() {
	
			$this->update_intervals  = array(
				'daily',
			);
		
		add_action( 'init', array( $this, 'schedule_the_events' ) );
		add_action( 'admin_init', array( $this, 'schedule_the_events' ) );
		add_filter( 'cron_schedules', array( $this, 'cron_add_custom_intervals' ) );
	}

	/**
	 * On an early action hook, check if the hook is scheduled - if not, schedule it.
	 */
	public function schedule_the_events() {
		$selected_interval = get_option( 'pmsc_exchange_rate_update', 'manual' );
		if ( 'manual' !== $selected_interval ) {
			foreach ( $this->update_intervals as $index => $interval ) {
				$event_hook = 'pmcs_cron_update';
				$event_timestamp = wp_next_scheduled( $event_hook );
				if ( ! $event_timestamp && $selected_interval == $interval ) {
					$time = current_time( 'timestamp' );
					wp_schedule_event( $time, $selected_interval, $event_hook );
				} elseif ( $event_timestamp && $selected_interval !== $interval ) {
					wp_unschedule_event( $event_timestamp, $event_hook );
				}
			}
		}
	}

	/**
	 * Cron add custom intervals.
	 *
	 * @param array $schedules
	 * @return array
	 */
	public function cron_add_custom_intervals( $schedules ) {
	
		return $schedules;
	}
}


/**
 * Add crons action hook.
 */
add_action( 'pmcs_cron_update', 'pmcs_cron_update' );

/**
 * Call update currency rates
 *
 * @return void
 */
function pmcs_cron_update() {
	pmcs()->exchange_rates->update();
}


<?php
/**
 * Scheduler
 *
 * @author  YITH
 * @package YITH/Search/DataIndex
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Schedule index process
 *
 * @since 2.0.0
 */
class YITH_WCAS_Data_Index_Scheduler {

	use YITH_WCAS_Trait_Singleton;

	/**
	 * Constructor
	 */
	private function __construct() {

	}

	/**
	 * Schedule the action.
	 *
	 * @param   string $action          Action to schedule.
	 * @param   string $transient_name  Data scheduled.
	 * @param   string $group           Group.
	 *
	 * @return void
	 */
	public function schedule( $action, $transient_name, $group ) {
		if ( get_transient( $transient_name ) && ! as_has_scheduled_action( $action, array( 'chunk' => $transient_name ), $group ) ) {
			as_enqueue_async_action( $action, array( 'chunk' => $transient_name ), $group );
		}
	}

	/**
	 * Schedule the indexing process action
	 *
	 * @param   string $action  Action to schedule.
	 * @return void
	 */
	public function schedule_index( $action ) {
		$interval = 'daily' === ywcas()->settings->get_schedule_indexing_interval() ? 1 : 7;
		$time     = ywcas()->settings->get_schedule_indexing_time();

		if ( ! as_has_scheduled_action( $action ) ) {
			as_schedule_recurring_action( strtotime( $time . ':00:00' ), $interval * DAY_IN_SECONDS, $action );
		}
	}

	/**
	 * Un-schedule remove the action scheduled and transient
	 *
	 * @param   string $action   Hook to remove.
	 * @param   string $group  Group to remove.
	 *
	 * @since 2.0.0
	 */
	public function unschedule( $action, $group = '' ) {
		$list_of_actions = as_get_scheduled_actions(
			array(
				'hook'  => $action,
				'group' => $group,
			)
		);
		if ( $list_of_actions ) {
			foreach ( $list_of_actions as $single_action ) {
				$args = $single_action->get_args();
				if ( isset( $args['chunk'] ) ) {
					delete_transient( $args['chunk'] );
				}
			}

			as_unschedule_all_actions( $single_action->get_hook() );
		}
	}

}

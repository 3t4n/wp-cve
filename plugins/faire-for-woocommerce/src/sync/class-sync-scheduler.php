<?php
/**
 * Sync Scheduler.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use ActionScheduler_Action;

/**
 * Sync Scheduler class.
 *
 * Based on the WooCommerce Action Scheduler API. See https://bit.ly/3yilXSB
 */
class Sync_Scheduler {

	/**
	 * The ID of the scheduled action.
	 *
	 * @var int
	 */
	public int $id;

	/**
	 * The action to execute on schedule.
	 *
	 * @var callable
	 */
	public $action;

	/**
	 * The hook triggering the scheduled action.
	 *
	 * @var string
	 */
	public string $hook;

	/**
	 * The arguments for the scheduled action.
	 *
	 * @var array
	 */
	public array $action_args = array();

	/**
	 * The scheduled interval in seconds.
	 *
	 * @var int
	 */
	public int $interval = 0;

	/**
	 * The group for the scheduled actions.
	 *
	 * @var string
	 */
	public string $group = '';

	/**
	 * A minute in seconds.
	 *
	 * @var int
	 */
	const MINUTE_IN_SECONDS = 60;

	/**
	 * Name of the group for scheduled actions.
	 *
	 * @var string
	 */
	const FAIRE_SCHEDULER_ACTIONS_GROUP = 'faire_scheduled_actions_';

	/**
	 * Class constructor.
	 *
	 * @param string   $job_name Job name to suffix the scheduled hook.
	 * @param callable $action   Action to execute on hook triggering.
	 * @param array    $args     Arguments for the action.
	 */
	public function __construct(
		string $job_name,
		callable $action,
		array $args
	) {
		$this->action      = $action;
		$this->hook        = 'faire_scheduler_hook_' . $job_name;
		$this->action_args = $args;

		add_action( $this->hook, $this->action );
	}

	/**
	 * Runs a recurrent job at given intervals.
	 *
	 * @param int $interval Recurrence interval in seconds.
	 *
	 * @return string The hook of the scheduled job.
	 */
	public function start_recurrent_job( int $interval ): string {
		$this->interval = $interval;
		$this->group    = self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'recurrent';

		$this->id = as_schedule_recurring_action(
			(int) wp_date( 'U' ) + $this->interval,
			$this->interval,
			$this->hook,
			$this->action_args,
			$this->group
		);

		return $this->hook;
	}

	/**
	 * Runs a single time async action, optionally a number of seconds later.
	 *
	 * If not delay time is given, the action will run as soon as possible.
	 *
	 * @param int $seconds_later Time in seconds to delay the execution of the action.
	 *
	 * @return string The hook of the scheduled job.
	 */
	public function start_once_job( int $seconds_later = 0 ): string {
		$this->group = self::FAIRE_SCHEDULER_ACTIONS_GROUP . 'single_time';

		$this->id = $seconds_later ?
			as_schedule_single_action(
				(int) wp_date( 'U' ) + $seconds_later,
				$this->hook,
				$this->action_args,
				$this->group
			) :
			as_enqueue_async_action(
				$this->hook,
				$this->action_args,
				$this->group
			);

		return $this->hook;
	}

	/**
	 * Cancels all occurrences of the scheduled action.
	 */
	public function cancel_job() {
		remove_action( $this->hook, $this->action );
		as_unschedule_all_actions( $this->hook, $this->action_args, $this->group );
	}

	/**
	 * Returns a filtered list of currently scheduled actions.
	 *
	 * Filters can be applied. See https://bit.ly/3LPQJpT
	 *
	 * @param array $filters Filters to apply to the actions list.
	 *
	 * @return array List of scheduled actions.
	 */
	public function get_jobs( array $filters ): array {
		return as_get_scheduled_actions( $filters );
	}

	/**
	 * Returns the recurrence interval of a scheduled job.
	 *
	 * @param ActionScheduler_Action $job The scheduled job.
	 *
	 * @return int The job recurrence interval.
	 */
	public function get_job_interval( ActionScheduler_Action $job ): int {
		return $job->get_schedule()->get_recurrence();
	}

}

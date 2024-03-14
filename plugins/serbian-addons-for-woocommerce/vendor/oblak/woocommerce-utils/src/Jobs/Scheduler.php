<?php
/**
 * Scheduler_Trait class file.
 *
 * @package WooCommerce Utils
 * @subpackage Jobs
 */

namespace Oblak\WooCommerce\Jobs;

use ActionScheduler;
use ActionScheduler_Action;
use DateTime;
use WC_Queue_Interface;

/**
 * Enables easy schedule handling for importer related jobs
 */
trait Scheduler {
    /**
     * Action name
     *
     * @var string|null
     */
    public ?string $name = null;

	/**
	 * Action scheduler group.
	 *
	 * @var string
	 */
	protected string $group = '';

	/**
	 * Queue instance.
	 *
	 * @var WC_Queue_Interface
	 */
	protected static $queue = null;

    /**
     * Initialize scheduler.
     */
    public function scheduler_init() {
        $this->onetime_init();
        $this->recurring_init();
    }

	/**
	 * Add one time actions
	 */
	public function onetime_init() {
		foreach ( $this->get_actions() as $action_name => $action_hook ) {
			\add_action(
                $action_hook,
                array( $this, 'do_action_or_reschedule' ),
                10,
                ( new \ReflectionMethod( $this::class, $action_name ) )->getNumberOfParameters(),
            );
		}
	}

    /**
     * Add recurring actions
     */
    public function recurring_init() {
        foreach ( $this->get_recurring_actions() as $action_name => $options ) {
            $hook = $this->get_action( $action_name );

            if ( ! $hook ) {
                continue;
            }

            $options      = \wp_parse_args(
                \array_map(
                    static fn( $arg ) => \is_callable( $arg ) ? $arg() : $arg,
                    $options ?? array(),
                ),
                $this->get_default_recurring_job_args( $action_name ),
            );
            $has_existing = $this->has_existing_jobs( $action_name, $options['args'] );

            if ( $options['enabled'] ) {
                ! $has_existing && self::queue()->schedule_recurring(
                    $options['timestamp'],
                    $options['interval'],
                    $hook,
                    $options['args'],
                    $this->group,
                );
            } else {
                $has_existing && self::queue()->cancel_all( $hook, $options['args'], $this->group );
            }
        }
    }

	/**
	 * Get queue instance.
	 *
	 * @return WC_Queue_Interface
	 */
	public static function queue(): WC_Queue_Interface {
        return self::$queue ??= \WC()->queue();
	}

	/**
	 * Set queue instance.
	 *
	 * @param WC_Queue_Interface $queue Queue instance.
	 */
	public function set_queue( WC_Queue_Interface $queue ) {
		self::$queue = $queue;
	}

	/**
	 * Gets the default scheduler actions for batching and scheduling actions.
	 */
	public function get_default_scheduler_actions() {
        $prefix  = '' === $this->group ? $this->group . '_' : '';
        $actions = array( 'schedule_action', 'queue_batches' );

        return \array_combine(
            $actions,
            \array_map(
                fn( $action ) => $prefix . $action . '_' . $this->name,
                $actions,
            ),
        );
	}

	/**
	 * Gets the actions for this specific scheduler.
	 *
	 * @return array<string, string>
	 */
	abstract public function get_scheduler_actions(): array;

    /**
     * Get recurring actions with their schedule.
     *
     * @return array<string, array{enabled: bool|callable(): bool, args: array|callable(): array, timestamp: int|callable(): int, interval: int|callable(): int}>
     */
    public function get_recurring_actions(): array {
        return array();
    }

    /**
     * Get default arguments for a recurring job.
     *
     * @param  string $action Action name.
     * @return array          Default arguments.
     */
    protected function get_default_recurring_job_args( string $action ): array {
        $args = array(
            'args'      => array(),
            'enabled'   => true,
            'interval'  => 15 * MINUTE_IN_SECONDS,
            'timestamp' => \strtotime( '+ 15 minutes' ),
        );

        //phpcs:ignore WooCommerce.Commenting
        return \apply_filters( 'woosync_recurring_job_default_args', $args, $this->name, $action );
    }

	/**
	 * Get all available scheduling actions.
	 * Used to determine action hook names and clear events.
	 */
	public function get_actions() {
		return \array_merge(
			$this->get_default_scheduler_actions(),
			$this->get_scheduler_actions(),
		);
	}

	/**
	 * Get an action tag name from the action name.
	 *
	 * @param string $action_name The action name.
	 * @return string|null
	 */
	public function get_action( $action_name ) {
		return $this->get_actions()[ $action_name ] ?? null;
	}

	/**
	 * Returns an array of actions and dependencies as key => value pairs.
	 *
	 * @return array
	 */
	public function get_dependencies() {
		return array();
	}

	/**
	 * Get dependencies associated with an action.
	 *
	 * @param string $action_name The action slug.
	 * @return string|null
	 */
	public function get_dependency( $action_name ) {
		$dependencies = $this->get_dependencies();
		return $dependencies[ $action_name ] ?? null;
	}

	/**
	 * Batch action size.
	 */
	public function get_batch_sizes() {
		return array(
			'queue_batches' => 100,
		);
	}

	/**
	 * Returns the batch size for an action.
	 *
	 * @param string $action Single batch action name.
	 * @return int Batch size.
	 */
	public function get_batch_size( $action ) {
		$batch_sizes = $this->get_batch_sizes();
		$batch_size  = $batch_sizes[ $action ] ?? 25;

		/**
		 * Filter the batch size for regenerating a report table.
		 *
		 * @param int    $batch_size Batch size.
         * @param string $scheduler_name Scheduler name.
		 * @param string $action Batch action name.
         * @return int
         *
         * @since 8.1.0
		 */
		return \apply_filters( 'woosync_scheduled_job_batch_size', $batch_size, $this->name, $action );
	}

	/**
	 * Flatten multidimensional arrays to store for scheduling.
	 *
	 * @param array $args Argument array.
	 * @return string
	 */
	public function flatten_args( $args ) {
		$flattened = array();

		foreach ( $args as $arg ) {
			$flattened[] = \is_array( $arg ) ? $this->flatten_args( $arg ) : $arg;
		}

		$string = '[' . \implode( ',', $flattened ) . ']';
		return $string;
	}

	/**
	 * Check if existing jobs exist for an action and arguments.
	 *
	 * @param string $action_name Action name.
	 * @param array  $args Array of arguments to pass to action.
	 * @return bool
	 */
	public function has_existing_jobs( $action_name, $args ) {
		$existing_jobs = self::queue()->search(
			array(
                'claimed'  => false,
                'group'    => $this->group,
                'hook'     => $this->get_action( $action_name ),
                'per_page' => 1,
                'search'   => $this->flatten_args( $args ),
                'status'   => 'pending',
			),
		);

		if ( $existing_jobs ) {
			$existing_job = \current( $existing_jobs );

			// Bail out if there's a pending single action, or a pending scheduled actions.
			if (
				( $this->get_action( $action_name ) === $existing_job->get_hook() ) ||
				(
					$this->get_action( 'schedule_action' ) === $existing_job->get_hook() &&
					\in_array( $this->get_action( $action_name ), $existing_job->get_args(), true )
				)
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the next blocking job for an action.
	 *
	 * @param string $action_name Action name.
	 * @return false|ActionScheduler_Action
	 */
	public function get_next_blocking_job( $action_name ) {
		$dependency = $this->get_dependency( $action_name );

		if ( ! $dependency ) {
			return false;
		}

		$blocking_jobs = self::queue()->search(
			array(
                'group'    => $this->group,
                'order'    => 'DESC',
                'orderby'  => 'date',
                'per_page' => 1,
                'search'   => $dependency, // search is used instead of hook to find queued batch creation.
                'status'   => 'pending',
			),
		);

		$next_job_schedule = null;

		if ( \is_array( $blocking_jobs ) ) {
			foreach ( $blocking_jobs as $blocking_job ) {
				$next_job_schedule = $this->get_next_action_time( $blocking_job );

				// Ensure that the next schedule is a DateTime (it can be null).
				if ( \is_a( $next_job_schedule, 'DateTime' ) ) {
					return $blocking_job;
				}
			}
		}

		return false;
	}

	/**
	 * Check for blocking jobs and reschedule if any exist.
	 */
	public function do_action_or_reschedule() {
		$action_hook = \current_action();
		$action_name = \array_search( $action_hook, $this->get_actions(), true );
		$args        = \func_get_args();

		// Check if any blocking jobs exist and schedule after they've completed
		// or schedule to run now if no blocking jobs exist.
		$blocking_job = $this->get_next_blocking_job( $action_name );
		if ( $blocking_job ) {
			$after = new \DateTime();
			self::queue()->schedule_single(
				$this->get_next_action_time( $blocking_job )->getTimestamp() + 5,
				$action_hook,
				$args,
				$this->group,
			);
		} else {
			\call_user_func_array( array( $this, $action_name ), $args );
		}
	}

	/**
	 * Get the DateTime for the next scheduled time an action should run.
	 * This function allows backwards compatibility with Action Scheduler < v3.0.
	 *
	 * @param  ActionScheduler_Action $action Action.
	 * @return DateTime|null
	 */
	public function get_next_action_time( $action ) {
        return $action->get_schedule()->next();
	}

	/**
	 * Schedule an action to run and check for dependencies.
	 *
	 * @param string $action_name Action name.
	 * @param array  $args Array of arguments to pass to action.
	 */
	public function schedule_action( $action_name, $args = array() ) {
		// Check for existing jobs and bail if they already exist.
		if ( $this->has_existing_jobs( $action_name, $args ) ) {
			return;
		}

		$action_hook = $this->get_action( $action_name );
		if ( ! $action_hook ) {
			return;
		}

		self::queue()->schedule_single( \time() + 5, $action_hook, $args, $this->group );
	}

	/**
	 * Queue a large number of batch jobs, respecting the batch size limit.
	 * Reduces a range of batches down to "single batch" jobs.
	 *
	 * @param int    $range_start Starting batch number.
	 * @param int    $range_end Ending batch number.
	 * @param string $single_batch_action Action to schedule for a single batch.
	 * @param array  $action_args Action arguments.
	 * @return void
	 */
	public function queue_batches( $range_start, $range_end, $single_batch_action, $action_args = array() ) {
		$batch_size       = $this->get_batch_size( 'queue_batches' );
		$range_size       = 1 + $range_end - $range_start;
		$action_timestamp = \time() + 5;

		if ( $range_size > $batch_size ) {
			// If the current batch range is larger than a single batch,
			// split the range into $queue_batch_size chunks.
			$chunk_size = (int) \ceil( $range_size / $batch_size );

			for ( $i = 0; $i < $batch_size; $i++ ) {
				$batch_start = (int) ( $range_start + ( $i * $chunk_size ) );
				$batch_end   = (int) \min( $range_end, $range_start + ( $chunk_size * ( $i + 1 ) ) - 1 );

				if ( $batch_start > $range_end ) {
					return;
				}

				$this->schedule_action(
					'queue_batches',
					array( $batch_start, $batch_end, $single_batch_action, $action_args ),
				);
			}
		} else {
			// Otherwise, queue the single batches.
			for ( $i = $range_start; $i <= $range_end; $i++ ) {
				$batch_action_args = \array_merge( array( $i ), $action_args );
				$this->schedule_action( $single_batch_action, $batch_action_args );
			}
		}
	}

	/**
	 * Clears all queued actions.
	 */
	public function clear_queued_actions() {
        ActionScheduler::store()->cancel_actions_by_group( $this->group );
	}
}

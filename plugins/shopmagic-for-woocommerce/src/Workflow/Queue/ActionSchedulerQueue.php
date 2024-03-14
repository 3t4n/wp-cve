<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Queue;

use ActionScheduler;
use ActionScheduler_Action;
use DateTimeImmutable;
use DateTimeInterface;

final class ActionSchedulerQueue implements Queue {
	public const HOOK  = 'shopmagic/core/queue/execute';
	public const GROUP = 'shopmagic-automation';

	/**
	 * Enqueue an action to run one time, as soon as possible
	 *
	 * @param string $hook The hook to trigger.
	 * @param array  $args Arguments to pass when the hook triggers.
	 * @return int The action ID.
	 */
	public function add( string $hook = self::HOOK, array $args = [], string $group = self::GROUP ): int {
		return $this->schedule( new DateTimeImmutable(), $hook, $args, $group );
	}

	public function schedule( DateTimeInterface $time, string $hook = self::HOOK, array $args = [], string $group = self::GROUP ): int {
		return as_schedule_single_action( $time->getTimestamp(), $hook, $args, $group );
	}

	public function cancel( int $action_id ): void {
		if ( ! ActionScheduler::is_initialized() ) {
			return;
		}

		ActionScheduler::store()->cancel_action( $action_id );
	}

	public function cancel_all( string $hook, array $args = [], string $group = self::GROUP ): void {
		as_unschedule_all_actions( $hook, $args, $group );
	}

	/**
	 * @param array<string, string|int> $args
	 *
	 * @return ActionScheduler_Action[]
	 */
	public function search( array $args = [] ): array {
		return as_get_scheduled_actions( $args, OBJECT );
	}
}

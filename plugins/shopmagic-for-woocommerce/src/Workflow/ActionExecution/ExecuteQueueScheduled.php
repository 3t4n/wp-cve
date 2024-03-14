<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\ActionExecution;

use DateTimeImmutable;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Queue\ActionSchedulerQueue;
use WPDesk\ShopMagic\Workflow\Queue\Queue;

/**
 * Execute action at a given time. Use scheduled queue to defer.
 */
final class ExecuteQueueScheduled implements ExecutionStrategy {

	/** @var Queue */
	private $queue_client;

	/** @var DateTimeImmutable */
	private $scheduled_time;

	public function __construct( Queue $queue_client, $scheduled_time ) {
		$this->queue_client   = $queue_client;
		$this->scheduled_time = ( new DateTimeImmutable() )->setTimestamp( $scheduled_time );
	}

	public function execute( Automation $automation, Event $event, Action $action, int $action_index, string $unique_id ): void {
		$this->queue_client->schedule(
			$this->scheduled_time,
			ActionSchedulerQueue::HOOK,
			[ $automation, $event, $action, $action_index, $unique_id, $event->get_provided_data() ]
		);
	}
}

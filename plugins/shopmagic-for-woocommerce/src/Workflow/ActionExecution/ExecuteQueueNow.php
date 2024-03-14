<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\ActionExecution;

use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Queue\ActionSchedulerQueue;
use WPDesk\ShopMagic\Workflow\Queue\Queue;

/**
 * Execute action asap but use queue client to spread the load.
 */
final class ExecuteQueueNow implements ExecutionStrategy {

	/** @var Queue */
	private $queue_client;

	public function __construct( Queue $queue_client ) {
		$this->queue_client = $queue_client;
	}

	public function execute( Automation $automation, Event $event, Action $action, int $action_index, string $unique_id ): void {
		$this->queue_client->add(
			ActionSchedulerQueue::HOOK,
			[ $automation, $event, $action, $action_index, $unique_id ]
		);
	}
}

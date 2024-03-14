<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator;

use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecuteNow;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecuteQueueNow;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionStrategy;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeLogger;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholderProcessor;
use WPDesk\ShopMagic\Workflow\Queue\Queue;


/**
 * Default executor based on ActionScheduler queue.
 * Should be always attached in ExecutionStrategyContainer.
 */
final class QueueExecutionCreator implements ExecutionCreator {

	/** @var Queue */
	private $queue_client;

	/**
	 * @var PlaceholderProcessor
	 */
	private $processor;

	/** @var OutcomeSaver */
	private $outcome_saver;

	/** @var OutcomeLogger */
	private $outcome_logger;

	public function __construct(
		PlaceholderProcessor $processor,
		OutcomeSaver $outcome_saver,
		OutcomeLogger $outcome_logger,
		Queue $queue
	) {
		$this->queue_client = $queue;
		$this->processor    = $processor;
		$this->outcome_saver = $outcome_saver;
		$this->outcome_logger = $outcome_logger;
	}

	public function create_executor( Automation $automation, Event $event, Action $action ): ExecutionStrategy {
		if ( $this->should_avoid_queue( $automation, $event, $action ) ) {
			return new ExecuteNow( $this->processor, $this->outcome_saver, $this->outcome_logger );
		}

		return new ExecuteQueueNow( $this->queue_client );
	}

	private function should_avoid_queue( Automation $automation, Event $event, Action $action ): bool {
		/**
		 * @param bool       $avoid_queue
		 * @param Automation $automation
		 * @param Event      $event
		 * @param Action     $action
		 * @param int        $action_index
		 *
		 * @return bool
		 */
		return (bool) apply_filters( 'shopmagic/core/queue/avoid_queue', false, $automation, $event, $action, 0 );
	}


	public function should_create( Action $action ): bool {
		return true;
	}
}

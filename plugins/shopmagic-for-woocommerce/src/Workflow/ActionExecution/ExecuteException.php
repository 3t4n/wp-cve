<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\ActionExecution;

use Throwable;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;

/**
 * Used when action for some reason shouldn't execute.
 */
final class ExecuteException implements ExecutionStrategy {

	/** @var Throwable */
	private $throwable;

	/** @var OutcomeSaver */
	private $outcome_saver;

	public function __construct(
		Throwable $throwable,
		OutcomeSaver $outcome_saver
	) {
		$this->throwable     = $throwable;
		$this->outcome_saver = $outcome_saver;
	}

	public function execute( Automation $automation, Event $event, Action $action, int $action_index, string $unique_id ): void {
		$this->outcome_saver->update_result(
			$unique_id,
			false,
			sprintf( 'error: %s', $this->throwable->getMessage() )
		);
	}
}

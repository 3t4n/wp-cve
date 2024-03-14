<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\ActionExecution;

use WPDesk\ShopMagic\Exception\ActionDisabledAfterStatusRecheckException;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;

/**
 * Specifics how to execute an action.
 *
 * @TODO: in 3.0 split into abstraction and stable packages
 * @note Actually, this is more like an Executor than execution strategy. Additionally, it needs as much as AutomationRunner and Action to execute properly.
 *       Have that in mind when refactoring in 3.0 version. It does specify *how to execute* action, yet only by sending it to the external library (Action Scheduler),
 *       so in case of our app it does finite piece of work.
 */
interface ExecutionStrategy {
	/**
	 * Execution is hitting ->execute method in action so in theory it is simple.
	 * But execution can be deferred using various tools or depends on additional variables.
	 *
	 * @param int $action_index Index action in automation. Required to properly connect the action with automation.
	 * @param string $unique_id Unique id of the execution. Is required to track what is going on with a single execution.
	 * @return void
	 *
	 * @throws ActionDisabledAfterStatusRecheckException If event supports SupportsDeferredCheck this exception can be thrown.
	 */
	public function execute( Automation $automation, Event $event, Action $action, int $action_index, string $unique_id );
}

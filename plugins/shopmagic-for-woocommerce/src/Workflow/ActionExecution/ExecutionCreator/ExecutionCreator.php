<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator;

use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionStrategy;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;

/**
 * Can create various executors from ExecutionStrategy
 *
 * @since 2.34
 */
interface ExecutionCreator {

	public function create_executor( Automation $automation, Event $event, Action $action ): ExecutionStrategy;

	public function should_create( Action $action ): bool;
}

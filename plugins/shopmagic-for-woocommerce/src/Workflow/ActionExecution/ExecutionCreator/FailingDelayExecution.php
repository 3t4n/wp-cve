<?php

declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator;

use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecuteException;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionStrategy;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;

/**
 * If action consist configuration from ShopMagic Delayed Actions
 * extension, but the plugin is not active, prevent execution. By this
 * way make safe to use, when delay functionality is unintentionally
 * missing
 */
final class FailingDelayExecution implements ExecutionCreator {

	/** @var OutcomeSaver */
	private $outcome_saver;

	public function __construct( OutcomeSaver $outcome_saver ) {
		$this->outcome_saver = $outcome_saver;
	}

	public function create_executor( Automation $automation, Event $event, Action $action ): ExecutionStrategy {
		return new ExecuteException(
			new \RuntimeException(
				__( 'Action execution prevented because action contains delay configuration, but ShopMagic Delayed Actions extension is not active.', 'shopmagic-for-woocommerce' )
			),
			$this->outcome_saver
		);
	}

	public function should_create( Action $action ): bool {
		return $action->get_parameters()->has( '_action_delayed' ) &&
			$action->get_parameters()->getBoolean( '_action_delayed' ) &&
			$action->get_parameters()->has( '_action_schedule_type' ) &&
			! WordPressPluggableHelper::is_plugin_active( 'shopmagic-delayed-actions/shopmagic-delayed-actions.php' );
	}
}

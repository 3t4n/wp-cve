<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator;

use WPDesk\ShopMagic\Exception\NoExecutionCreatorFound;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionStrategy;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\Event;

/**
 * Handles multiple ExecutionStrategies in an extensible way.
 *
 * Old methods remains stubbed for interface backward compatibility.
 */
final class ExecutionCreatorContainer implements ExecutionCreator {

	/** @var ExecutionCreator[] */
	private $creators = [];

	public function add_execution_creator( ExecutionCreator $creator ): void {
		$this->creators[] = $creator;
	}

	/**
	 * Fire attached executors in reversed order. This way it's easier for external ExecutionCreator to hook and override execution behavior.
	 * Possibly, that should leave core default executor untouched as last executed element.
	 *
	 * @throws NoExecutionCreatorFound
	 */
	public function create_executor( Automation $automation, Event $event, Action $action ): ExecutionStrategy {
		if ( ! $this->should_create( $action ) ) {
			throw new NoExecutionCreatorFound( self::class . ' needs at least one ExecutionCreator attached through ' . self::class . '::add_execution_creator method.' );
		}

		foreach ( array_reverse( $this->creators ) as $creator ) {
			if ( $creator->should_create( $action ) ) {
				return $creator->create_executor( $automation, $event, $action );
			}
		}

		throw new NoExecutionCreatorFound( 'No valid ExecutionCreator found. Possibly, each attached creator returned `false` in ExecutionCreator::should_create().' );
	}

	public function should_create( Action $action ): bool {
		return ! empty( $this->creators );
	}

	/** @internal For testing purposes only.
	 * @return ExecutionCreator[] */
	public function get_creators(): array {
		return $this->creators;
	}

}

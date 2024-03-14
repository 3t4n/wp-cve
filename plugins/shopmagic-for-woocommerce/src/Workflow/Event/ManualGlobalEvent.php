<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

/**
 * Interface for all events that can be manually triggered at will and all of them should be triggered together.
 *
 * @see ManualEvent
 */
interface ManualGlobalEvent {
	/**
	 * Fires an event.
	 *
	 * @param array $args Trigger arguments ie. [ $order ]
	 *
	 * @return void
	 */
	public static function trigger( array $args): void;
}

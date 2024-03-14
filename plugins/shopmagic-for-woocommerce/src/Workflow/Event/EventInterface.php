<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

interface EventInterface {

	/**
	 * Warmup event if needed and register for event listener.
	 * Most of the time in this method Event should enqueue itself to WordPress hook.
	 * This method should be used along with hook callback and Event::trigger_automation().
	 *
	 * @return void
	 */
	public function initialize(): void;

	/**
	 * The most important element of the Event - through this method the chain of automation is triggered.
	 * It should be used inside callback for hook registered in Event::initialize() method.
	 *
	 * @see EventInterface::initialize()
	 */
	public function trigger_automation(): void;

}

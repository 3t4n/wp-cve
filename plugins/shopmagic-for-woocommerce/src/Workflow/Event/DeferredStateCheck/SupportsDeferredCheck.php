<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck;

/**
 * Event capability to check if the deferred event still qualifies for running an action.
 */
interface SupportsDeferredCheck {
	/**
	 * Return true if event state still qualifies for running an action.
	 */
	public function is_event_still_valid(): bool;
}

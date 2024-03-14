<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

/**
 * Terminable extends Event with ability to detach all hooks registered in initialization method.
 */
interface TerminableInterface {

	/**
	 * Allow to detach all registered listeners.
	 */
	public function shutdown(): void;
}

<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use WPDesk\ShopMagic\Workflow\Components\AbstractCollection;
use WPDesk\ShopMagic\Workflow\Components\Sortable;

/**
 * @extends AbstractCollection<Event>
 * @implements Sortable<Event>
 */
final class EventsList extends AbstractCollection implements Sortable {

	protected $type = Event::class;

	/**
	 * @param string $offset
	 *
	 * @return Event
	 */
	public function offsetGet( $offset ): object {
		if ( $this->offsetExists( $offset ) ) {
			return clone apply_filters( 'shopmagic/core/single_event', parent::offsetGet( $offset ) );
		}

		return new NullEvent( $offset );
	}

	public function compare( object $a, object $b ): int {
		return strcmp( $a->get_group_slug(), $b->get_group_slug() );
	}

}

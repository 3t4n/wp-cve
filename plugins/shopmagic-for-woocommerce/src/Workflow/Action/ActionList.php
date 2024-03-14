<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action;

use WPDesk\ShopMagic\Workflow\Components\AbstractCollection;


/**
 * @extends AbstractCollection<Action>
 */
final class ActionList extends AbstractCollection {

	protected $type = Action::class;

	public function offsetGet( $offset ): object {
		if ( $this->offsetExists( $offset ) ) {
			return clone apply_filters( 'shopmagic/core/single_action', parent::offsetGet( $offset ) );
		}

		return new NullAction();
	}

}

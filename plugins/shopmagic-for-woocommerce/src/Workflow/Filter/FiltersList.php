<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Filter;

use WPDesk\ShopMagic\Workflow\Components\MatchableCollection;
use WPDesk\ShopMagic\Workflow\Components\Sortable;


/**
 * @implements Sortable<Filter>
 * @extends MatchableCollection<Filter>
 */
final class FiltersList extends MatchableCollection implements Sortable {

	protected $type = Filter::class;

	public function offsetGet( $offset ): object {
		if ( $this->offsetExists( $offset ) ) {
			return clone apply_filters( 'shopmagic/core/single_filter', parent::offsetGet( $offset ) );
		}

		// Sometimes we use string literal 'null'.
		if ( is_string( $offset ) && $offset !== 'null' ) {
			return new NullFilter( $offset );
		}

		return new NullFilter();
	}

	public function compare( object $a, object $b ): int {
		$group_compare = strcmp( $a->get_group_slug(), $b->get_group_slug() );
		if ( $group_compare === 0 ) {
			return strcmp( $a->get_name(), $b->get_name() );
		}

		return $group_compare;
	}
}

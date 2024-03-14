<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder;

use WPDesk\ShopMagic\Workflow\Components\MatchableCollection;
use WPDesk\ShopMagic\Workflow\Components\Sortable;

/**
 * @extends MatchableCollection<Placeholder>
 * @implements Sortable<Placeholder>
 */
final class PlaceholdersList extends MatchableCollection implements Sortable {

	/** @var string */
	protected $type = Placeholder::class;

	public function compare( object $a, object $b ): int {
		$group_compare = strcmp( $a->get_group_slug(), $b->get_group_slug() );
		if ( $group_compare === 0 ) {
			return strcmp( $a->get_name(), $b->get_name() );
		}

		return $group_compare;
	}
}

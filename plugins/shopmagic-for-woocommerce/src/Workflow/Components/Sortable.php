<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

/**
 * @template T of object
 */
interface Sortable {

	/**
	 * @param T $a
	 * @param T $b
	 */
	public function compare( object $a, object $b ): int;

}

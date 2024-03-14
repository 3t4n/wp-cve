<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction\DAO;

/**
 * @template T of object
 */
interface ObjectHydrator {

	/**
	 * @param T $object
	 *
	 * @return array<string, scalar|null>
	 */
	public function normalize( object $object ): array;

	public function supports_normalization( object $object ): bool;
}

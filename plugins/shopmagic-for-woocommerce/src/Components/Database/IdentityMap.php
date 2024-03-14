<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database;

/**
 * Holds objects, mapped by provided ID to avoid multiple queries for the same object.
 *
 * @template T of object
 */
class IdentityMap {
	/** @var array<string, T> */
	private $map = [];

	/**
	 * @param T $object
	 */
	public function put( object $object ): void {
		$this->map[ $object->get_id() ] = $object;
	}

	/**
	 * @param string $id
	 *
	 * @return T|null
	 */
	public function get( string $id ): ?object {
		return $this->map[ $id ] ?? null;
	}

	/**
	 * @phpstan-assert-if-true !null $this->get()
	 */
	public function has( string $id ): bool {
		return isset( $this->map[ $id ] );
	}

}

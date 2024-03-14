<?php
/**
 * Dropp
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use ArrayAccess;
use Countable;

/**
 * Dropp
 */
class Collection implements ArrayAccess, Countable  {
	/**
	 * Construct
	 */
	public function __construct( protected array $items = [] ) {
	}

	/**
	 * Count
	 *
	 * @return integer Item count.
	 */
	public function count(): int
	{
		return count( $this->items );
	}
	/**
	 * Add
	 *
	 * @param  mixed      $item Item to add to the collection.
	 * @return Collection       This object.
	 */
	public function add( mixed $item ): static
	{
		$this->items[] = $item;
		return $this;
	}

	/**
	 * Merge
	 *
	 * @param $collection
	 *
	 * @return Collection       This object.
	 */
	public function merge( $collection ): static
	{
		$this->items = array_merge( $this->items, $collection->to_array() );
		return $this;
	}

	/**
	 * Filter
	 *
	 * @param $callback
	 *
	 * @return Collection       This object.
	 */
	public function filter( $callback ): static
	{
		$this->items = array_filter( $this->items, $callback );
		return $this;
	}

	/**
	 * To array
	 *
	 * @return array Collection as array.
	 */
	public function to_array(): array
	{
		return $this->items;
	}

	/**
	 * Map
	 */
	public function map( $callback, ...$params ): array
	{
		return array_map(
			function( $item ) use ( $callback, $params ) {
				return call_user_func_array( [ $item, $callback ], $params );
			},
			$this->items
		);
	}

	/**
	 * Offset set
	 *
	 * @param int|string $offset Offset.
	 * @param mixed      $value  Value.
	 */
	public function offsetSet( $offset, mixed $value ): void {
		if ( is_null( $offset ) ) {
			$this->items[] = $value;
		} else {
			$this->items[$offset ] = $value;
		}
	}

	/**
	 * Offset exists
	 *
	 * @param  int|string $offset Offset.
	 * @return boolean            True when offset exists.
	 */
	public function offsetExists( $offset ): bool
	{
		return isset($this->items[$offset ] );
	}

	/**
	 * Offset unset
	 *
	 * @param  int|string $offset Offset.
	 */
	public function offsetUnset( $offset ): void {
		unset($this->items[$offset ] );
	}

	/**
	 * Offset get
	 *
	 * @param  int|string $offset Offset.
	 * @return mixed              Value.
	 */
	public function offsetGet( $offset ): mixed
	{
		return $this->items[$offset] ?? null;
	}

	/**
	 * Is empty
	 */
	public function isEmpty(): bool
	{
		return empty( $this->items );
	}
}

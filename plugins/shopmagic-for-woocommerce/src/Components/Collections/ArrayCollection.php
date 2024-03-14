<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Collections;

/**
 * @template TKey of array-key
 * @template T
 * @template-implements Collection<TKey, T>
 * @template-implements \ArrayAccess<TKey, T>
 */
final class ArrayCollection implements Collection, \ArrayAccess {

	/** @var array|iterable */
	private $storage;

	public function __construct( array $storage = [] ) {
		$this->storage = $storage;
	}

	public function count(): int {
		return count( $this->storage );
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator( $this->storage );
	}

	public function offsetExists( $offset ): bool {
		return isset( $this->storage[ $offset ] );
	}

	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->storage[ $offset ];
	}

	public function offsetSet( $offset, $value ): void {
		if ( $offset === null ) {
			$this->storage[] = $value;
		} else {
			$this->storage[ $offset ] = $value;
		}
	}

	public function offsetUnset( $offset ): void {
		unset( $this->storage[ $offset ] );
	}

	public function unique(): Collection {
		return new static( array_unique( $this->storage ) );
	}

	public function map( \Closure $func ): Collection {
		return new static( array_map( $func, $this->storage, array_keys( $this->storage ) ) );
	}

	public function is_empty(): bool {
		return count( $this->storage ) === 0;
	}

	public function reduce( \Closure $func, $initial = null ) {
		return array_reduce( $this->storage, $func, $initial );
	}

	public function find_first( \Closure $func ) {
		foreach ( $this->storage as $key => $element ) {
			if ( $func( $key, $element ) ) {
				return $element;
			}
		}

		return null;
	}

	public function to_array(): array {
		return $this->storage;
	}

	public function filter( \Closure $func ): Collection {
		return new static( array_filter( $this->storage, $func ) );
	}

	public function slice( int $offset, ?int $length = null ): Collection {
		return new static( array_slice( $this->storage, $offset, $length, true ) );
	}

	public function get( $key ) {
		return $this->storage[ $key ] ?? null;
	}

	public function has( $key ): bool {
		return isset( $this->storage[ $key ] );
	}

	public function set( $key, $value ): void {
		$this->storage[ $key ] = $value;
	}
}

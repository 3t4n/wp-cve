<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

use ArrayAccess;
use IteratorAggregate;
use WPDesk\ShopMagic\Exception\ItemNotExists;

/**
 * @template T of object
 * @template-implements \IteratorAggregate<string, T>
 * @template-implements \ArrayAccess<string, T>
 */
abstract class AbstractCollection implements ArrayAccess, IteratorAggregate, \Countable {

	/** @var class-string<T> */
	protected $type = '';

	/** @var array<string, T> */
	private $storage = [];

	/**
	 * @param string $offset
	 *
	 * @return T
	 */
	public function offsetGet( $offset ): object {
		if ( $this->offsetExists( $offset ) ) {
			return $this->storage[ $offset ];
		}
		throw ItemNotExists::resource_not_found( $this->type, $offset );
	}

	public function offsetExists( $offset ): bool {
		return isset( $this->storage[ $offset ] );
	}

	/** @return \Generator<T> */
	public function getIterator(): \Traversable {
		if ( $this instanceof Sortable ) {
			uasort( $this->storage, [ $this, 'compare' ] );
		}
		yield from $this->storage;
	}

	public function offsetUnset( $offset ): void {
		unset( $this->storage[ $offset ] );
	}

	public function offsetSet( $offset, $value ): void {
		if ( ! $value instanceof NamedComponent ) {
			throw new \InvalidArgumentException( "Workflow extension requires NamedComponent to register." );
		}

		$this->add( $value );
	}

	public function add( NamedComponent $component ): void {
		if ( empty( $component->get_id() ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					"Workflow extension component needs a valid ID. Trying to register %s",
					get_class( $component )
				)
			);
		}

		if ( ! $component instanceof $this->type ) {
			throw new \TypeError(
				sprintf( 'Argument 2 passed to WPDesk\ShopMagic\Event\EventsList::offsetSet() must be an instance of %s, %s given',
					$this->type,
					\get_class( $component )
				)
			);
		}

		$this->storage[ $component->get_id() ] = $component;
	}

	/**
	 * @template U
	 *
	 * @param callable(T): U $fn
	 *
	 * @return static
	 */
	public function filter( callable $fn ): self {
		$new = new $this();
		foreach ( $this as $key => $value ) {
			if ( $fn( $value ) ) {
				$new[ $key ] = $value;
			}
		}

		return $new;
	}

	/**
	 * @template U
	 *
	 * @param callable(T, string): U $fn
	 *
	 * @return \Generator
	 */
	public function map( callable $fn ): \Generator {
		foreach ( $this as $key => $value ) {
			yield $key => $fn( $value, $key );
		}
	}

	/**
	 * @template U
	 *
	 * @param Closure(?U, T): U $fn
	 * @param ?U                $initial
	 *
	 * @return U
	 */
	public function reduce( \Closure $fn, $initial = null ) {
		return array_reduce( $this->storage, $fn, $initial );
	}

	public function count(): int {
		return count( $this->storage );
	}

}

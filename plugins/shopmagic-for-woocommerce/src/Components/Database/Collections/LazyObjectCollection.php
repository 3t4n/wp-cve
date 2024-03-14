<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Collections;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;

/**
 * @template TKey of array-key
 * @template T of object
 * @template-implements Collection<TKey, T>
 */
class LazyObjectCollection implements Collection {

	/** @var array<string, mixed> */
	private $raw_data;

	/** @var array<TKey, T> */
	private $object_collection = [];

	private $initialized = false;

	/** @var ObjectDehydrator<T> */
	private $denormalizer;

	/**
	 * @param array<string, mixed> $raw_data
	 * @param ObjectDehydrator<T>  $denormalizer
	 */
	public function __construct( array $raw_data, ObjectDehydrator $denormalizer ) {
		$this->raw_data     = $raw_data;
		$this->denormalizer = $denormalizer;
	}

	public function is_empty(): bool {
		return $this->raw_data === [];
	}

	public function getIterator(): \Traversable {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return new \ArrayIterator( $this->object_collection );
	}

	private function initialize(): void {
		foreach ( $this->raw_data as $raw ) {
			$this->object_collection[] = $this->denormalizer->denormalize( $raw );
		}
		$this->initialized = true;
	}

	public function count(): int {
		return count( $this->raw_data );
	}

	public function unique(): Collection {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return new ArrayCollection( array_unique( $this->object_collection ) );
	}

	public function map( \Closure $func ): Collection {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return new ArrayCollection( array_map( $func,
			$this->object_collection,
			array_keys( $this->object_collection ) ) );
	}


	public function filter( \Closure $func ): Collection {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return new ArrayCollection( array_filter( $this->object_collection, $func ) );
	}

	public function find_first( \Closure $func ) {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		foreach ( $this->object_collection as $key => $element ) {
			if ( $func( $key, $element ) ) {
				return $element;
			}
		}

		return null;
	}

	/**
	 * @template U of mixed
	 *
	 * @param \Closure(?U, T): U $func
	 * @param ?U                 $initial
	 *
	 * @return U|null
	 */
	public function reduce( \Closure $func, $initial = null ) {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return array_reduce( $this->object_collection, $func, $initial );
	}

	public function to_array(): array {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return $this->object_collection;
	}

	public function slice( int $offset, ?int $length = null ): Collection {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return new ArrayCollection( array_slice( $this->object_collection, $offset, $length, true ) );
	}

	public function get( $key ) {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return $this->object_collection[ $key ] ?? null;
	}

	public function has( $key ): bool {
		if ( $this->initialized === false ) {
			$this->initialize();
		}

		return isset( $this->object_collection[ $key ] );
	}

	public function set( $key, $value ): void {
//		if ( $this->initialized === true ) {
//			throw new \LogicException('You cant add elements to collection after initialization.');
//		}
		$this->object_collection[ $key ] = $value;
	}
}

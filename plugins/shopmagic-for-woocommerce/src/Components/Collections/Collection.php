<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Collections;

/**
 * Collection of DAO Items.
 *
 * @template TKey of array-key
 * @template T
 * @template-extends \IteratorAggregate<TKey, T>
 */
interface Collection extends \Countable, \IteratorAggregate {
	public function is_empty(): bool;

	/**
	 * @return array<TKey, T>
	 */
	public function to_array(): array;

	/**
	 * @template U
	 *
	 * @param \Closure(T, ?TKey): U $func
	 *
	 * @return Collection<TKey, U>
	 */
	public function map( \Closure $func ): Collection;

	/**
	 * Retruns a new collection with unique values.
	 *
	 * @return Collection<TKey, T>
	 */
	public function unique(): Collection;

	/**
	 * @template U
	 *
	 * @param \Closure(U, T): U $func
	 * @param ?U                $initial
	 *
	 * @return U
	 */
	public function reduce( \Closure $func, $initial = null );

	/**
	 * @param \Closure(T): bool $func
	 *
	 * @return Collection<TKey, T>
	 */
	public function filter( \Closure $func ): Collection;

	/**
	 * @param \Closure(TKey, T): bool $func
	 *
	 * @return T|null
	 */
	public function find_first( \Closure $func );

	/**
	 * @param int      $offset
	 * @param int|null $length
	 *
	 * @return Collection<TKey, T>
	 */
	public function slice( int $offset, ?int $length = null ): Collection;

	/**
	 * @param TKey $key
	 *
	 * @return T|null
	 */
	public function get( $key );

	/**
	 * @param TKey $key
	 *
	 * @phpstan-assert-if-true !null $this->get()
	 */
	public function has( $key ): bool;

	/**
	 * @param TKey $key
	 * @param T    $value
	 */
	public function set( $key, $value ): void;
}

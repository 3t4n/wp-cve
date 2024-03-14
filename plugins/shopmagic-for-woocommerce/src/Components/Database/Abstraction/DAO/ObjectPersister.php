<?php

namespace WPDesk\ShopMagic\Components\Database\Abstraction\DAO;

/**
 * @template T of object
 * Can save/get/find/delete object from some kind of source.
 */
interface ObjectPersister {
	/**
	 * @param string|numeric $id
	 *
	 * @return T
	 */
	public function find( $id ): object;

	/**
	 * Can this DAO handle a given item?
	 *
	 * @param object $item
	 *
	 * @phpstan-assert-if-true T $item
	 * @return bool True when this DAO can use provided DAOItem
	 */
	public function can_handle( object $item ): bool;

	/**
	 * Save an item to the source.
	 *
	 * @param T $item
	 *
	 * @return bool True if succeeded.
	 */
	public function save( object $item ): bool;

	/**
	 * @param T $item
	 *
	 * @return void
	 */
	public function delete( object $item );

	/**
	 * Refresh item from source and return a new one.
	 *
	 * @param T $item
	 *
	 * @return T Brand new $item.
	 */
	public function refresh( object $item ): object;

	/**
	 * @return ObjectRepository<T>
	 */
	public function get_repository(): ObjectRepository;
}

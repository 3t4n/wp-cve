<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction\DAO;

/**
 * @template T of object
 * @extends ObjectRepository<T>
 */
interface CountableRepository extends ObjectRepository {

	public function count( array $criteria ): int;
}

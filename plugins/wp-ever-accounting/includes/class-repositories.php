<?php
/**
 * Repository Store.
 *
 * This will store all the available repositories
 * of different objects. It will load the correct repository
 * for handling the object's insert, update, delete.
 *
 * @since 1.1.0
 * @package EverAccounting
 */

namespace EverAccounting;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Repositories\Accounts;
use EverAccounting\Repositories\Categories;
use EverAccounting\Repositories\Contacts;
use EverAccounting\Repositories\Currencies;
use EverAccounting\Repositories\Transactions;
use EverAccounting\Repositories\Notes;
use EverAccounting\Repositories\Document_Items;
use EverAccounting\Repositories\Documents;
use EverAccounting\Repositories\Items;
use EverAccounting\Abstracts\Resource_Repository;
use EverAccounting\Repositories\Transfers;

defined( 'ABSPATH' ) || exit;

/**
 * Class Store
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Repositories
 */
class Repositories {
	/**
	 * Contains an instance of the resource repository class that we are working with.
	 *
	 * @var Resource_Repository
	 */
	private $instance = null;

	/**
	 * Contains an array of default supported repositories.
	 * Format of object name => class name.
	 * Example: 'item' => 'Items::class'
	 * You can also pass something like item-<type> for item repository and
	 * that type will be used first when available, if a repository is requested like
	 * this and doesn't exist, then the store would fall back to 'item'.
	 * Ran through `eaccounting_repositories`.
	 *
	 * @var array
	 */
	private $repositories = array(
		'items'          => Items::class,
		'categories'     => Categories::class,
		'contacts'       => Contacts::class,
		'transactions'   => Transactions::class,
		'accounts'       => Accounts::class,
		'currencies'     => Currencies::class,
		'documents'      => Documents::class,
		'document-items' => Document_Items::class,
		'notes'          => Notes::class,
		'transfers'      => Transfers::class,
	);

	/**
	 * Contains the name of the current repository class name.
	 *
	 * @var string
	 */
	private $repository_class = '';

	/**
	 * The object type this store works with.
	 *
	 * @var string
	 */
	private $object_type = '';

	/**
	 * Initiate the correct repository for the object.
	 *
	 * @param string $object_type Name of object.
	 *
	 * @throws \Exception If the object type is not supported.
	 */
	public function __construct( $object_type ) {
		$this->object_type  = $object_type;
		$this->repositories = apply_filters( 'eaccounting_repositories', $this->repositories );

		// If this object type can't be found, check to see if we can load one
		// level up (so if contact-type isn't found, we try contact).
		if ( ! array_key_exists( $object_type, $this->repositories ) ) {
			$pieces      = explode( '-', $object_type );
			$object_type = $pieces[0];
		}

		if ( array_key_exists( $object_type, $this->repositories ) ) {
			$repository = apply_filters( 'eaccounting_' . $object_type . '_repository', $this->repositories [ $object_type ] );
			if ( is_object( $repository ) ) {
				$this->repository_class = get_class( $repository );
				$this->instance         = $repository;
			} else {
				if ( ! class_exists( $repository ) ) {
					throw new \Exception( __( 'Repository class does not exist.', 'wp-ever-accounting' ) );
				}
				$this->repository_class = $repository;
				$this->instance         = new $repository();
			}
		} else {
			throw new \Exception( __( 'Invalid repository.', 'wp-ever-accounting' ) );
		}
	}

	/**
	 * Only store the object type to avoid serializing the repository instance.
	 *
	 * @return array
	 */
	public function __sleep() {
		return array( 'object_type' );
	}

	/**
	 * Re-run the constructor with the object type.
	 *
	 * @throws \Exception When validation fails.
	 */
	public function __wakeup() {
		$this->__construct( $this->object_type );
	}

	/**
	 * Loads a repository.
	 *
	 * @param string $object_type Name of object.
	 *
	 * @return Repositories
	 * @throws \Exception When validation fails.
	 *
	 * @since 1.1.0
	 */
	public static function load( $object_type ) {
		return new Repositories( $object_type );
	}

	/**
	 * Returns the class name of the current repository.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public function get_repository_class() {
		return $this->repository_class;
	}

	/**
	 * Returns the object type of the current repository.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Reads an object from the repository.
	 *
	 * @param Resource_Model $data model instance.
	 *
	 * @since 1.1.0
	 */
	public function read( &$data ) {
		$this->instance->read( $data );
	}

	/**
	 * Create an object using repository.
	 *
	 * @param Resource_Model $data model instance.
	 *
	 * @since 1.1.0
	 */
	public function insert( &$data ) {
		$this->instance->insert( $data );
	}

	/**
	 * Update an object using repository.
	 *
	 * @param Resource_Model $data model instance.
	 *
	 * @since 1.1.0
	 */
	public function update( &$data ) {
		$this->instance->update( $data );
	}

	/**
	 * Delete an object using repository.
	 *
	 * @param Resource_Model $data data instance.
	 *
	 * @since 1.1.0
	 */
	public function delete( &$data ) {
		$this->instance->delete( $data );
	}

	/**
	 * Repository can define additional function. This passes
	 * through to the instance if that function exists.
	 *
	 * @param string $method Method.
	 * @param array  $parameters Parameters.
	 * @return mixed
	 * @since 1.1.0
	 */
	public function __call( $method, $parameters ) {
		if ( is_callable( array( $this->instance, $method ) ) ) {
			$object     = array_shift( $parameters );
			$parameters = array_merge( array( &$object ), $parameters );

			return call_user_func_array( array( $this->instance, $method ), $parameters );
		}
	}
}

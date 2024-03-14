<?php
/**
 * Overview of Main Registry Class.
 *
 * @package    EverAccounting
 * @subpackage Abstracts
 * @since      1.0.2
 */

namespace EverAccounting\Abstracts;

defined( 'ABSPATH' ) || exit();

/**
 * Class Registry
 *
 * @package EverAccounting\Abstracts
 */
abstract class Registry extends \ArrayObject {
	/**
	 * Array of registry items.
	 *
	 * @since 1.0.2
	 * @var   array
	 */
	private $items = array();

	/**
	 * Registry constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->init();
	}


	/**
	 * Initialize the registry.
	 *
	 * Each sub-class will need to do various initialization operations in this method.
	 *
	 * @since 1.0.2
	 */
	abstract public function init();

	/**
	 * Adds an item to the registry.
	 *
	 * @param int   $item_id    Item ID.
	 * @param array $attributes {
	 *                          Item attributes.
	 *
	 * @type string $class      Item handler class.
	 * @type string $file       Item handler class file.
	 * }
	 * @return true Always true.
	 * @since 1.0.2
	 */
	public function add_item( $item_id, $attributes ) {
		foreach ( $attributes as $attribute => $value ) {
			$this->items[ $item_id ][ $attribute ] = $value;
		}

		return true;
	}

	/**
	 * Removes an item from the registry by ID.
	 *
	 * @param string $item_id Item ID.
	 *
	 * @since 1.0.2
	 */
	public function remove_item( $item_id ) {
		unset( $this->items[ $item_id ] );
	}

	/**
	 * Retrieves an item and its associated attributes.
	 *
	 * @param string $item_id Item ID.
	 *
	 * @return array|false Array of attributes for the item if registered, otherwise false.
	 * @since 1.0.2
	 */
	public function get( $item_id ) {
		if ( isset( $this->items[ $item_id ] ) ) {
			return $this->items[ $item_id ];
		}

		return false;
	}

	/**
	 * Retrieves registered items.
	 *
	 * @return array The list of registered items.
	 * @since 1.0.2
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * Only intended for use by tests.
	 *
	 * @since 1.0.2
	 */
	public function reset_items() {
		$this->items = array();
	}

	/**
	 * Determines whether an item exists.
	 *
	 * @param string $offset Item ID.
	 *
	 * @return bool True if the item exists, false on failure.
	 * @since 1.0.2
	 */
	public function offsetExists( $offset ) {
		return false !== $this->get( $offset );
	}

	/**
	 * Retrieves an item by its ID.
	 *
	 * Defined only for compatibility with ArrayAccess, use get() directly.
	 *
	 * @param string $offset Item ID.
	 *
	 * @return mixed The registered item, if it exists.
	 * @since 1.0.2
	 */
	public function offsetGet( $offset ) {
		return $this->get( $offset );
	}

	/**
	 * Adds/overwrites an item in the registry.
	 *
	 * Defined only for compatibility with ArrayAccess, use add_item() directly.
	 *
	 * @param string $offset Item ID.
	 * @param mixed  $value  Item attributes.
	 *
	 * @since 1.0.2
	 */
	public function offsetSet( $offset, $value ) {
		$this->add_item( $offset, $value );
	}

	/**
	 * Removes an item from the registry.
	 *
	 * Defined only for compatibility with ArrayAccess, use remove_item() directly.
	 *
	 * @param string $offset Item ID.
	 *
	 * @since 1.0.2
	 */
	public function offsetUnset( $offset ) {
		$this->remove_item( $offset );
	}
}

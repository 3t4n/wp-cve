<?php
/**
 * Handle the category object.
 *
 * @package     EverAccounting\Models
 * @class       Category
 * @version     1.1.0
 */

namespace EverAccounting\Models;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Repositories;

defined( 'ABSPATH' ) || exit;

/**
 * Class Category
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Category extends Resource_Model {
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'category';

	/**
	 * Cache group.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $cache_group = 'ea_categories';

	/**
	 * Item Data array.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'         => '',
		'type'         => '',
		'color'        => '',
		'enabled'      => 1,
		'date_created' => null,
	);


	/**
	 * Get the category if ID is passed, otherwise the category is new and empty.
	 *
	 * @param int|object|Category $item Item object to read.
	 */
	public function __construct( $item = 0 ) {
		parent::__construct( $item );

		if ( $item instanceof self ) {
			$this->set_id( $item->get_id() );
		} elseif ( is_numeric( $item ) ) {
			$this->set_id( $item );
		} elseif ( ! empty( $item->id ) ) {
			$this->set_id( $item->id );
		} elseif ( is_array( $item ) ) {
			$this->set_props( $item );
		} else {
			$this->set_object_read( true );
		}

		$this->repository = Repositories::load( 'categories' );

		if ( $this->get_id() > 0 ) {
			$this->repository->read( $this );
		}

		$this->required_props = array(
			'name' => __( 'Category name', 'wp-ever-accounting' ),
			'type' => __( 'Category type', 'wp-ever-accounting' ),
		);
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	|
	| Methods which create, read, update and delete discounts from the database.
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	|
	| Functions for getting item data. Getter methods wont change anything unless
	| just returning from the props.
	|
	*/

	/**
	 * Get category name.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_name( $context = 'edit' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get the category type.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_type( $context = 'edit' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get the category color.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null
	 */
	public function get_color( $context = 'edit' ) {
		return $this->get_prop( 'color', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting item data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Set the category name.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Category name.
	 */
	public function set_name( $value ) {
		$this->set_prop( 'name', eaccounting_clean( $value ) );
	}

	/**
	 * Set the category type.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value  Category type.
	 */
	public function set_type( $value ) {
		if ( array_key_exists( $value, eaccounting_get_category_types() ) ) {
			$this->set_prop( 'type', eaccounting_clean( $value ) );
		}
	}

	/**
	 * Set the category color.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Category color.
	 */
	public function set_color( $value ) {
		$this->set_prop( 'color', eaccounting_clean( $value ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Additional methods
	|--------------------------------------------------------------------------
	|
	| Does extra thing as helper functions.
	|
	*/

	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	|
	| Checks if a condition is true or false.
	|
	*/

}

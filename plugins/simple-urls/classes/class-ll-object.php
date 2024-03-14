<?php
/**
 * Declare class LL_Object
 *
 * @package LL_Object
 */

namespace LassoLite\Classes;

/**
 * Class LL_Object
 */
class LL_Object {

	/**
	 * Columns of table
	 *
	 * @var array $columns
	 */
	protected $columns;

	/**
	 * Data from DB
	 *
	 * @var object $data
	 */
	protected $data;

	/**
	 * LL_Object constructor.
	 *
	 * @param object $object An object.
	 */
	public function __construct( $object ) {
		$this->map_properties( $object );
	}

	/**
	 * Get value for a property
	 *
	 * @param string $name Function name (get_property_name).
	 *
	 * @return mixed Property value.
	 */
	public function __get( $name ) {
		$method = strtolower( $name );

		$property = $this->get_property_name( $method );

		if ( ! $property ) {
			$property = $method;
		}

		if ( ! in_array( $property, $this->columns, true ) ) {
			return null;
		}

		// ? see if there is an extra getter method: get_name()
		if ( ! method_exists( $this, $method ) ) {
			// ? if there is no getter, receive all public/protected vars and return the correct one if found
			return $this->data[ $property ] ?? null;
		} else {
			return $this->$method(); // ? call the getter
		}

	}

	/**
	 * Set value for a property
	 *
	 * @param string $name  Function name (set_property_name).
	 * @param mix    $value Property value.
	 */
	public function __set( $name, $value ) {
		$method   = strtolower( $name );
		$property = $this->get_property_name( $name );
		if ( ! $property ) {
			$property = $method;
		}

		if ( ! in_array( $property, $this->columns, true ) ) {
			return;
		}
		// ? see if there exists a extra setter method: setName()
		if ( ! method_exists( $this, $method ) ) {
			// ? if there is no setter, receive all public/protected vars and set the correct one if found
			$this->data[ $property ] = $value;
		} else {
			$this->$method( $value ); // ? call the setter with the value
		}
	}

	/**
	 * Get property name by method
	 *
	 * @param string $method Method.
	 */
	public function get_property_name( $method ) {
		return substr_replace( $method, '', 0, 4 );
	}

	/**
	 * Map data into object
	 *
	 * @param object $row A record from DB.
	 */
	protected function map_properties( $row ) {
		$this->columns = array_keys( (array) $row );
		foreach ( $this->columns as &$column ) {
			$column = strtolower( $column );
			$method = 'set_' . $column;
			$this->$method( $row->$column ?? null );
		}
		return $this;
	}

	/**
	 * Call a method in this class
	 *
	 * @param string $method Method name.
	 * @param array  $args   Arguments.
	 */
	public function __call( $method, $args ) {
		$prefix = substr_replace( $method, '', 4 );

		switch ( $prefix ) {
			case 'get_':
				return $this->$method;

			case 'set_':
				$this->$method = $args[0] ?? null;
				break;
		}

		return null;
	}
}

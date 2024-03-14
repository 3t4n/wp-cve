<?php

namespace TotalContestVendors\TotalCore\Foundation;

use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Misc;

/**
 * Class Environment
 * @package TotalContestVendors\TotalCore\Foundation
 */
class Environment implements \TotalContestVendors\TotalCore\Contracts\Foundation\Environment {
	/**
	 * @var array $items
	 */
	protected $items;

	/**
	 * Environment constructor.
	 *
	 * @param $items
	 */
	public function __construct( $items ) {
		$this->items = is_array( $items ) ? $items : [];
	}

	/**
	 * Get items as array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->items;
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
    #[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return (bool) $this->get( $offset );
	}

	/**
	 * Get item.
	 *
	 * @param      $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		$value = Arrays::getDotNotation( $this->items, $key, $default );

		return Misc::value( $value );
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
    #[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->get( $offset );
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
    #[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->set( $offset, $value );
	}

	/**
	 * Set item.
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public function set( $key, $value ) {
		$this->items = Arrays::setDotNotation( $this->items, $key, $value );

		return $this->items;
	}

	/**
	 * @param mixed $offset
	 */
    #[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		$this->set( $offset, null );
	}

	/**
	 * @return mixed|string|void
	 */
	public function serialize() {
		return json_encode( $this->items );
	}

	/**
	 * @param string $serialized
	 */
	public function unserialize( $serialized ) {
		$this->items = json_decode( $serialized, true );
	}

	/**
	 * @return array|mixed
	 */
    #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->items;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->get( $key, null );
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public function __set( $key, $value ) {
		return $this->set( $key, $value );
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function __isset( $key ) {
		return (bool) $this->get( $key );
	}
}

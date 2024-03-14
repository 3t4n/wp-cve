<?php

namespace TotalContest\Migrations\Contest\Templates;

use TotalContest\Contracts\Migrations\Contest\Template\Template as TemplateContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Template.
 * @package TotalContest\Migrations\Contest\Templates
 */
class Template implements TemplateContract {
	protected $id;
	protected $newId;
	protected $data = [];

	/**
	 * @param $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $newId
	 */
	public function setNewId( $newId ) {
		$this->newId = $newId;
	}

	/**
	 * @return mixed
	 */
	public function getNewId() {
		return $this->newId;
	}

	/**
	 * @return array
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->data + [ 'id' => $this->getId(), 'newId' => $this->getNewId() ];
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->data[ $offset ] );
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed|null
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return Arrays::getDotNotation( $this->data, $offset, null );
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->data = Arrays::setDotNotation( $this->data, $offset, $value );
	}

	/**
	 * @param mixed $offset
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->data[ $offset ] );
	}
}

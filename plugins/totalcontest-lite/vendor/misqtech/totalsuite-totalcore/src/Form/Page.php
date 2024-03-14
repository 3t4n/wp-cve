<?php

namespace TotalContestVendors\TotalCore\Form;

use TotalContestVendors\TotalCore\Contracts\Form\Page as PageContract;

/**
 * Class Page
 *
 * @package TotalContestVendors\TotalCore\Form
 */
class Page implements PageContract {
	protected $fields = [];
	protected $errors = false;

	public function __construct() {
	}

	public function validate() {
		foreach ( $this->fields as $field ):
			$validation = $field->validate();
			if ( $validation !== true ):
				$this->errors[ $field->getName() ] = $validation;
			endif;
		endforeach;

		return empty( $this->errors ) ? true : $this->errors;
	}

	public function errors() {
		return $this->errors;
	}

	public function toArray() {
		$array = [];
		foreach ( $this->fields as $field ):
			$array[ $field->getName() ] = $field->getValue();
		endforeach;

		return $array;
	}

	public function __toString() {
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render() {
		$page = new \TotalContestVendors\TotalCore\Helpers\Html(
			'div',
			[ 'class' => \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) . '-form-page' ],
			implode( '', $this->fields )
		);

		return $page->render();
	}

	/**
	 * Whether a offset exists
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param  mixed  $offset  <p>
	 *                      An offset to check for.
	 *                      </p>
	 *
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->fields[ $offset ] );
	}

	/**
	 * Offset to retrieve
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param  mixed  $offset  <p>
	 *                      The offset to retrieve.
	 *                      </p>
	 *
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->fields[ $offset ];
	}

	/**
	 * Offset to set
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param  mixed  $offset  <p>
	 *                      The offset to assign the value to.
	 *                      </p>
	 * @param  mixed  $value  <p>
	 *                      The value to set.
	 *                      </p>
	 *
	 * @return void
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		if ( $offset === '' || is_null( $offset ) ):
			$offset = count( $this->fields );
		endif;

		$this->fields[ $offset ] = $value;

		if ( $this->fields[ $offset ] instanceof Field ):
			$this->fields[ $offset ]->onAttach( $this );
		endif;
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param  mixed  $offset  <p>
	 *                      The offset to unset.
	 *                      </p>
	 *
	 * @return void
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		if ( $this->fields[ $offset ] instanceof Field ):
			$this->fields[ $offset ]->onDetach( $this );
		endif;

		unset( $this->fields[ $offset ] );
	}

	/**
	 * Return the current element
	 *
	 * @link  http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function current() {
		return current( $this->fields );
	}

	/**
	 * Move forward to next element
	 *
	 * @link  http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function next() {
		next( $this->fields );
	}

	/**
	 * Return the key of the current element
	 *
	 * @link  http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function key() {
		return key( $this->fields );
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link  http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	#[\ReturnTypeWillChange]
	public function valid() {
		return current( $this->fields ) !== false;
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link  http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */

	#[\ReturnTypeWillChange]
	public function rewind() {
		reset( $this->fields );
	}

	/**
	 * Count elements of an object
	 *
	 * @link  http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	#[\ReturnTypeWillChange]
	public function count() {
		return count( $this->fields );
	}
}

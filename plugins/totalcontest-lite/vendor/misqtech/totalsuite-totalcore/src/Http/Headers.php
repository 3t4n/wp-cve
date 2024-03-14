<?php

namespace TotalContestVendors\TotalCore\Http;

use TotalContestVendors\TotalCore\Contracts\Http\Headers as HeadersContract;

class Headers implements HeadersContract {
	/**
	 * @var array $headers
	 */
	protected $headers = [];
	/**
	 * @var int $status
	 */
	protected $status = 200;

	/**
	 * Headers constructor.
	 *
	 * @param array $headers
	 * @param int   $status
	 */
	public function __construct( $headers = [], $status = 200 ) {
		$this->status = (int) $status;

		foreach ( $headers as $name => $value ):
			$this->headers[ (string) strtoupper( $name ) ] = (string) $value;
		endforeach;
	}

	/**
	 * @return $this
	 */
	public function send() {
		foreach ( $this->headers as $name => $value ):
			header( "{$name}: {$value}", true, $this->status );
		endforeach;

		return $this;
	}

	/**
	 * Whether a offset exists
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset <p>
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
		return isset( $this->headers[ $offset ] );
	}

	/**
	 * Offset to retrieve
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to retrieve.
	 *                      </p>
	 *
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
    #[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->headers[ $offset ];
	}

	/**
	 * Offset to set
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to assign the value to.
	 *                      </p>
	 * @param mixed $value  <p>
	 *                      The value to set.
	 *                      </p>
	 *
	 * @return void
	 * @since 5.0.0
	 */
    #[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->headers[ strtoupper( $offset ) ] = (string) $value;
	}

	/**
	 * Offset to unset
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to unset.
	 *                      </p>
	 *
	 * @return void
	 * @since 5.0.0
	 */
    #[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->headers[ $offset ] );
	}

	/**
	 * @return int
	 */
	public function status() {
		return $this->status;
	}

	/**
	 * Get JSON.
	 *
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
		return $this->headers;
	}
}

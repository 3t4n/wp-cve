<?php
/**
 * Interface ExtraFieldsCollection.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model\Extra;

use ArrayAccess;
use Countable;
use Exception;
use Iterator;

/**
 * Represents a collection of Extra Fields as they may be present at several locations in ZIP files.
 */
class ExtraFieldsCollection implements ArrayAccess, Countable, Iterator {
	/**
	 * The map of Extra Fields.
	 * Maps from Header ID to Extra Field.
	 * Must not be null, but may be empty if no Extra Fields are used.
	 * The map is sorted by Header IDs in ascending order.
	 *
	 * @var ZipExtraField[] $collection
	 */
	protected $collection = array();

	/**
	 * Returns the number of Extra Fields in this collection.
	 */
	public function count(): int {
		return count( $this->collection );
	}

	/**
	 * Returns the Extra Field with the given Header ID or null if no such Extra Field exists.
	 *
	 * @param int $header_id The requested Header ID.
	 * @return ZipExtraField|null The Extra Field with the given Header ID or if no such Extra Field exists.
	 * @throws Exception Throws Exception.
	 */
	public function get( int $header_id ): ?ZipExtraField {
		$this->validate_header_id( $header_id );
		return $this->collection[ $header_id ] ?? null;
	}

	/**
	 * Validate header Id.
	 *
	 * @param int $header_id Header ID.
	 * @return void
	 * @throws Exception Throws exception.
	 */
	private function validate_header_id( int $header_id ) {
		if ( 0 > $header_id || 0xFFFF < $header_id ) {
			throw new Exception( '$headerId out of range' );
		}
	}

	/**
	 * Stores the given Extra Field in this collection.
	 *
	 * @param ZipExtraField $extra_field The Extra Field to store in this collection.
	 * @return ZipExtraField the Extra Field previously associated with the Header ID of
	 *                       of the given Extra Field or null if no such Extra Field existed
	 * @throws Exception Throws exception.
	 */
	public function add( ZipExtraField $extra_field ): ZipExtraField {
		$header_id = $extra_field->get_header_id();
		$this->validate_header_id( $header_id );
		$this->collection[ $header_id ] = $extra_field;

		return $extra_field;
	}

	/**
	 * Returns Extra Field exists.
	 *
	 * @param int $header_id The requested Header ID.
	 */
	public function has( int $header_id ): bool {
		return isset( $this->collection[ $header_id ] );
	}

	/**
	 * Removes the Extra Field with the given Header ID.
	 *
	 * @param int $header_id The requested Header ID.
	 * @return ZipExtraField|null The Extra Field with the given Header ID or null if no such Extra Field exists.
	 * @throws Exception Throws exception.
	 */
	public function remove( int $header_id ): ?ZipExtraField {
		$this->validate_header_id( $header_id );
		if ( isset( $this->collection[ $header_id ] ) ) {
			$ef = $this->collection[ $header_id ];
			unset( $this->collection[ $header_id ] );
			return $ef;
		}

		return null;
	}

	/**
	 * Whether an offset exists.
	 *
	 * @param mixed $offset An offset to check for.
	 * @return bool True on success or false on failure.
	 */
	public function offsetExists( $offset ): bool {
		return isset( $this->collection[ (int) $offset ] );
	}

	/**
	 * Offset to retrieve.
	 *
	 * @param mixed $offset The offset to retrieve.
	 */
	public function offsetGet( $offset ) {
		return $this->collection[ (int) $offset ] ?? null;
	}

	/**
	 * Offset to set.
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 * @throws Exception Throws exception.
	 */
	public function offsetSet( $offset, $value ) {
		if ( ! $value instanceof ZipExtraField ) {
			throw new Exception( 'value is not instanceof ' . ZipExtraField::class );
		}
		$this->add( $value );
	}

	/**
	 * Offset to unset.
	 *
	 * @param mixed $offset The offset to unset.
	 * @return void
	 * @throws Exception Throws exception.
	 */
	public function offsetUnset( $offset ) {
		$this->remove( $offset );
	}

	/**
	 * Return the current element.
	 */
	public function current(): ZipExtraField {
		return current( $this->collection );
	}

	/**
	 * Move forward to next element.
	 *
	 * @return void
	 */
	public function next() {
		next( $this->collection );
	}

	/**
	 * Return the key of the current element.
	 *
	 * @return int Scalar on success, or null on failure.
	 */
	public function key(): int {
		return key( $this->collection );
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @return bool The return value will cast to boolean and then evaluated. Returns true on success or false on failure.
	 */
	public function valid(): bool {
		return key( $this->collection ) !== null;
	}

	/**
	 * Rewind the Iterator to the first element.
	 *
	 * @return void
	 */
	public function rewind() {
		reset( $this->collection );
	}

	/**
	 * To string method.
	 *
	 * @return string
	 */
	public function __toString(): string {
		$formats = array();
		foreach ( $this->collection as $value ) {
			$formats[] = (string) $value;
		}

		return implode( "\n", $formats );
	}

	/**
	 * If clone extra fields.
	 */
	public function __clone() {
		foreach ( $this->collection as $k => $v ) {
			$this->collection[ $k ] = clone $v;
		}
	}
}

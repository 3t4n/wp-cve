<?php
/**
 * Handle the collection.
 *
 * @package     EverAccounting
 * @class       Collection
 * @version     1.0.2
 */

namespace EverAccounting;

use EverAccounting\Interfaces\Arrayable;

defined( 'ABSPATH' ) || exit;

/**
 * Class Collection
 *
 * @package EverAccounting
 */
class Collection implements Arrayable {
	/**
	 * The items contained in the collection.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Create a new collection.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $items Items.
	 */
	public function __construct( $items = array() ) {
		if ( $items instanceof Collection ) {
			$this->items = $items->all();
		} elseif ( $items instanceof Arrayable ) {
			$this->items = $items->to_array();
		} elseif ( is_array( $items ) ) {
			$this->items = $items;
		} else {
			$this->items = array();
		}
	}

	/**
	 * Copy the collection
	 *
	 * @since 1.1.0
	 * @return $this
	 */
	public function copy() {
		return new static( $this->items );
	}

	/**
	 * Create a new collection instance if the value isn't one already.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $items Items.
	 *
	 * @return static
	 */
	public static function make( $items = null ) {
		return new static( $items );
	}

	/**
	 * Get all of the items in the collection.
	 *
	 * @since 1.0.2
	 *
	 * @return array
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * Execute a callback over each item.
	 *
	 * @since 1.0.2
	 *
	 * @param callable $callback Callback.
	 *
	 * @return object
	 */
	public function each( $callback ) {
		return new static( array_map( $callback, $this->items ) );
	}

	/**
	 * Run a filter over each of the items.
	 *
	 * @since 1.0.2
	 *
	 * @param callable $callback Callback.
	 *
	 * @return static
	 */
	public function filter( $callback = null ) {
		return new static( array_filter( $this->items, $callback ) );
	}

	/**
	 * Filter items by the given key value pair.
	 *
	 * @since 1.0.2
	 *
	 * @param string $key    Key.
	 * @param mixed  $value Value.
	 * @param bool   $strict    Strict.
	 *
	 * @return static
	 */
	public function where( $key, $value, $strict = true ) {
		return $this->filter(
			function ( $item ) use ( $key, $value, $strict ) {
				return $strict ? self::data_get( $item, $key ) === $value : self::data_get( $item, $key ) === $value;
			}
		);
	}

	/**
	 * Filter items by the given key value pair using loose comparison.
	 *
	 * @since 1.0.2
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 *
	 * @return static
	 */
	public function where_loose( $key, $value ) {
		return $this->where( $key, $value, false );
	}

	/**
	 * Flip the items in the collection.
	 *
	 * @since 1.0.2
	 *
	 * @return static
	 */
	public function flip() {
		return new static( array_flip( $this->items ) );
	}

	/**
	 * Remove an item from the collection by key.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $key Key.
	 *
	 * @return void
	 */
	public function forget( $key ) {
		$this->offsetUnset( $key );
	}

	/**
	 * Get an item from the collection by key.
	 *
	 * @since 1.0.2
	 * @param mixed $key    Key.
	 * @param mixed $default Default.
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		if ( $this->offsetExists( $key ) ) {
			return $this->items[ $key ];
		}

		return $default instanceof \Closure ? $default() : $default;
	}

	/**
	 * Determine if an item exists in the collection by key.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $key Key.
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return $this->offsetExists( $key );
	}

	/**
	 * Determine if the collection is empty or not.
	 *
	 * @since 1.0.2
	 *
	 * @return bool
	 */
	public function is_empty() {
		return empty( $this->items );
	}

	/**
	 * Get the keys of the collection items.
	 *
	 * @since 1.0.2
	 *
	 * @return static
	 */
	public function keys() {
		return new static( array_keys( $this->items ) );
	}

	/**
	 * Get the last item from the collection.
	 *
	 * @return mixed|null
	 */
	public function last() {
		return count( $this->items ) > 0 ? end( $this->items ) : null;
	}

	/**
	 * Get the first item from the collection.
	 *
	 * @return mixed|null
	 */
	public function first() {
		return count( $this->items ) > 0 ? current( $this->items ) : null;
	}

	/**
	 * Run a map over each of the items.
	 *
	 * @since 1.0.2
	 *
	 * @param callable $callback Callback.
	 *
	 * @return static
	 */
	public function map( $callback ) {
		return new static( array_map( $callback, $this->items, array_keys( $this->items ) ) );
	}

	/**
	 * Get and remove the last item from the collection.
	 *
	 * @since 1.0.2
	 *
	 * @return mixed|null
	 */
	public function pop() {
		return array_pop( $this->items );
	}

	/**
	 * Push an item onto the beginning of the collection.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $value Value.
	 *
	 * @return void
	 */
	public function prepend( $value ) {
		array_unshift( $this->items, $value );
	}

	/**
	 * Push an item onto the end of the collection.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $value Value.
	 *
	 * @return void
	 */
	public function push( $value ) {
		$this->offsetSet( null, $value );
	}

	/**
	 * Put an item in the collection by key.
	 *
	 * @since 1.0.2
	 * @param mixed $key  Key.
	 * @param mixed $value Value.
	 *
	 * @return void
	 */
	public function put( $key, $value ) {
		$this->offsetSet( $key, $value );
	}

	/**
	 * Get one or more items randomly from the collection.
	 *
	 * @since 1.0.2
	 *
	 * @param int $amount Amount.
	 *
	 * @return mixed
	 */
	public function random( $amount = 1 ) {
		if ( $this->is_empty() ) {
			return array();
		}

		$keys = array_rand( $this->items, $amount );

		return is_array( $keys ) ? array_intersect_key( $this->items, array_flip( $keys ) ) : $this->items[ $keys ];
	}

	/**
	 * Merge the collection with the given items.
	 *
	 * @since 1.0.2
	 *
	 * @param Arrayable|array $items Items.
	 *
	 * @return static
	 */
	public function merge( $items ) {
		$items = new static( $items );
		return new static( array_merge( $this->items, $items->all() ) );
	}

	/**
	 * Reduce the collection to a single value.
	 *
	 * @since 1.0.2
	 * @param callable $callback Callback.
	 * @param mixed    $initial Initial.
	 *
	 * @return mixed
	 */
	public function reduce( $callback, $initial = null ) {
		return array_reduce( $this->items, $callback, $initial );
	}

	/**
	 * Create a collection of all elements that do not pass a given truth test.
	 *
	 * @since 1.0.2
	 *
	 * @param callable|mixed $callback Callback.
	 *
	 * @return static
	 */
	public function reject( $callback ) {
		if ( $this->use_as_callable( $callback ) ) {
			return $this->filter(
				function ( $item ) use ( $callback ) {
					return ! $callback( $item );
				}
			);
		}

		return $this->filter(
			function ( $item ) use ( $callback ) {
				return $callback !== $item;
			}
		);
	}

	/**
	 * Reverse items order.
	 *
	 * @since 1.0.2
	 *
	 * @param bool $preserve_keys Preserve keys.
	 *
	 * @return static
	 */
	public function reverse( $preserve_keys = true ) {
		return new static( array_reverse( $this->items, $preserve_keys ) );
	}

	/**
	 * Determine if the given value is callable, but not a string.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $value Value.
	 *
	 * @return bool
	 */
	protected function use_as_callable( $value ) {
		return ! is_string( $value ) && is_callable( $value );
	}

	/**
	 * Search the collection for a given value and return the corresponding key if successful.
	 *
	 * @since 1.0.2
	 * @param mixed $value Value.
	 * @param bool  $strict Strict.
	 * @return mixed
	 */
	public function search( $value, $strict = false ) {
		if ( ! $this->use_as_callable( $value ) ) {
			return array_search( $value, $this->items, $strict ? true : false );
		}

		foreach ( $this->items as $key => $item ) {
			if ( $value( $item, $key ) ) {
				return $key;
			}
		}

		return false;
	}


	/**
	 * Sort through each item with a callback.
	 * callback function($a, $b){
	 *  return $a[key] > $b[key]; //ASC
	 *  return $a[key] < $b[key]; //DESC
	 * }
	 *
	 * @since 1.0.2
	 *
	 * @param callable|int|null $callback Callback.
	 *
	 * @return static
	 */
	public function sort( $callback = null ) {
		$items = $this->items;

		$callback && is_callable( $callback )
			? uasort( $items, $callback )
			: asort( $items, $callback );

		return new static( $items );
	}

	/**
	 * Sort items in descending order by key.
	 *
	 * @param int $options Options.
	 *
	 * @return static
	 */
	public function sort_desc( $options = SORT_REGULAR ) {
		$items = $this->items;

		arsort( $items, $options );

		return new static( $items );
	}

	/**
	 * Take the first or last {$limit} items.
	 *
	 * @since 1.0.2
	 *
	 * @param int $limit Limit.
	 *
	 * @return static
	 */
	public function take( $limit = null ) {
		if ( $limit < 0 ) {
			return $this->slice( $limit, abs( $limit ) );
		}

		return $this->slice( 0, $limit );
	}

	/**
	 * Slice the underlying collection array.
	 * equivalent of offset.
	 *
	 * @since 1.0.2
	 * @param int  $offset Offset.
	 * @param int  $length Length.
	 * @param bool $preserve_keys Preserve keys.
	 *
	 * @return static
	 */
	public function slice( $offset, $length = null, $preserve_keys = false ) {
		return new static( array_slice( $this->items, $offset, $length, $preserve_keys ) );
	}

	/**
	 * Splice portion of the underlying collection array.
	 *
	 * @since 1.0.2
	 * @param int   $offset     Offset.
	 * @param int   $length Length.
	 * @param mixed $replacement    Replacement.
	 *
	 * @return static
	 */
	public function splice( $offset, $length = 0, $replacement = array() ) {
		return new static( array_splice( $this->items, $offset, $length, $replacement ) );
	}

	/**
	 * Return only unique items from the collection array.
	 *
	 * @since 1.0.2
	 *
	 * @return static
	 */
	public function unique() {
		return new static( array_unique( $this->items ) );
	}

	/**
	 * Reset the keys on the underlying array.
	 *
	 * @since 1.0.2
	 *
	 * @return static
	 */
	public function values() {
		return new static( array_values( $this->items ) );
	}

	/**
	 * Count the number of items in the collection.
	 *
	 * @since 1.0.2
	 *
	 * @return int
	 */
	public function count() {
		return count( $this->items );
	}

	/**
	 * Determine if an item exists at an offset.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $key Key.
	 *
	 * @return bool
	 */
	public function offsetExists( $key ) {
		return array_key_exists( $key, $this->items );
	}

	/**
	 * Get an item at a given offset.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $key    Key.
	 *
	 * @return mixed
	 */
	public function offsetGet( $key ) {
		return $this->items[ $key ];
	}

	/**
	 * Set the item at a given offset.
	 *
	 * @since 1.0.2
	 * @param mixed $key  Key.
	 * @param mixed $value Value.
	 *
	 * @return void
	 */
	public function offsetSet( $key, $value ) {
		if ( is_null( $key ) ) {
			$this->items[] = $value;
		} else {
			$this->items[ $key ] = $value;
		}
	}

	/**
	 * Get and remove the first item from the collection.
	 *
	 * @since 1.0.2
	 *
	 * @return mixed|null
	 */
	public function shift() {
		return array_shift( $this->items );
	}

	/**
	 * Shuffle the items in the collection.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function shuffle() {
		shuffle( $this->items );

		return $this;
	}

	/**
	 * Chunk the underlying collection array.
	 *
	 * @since 1.0.2
	 * @param int  $size Size.
	 * @param bool $preserve_keys Preserve keys.
	 *
	 * @return static
	 */
	public function chunk( $size, $preserve_keys = false ) {
		$chunks = array();

		foreach ( array_chunk( $this->items, $size, $preserve_keys ) as $chunk ) {
			$chunks[] = new static( $chunk );
		}

		return new static( $chunks );
	}

	/**
	 * Unset the item at a given offset.
	 *
	 * @since 1.0.2
	 *
	 * @param string $key Key.
	 *
	 * @return void
	 */
	public function offsetUnset( $key ) {
		unset( $this->items[ $key ] );
	}

	/**
	 * Get data.
	 *
	 * @since 1.0.2
	 *
	 * @param  array|object $target Target.
	 * @param string       $key Key.
	 * @param null         $default Default.
	 *
	 * @return array
	 */
	public static function data_get( $target, $key, $default = null ) {
		if ( is_null( $key ) ) {
			return $target;
		}

		$key = is_array( $key ) ? $key : explode( '.', $key );

		foreach ( $key as $i => $segment ) {
			unset( $key[ $i ] );

			if ( is_null( $segment ) ) {
				return $target;
			}

			if ( '*' === $segment ) {
				if ( $target instanceof Collection ) {
					$target = $target->all();
				} elseif ( ! is_array( $target ) ) {
					return $default instanceof \Closure ? $default() : $default;
				}
				$result = array();

				foreach ( $target as $item ) {
					$result[] = self::data_get( $item, $key );
				}

				return in_array( '*', $key, true ) ? $result : $result;
			}

			if ( array_key_exists( $segment, $target ) ) {
				$target = $target[ $segment ];
			} elseif ( is_object( $target ) && isset( $target->{$segment} ) ) {
				$target = $target->{$segment};
			} else {
				return $default instanceof \Closure ? $default() : $default;
			}
		}

		return $target;
	}

	/**
	 * Returns collection as pure array.
	 * Does depth array casting.
	 *
	 * @since 1.0.2
	 *
	 * @return array
	 */
	public function to_array() {
		$output = array();
		$value  = null;
		foreach ( $this->items as $key => $value ) {
			if ( ! is_object( $value ) ) {
				$output[ $key ] = $value;
			} elseif ( method_exists( $value, 'to_array' ) ) {
				$output[ $key ] = $value->to_array();
			} else {
				$output[ $key ] = (array) $value;
			}
		}

		return $output;
	}

	/**
	 * Returns collection as a string.
	 *
	 * @since 1.0.2
	 *
	 * @return  string Converted string.
	 */
	public function __toString() {
		$result = wp_json_encode( $this->to_array() );

		if ( ! $result ) {
			return '';
		}

		return $result;
	}
}

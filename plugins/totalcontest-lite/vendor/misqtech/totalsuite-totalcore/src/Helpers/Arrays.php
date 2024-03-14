<?php

namespace TotalContestVendors\TotalCore\Helpers;

/**
 * Class Arrays
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Arrays {

	/**
	 * Parse args.
	 *
	 * @param array $args
	 * @param array $defaults
	 *
	 * @return array
	 */
	public static function parse( $args, $defaults ) {
		$args   = (array) $args;
		$result = (array) $defaults;
		foreach ( $args as $key => &$value ):
			if ( is_array( $value ) && isset( $result[ $key ] ) ):
				$result[ $key ] = self::parse( $value, $result[ $key ] );
			else:
				$result[ $key ] = $value;
			endif;
		endforeach;

		return $result;
	}

	/**
	 * Get item from array using dot notation.
	 *
	 * @param array  $haystack
	 * @param string $needle
	 * @param null   $default
	 *
	 * @return mixed|null
	 */
	public static function getDotNotation( $haystack, $needle, $default = null ) {
		return self::getDeep( $haystack, explode( '.', $needle ), $default );
	}

	/**
	 * Get item from array using array.
	 *
	 * @param array $haystack
	 * @param array $needle
	 * @param null  $default
	 *
	 * @return mixed|null
	 */
	public static function getDeep( $haystack, $needle, $default = null ) {
		if ( empty( $haystack ) || empty( $needle ) ):
			return $default;
		endif;

		if ( is_array( $haystack ) && is_array( $needle ) ):
			foreach ( $needle as $path ):
				if ( isset( $haystack[ $path ] ) ):
					$haystack = $haystack[ $path ];
				else:
					$haystack = $default;
					break;
				endif;
			endforeach;
		endif;

		return $haystack;
	}

	/**
	 * Set an item in array using array.
	 *
	 * @param array $haystack
	 * @param array $needle
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public static function setDeep( $haystack, $needle, $value ) {
		if ( is_array( $haystack ) && is_array( $needle ) ):

			end( $needle );
			$last = key( $needle );
			reset( $needle );

			$path = &$haystack;
			foreach ( $needle as $key => $item ):
				if ( ! isset( $path[ $item ] ) ):
					$path[ $item ] = [];
				endif;

				if ( $key === $last ):
					$path[ $item ] = $value;
					break;
				else:
					$path = &$path[ $item ];
				endif;

			endforeach;
		endif;

		return $haystack;
	}

	/**
	 * Set item in array using dot notation.
	 *
	 * @param array  $haystack
	 * @param string $needle
	 * @param mixed  $value
	 *
	 * @return mixed
	 */
	public static function setDotNotation( $haystack, $needle, $value ) {
		$needle = explode( '.', $needle );

		if ( is_array( $haystack ) && is_array( $needle ) ):

			end( $needle );
			$last = key( $needle );
			reset( $needle );

			$path = &$haystack;
			foreach ( $needle as $key => $item ):
				if ( ! isset( $path[ $item ] ) ):
					$path[ $item ] = [];
				endif;

				if ( $key === $last ):
					$path[ $item ] = $value;
					break;
				else:
					$path = &$path[ $item ];
				endif;

			endforeach;
		endif;

		return $haystack;
	}

    /**
     * @param mixed|array $haystack
     * @param callable $callback
     *
     * @return array|mixed
     */
	public static function apply($haystack, $callback) {

		if(is_array($haystack)) {
			foreach ($haystack as $key => $item) {
				$haystack[$key] = static::apply($item, $callback);
			}

			return $haystack;
		}

		return call_user_func($callback, $haystack);
	}

    /**
     * @param array $haystack
     * @param string $separator
     *
     * @return string
     */
    public static function stringify($haystack, $separator = ', ') {

        foreach ($haystack as $index => $item) {

            if(is_array($item)) {
                $haystack[$index] = static::stringify($item, $separator);
            } else {
                $haystack[$index] = $item;
            }
        }

        return implode($separator, $haystack);
    }
}
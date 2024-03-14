<?php
/**
 * Validator functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils/functions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Checks if the variable “seems” a natural number.
 *
 * That is, it checks if the variable is a positive integer or a string that can be converted to a positive integer.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the variable seems a natural number.
 *
 * @since 2.0.0
 */
function nc_can_be_natural_number( $var ) {
	if ( is_string( $var ) ) {
		return ! empty( preg_match( '/^[0-9]+$/', $var ) );
	}//end if
	return is_int( $var ) && 0 < $var;
}//end nc_can_be_natural_number()

/**
 * Checks if the variable is a valid date (YYYY-MM-DD).
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the variable is a valid date.
 *
 * @since 2.0.0
 */
function nc_is_date( $var ) {
	return is_string( $var ) && ! empty( preg_match( '/^[0-9]{4}-[01][0-9]-[0123][0-9]$/', $var ) );
}//end nc_is_date()

/**
 * Checks if the variable is a valid time (HH:MM).
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the variable is a valid time.
 *
 * @since 2.0.0
 */
function nc_is_time( $var ) {
	return is_string( $var ) && ! empty( preg_match( '/^[012][0-9]:[0-5][0-9]$/', $var ) );
}//end nc_is_time()

/**
 * Checks if the variable is a valid datetime (YYYY-MM-DDThh:mm:ssTZ).
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the variable is a valid datetime.
 *
 * @since 2.0.0
 */
function nc_is_datetime( $var ) {
	if ( ! is_string( $var ) ) {
		return false;
	}//end if

	if ( false === strpos( $var, 'T' ) ) {
		return;
	}//end if

	$datetime = explode( 'T', $var );
	$date     = $datetime[0];
	if ( ! nc_is_date( $date ) ) {
		return false;
	}//end if

	$time = substr( $datetime[1], 0, 5 );
	if ( ! nc_is_time( $time ) ) {
		return false;
	}//end if

	return true;
}//end nc_is_datetime()

/**
 * Checks if the variable is not empty (as in, the opposite of what PHP’s `empty` function returns).
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether var is empty or not.
 *
 * @since 2.0.0
 */
function nc_is_not_empty( $var ) {
	return ! empty( $var );
}//end nc_is_not_empty()

/**
 * Checks if the varirable is a valid Nelio Content license.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the varirable is a valid Nelio Content license.
 *
 * @since 2.0.0
 */
function nc_is_valid_license( $var ) {
	if ( ! is_string( $var ) ) {
		return false;
	}//end if

	return (
		! empty( preg_match( '/^[a-zA-Z0-9$#]{21}$/', $var ) ) ||
		! empty( preg_match( '/^[a-zA-Z0-9$#]{26}$/', $var ) )
	);
}//end nc_is_valid_license()

/**
 * Checks if the varirable is a valid URL.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the varirable is a valid URL.
 *
 * @since 2.0.0
 */
function nc_is_url( $var ) {
	return is_string( $var ) && ! empty( filter_var( $var, FILTER_VALIDATE_URL ) );
}//end nc_is_url()

/**
 * Checks if the varirable is a valid email address.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the varirable is a valid email address.
 *
 * @since 2.0.0
 */
function nc_is_email( $var ) {
	return is_string( $var ) && ! empty( preg_match( '/^[a-z0-9._%+-]+@[a-z0-9][a-z0-9.-]*\.[a-z]{2,63}$/', $var ) );
}//end nc_is_email()

/**
 * Checks if the varirable is a valid twitter handle.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the varirable is a valid twitter handle.
 *
 * @since 2.0.0
 */
function nc_is_twitter_handle( $var ) {
	return is_string( $var ) && ! empty( preg_match( '/^@[^@\s]+$/', $var ) );
}//end nc_is_twitter_handle()

/**
 * Checks if the variable seems a boolean or not.
 *
 * That is, it checks if the variable is indeed a boolean, or if it’s a string such as “true” or “false”.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the varirable is a boolean or not.
 *
 * @since 2.0.0
 */
function nc_can_be_bool( $var ) {
	return true === $var || false === $var || 'true' === $var || 'false' === $var;
}//end nc_can_be_bool()

/**
 * Converts a variable that seems a bool into an actual bool.
 *
 * @param mixed $var the var that seems like a bool.
 *
 * @return boolean the var as a boolean.
 *
 * @since 2.0.0
 */
function nc_bool( $var ) {
	return true === $var || 'true' === $var;
}//end nc_bool()

/**
 * Checks if the varirable is a valid post type (i.e. a post type that exists and is enabled in Nelio Content) or not.
 *
 * @param mixed $var the var we want to check.
 *
 * @return boolean whether the varirable is a valid post type.
 *
 * @since 2.0.0
 */
function nc_is_valid_post_type( $var ) {

	if ( empty( $var ) ) {
		return false;
	}//end if

	$settings   = Nelio_Content_Settings::instance();
	$post_types = $settings->get( 'calendar_post_types', array() );
	if ( ! in_array( $var, $post_types, true ) ) {
		return false;
	}//end if

	$post_type = get_post_type_object( $var );
	return ! empty( $post_type );

}//end nc_is_valid_post_type()

/**
 * Returns a function that checks if the variable is an array and all its elements are of the given predicate.
 *
 * @param callable $predicate name of a boolean function to test each element in the array.
 *
 * @return function a function that checks if the variable is an array of the expected type.
 *
 * @since 2.2.2
 */
function nc_is_array( $predicate ) {
	return function( $value ) use ( $predicate ) {
		return is_array( $value ) && array_reduce(
			$value,
			function( $carry, $item ) use ( $predicate ) {
				return $carry && call_user_func( $predicate, $item );
			},
			true
		);
	};
}//end nc_is_array()

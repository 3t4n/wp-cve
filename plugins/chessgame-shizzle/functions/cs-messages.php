<?php
/*
 * Functions to handle static variables for messages and errors.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add messages from the form to show again after submitting an entry.
 * @param message: string with html and text to show.
 * @param error: if it is a validation error for the form (default false).
 * @param error_field: which field does not validate.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_add_message( $message = false, $error = false, $error_field = false ) {

	static $chessgame_shizzle_messages;

	if ( ! isset( $chessgame_shizzle_messages ) || ! is_array( $chessgame_shizzle_messages ) ) {
		$chessgame_shizzle_messages = array();
	}

	if ( $message ) {
		$chessgame_shizzle_messages[] = $message;
	}

	if ( $error === true ) {
		chessgame_shizzle_add_error( true );
	}

	if ( $error_field ) {
		chessgame_shizzle_add_error_field( $error_field );
	}

	return $chessgame_shizzle_messages;

}


/*
 * Returns string with html with messages.
 *
 * @return string html with error messages wrapped in p elements.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_messages() {

	$chessgame_shizzle_messages = chessgame_shizzle_add_message();
	$chessgame_shizzle_errors = chessgame_shizzle_get_errors();
	$chessgame_shizzle_error_fields = chessgame_shizzle_get_error_fields();

	$messages = '';

	if ( $chessgame_shizzle_error_fields && is_array( $chessgame_shizzle_error_fields ) && ! empty( $chessgame_shizzle_error_fields ) ) {
		// There was no data filled in, even though that was mandatory.
		$chessgame_shizzle_error_fields = chessgame_shizzle_array_flatten( $chessgame_shizzle_error_fields );
		$chessgame_shizzle_error_fields = implode( ', ', $chessgame_shizzle_error_fields );
		$chessgame_shizzle_messages[] = '<p class="error_fields cs-error-fields"><strong>' . esc_html__('There were errors submitting your chessgame.', 'chessgame-shizzle') . '</strong></p>';
		$chessgame_shizzle_messages[] = '<p class="error_fields cs-error-fields" style="display: none;">' . chessgame_shizzle_array_flatten( $chessgame_shizzle_error_fields ) . '</p>';
	}

	$chessgame_shizzle_messages = apply_filters( 'chessgame_shizzle_messages', $chessgame_shizzle_messages );

	foreach ( $chessgame_shizzle_messages as $message ) {
		$messages .= $message;
	}

	return $messages;

}


/*
 * Add errors to return the form after submitting an entry.
 *
 * @param bool $error is there a fatal error in submitting the form.
 *
 * @return bool if there was a fatal error already.
 *
 * @since 1.1.2
 */
function chessgame_shizzle_add_error( $error = false ) {

	static $chessgame_shizzle_errors;

	if ( ! isset( $chessgame_shizzle_errors ) || ! is_bool( $chessgame_shizzle_errors ) ) {
		$chessgame_shizzle_errors = false;
	}

	if ( $error === true ) {
		$chessgame_shizzle_errors = $error;
	}

	return $chessgame_shizzle_errors;

}


/*
 * Returns bool, if errors were found.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_errors() {

	$chessgame_shizzle_errors = chessgame_shizzle_add_error();

	if ( ! isset( $chessgame_shizzle_errors ) ) {
		$chessgame_shizzle_errors = false;
	}

	$chessgame_shizzle_errors = apply_filters( 'chessgame_shizzle_errors', $chessgame_shizzle_errors );

	return $chessgame_shizzle_errors;

}


/*
 * Add error_field to mark as red in the form after submitting an entry.
 *
 * @param string $field name of the formfield.
 *
 * @return array error_fields that were added to the static var.
 *
 * @since 1.1.2
 */
function chessgame_shizzle_add_error_field( $error_field = false ) {

	static $chessgame_shizzle_error_fields;

	if ( ! isset( $chessgame_shizzle_error_fields ) || ! is_array( $chessgame_shizzle_error_fields ) ) {
		$chessgame_shizzle_error_fields = array();
	}

	if ( $error_field ) {
		$chessgame_shizzle_error_fields[] = $error_field;
	}

	return $chessgame_shizzle_error_fields;

}


/*
 * Returns array with the fields that didnot validate.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_error_fields() {

	$chessgame_shizzle_error_fields = chessgame_shizzle_add_error_field();

	$chessgame_shizzle_error_fields = apply_filters( 'chessgame_shizzle_error_fields', $chessgame_shizzle_error_fields );

	return $chessgame_shizzle_error_fields;

}


/*
 * Add formdata from the form to show again after submitting an entry.
 * Parameters:
 * - field: string with name of the formfield.
 * - value: value of the formfield to be used again.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_add_formdata( $field = false, $value = false ) {

	static $chessgame_shizzle_formdata;

	if ( ! isset( $chessgame_shizzle_formdata ) || ! is_array( $chessgame_shizzle_formdata ) ) {
		$chessgame_shizzle_formdata = array();
	}

	if ( $field && $value ) {
		$chessgame_shizzle_formdata["$field"] = esc_attr( $value );
	}

	return $chessgame_shizzle_formdata;

}


/*
 * formdata to be used again on the frontend form after submitting.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_formdata() {

	$chessgame_shizzle_formdata = chessgame_shizzle_add_formdata();

	$chessgame_shizzle_formdata = apply_filters( 'chessgame_shizzle_formdata', $chessgame_shizzle_formdata );

	return $chessgame_shizzle_formdata;

}


/*
 * Flattens an array, or returns false on fail.
 * Taken from:
 * https://stackoverflow.com/questions/7179799/how-to-flatten-array-of-arrays-to-array
 *
 * @param array Array flat or multi-dimensional.
 * @return array Array flat or false on fail.
 *
 * @since 1.2.6
 */
function chessgame_shizzle_array_flatten( $array ) {

	if ( ! is_array( $array ) ) {
		return false;
	}

	$result = array();
	foreach ($array as $key => $value) {
		if ( is_array($value) ) {
			$result = array_merge( $result, array_flatten($value) );
		} else {
			$result[$key] = $value;
		}
	}

	return $result;

}

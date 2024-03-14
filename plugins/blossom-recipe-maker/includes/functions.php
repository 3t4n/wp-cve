<?php
/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns sanitized data of $_POST, $_GET or $_REQUEST.
 *
 * @param  string $type
 * @return array
 */
function blossom_recipe_maker_get_submitted_data( $type = 'request' ) {
	switch ( $type ) {
		case 'get':
			$data = $_GET; // @phpcs:ignore
			break;

		case 'post':
		  $data = $_POST; // @phpcs:ignore
			break;

		default:
		  $data = $_REQUEST; // @phpcs:ignore

			break;
	}

	return blossom_recipe_maker_sanitize_array( $data );
}

function blossom_recipe_maker_sanitize_array( array $data ) {
	foreach ( $data as $key => &$value ) {
		if ( is_array( $value ) ) {
			$value = blossom_recipe_maker_sanitize_array( $value );
		} else {
			if ( is_int( $value ) ) {
				$value = (int) $value;
			} elseif ( is_string( $value ) ) {
				$value = sanitize_text_field( wp_unslash( $value ) );
			}
		}
	}

	return $data;
}


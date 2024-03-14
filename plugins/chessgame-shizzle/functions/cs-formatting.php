<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Sanitize post content field.
 *
 * @since 1.0.1
 */
function chessgame_shizzle_sanitize_content( $content ) {
	$content = wp_kses_post( $content );
	return $content;
}


/*
 * Sanitize general meta fields.
 *
 * @since 1.0.1
 */
function chessgame_shizzle_sanitize_meta( $meta ) {
	$meta = sanitize_text_field( $meta );
	return $meta;
}


/*
 * Sanitize Elo meta fields.
 * Used for Elo ratings.
 *
 * @since 1.0.1
 */
function chessgame_shizzle_sanitize_meta_elo( $meta ) {
	$meta = (int) $meta;
	if ( $meta > 5000 ) { // unpossible!?!
		return 0;
	}
	return $meta;
}


/*
 * Sanitize meta field for Eco code.
 * Make sure we only accept values that are set as a key in our own data array.
 *
 * @since 1.0.1
 */
function chessgame_shizzle_sanitize_meta_code( $code ) {
	// Eco Codes
	$code = sanitize_text_field( $code );
	$code = strtoupper( $code );
	$codes = chessgame_shizzle_get_array_openingcodes();
	foreach ( $codes as $key => $value ) {
		if ( $code === $key ) {
			return $key;
		}
	}
	return 0; // No valid code found.
}


/*
 * Sanitize PGN meta field.
 * Just like the post content, but some specifics to have PGN not break.
 *
 * @since 1.0.1
 */
function chessgame_shizzle_sanitize_pgn( $pgn ) {
	$pgn = chessgame_shizzle_cleanup_pgn( $pgn );
	$pgn = wp_kses_post( $pgn );
	return $pgn;
}


/*
 * Function to format values for beeing send by mail.
 * Since users can input malicious code we have to make
 * sure that this code is being taken care of.
 *
 * @since 1.0.3
 */
function chessgame_shizzle_format_values_for_mail( $value ) {
	$value = htmlspecialchars_decode($value, ENT_COMPAT);
	$value = str_replace('<', '{', $value);
	$value = str_replace('>', '}', $value);
	$value = str_replace('&#34;', '"', $value);
	$value = str_replace('&#034;', '"', $value);
	$value = str_replace('&#39;', "'", $value);
	$value = str_replace('&#039;', "'", $value);
	$value = str_replace('&#47;', '/', $value);
	$value = str_replace('&#047;', '/', $value);
	$value = str_replace('&#92;', '\\', $value);
	$value = str_replace('&#092;', '\\', $value);
	return $value;
}


/*
* Truncate a slug.
*
* @since 1.1.8
*
* @see utf8_uri_encode()
*
* @param string $slug   The slug to truncate.
*
* @return string The truncated slug.
*/
function chessgame_shizzle_truncate_slug( $slug ) {
	$slug = substr( $slug, 0, 100 );
	$slug = utf8_uri_encode( $slug, 100 );
	$slug = remove_accents( $slug);
	$slug = sanitize_file_name( $slug);
	return rtrim( $slug, '-' );
}

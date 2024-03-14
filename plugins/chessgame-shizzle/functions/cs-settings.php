<?php
/*
 * Settings page for the guestbook
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Register Settings
 *
 * @since 1.0.0
 */
function chessgame_shizzle_register_settings() {
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-boardtheme',         'strval' ); // 'shredderchess'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-honeypot',           'strval' ); // 'true'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-honeypot_value',     'intval' ); // $random
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-mail-from',          'strval' ); // ''
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-nonce',              'strval' ); // 'true'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-notifybymail',       'strval' ); // comma separated list with user IDs
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-piecetheme',         'strval' ); // 'alpha'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-rss',                'strval' ); // 'true'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-simple-list-search', 'strval' ); // 'true'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-timeout',            'strval' ); // 'true'
	register_setting( 'chessgame_shizzle_options', 'chessgame_shizzle-version',            'strval' ); // C_SHIZZLE_VER

}
add_action( 'admin_init', 'chessgame_shizzle_register_settings' );


/*
 * Use a custom field name for the form fields that are different for each website.
 *
 * @param string field name of the requested field.
 * @return string hashed fieldname or fieldname, prepended with chessgame_shizzle.
 *
 * @since 1.0.6
 */
function chessgame_shizzle_get_field_name( $field ) {

	if ( ! in_array( $field, array( 'honeypot', 'honeypot2', 'nonce', 'timeout', 'timeout2' ) ) ) {
		return 'chessgame_shizzle_' . $field;
	}

	$blog_url = get_option( 'siteurl' );
	// $blog_url = get_bloginfo('wpurl'); // Will be different depending on scheme (http/https).

	$key = 'chessgame_shizzle_' . $field . '_field_name_' . $blog_url;
	$field_name = wp_hash( $key, 'auth' );
	$field_name = 'chessgame_shizzle_' . $field_name;

	return $field_name;
}


/*
 * Set default options.
 * Idea is to have all options in the database and thus cached, so we hit an empty cache less often.
 *
 * @since 1.0.8
 */
function chessgame_shizzle_set_defaults() {

	if ( get_option('chessgame_shizzle-boardtheme', false) === false ) {
		update_option( 'chessgame_shizzle-boardtheme', 'shredderchess' );
	}
	if ( get_option('chessgame_shizzle-piecetheme', false) === false ) {
		update_option( 'chessgame_shizzle-piecetheme', 'alpha' );
	}
	if ( get_option('chessgame_shizzle-honeypot', false) === false ) {
		update_option( 'chessgame_shizzle-honeypot', 'true' );
	}
	if ( get_option('chessgame_shizzle-honeypot_value', false) === false ) {
		$random = rand( 1, 99 );
		update_option( 'chessgame_shizzle-honeypot_value', $random );
	}
	if ( get_option('chessgame_shizzle-nonce', false) === false ) {
		update_option( 'chessgame_shizzle-nonce', 'true' );
	}
	if ( get_option('chessgame_shizzle-rss', false) === false ) {
		update_option( 'chessgame_shizzle-rss', 'true' );
	}
	if ( get_option('chessgame_shizzle-simple-list-search', false) === false ) {
		update_option( 'chessgame_shizzle-simple-list-search', 'true' );
	}
	if ( get_option('chessgame_shizzle-timeout', false) === false ) {
		update_option( 'chessgame_shizzle-timeout', 'true' );
	}

	update_option('chessgame_shizzle-version', C_SHIZZLE_VER);

}

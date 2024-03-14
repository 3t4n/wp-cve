<?php


/*
 * Set meta_keys so we can find the post with the shortcode back.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_save_post_for_shortcode_meta( $id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;

	$post = get_post( $id );

	if ( has_shortcode( $post->post_content, 'chessgame_shizzle_form' ) ) {
		// Set a meta_key so we can find the post with the shortcode back.
		$meta_value = get_post_meta( $id, 'chessgame_shizzle_form', true );
		if ( $meta_value !== 'true' ) {
			update_post_meta( $id, 'chessgame_shizzle_form', 'true' );
		}
	} else {
		// Remove the meta_key in case it is set.
		delete_post_meta( $id, 'chessgame_shizzle_form' );
	}

	if ( has_shortcode( $post->post_content, 'chessgame_shizzle_simple_list' ) ) {
		// Set a meta_key so we can find the post with the shortcode back.
		$meta_value = get_post_meta( $id, 'chessgame_shizzle_simple_list', true );
		if ( $meta_value !== 'true' ) {
			update_post_meta( $id, 'chessgame_shizzle_simple_list', 'true' );
		}
	} else {
		// Remove the meta_key in case it is set.
		delete_post_meta( $id, 'chessgame_shizzle_simple_list' );
	}

}
add_action( 'save_post', 'chessgame_shizzle_save_post_for_shortcode_meta' );


/*
 * Make the meta field above protected, so it is not in the custom fields metabox.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_is_protected_shortcode_meta( $protected, $meta_key, $meta_type ) {

	switch ($meta_key) {
		case 'chessgame_shizzle_form':
			return true;
		case 'chessgame_shizzle_simple_list':
			return true;
	}

	return $protected;

}
add_filter( 'is_protected_meta', 'chessgame_shizzle_is_protected_shortcode_meta', 10, 3 );


/*
 * Register the meta fields for posts and chessgames.
 *
 * @since 1.2.7
 */
function chessgame_shizzle_register_meta() {

	$args = array(
		'object_subtype'    => '', // Specific post type, empty for all post types.
		'type'              => 'string', // Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
		'description'       => '',
		'default'           => '',
		'single'            => true, // Whether the meta key has one value per object, or an array of values per object.
		'sanitize_callback' => null,
		'auth_callback'     => null,
		'show_in_rest'      => false,
		'revisions_enabled' => true, // Since WordPress 6.4.
	);
	register_meta( 'post', 'chessgame_shizzle_form', $args );
	register_meta( 'post', 'chessgame_shizzle_simple_list', $args );

	$args = array(
		'object_subtype'    => 'cs_chessgame', // Specific post type, empty for all post types.
		'type'              => 'string', // Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
		'description'       => '',
		'default'           => '',
		'single'            => true, // Whether the meta key has one value per object, or an array of values per object.
		'sanitize_callback' => null,
		'auth_callback'     => null,
		'show_in_rest'      => false,
		'revisions_enabled' => true, // Since WordPress 6.4.
	);
	register_meta( 'post', 'cs_chessgame_pgn', $args );
	register_meta( 'post', 'cs_chessgame_white_player', $args );
	register_meta( 'post', 'cs_chessgame_black_player', $args );
	register_meta( 'post', 'cs_chessgame_result', $args );
	register_meta( 'post', 'cs_chessgame_datetime', $args );
	register_meta( 'post', 'cs_chessgame_location', $args );
	register_meta( 'post', 'cs_chessgame_tournament', $args );
	register_meta( 'post', 'cs_chessgame_round', $args );
	register_meta( 'post', 'cs_chessgame_submitter', $args );
	register_meta( 'post', 'cs_chessgame_code', $args );

	$args = array(
		'object_subtype'    => 'cs_chessgame', // Specific post type, empty for all post types.
		'type'              => 'integer', // Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
		'description'       => '',
		'default'           => 0,
		'single'            => true, // Whether the meta key has one value per object, or an array of values per object.
		'sanitize_callback' => null,
		'auth_callback'     => null,
		'show_in_rest'      => false,
		'revisions_enabled' => true, // Since WordPress 6.4.
	);
	register_meta( 'post', 'cs_chessgame_elo_white_player', $args );
	register_meta( 'post', 'cs_chessgame_elo_black_player', $args );
	register_meta( 'post', 'cs_chessgame_level', $args );

	$args = array(
		'object_subtype'    => 'cs_chessgame', // Specific post type, empty for all post types.
		'type'              => 'boolean', // Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
		'description'       => '',
		'default'           => 0,
		'single'            => true, // Whether the meta key has one value per object, or an array of values per object.
		'sanitize_callback' => null,
		'auth_callback'     => null,
		'show_in_rest'      => false,
		'revisions_enabled' => true, // Since WordPress 6.4.
	);
	register_meta( 'post', 'cs_chessgame_puzzle', $args );

}
add_action( 'init', 'chessgame_shizzle_register_meta', 99 );

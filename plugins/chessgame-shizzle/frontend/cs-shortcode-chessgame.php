<?php
/*
 * Shortcode Function for single game in iframe.
 *
 * @since 1.0.9
 */
function chessgame_shizzle_shortcode_game( $shortcode_atts ) {

	$output = '';

	if ( (int) $shortcode_atts['postid'] > 0 ) {
		$postid = (int) $shortcode_atts['postid'];
	} else {
		return $output;
	}

	$post = get_post( $postid );

	if ( is_object( $post ) && is_a( $post, 'WP_Post' ) ) {
		if ($post->post_status !== 'trash') {
			$output .= chessgame_shizzle_get_iframe( $postid );
		}
	}

	$output = apply_filters( 'chessgame_shizzle_shortcode_game', $output );

	return $output;

}
add_shortcode( 'chessgame_shizzle_game', 'chessgame_shizzle_shortcode_game' );


/*
 * Shortcode Function for single game in iframe.
 *
 * @since 1.0.9
 */
function chessgame_shizzle_shortcode_game_extended( $shortcode_atts ) {

	$output = '';

	if ( (int) $shortcode_atts['postid'] > 0 ) {
		$postid = (int) $shortcode_atts['postid'];
	} else {
		return $output;
	}

	$post = get_post( $postid );

	if ( is_object( $post ) && is_a( $post, 'WP_Post' ) ) {
		if ($post->post_status !== 'trash') {
			$output .= chessgame_shizzle_get_iframe_extended( $postid );
		}
	}

	$output = apply_filters( 'chessgame_shizzle_shortcode_game', $output );

	return $output;

}
add_shortcode( 'chessgame_shizzle_game_extended', 'chessgame_shizzle_shortcode_game_extended' );

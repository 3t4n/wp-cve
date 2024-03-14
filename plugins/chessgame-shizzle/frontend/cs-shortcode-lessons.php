<?php
/*
 * Shortcode Function for lessons in iframe.
 *
 * @since 1.1.9
 */
function chessgame_shizzle_shortcode_lessons( $shortcode_atts ) {

	return chessgame_shizzle_get_lesson();

}
add_shortcode( 'chessgame_shizzle_lessons', 'chessgame_shizzle_shortcode_lessons' );

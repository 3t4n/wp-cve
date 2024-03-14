<?php

/*	Exit if .php file accessed directly
*/
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

$settings = get_option( 'jr_saoe_settings' );
global $jr_saoe_filters;
foreach ( $jr_saoe_filters as $one_filter ) {
	if ( !isset( $one_filter['disabled'] ) ) {
		if ( $settings[ $one_filter['filter'] ] ) {
			if ( isset( $one_filter['functions'] ) ) {
				foreach ( $one_filter['functions'] as $function => $parameters ) {
					add_filter( 
						$one_filter['filter'], 
						$function, 
						$settings['priority'][ $one_filter['filter'] ], 
						$parameters
					);
				}
			} else {
				add_filter( 
					$one_filter['filter'], 
					'do_shortcode', 
					$settings['priority'][ $one_filter['filter'] ] 
				);
			}
		}
	}
}


/*	Special Functions, written to make this Table-Driven Approach work
	for Filters where the do_shortcode() function cannot be used directly.
*/

/*	Written to do_shortcode() at wp_trim_excerpt Filter.
	Code adapted from WordPress 3.9.1 Function wp_trim_excerpt() source code
	in wp-includes/formatting.php.
*/
function jr_saoe_wp_trim_excerpt( $text, $raw_excerpt ) {
	if ( '' == $raw_excerpt ) {
		$text = get_the_content( '' );
		/*	Rather than strip_shortcodes():
		*/
		$text = do_shortcode( $text );
		$text = apply_filters( 'the_content', $text );
		$text = str_replace(']]>', ']]&gt;', $text);
		$excerpt_length = apply_filters( 'excerpt_length', 55 );
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	} else {
		$text = do_shortcode( $raw_excerpt );
	}
	return $text;
}

/*	Written to do_shortcode() at get_post_metadata Filter.
	Code adapted from WordPress 3.9 get_metadata() source code.
*/
function jr_saoe_get_post_metadata( $meta_value, $post_id, $meta_key, $single ) {
	if ( !( $meta_cache = wp_cache_get( $post_id, 'post_meta' ) ) ) {
		$meta_cache = update_meta_cache( 'post', array( $post_id ) );
		$meta_cache = $meta_cache[$post_id];
	}
	if ( $meta_key ) {
		if ( isset( $meta_cache[$meta_key] ) ) {
			if ( $single )
				return jr_saoe_maybe_array( maybe_unserialize( $meta_cache[$meta_key][0] ) );
			else
				return jr_saoe_maybe_array( array_map( 'maybe_unserialize', $meta_cache[$meta_key] ) );
		}
	} else {
		return jr_saoe_maybe_array( $meta_cache );
	}
	/*	do_shortcode() not required, so let get_metadata() do the work.
	*/
	return NULL;
}

function jr_saoe_maybe_array( $meta ) {
	if ( is_array( $meta ) ) {
		array_walk_recursive( $meta, 'jr_saoe_array_callback' );
	} else {
		$meta = do_shortcode( $meta );
	}
	return $meta;
}

function jr_saoe_array_callback( &$value, $key ) {
	$value = do_shortcode( $value );
}
?>
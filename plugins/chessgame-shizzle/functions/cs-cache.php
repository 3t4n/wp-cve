<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Clear the cache of the most common Cache plugins.
 *
 * @since 1.1.2
 */
function chessgame_shizzle_clear_cache() {

	/* Default WordPress */
	wp_cache_flush();

	/* Cachify */
	if ( class_exists('Cachify') ) {
		$cachify = new Cachify();
		if ( method_exists($cachify, 'flush_total_cache') ) {
			$cachify->flush_total_cache(true);
		}
	}

	/* W3 Total Cache */
	if ( function_exists('w3tc_pgcache_flush') ) {
		w3tc_pgcache_flush();
	}

	/* WP Fastest Cache */
	if ( class_exists('WpFastestCache') ) {
		$wp_fastest_cache = new WpFastestCache();
		if ( method_exists($wp_fastest_cache, 'deleteCache') ) {
			$wp_fastest_cache->deleteCache();
		}
	}

	/* WP Super Cache */
	if ( function_exists('wp_cache_clear_cache') ) {
		$GLOBALS['super_cache_enabled'] = 1;
		wp_cache_clear_cache();
	}

}


/*
 * Flush widget cache on save_post action.
 * Used for the Recent Chessgame widget.
 *
 * @since 1.1.2
 *
 * @param int post_id ID of the post that gets saved/deleted.
 */
function chessgame_shizzle_clear_cache_for_widgets( $post_id ) {

	$post_type = get_post_type($post_id);

	if ( 'cs_chessgame' === $post_type ) {
		chessgame_shizzle_clear_cache();
	}

}
add_action( 'save_post', 'chessgame_shizzle_clear_cache_for_widgets' );
add_action( 'delete_post', 'chessgame_shizzle_clear_cache_for_widgets' );

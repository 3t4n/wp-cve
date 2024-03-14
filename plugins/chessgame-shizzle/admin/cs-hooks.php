<?php
/*
 * Misc hooks for Chessgame Shizzle admin.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Load CSS for admin.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_admin_enqueue() {
	wp_enqueue_style( 'chessgame-shizzle-admin-css', plugins_url( '/css/chessgame-shizzle-admin.css', __FILE__ ), false, C_SHIZZLE_VER, 'all' );
	wp_enqueue_script( 'chessgame-shizzle-admin-js', plugins_url( '/js/chessgame-shizzle-admin.js', __FILE__ ), 'jquery', C_SHIZZLE_VER, true );

	// Also load frontend CSS for lessons.
	chessgame_shizzle_frontend_enqueue();
}
add_action( 'admin_enqueue_scripts', 'chessgame_shizzle_admin_enqueue' );

<?php
/*
 * Misc hooks for Chessgame Shizzle frontend.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Register JS/CSS for frontend.
 * Enqueue where necessary, in content filter and widgets.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_frontend_enqueue() {

	wp_register_style( 'chessgame-shizzle-frontend-css', plugins_url( '/css/chessgame-shizzle-frontend.css', __FILE__ ), false, C_SHIZZLE_VER, 'all' );
	wp_register_style( 'cs-jquery-ui', plugins_url( '/css/jquery-ui.css', __FILE__ ), false, C_SHIZZLE_VER, 'all' );
	wp_register_script( 'chessgame-shizzle-frontend-js', plugins_url( '/js/chessgame-shizzle-frontend.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), C_SHIZZLE_VER, true );
	$data_to_be_passed = array(
		'ajax_url'    => admin_url('admin-ajax.php'),
		'honeypot'    => chessgame_shizzle_get_field_name( 'honeypot' ),
		'honeypot2'   => chessgame_shizzle_get_field_name( 'honeypot2' ),
		'timeout'     => chessgame_shizzle_get_field_name( 'timeout' ),
		'timeout2'    => chessgame_shizzle_get_field_name( 'timeout2' ),
		'nonce'       => chessgame_shizzle_get_field_name( 'nonce' ),
		'nonce_fen'   => wp_create_nonce( 'chessgame_shizzle_fen_ajax' ),
		'post_id'     => get_the_ID(),
		'preview_url' => C_SHIZZLE_URL . '/thirdparty/pgn4web/cs-preview-iframe.php',
		'oldbrowser'  => esc_html__( 'You are using a browser that is too old, please upgrade to use the Preview function.', 'chessgame-shizzle' ),
	);
	wp_localize_script( 'chessgame-shizzle-frontend-js', 'chessgame_shizzle_frontend_script', $data_to_be_passed );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'cs-jquery-ui' );

	wp_enqueue_style( 'chessgame-shizzle-frontend-css' );
	wp_enqueue_script( 'chessgame-shizzle-frontend-js' );

}
add_action('wp_enqueue_scripts', 'chessgame_shizzle_frontend_enqueue');

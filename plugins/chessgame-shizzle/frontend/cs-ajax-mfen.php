<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Callback function for handling the Ajax requests that are generated from the JavaScript in chessgame-shizzle-frontend.js
 */
add_action( 'wp_ajax_chessgame_shizzle_fen_image_ajax', 'chessgame_shizzle_fen_image_ajax_callback' );
add_action( 'wp_ajax_nopriv_chessgame_shizzle_fen_image_ajax', 'chessgame_shizzle_fen_image_ajax_callback' );
function chessgame_shizzle_fen_image_ajax_callback() {

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['security']) ) {
		$verified = wp_verify_nonce( $_POST['security'], 'chessgame_shizzle_fen_ajax' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		esc_html_e('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		die();
	}

	$fen = '';
	if (isset($_POST['fen'])) {
		$fen = wp_kses_post( trim( (string) $_POST['fen'] ) );
	}
	if (isset($_POST['post_id'])) {
		$post_id = (int) $_POST['post_id'];
		$post = get_post( $post_id );
		$description = chessgame_shizzle_truncate_slug( get_the_title( $post ) );
	}

	if ( ! function_exists( 'gd_info' ) ) {
		echo 'error: GD extension is not loaded for PHP, please contact the site administrator.';
	} else if ( is_object( $post ) && is_a( $post, 'WP_Post' ) && isset($fen) && strlen($fen) > 0 && isset( $description ) ) {

		$board = new MFEN();
		$random = rand( 1, 20 );
		if ( $random === 10 ) {
			// purge cache once in a while, but always before rendering.
			$board->purge();
		}
		$board->set_fen( $fen );
		$filename = $board->render( true, $description ); // true returns filename instead of raw image.
		$board->destroy();

		echo $filename;

	} else {
		echo 'error: no FEN code or post ID set.';
	}

	die(); // this is required to return a proper result

}

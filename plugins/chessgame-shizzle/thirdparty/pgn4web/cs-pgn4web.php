<?php
/*
 * Specific hooks and functions for pgn4web.
 */


/*
 * Register CSS/JS for pgn4web.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_pgn4web_register() {
	wp_register_style( 'cs-pgn4web-mini', plugins_url( '/mini.css', __FILE__ ), false, C_SHIZZLE_VER, 'all' );
	wp_register_script('cs-pgn4web', plugins_url('/pgn4web.js', __FILE__), false, C_SHIZZLE_VER, true );

	wp_register_script('cs-pgn4web-cs', plugins_url('cs-pgn4web.js', __FILE__), false, C_SHIZZLE_VER, true );
	$piecetheme_url = chessgame_shizzle_get_piecetheme_url();

	$data_to_be_passed = array(
		'imagepath'      => esc_attr( $piecetheme_url ),
		'startButton'    => esc_attr__( 'go to game start', 'chessgame-shizzle' ),
		'backButton'     => esc_attr__( 'move backward', 'chessgame-shizzle' ),
		'autoplayButton' => esc_attr__( 'toggle autoplay (start)', 'chessgame-shizzle' ), // FIXME: js overrides on click event (stop, start).
		'forwardButton'  => esc_attr__( 'move forward', 'chessgame-shizzle' ),
		'endButton'      => esc_attr__( 'go to game end', 'chessgame-shizzle' ),
	);
	wp_localize_script( 'cs-pgn4web-cs', 'cs_pgn4web', $data_to_be_passed );
}
add_action('wp_enqueue_scripts', 'chessgame_shizzle_pgn4web_register');


/*
 * Enqueue CSS/JS for pgn4web.
 * Call this wherever you need it.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_pgn4web_enqueue() {
	wp_enqueue_style( 'cs-pgn4web-mini' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cs-pgn4web' );
	wp_enqueue_script( 'cs-pgn4web-cs' );
}


/*
 * Load CSS/JS for pgn4web directly without enqueue.
 * Call this wherever you need it.
 * This can be useful when called from an iframe that has no enqueue functionality available.
 *
 * @param int $post_id ID of the post. (since 1.2.0)
 *
 * @since 1.1.9
 */
function chessgame_shizzle_pgn4web_dead_enqueue( $post_id ) {

	// Localize
	$piecetheme_url = chessgame_shizzle_get_piecetheme_url();
	$piecetheme_url = str_replace('/', '\/', $piecetheme_url);
	?>
	<script type='text/javascript'>
	var cs_pgn4web = {
		"imagepath":"<?php echo esc_attr( $piecetheme_url ); ?>",
		"startButton":"<?php esc_attr_e( 'go to game start', 'chessgame-shizzle' ); ?>",
		"backButton":"<?php esc_attr_e( 'move backward', 'chessgame-shizzle' ); ?>",
		"autoplayButton":"<?php esc_attr_e( 'toggle autoplay (start)', 'chessgame-shizzle' ); ?>",
		"forwardButton":"<?php esc_attr_e( 'move forward', 'chessgame-shizzle' ); ?>",
		"endButton":"<?php esc_attr_e( 'go to game end', 'chessgame-shizzle' ); ?>"
	};
	</script>
	<?php

	// CSS
	$url = C_SHIZZLE_URL . '/frontend/css/chessgame-shizzle-frontend.css?ver=' . C_SHIZZLE_VER;
	echo "<link rel='stylesheet' id='chessgame-shizzle-frontend-css-css' href='" . esc_attr( $url ) . "' type='text/css' media='all' />";

	$url = plugins_url( '/mini.css', __FILE__ ) . '?ver=' . C_SHIZZLE_VER;
	echo "<link rel='stylesheet' id='cs-pgn4web-mini-css' href='" . esc_attr( $url ) . "' type='text/css' media='all' />";

	// JS
	$url = get_bloginfo('wpurl') . '/wp-includes/js/jquery/jquery.js?ver=' . C_SHIZZLE_VER;
	echo "<script type='text/javascript' src='" . esc_attr( $url ) . "'></script>";

	$url = C_SHIZZLE_URL . '/frontend/js/chessgame-shizzle-frontend.js?ver=' . C_SHIZZLE_VER;
	echo "<script type='text/javascript' src='" . esc_attr( $url ) . "'></script>";
	?>

	<script type='text/javascript'>
	var chessgame_shizzle_frontend_script = {
		"ajax_url": "<?php echo admin_url('admin-ajax.php'); ?>",
		"honeypot": "<?php echo chessgame_shizzle_get_field_name( 'honeypot' ); ?>",
		"honeypot2": "<?php echo chessgame_shizzle_get_field_name( 'honeypot2' ); ?>",
		"timeout": "<?php echo chessgame_shizzle_get_field_name( 'timeout' ); ?>",
		"timeout2": "<?php echo chessgame_shizzle_get_field_name( 'timeout2' ); ?>",
		"nonce_fen": "<?php echo wp_create_nonce( 'chessgame_shizzle_fen_ajax' ); ?>",
		"post_id": "<?php echo (int) $post_id; ?>",
		"preview_url": "<?php echo C_SHIZZLE_URL . '/thirdparty/pgn4web/cs-preview-iframe.php'; ?>",
		"oldbrowser": "<?php esc_html__( 'You are using a browser that is too old, please upgrade to use the Preview function.', 'chessgame-shizzle' ); ?>",
	};
	</script>

	<?php
	$url =  plugins_url( '/pgn4web.js', __FILE__ ) . '?ver=' . C_SHIZZLE_VER;
	echo "<script type='text/javascript' src='" . esc_attr( $url ) . "'></script>";

	$url =  plugins_url( '/cs-pgn4web.js', __FILE__ ) . '?ver=' . C_SHIZZLE_VER;
	echo "<script type='text/javascript' src='" . esc_attr( $url ) . "'></script>";

}

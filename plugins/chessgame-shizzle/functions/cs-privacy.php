<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add example text to the privacy policy.
 *
 * @since 1.0.6
 */
function chessgame_shizzle_add_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	/* translators: example text for the privacy policy. */
	$content = sprintf(
		'<p>' . esc_html__( 'Submitted, stored and published chessgames can contain the names of players together with the Elo rating they had at that time. They can also contain the game they have played, together with possible metadata like location and tournament.', 'chessgame-shizzle' ) . '</p>'
	);

	wp_add_privacy_policy_content(
		'Chessgame Shizzle',
		wp_kses_post( wpautop( $content, false ) )
	);
}
add_action( 'admin_init', 'chessgame_shizzle_add_privacy_policy_content' );

<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Load an iframe with a chessgame.
 *
 * @param  int    $post_id ID of a chessgame post.
 * @return string $iframe  html with an iframe element.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_iframe( $post_id ) {
	$title = get_the_title( $post_id);
	$post_url = C_SHIZZLE_URL . '/thirdparty/pgn4web/cs-template-iframe.php';
	$post_url = add_query_arg( 'p', $post_id, $post_url );
	// The reload of the src attribute is done because most browsers cache the url. This will reset and reload the iframe.
	$iframe = '
		<iframe src="' . esc_attr( $post_url ) . '" class="cs-iframe cs-iframe-' . (int) $post_id . '" name="cs-iframe-' . (int) $post_id . '" id="cs-iframe-' . (int) $post_id . '" title="' . esc_attr( $title ) . '"></iframe>
		<script>
		document.getElementById("cs-iframe-' . (int) $post_id . '").src=document.getElementById("cs-iframe-' . (int) $post_id . '").src
		</script>
	';
	return $iframe;
}


/*
 * Load an extended iframe with a chessgame.
 *
 * @param  int    $post_id ID of a chessgame post.
 * @param  string $class   class to be added to the src attribute of the iframe.
 * @return string $iframe  html with an iframe element.
 *
 * @since 1.0.9
 */
function chessgame_shizzle_get_iframe_extended( $post_id, $class = 'cs-default' ) {
	$title = get_the_title( $post_id);
	$class = sanitize_html_class( $class );
	$iframe_class = 'cs-iframe cs-iframe-extended cs-iframe-' . (int) $post_id . ' ' . esc_attr( $class );
	$post_url = C_SHIZZLE_URL . '/thirdparty/pgn4web/cs-template-iframe-extended.php';
	$post_url = add_query_arg( 'p', $post_id, $post_url );
	$post_url = add_query_arg( 'class', $class, $post_url );
	// The reload of the src attribute is done because most browsers cache the url. This will reset and reload the iframe.
	$iframe = '
		<iframe src="' . esc_attr( $post_url ) . '" class="' . esc_attr( $iframe_class ) . '" name="cs-iframe-' . (int) $post_id . '" id="cs-iframe-' . (int) $post_id . '" title="' . esc_attr( $title ) . '"></iframe>
		<script>
		document.getElementById("cs-iframe-' . (int) $post_id . '").src=document.getElementById("cs-iframe-' . (int) $post_id . '").src
		</script>
	';
	return $iframe;
}


/*
 * Add extended iframe to content if desired.
 *
 * @param  string $content html content of the post.
 * @return string $content html content of the post with iframe.
 *
 * @since 1.2.6
 */
function chessgame_shizzle_get_iframe_extended_for_content_filter( $content ) {

	$post_id = get_the_ID();
	$post_type = get_post_type();
	if ( $post_type !== 'cs_chessgame' || is_admin() ) {
		return $content;
	}

	$iframe = chessgame_shizzle_get_iframe_extended( $post_id );

	$content = $content . $iframe;

	return $content;

}
// How to use this:
//add_filter( 'the_content', 'chessgame_shizzle_get_iframe_extended_for_content_filter', 13 );

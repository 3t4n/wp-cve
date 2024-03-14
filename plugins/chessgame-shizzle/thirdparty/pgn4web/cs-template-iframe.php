<?php
/*
 * Support chessgame in an iframe.
 *
 * @since 1.0.3
 *
 */

	// post_id
	if ( isset($_GET['p']) && (int) $_GET['p'] > 0 ) {
		$post_id = (int) $_GET['p'];
	}
	if ( ! isset( $post_id ) ) {
		die('not enough parameters');
	}

	require_once '../../../../../wp-load.php';

	?>

	<!DOCTYPE html>
	<html>
		<head>
			<?php
			if ( function_exists( 'wp_custom_css_cb' ) ) {
				wp_custom_css_cb();
			}
			?>
		</head>

		<body class="cs-iframe">
			<?php

			$post = get_post( $post_id );
			if ( is_object( $post ) && is_a( $post, 'WP_Post' ) ) {

				$content = '<div class="cs-chessgame-iframe">'; // Simple iframe has no GameText visible (except for puzzle).
				$content .= chessgame_shizzle_content_get_pgn( $content, $post_id );
				$content .= '</div">';
				echo $content;

				chessgame_shizzle_pgn4web_dead_enqueue( $post_id );

			}
			?>
		</body>
	</html>

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

	// class for this iframe.
	$class = 'cs-default';
	if ( isset($_GET['class']) && strlen($_GET['class']) > 0 ) {
		$class = sanitize_html_class( $_GET['class'] );
	}
	$body_class = 'cs-iframe cs-iframe-extended ' . $class;

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

		<body class="<?php echo esc_attr( $body_class ); ?>">
			<?php

			$post = get_post( $post_id );
			if ( is_object( $post ) && is_a( $post, 'WP_Post' ) ) {

				echo '<h2>';
				the_title();
				echo '</h2>';

				$content = '<div class="cs-chessgame-iframe-extended">';
				$content .= $post->post_content;
				$content .= chessgame_shizzle_content_get_players( $content, $post_id ); // prio 12
				$content .= chessgame_shizzle_content_get_meta( $content, $post_id );    // prio 13
				$content .= chessgame_shizzle_content_get_pgn( $content, $post_id );     // prio 14
				$content .= '</div">';
				echo $content;

				chessgame_shizzle_pgn4web_dead_enqueue( $post_id );

			}
			?>
		</body>
	</html>

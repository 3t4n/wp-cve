<?php
/*
 * Bulk action for generating a featured image from a FEN code.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Define bulk action for generating a featured image from a FEN code.
 *
 * @param array $actions Existing actions.
 * @return array
 *
 * @since 1.1.8
 */
function chessgame_shizzle_define_bulk_actions( $actions ) {

	$actions['cs_featured_image'] = esc_html__( 'Generate featured image from game', 'chessgame-shizzle' );

	return $actions;

}
add_filter( 'bulk_actions-edit-cs_chessgame', 'chessgame_shizzle_define_bulk_actions' );


/*
 * Handle bulk action for generating a featured image from a FEN code.
 *
 * @param  string $redirect_to URL to redirect to.
 * @param  string $action      Action name.
 * @param  array  $ids         List of ids.
 * @return string
 *
 * @since 1.1.8
 */
function chessgame_shizzle_handle_bulk_actions( $redirect_to, $action, $ids ) {

	$changed = 0;

	if ( 'cs_featured_image' === $action ) {
		foreach ( $ids as $post_id ) {

			$post = get_post( $post_id );
			$description = chessgame_shizzle_truncate_slug( get_the_title( $post ) );
			$cs_pgn = get_post_meta($post_id, 'cs_chessgame_pgn', true);
			$cs_puzzle = get_post_meta($post_id, 'cs_chessgame_puzzle', true);

			$cs_fen = chessgame_shizzle_pgn_get_fen( $cs_pgn );
			$cs_new_fen = chessgame_shizzle_get_new_fen();
			$cs_fen_position = chessgame_shizzle_pgn_get_last_position( $cs_pgn );

			if ( strlen($cs_fen) > 0 && strlen($cs_new_fen) > 0 && $cs_fen !== $cs_new_fen ) {

				$board = new MFEN();
				$board->set_fen( $cs_fen );
				$filename = $board->render( true, $description ); // true returns filename instead of raw image.

				$attachment_id = media_sideload_image( $filename, $post_id, $description, 'id' ); // description really is title.

				$board->destroy();

				if ( is_int( $attachment_id ) && $attachment_id > 0 ) {
					set_post_thumbnail( $post_id, $attachment_id ); // sets meta key for chessgame post.
					$changed++;
				}

			} else if ( ! $cs_puzzle && strlen( $cs_fen_position ) > 0 ) {

				$board = new MFEN();
				$board->set_fen( $cs_fen_position );
				$filename = $board->render( true, $description ); // true returns filename instead of raw image.

				$attachment_id = media_sideload_image( $filename, $post_id, $description, 'id' ); // description really is title.

				$board->destroy();

				if ( is_int( $attachment_id ) && $attachment_id > 0 ) {
					set_post_thumbnail( $post_id, $attachment_id ); // sets meta key for chessgame post.
					$changed++;
				}

			}
		}
	}

	if ( $changed > 0 ) {
		$board = new MFEN();
		$board->purge(); // purge cache

		$redirect_to = add_query_arg(
			array(
				'post_type'   => 'cs_chessgame',
				'bulk_action' => 'cs_featured_image',
				'changed'     => $changed,
				'ids'         => join( ',', $ids ),
			),
			$redirect_to
		);

	}

	return esc_url_raw( $redirect_to );

}
add_filter( 'handle_bulk_actions-edit-cs_chessgame', 'chessgame_shizzle_handle_bulk_actions', 10, 3 );


/*
 * Display admin notice after bulk action for generating a featured image from a FEN code.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_handle_bulk_admin_notice() {
	global $post_type, $pagenow;

	// Bail out if not on chessgame list page.
	if ( 'edit.php' !== $pagenow || 'cs_chessgame' !== $post_type || ! isset( $_REQUEST['bulk_action'] ) ) {
		return;
	}

	$changed     = isset( $_REQUEST['changed'] ) ? absint( $_REQUEST['changed'] ) : 0;
	$bulk_action = sanitize_text_field( wp_unslash( $_REQUEST['bulk_action'] ) );

	if ( 'cs_featured_image' === $bulk_action && $changed > 0 ) {
		/* translators: %d: game count */
		$message = sprintf( _n( '%d featured image generated.', '%d featured images generated.', $changed, 'chessgame-shizzle' ), number_format_i18n( $changed ) );
		echo '<div class="updated"><p>' . esc_html( $message ) . '</p></div>';
	}

}
add_action( 'admin_notices', 'chessgame_shizzle_handle_bulk_admin_notice' );

<?php
/**
 * Main function.
 *
 * @since 2.0.0
 * @package AutoClose
 */

/**
 * Main function.
 *
 * @since 2.0.0
 */
function acc_main() {

	$comment_age      = acc_get_option( 'comment_age' );
	$pbtb_age         = acc_get_option( 'pbtb_age' );
	$comment_pids     = acc_get_option( 'comment_pids' );
	$pbtb_pids        = acc_get_option( 'pbtb_pids' );
	$delete_revisions = acc_get_option( 'delete_revisions' );

	// Get the post types.
	$comment_post_types = acc_parse_post_types( acc_get_option( 'comment_post_types' ) );
	$pbtb_post_types    = acc_parse_post_types( acc_get_option( 'pbtb_post_types' ) );

	// Close Comments on posts.
	if ( acc_get_option( 'close_comment' ) ) {
		acc_close_discussions(
			'comment',
			array(
				'age'        => $comment_age,
				'post_types' => $comment_post_types,
			)
		);
	}

	// Close Pingbacks/Trackbacks on posts.
	if ( acc_get_option( 'close_pbtb' ) ) {
		acc_close_discussions(
			'ping',
			array(
				'age'        => $pbtb_age,
				'post_types' => $pbtb_post_types,
			)
		);
	}

	// Open Comments on these posts.
	if ( ! empty( $comment_pids ) ) {
		acc_open_discussions(
			'comment',
			array(
				'post_ids' => $comment_pids,
			)
		);
	}

	// Open Pingbacks / Trackbacks on these posts.
	if ( ! empty( $pbtb_pids ) ) {
		acc_open_discussions(
			'comment',
			array(
				'post_ids' => $pbtb_pids,
			)
		);
	}

	// Delete Post Revisions (WordPress 2.6 and above).
	if ( $delete_revisions ) {
		acc_delete_revisions();
	}
}
add_action( 'acc_cron_hook', 'acc_main' );

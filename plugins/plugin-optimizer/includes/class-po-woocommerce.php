<?php
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Comments class.
 */
class SOSPO_WC_Comments {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		// Rating posts.
		add_filter( 'comments_open', array( __CLASS__, 'comments_open' ), 10, 2 );
		add_action( 'comment_moderation_recipients', array( __CLASS__, 'comment_moderation_recipients' ), 10, 2 );

		// Secure order notes.
		add_filter( 'comments_clauses', array( __CLASS__, 'exclude_order_comments' ), 10, 1 );
		add_filter( 'comment_feed_where', array( __CLASS__, 'exclude_order_comments_from_feed_where' ) );

		// Secure webhook comments.
		add_filter( 'comments_clauses', array( __CLASS__, 'exclude_webhook_comments' ), 10, 1 );
		add_filter( 'comment_feed_where', array( __CLASS__, 'exclude_webhook_comments_from_feed_where' ) );

		// Count comments.
		add_filter( 'wp_count_comments', array( __CLASS__, 'wp_count_comments' ), 10, 2 );

		// Delete comments count cache whenever there is a new comment or a comment status changes.
		add_action( 'wp_insert_comment', array( __CLASS__, 'delete_comments_count_cache' ) );
		add_action( 'wp_set_comment_status', array( __CLASS__, 'delete_comments_count_cache' ) );

		// Support avatars for `review` comment type.
		add_filter( 'get_avatar_comment_types', array( __CLASS__, 'add_avatar_for_review_comment_type' ) );

		// Set comment type.
		add_action( 'preprocess_comment', array( __CLASS__, 'update_comment_type' ), 1 );
	}

	/**
	 * See if comments are open.
	 *
	 * @param  bool $open    Whether the current post is open for comments.
	 * @param  int  $post_id Post ID.
	 * @return bool
	 */
	public static function comments_open( $open, $post_id ) {
		if ( 'product' === get_post_type( $post_id ) && ! post_type_supports( 'product', 'comments' ) ) {
			$open = false;
		}
		return $open;
	}

	/**
	 * Exclude order comments from queries and RSS.
	 *
	 * This code should exclude shop_order comments from queries. Some queries (like the recent comments widget on the dashboard) are hardcoded.
	 * and are not filtered, however, the code current_user_can( 'read_post', $comment->comment_post_ID ) should keep them safe since only admin and.
	 * shop managers can view orders anyway.
	 *
	 * The frontend view order pages get around this filter by using remove_filter('comments_clauses', array( 'WC_Comments' ,'exclude_order_comments'), 10, 1 );
	 *
	 * @param  array $clauses A compacted array of comment query clauses.
	 * @return array
	 */
	public static function exclude_order_comments( $clauses ) {
		$clauses['where'] .= ( $clauses['where'] ? ' AND ' : '' ) . " comment_type != 'order_note' ";
		return $clauses;
	}

	/**
	 * Exclude order comments from queries and RSS.
	 *
	 * @param  string $where The WHERE clause of the query.
	 * @return string
	 */
	public static function exclude_order_comments_from_feed_where( $where ) {
		return $where . ( $where ? ' AND ' : '' ) . " comment_type != 'order_note' ";
	}

	/**
	 * Exclude webhook comments from queries and RSS.
	 *
	 * @param  array $clauses A compacted array of comment query clauses.
	 * @return array
	 */
	public static function exclude_webhook_comments( $clauses ) {
		$clauses['where'] .= ( $clauses['where'] ? ' AND ' : '' ) . " comment_type != 'webhook_delivery' ";
		return $clauses;
	}

	/**
	 * Exclude webhook comments from queries and RSS.
	 *
	 * @param  string $where The WHERE clause of the query.
	 * @return string
	 */
	public static function exclude_webhook_comments_from_feed_where( $where ) {
		return $where . ( $where ? ' AND ' : '' ) . " comment_type != 'webhook_delivery' ";
	}

	/**
	 * Modify recipient of review email.
	 *
	 * @param array $emails     Emails.
	 * @param int   $comment_id Comment ID.
	 * @return array
	 */
	public static function comment_moderation_recipients( $emails, $comment_id ) {
		$comment = get_comment( $comment_id );

		if ( $comment && 'product' === get_post_type( $comment->comment_post_ID ) ) {
			$emails = array( get_option( 'admin_email' ) );
		}

		return $emails;
	}

	/**
	 * Delete comments count cache whenever there is
	 * new comment or the status of a comment changes. Cache
	 * will be regenerated next time WC_Comments::wp_count_comments()
	 * is called.
	 */
	public static function delete_comments_count_cache() {
		delete_transient( 'wc_count_comments' );
	}

	/**
	 * Remove order notes and webhook delivery logs from wp_count_comments().
	 *
	 * @param  object $stats   Comment stats.
	 * @param  int    $post_id Post ID.
	 * @return object
	 */
	public static function wp_count_comments( $stats, $post_id ) {
		global $wpdb;

		if ( 0 === $post_id ) {
			$stats = get_transient( 'wc_count_comments' );

			if ( ! $stats ) {
				$stats = array(
					'total_comments' => 0,
					'all'            => 0,
				);

				$count = $wpdb->get_results(
					"
					SELECT comment_approved, COUNT(*) AS num_comments
					FROM {$wpdb->comments}
					WHERE comment_type NOT IN ('action_log', 'order_note', 'webhook_delivery')
					GROUP BY comment_approved
					",
					ARRAY_A
				);

				$approved = array(
					'0'            => 'moderated',
					'1'            => 'approved',
					'spam'         => 'spam',
					'trash'        => 'trash',
					'post-trashed' => 'post-trashed',
				);

				foreach ( (array) $count as $row ) {
					// Don't count post-trashed toward totals.
					if ( ! in_array( $row['comment_approved'], array( 'post-trashed', 'trash', 'spam' ), true ) ) {
						$stats['all']            += $row['num_comments'];
						$stats['total_comments'] += $row['num_comments'];
					} elseif ( ! in_array( $row['comment_approved'], array( 'post-trashed', 'trash' ), true ) ) {
						$stats['total_comments'] += $row['num_comments'];
					}
					if ( isset( $approved[ $row['comment_approved'] ] ) ) {
						$stats[ $approved[ $row['comment_approved'] ] ] = $row['num_comments'];
					}
				}

				foreach ( $approved as $key ) {
					if ( empty( $stats[ $key ] ) ) {
						$stats[ $key ] = 0;
					}
				}

				$stats = (object) $stats;
				set_transient( 'wc_count_comments', $stats );
			}
		}

		return $stats;
	}

	/**
	 * Make sure WP displays avatars for comments with the `review` type.
	 *
	 * @param  array $comment_types Comment types.
	 * @return array
	 */
	public static function add_avatar_for_review_comment_type( $comment_types ) {
		return array_merge( $comment_types, array( 'review' ) );
	}

	/**
	 * Update comment type of product reviews.
	 *
	 * @param array $comment_data Comment data.
	 * @return array
	 */
	public static function update_comment_type( $comment_data ) {
		if ( ! is_admin() && isset( $_POST['comment_post_ID'], $comment_data['comment_type'] ) && self::is_default_comment_type( $comment_data['comment_type'] ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) { // WPCS: input var ok, CSRF ok.
			$comment_data['comment_type'] = 'review';
		}

		return $comment_data;
	}

	/**
	 * Determines if a comment is of the default type.
	 *
	 * Prior to WordPress 5.5, '' was the default comment type.
	 * As of 5.5, the default type is 'comment'.
	 *
	 * @param string $comment_type Comment type.
	 * @return bool
	 */
	private static function is_default_comment_type( $comment_type ) {
		return ( '' === $comment_type || 'comment' === $comment_type );
	}
}

SOSPO_WC_Comments::init();

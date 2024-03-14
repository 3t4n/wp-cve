<?php
/**
 * @package           WpbCommentModeration
 * @subpackage        Comments List Table
 * @author            WPBeginner
 * @copyright         2021 WPBeginner
 * @license           GPL-2.0-or-later
 *
 * Replace a couple of permissions checks.
 */

namespace WPB\CommentModerationRole;

/**
 * Custom class for displaying the comment moderation table.
 *
 * @see WP_Comments_List_Table
 */
class WPB_Comments_List_Table extends \WP_Comments_List_Table {

	public function __construct( $args ) {
		parent::__construct( $args );

		$this->screen = convert_to_screen( $args['screen'] );

		add_filter( "manage_{$this->screen->id}_columns", array( $this, 'get_columns' ), 50 );
	}

	/**
	 * Column info/headers for the comments screen.
	 *
	 * Unable to inherit due to WordPress weirdness so hard coded instead.
	 *
	 * @return array Comment info.
	 */
	protected function get_column_info() {
		$this->_column_headers = array(
			array(
				'cb'       => '<input type="checkbox" />',
				'author'   => __( 'Author', 'comment-moderation-role' ),
				'comment'  => __( 'Comment', 'comment-moderation-role' ),
				'response' => __( 'In response to', 'comment-moderation-role' ),
				'date'     => __( 'Submitted on', 'comment-moderation-role' ),
			),
			array(),
			array(
				'author'   => array( 'comment_author', false ),
				'response' => array( 'comment_post_ID', false ),
				'date'     => array( 'comment_date', false ),
			),
			'comment',
		);

		return $this->_column_headers;
	}

	/**
	 * Columns available on the moderation screen.
	 *
	 * @return array Columns and their headers.
	 */
	public function get_columns() {
		return $this->get_column_info()[0];
	}

	/**
	 * Replace ajax permission check with moderator capability.
	 *
	 * @return bool Whether current user can moderate comments.
	 */
	public function ajax_user_can() {
		return current_user_can( moderator_cap() );
	}

	/**
	 * Display views available on the comment screen.
	 *
	 * The only change from the Core list table is the URL used for admin pages.
	 *
	 * @global int $post_id
	 * @global string $comment_status
	 * @global string $comment_type
	 */
	protected function get_views() {
		global $post_id, $comment_status, $comment_type;

		$status_links = array();
		$num_comments = ( $post_id ) ? wp_count_comments( $post_id ) : wp_count_comments();

		$stati = array(
			/* translators: %s: Number of comments. */
			'all'       => _nx_noop(
				'All <span class="count">(%s)</span>',
				'All <span class="count">(%s)</span>',
				'comments',
				'comment-moderation-role'
			), // Singular not used.

			/* translators: %s: Number of comments. */
			'mine'      => _nx_noop(
				'Mine <span class="count">(%s)</span>',
				'Mine <span class="count">(%s)</span>',
				'comments',
				'comment-moderation-role'
			),

			/* translators: %s: Number of comments. */
			'moderated' => _nx_noop(
				'Pending <span class="count">(%s)</span>',
				'Pending <span class="count">(%s)</span>',
				'comments',
				'comment-moderation-role'
			),

			/* translators: %s: Number of comments. */
			'approved'  => _nx_noop(
				'Approved <span class="count">(%s)</span>',
				'Approved <span class="count">(%s)</span>',
				'comments',
				'comment-moderation-role'
			),

			/* translators: %s: Number of comments. */
			'spam'      => _nx_noop(
				'Spam <span class="count">(%s)</span>',
				'Spam <span class="count">(%s)</span>',
				'comments',
				'comment-moderation-role'
			),

			/* translators: %s: Number of comments. */
			'trash'     => _nx_noop(
				'Trash <span class="count">(%s)</span>',
				'Trash <span class="count">(%s)</span>',
				'comments',
				'comment-moderation-role'
			),
		);

		if ( ! EMPTY_TRASH_DAYS ) {
			unset( $stati['trash'] );
		}

		$link = admin_url( 'admin.php?page=am-comment-moderation' );
		if ( ! empty( $comment_type ) && 'all' !== $comment_type ) {
			$link = add_query_arg( 'comment_type', $comment_type, $link );
		}

		foreach ( $stati as $status => $label ) {
			$current_link_attributes = '';

			if ( $status === $comment_status ) {
				$current_link_attributes = ' class="current" aria-current="page"';
			}

			if ( 'mine' === $status ) {
				$current_user_id    = get_current_user_id();
				$num_comments->mine = get_comments(
					array(
						'post_id' => $post_id ? $post_id : 0,
						'user_id' => $current_user_id,
						'count'   => true,
					)
				);
				$link               = add_query_arg( 'user_id', $current_user_id, $link );
			} else {
				$link = remove_query_arg( 'user_id', $link );
			}

			if ( ! isset( $num_comments->$status ) ) {
				$num_comments->$status = 10;
			}
			$link = add_query_arg( 'comment_status', $status, $link );
			if ( $post_id ) {
				$link = add_query_arg( 'p', absint( $post_id ), $link );
			}

			$status_links[ $status ] = "<a href='$link'$current_link_attributes>" . sprintf(
				translate_nooped_plural( $label, $num_comments->$status ),
				sprintf(
					'<span class="%s-count">%s</span>',
					( 'moderated' === $status ) ? 'pending' : $status,
					number_format_i18n( $num_comments->$status )
				)
			) . '</a>';
		}

		/**
		 * Filters the comment status links.
		 *
		 * @since 2.5.0
		 * @since 5.1.0 The 'Mine' link was added.
		 *
		 * @param string[] $status_links An associative array of fully-formed comment status links. Includes 'All', 'Mine',
		 *                              'Pending', 'Approved', 'Spam', and 'Trash'.
		 */
		return apply_filters( 'comment_status_links', $status_links );
	}

}

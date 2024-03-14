<?php

class Youzify_Comments_Tab {

	/**
	 * Tab Core
	 */
	function tab() {

		// Get Comments Page Number
		$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;

		// Get Max Comments Per Page
		$commentsNbr = youzify_option( 'youzify_profile_comments_nbr', 5 );

		echo '<div class="youzify-tab youzify-comments"><div id="youzify-main-comments" class="youzify-tab youzify-tab-comments">';

		$this->comments_core( array(
			'user_id' => bp_displayed_user_id(),
			'number'  => $commentsNbr,
			'offset'  => ( $paged - 1 ) * $commentsNbr,
			'paged'   => $paged
		) );

		youzify_loading();

		echo '</div></div>';

		youzify_profile_posts_comments_pagination();

	}

	/**
	 * Comments Core.
	 */
	function comments_core( $args ) {

		// Get Base
		$base = isset( $args['base'] ) ? $args['base'] : get_pagenum_link( 1 );

		// Get User Comments Number
		$total_comments = youzify_get_comments_number( $args['user_id'] );

		// Pagination
		$comments_page = ! empty( $args['paged'] ) ? $args['paged'] : 1 ;

		// Remove BuddyPress Filters.
		remove_filter( 'comments_pre_query', 'bp_comments_pre_query', 10, 2 );

		// Query
		$comments_query = new WP_Comment_Query();
		$comments 		= $comments_query->query( $args );

		// Comment Loop
		if ( $comments ) {

			// Show / Hide Comment Elements
			$display_date 	  = youzify_option( 'youzify_display_comment_date', 'on' );
			$display_button   = youzify_option( 'youzify_display_view_comment', 'on' );
			$display_title 	  = youzify_option( 'youzify_display_comment_title', 'on' );
			$display_username = youzify_option( 'youzify_display_comment_username', 'on' );

			?>

			<div class="youzify-comments-page" data-post-page="<?php echo $comments_page; ?>">

			<?php foreach ( $comments as $comment ) : ?>

			<?php

				// Get Comment Data
				$comment_ID 	 = $comment->comment_ID;
				$post_id 		 = $comment->comment_post_ID;
				$comment_content = $comment->comment_content;

				// Get Comment Url
				$post_url	 = get_the_permalink( $post_id );
				$comment_url = $post_url . "#comment-" . $comment_ID;

			?>

			<div class="youzify-tab-comment">
				<div class="youzify-comment-content">
					<div class="youzify-comment-head">
						<div class="youzify-comment-img"><?php echo bp_core_fetch_avatar( array('item_id' => $args['user_id'], 'type' => 'thumb' ) ); ?></div>
						<div class="youzify-comment-meta">
							<?php if ( 'on' == $display_title ) :?>
							<a href="<?php echo $post_url; ?>" class="youzify-comment-fullname"><?php echo get_the_title( $post_id ); ?></a>
							<?php endif; ?>
							<?php if ( 'on' == $display_button ) : ?>
								<a href="<?php echo $comment_url; ?>" class="view-comment-button">
									<i class="fas fa-comment-dots"></i><?php _e( 'View Comment', 'youzify' ); ?>
								</a>
							<?php endif; ?>
							<ul>
								<?php if ( 'on' == $display_username ) : ?>
								<li class="youzify-comment-author">@<?php echo get_the_author_meta( 'user_login', $args['user_id'] ); ?></li>
								<?php endif; ?>
								<?php if ( 'on' == $display_date ) : ?>
									<?php $date_format = apply_filters( 'youzify_comments_tab_comment_date_format', 'F j, Y' ); ?>
								<li class="youzify-comment-date"><span>&#8226;</span><?php comment_date( $date_format, $comment_ID ); ?></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<div class="youzify-comment-excerpt">
						<p><?php echo youzify_get_excerpt( $comment_content , 50 ); ?></p>
					</div>
				</div>
			</div>

			<?php endforeach; ?>

		<?php $this->pagination( $args, $total_comments, $base ); ?>

		</div>

		<?php } else { ?>

		<div class="youzify-info-msg youzify-failure-msg">
			<div class="youzify-msg-icon">
				<i class="fas fa-exclamation-triangle"></i>
			</div>
		 	<p><?php _e( 'Sorry, no comments found !', 'youzify' ); ?></p>
		 </div>

		<?php

		}

	}

	/**
	 * Pagination.
	 */
	function pagination( $args, $total_comments, $base = null ) {

		//Get Comments Per Page Number
		$commentsNbr = youzify_option( 'youzify_profile_comments_nbr', 5 );
		$commentsNbr = $commentsNbr ? $commentsNbr : 1;

		// Get total Pages Number
		$max_page = ceil( $total_comments / $commentsNbr );

		// Get Current Page Number
		$cpage = ! empty( $args['paged'] ) ?  $args['paged'] : 1 ;

		// Get Next and Previous Pages Number
		if ( ! empty( $cpage ) ) {
			$next_page = $cpage + 1;
			$prev_page = $cpage - 1;
		}

		// Pagination Settings
		$comments_args = array(
			'base'        => $base . '%_%',
			'format' 	  => 'page/%#%',
			'total'       => $max_page,
			'current'     => $cpage,
			'show_all'    => false,
			'end_size'    => 1,
			'mid_size'    => 2,
			'prev_next'   => True,
			'prev_text'   => '<div class="youzify-page-symbole">&laquo;</div><span class="youzify-next-nbr">'. $prev_page .'</span>',
			'next_text'   => '<div class="youzify-page-symbole">&raquo;</div><span class="youzify-next-nbr">'. $next_page .'</span>',
			'type'         => 'plain',
			'add_args'     => false,
			'add_fragment' => '',
			'before_page_number' => '<span class="youzify-page-nbr">',
			'after_page_number'  => '</span>',
		);

		// Call Pagination Function
		$paginate_comments = paginate_links( $comments_args );

		// Print Comments Pagination
		if ( $paginate_comments ) {
			echo sprintf( '<nav class="youzify-pagination" data-base="%1s">' , $base );
			echo '<span class="youzify-pagination-pages">';
			printf( __( 'Page %1$d of %2$d' , 'youzify' ), $cpage, $max_page );
			echo "</span><div class='comments-nav-links youzify-nav-links'>$paginate_comments</div></nav>";
		}
	}

}
<?php

class Youzify_Posts_Tab {

	/**
	 * Tab Content
	 */
	function tab() {

		// Prepare Posts Arguments.
		$args = array(
			'post_type'		 => apply_filters( 'youzify_profile_posts_tab_post_type', 'post' ),
			'order' 		 => 'DESC',
			'paged' 		 => get_query_var( 'page' ) ? get_query_var( 'page' ) : 1,
			'post_status'	 => 'publish',
			'posts_per_page' => youzify_option( 'youzify_profile_posts_per_page', 5 ),
			'author' 		 => bp_displayed_user_id(),
		);

		echo '<div class="youzify-tab youzify-posts"><div id="youzify-main-posts" class="youzify-tab youzify-tab-posts">';

		$this->posts_core( $args );

		youzify_loading();

		echo '</div></div>';

		// Pagination Script.
 		youzify_profile_posts_comments_pagination();

	}

	/**
	 * Post Core .
	 */
	function posts_core( $args ) {

		// Init Vars.
		$posts_exist = false;

		$blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

		$blogs_ids = apply_filters( 'youzify_profile_posts_tab_blog_ids', $blogs_ids );

		// Posts Pagination
		$posts_page = ! empty( $args['page'] ) ? $args['page'] : 1 ;

		// Get Base
		$base = isset( $args['base'] ) ? $args['base'] : get_pagenum_link( 1 );

		echo '<div class="youzify-posts-page" data-post-page="' . $posts_page . '">';

		// Show / Hide Post Elements
		$display_meta 		= youzify_option( 'youzify_display_post_meta', 'on' );
		$display_date 		= youzify_option( 'youzify_display_post_date', 'on' );
		$display_cats 		= youzify_option( 'youzify_display_post_cats', 'on' );
		$display_excerpt	= youzify_option( 'youzify_display_post_excerpt', 'on' );
		$display_readmore 	= youzify_option( 'youzify_display_post_readmore', 'on' );
		$display_comments 	= youzify_option( 'youzify_display_post_comments', 'on' );
		$display_meta_icons = youzify_option( 'youzify_display_post_meta_icons', 'on' );

		foreach ( $blogs_ids as $b ) {

		    switch_to_blog( $b->blog_id );

			// init WP Query
			$posts_query = new WP_Query( $args );

			if ( $posts_query->have_posts() ) : $posts_exist = true;

			while ( $posts_query->have_posts() ) : $posts_query->the_post();

				// Get Post Data
				$post_id = $posts_query->post->ID;

			?>

			<div class="youzify-tab-post">

				<?php youzify_get_post_thumbnail( array( 'attachment_id' => get_post_thumbnail_id( $post_id ), 'size' => 'medium', 'element' => 'profile-posts-tab' ) ); ?>

				<div class="youzify-post-container">

					<div class="youzify-post-inner-content">
						<?php do_action( 'youzify_before_posts_tab_container' ); ?>
						<div class="youzify-post-head">

							<h2 class="youzify-post-title">
								<a href="<?php the_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id ); ?></a>
							</h2>

							<?php if ( 'on' == $display_meta ) : ?>

							<div class="youzify-post-meta">

								<ul>

									<?php if ( 'on' == $display_date ) : ?>
										<li>
											<?php if ( 'on' == $display_meta_icons ) : ?>
												<i class="far fa-calendar-alt"></i>
											<?php endif; ?>
											<?php echo get_the_date( '', $post_id ); ?>
										</li>
									<?php endif; ?>

									<?php if ( 'on' == $display_cats ) : ?>

									<?php youzify_get_post_categories( $post_id, $display_meta_icons ); ?>

									<?php endif; ?>

									<?php if ( 'on' == $display_comments ) : ?>
										<li>
											<?php if ( 'on' == $display_meta_icons ) : ?>
												<i class="far fa-comments"></i>
											<?php endif; ?>
											<?php echo wp_count_comments( $post_id )->total_comments; ?>
										</li>
									<?php endif; ?>

								</ul>

							</div>

							<?php endif; ?>

						</div>
						<?php if ( 'on' == $display_excerpt ) : ?>
						<div class="youzify-post-text">
							<?php $post_excerpt = get_the_excerpt(); ?>
							<?php $post_excerpt = ! empty( $post_excerpt ) ? $post_excerpt : do_shortcode( youzify_get_excerpt( get_the_content(), 25 ) ); ?>
							<p><?php echo apply_filters( 'youzify_profile_posts_tab_post_excerpt', $post_excerpt, $post_id ); ?></p>
						</div>
						<?php endif; ?>

						<?php if ( 'on' == $display_readmore ) : ?>
							<a href="<?php the_permalink( $post_id ); ?>" class="youzify-read-more">
								<div class="youzify-rm-icon">
									<i class="fas fa-angle-double-right"></i>
								</div>
								<?php echo apply_filters( 'youzify_profile_tab_posts_read_more_button', __( 'Read More', 'youzify' ) ); ?>
							</a>
						<?php endif; ?>
						<?php do_action( 'youzify_after_posts_tab_container' ); ?>
					</div>

				</div>

			</div>

			<?php endwhile;?>
			<?php wp_reset_postdata(); ?>
		    <?php $this->pagination( $args, $posts_query->max_num_pages, $base ); ?>
			<?php endif; ?>


		<?php

		    restore_current_blog();
		}

		if ( ! $posts_exist ) {
			echo '<div class="youzify-info-msg youzify-failure-msg"><div class="youzify-msg-icon"><i class="fas fa-exclamation-triangle"></i></div>
		 	<p>'. __( 'Sorry, no posts found!', 'youzify' ) . '</p></div>';
		}

		echo '</div>';
	}

	/**
	 * Pagination
	 */
	function pagination( $args = null, $numpages = '', $base = null ) {

		// Get current Page Number
		$paged = ! empty( $args['paged'] ) ? $args['paged'] : 1 ;

		// Get Total Pages Number
		if ( $numpages == '' ) {
			global $wp_query;
			$numpages = $wp_query->max_num_pages;
			if ( ! $numpages ) {
				$numpages = 1;
			}
		}

		// Get Next and Previous Pages Number
		if ( ! empty( $paged ) ) {
			$next_page = $paged + 1;
			$prev_page = $paged - 1;
		}

		// Pagination Settings
		$pagination_args = array(
			'base'            		=> $base . '%_%',
			'format'          		=> 'page/%#%',
			'total'           		=> $numpages,
			'current'         		=> $paged,
			'show_all'        		=> False,
			'end_size'        		=> 1,
			'mid_size'        		=> 2,
			'prev_next'       		=> True,
			'prev_text'       		=> '<div class="youzify-page-symbole">&laquo;</div><span class="youzify-next-nbr">'. $prev_page .'</span>',
			'next_text'       		=> '<div class="youzify-page-symbole">&raquo;</div><span class="youzify-next-nbr">'. $next_page .'</span>',
			'type'            		=> 'plain',
			'add_args'        		=> false,
			'add_fragment'    		=> '',
			'before_page_number' 	=> '<span class="youzify-page-nbr">',
			'after_page_number' 	=> '</span>',
		);

		// Call Pagination Function
		$paginate_links = paginate_links( $pagination_args );

		// Print Pagination
		if ( $paginate_links ) {
			echo sprintf( '<nav class="youzify-pagination" data-base="%1s">' , $base );
			echo '<span class="youzify-pagination-pages">';
			printf( __( 'Page %1$d of %2$d', 'youzify' ), $paged, $numpages );
			echo "</span><div class='posts-nav-links youzify-nav-links'>$paginate_links</div></nav>";
		}

	}

}
<?php

// Get the query data.
$query = FLBuilderLoop::query( $settings );

// Render the posts.
if ( $query->have_posts() ) {

	?>
<div class="xpro-post-grid-wrapper xpro-post-grid-layout-<?php echo esc_attr( $settings->layout ); ?>">
	<div class="xpro-post-grid-main cbp">
		<?php
		if ( 'yes' === $settings->show_pagination ) {
			$found_posts    = 0;
			$paged          = 1;
			$args['offset'] = '';
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			}
		}

		while ( $query->have_posts() ) {

			$query->the_post();

			$post_id   = get_the_ID();
			$permalink = get_permalink();

			$cat_list  = wp_get_post_terms( $post_id, 'category' );
			$count_cat = count( $cat_list );

			ob_start();

			?>
			<div class="cbp-item xpro-post-grid-item">

				<?php if ( has_post_thumbnail() && $settings->show_image ) { ?>
					<div class="xpro-post-grid-image">
						<?php echo wp_get_attachment_image( get_post_thumbnail_id( $post_id ), $settings->thumbnail ); ?>

						<?php if ( 'yes' === $settings->show_btn && '3' === $settings->layout ) { ?>
							<a href="<?php esc_url( get_permalink( $post_id ) ); ?>" class="pro-post-grid-btn">
								<?php echo esc_html( $settings->show_btn_text ); ?>
							</a>
						<?php } ?>
					</div>
				<?php } ?>

				<div class="xpro-post-grid-content">
					<?php if ( 'yes' === $settings->show_author && '4' === $settings->layout ) { ?>
						<div class="xpro-post-grid-author">
							<?php if ( 'yes' === $settings->show_author_avatar ) : ?>
								<img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ) ) ); ?>" alt="author-avatar">
							<?php endif; ?>
						</div>
					<?php } ?>

					<?php if ( '3' !== $settings->layout && '7' !== $settings->layout ) { ?>
						<ul class="xpro-post-grid-meta-list">
							<?php if ( 'yes' === $settings->show_date_meta ) : ?>
								<li class="xpro-post-grid-meta-date">
									<i class="<?php echo esc_attr( $settings->date_meta_icon ); ?>" aria-hidden="true"> </i>
									<?php the_time( 'F j, Y' ); ?>
								</li>
							<?php endif; ?>
							<?php if ( 'yes' === $settings->show_category_meta && get_the_category_list() ) : ?>
								<li class="xpro-post-grid-meta-category">
									<i class="<?php echo esc_attr( $settings->category_meta_icon ); ?>" aria-hidden="true"> </i>
									<span><?php echo get_the_category_list( ',' ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( 'yes' === $settings->show_comments_meta ) : ?>
								<li class="xpro-post-grid-meta-comments">
									<i class="<?php echo esc_attr( $settings->comments_meta_icon ); ?>" aria-hidden="true"> </i>
									<?php comments_number( esc_html__( 'No Comments', 'xpro-bb-addons' ), esc_html__( '1 Comment', 'xpro-bb-addons' ), esc_html__( '% Comments', 'xpro-bb-addons' ) ); ?>
								</li>
							<?php endif; ?>
						</ul>
					<?php } ?>

					<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
						<h2 class="xpro-post-grid-title"><?php the_title(); ?></h2>
					</a>

					<?php if ( '3' === $settings->layout ) { ?>
						<ul class="xpro-post-grid-meta-list">
							<?php if ( 'yes' === $settings->show_date_meta ) : ?>
								<li class="xpro-post-grid-meta-date">
									<i class="<?php echo esc_attr( $settings->date_meta_icon ); ?>" aria-hidden="true"> </i>
									<?php the_time( 'F j, Y' ); ?>
								</li>
							<?php endif; ?>
							<?php if ( 'yes' === $settings->show_category_meta && get_the_category_list() ) : ?>
								<li class="xpro-post-grid-meta-category">
									<i class="<?php echo esc_attr( $settings->category_meta_icon ); ?>" aria-hidden="true"> </i>
									<span><?php echo get_the_category_list( ',' ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( 'yes' === $settings->show_comments_meta ) : ?>
								<li class="xpro-post-grid-meta-comments">
									<i class="<?php echo esc_attr( $settings->comments_meta_icon ); ?>" aria-hidden="true"> </i>
									<?php comments_number( esc_html__( 'No Comments', 'xpro-bb-addons' ), esc_html__( '1 Comment', 'xpro-bb-addons' ), esc_html__( '% Comments', 'xpro-bb-addons' ) ); ?>
								</li>
							<?php endif; ?>
						</ul>
					<?php } ?>

					<?php
					if ( 'yes' === $settings->show_content ) {
						$limit = $settings->content_length ? $settings->content_length : 15;

						$content = explode( ' ', get_the_excerpt(), $limit );

						if ( count( $content ) >= $limit ) {
							array_pop( $content );
							$content = implode( ' ', $content ) . '...';
						} else {
							$content = implode( ' ', $content );
						}

						$content = preg_replace( '`[[^]]*]`', '', $content );
						?>
						<p class="xpro-post-grid-excerpt"><?php echo $content; ?></p>
					<?php } ?>

					<?php if ( 'yes' === $settings->show_btn && '3' !== $settings->layout ) { ?>
						<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="xpro-post-grid-btn">
							<?php echo $settings->show_btn_text; ?>
						</a>
					<?php } ?>

					<?php if ( 'yes' === $settings->show_author && '4' !== $settings->layout ) { ?>
						<div class="xpro-post-grid-author">
							<?php if ( 'yes' === $settings->show_author_avatar ) { ?>
								<img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ) ) ); ?>" alt="author-avatar">
							<?php } ?>
							<div class="xpro-post-grid-author-content">
								<?php if ( $settings->author_title ) { ?>
									<span class="xpro-post-grid-author-title"><?php echo esc_html( $settings->author_title ); ?></span>
								<?php } ?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="xpro-post-grid-author-name">
									<?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
								</a>
							</div>
						</div>
					<?php } ?>

					<?php if ( '7' === $settings->layout ) { ?>
						<ul class="xpro-post-grid-meta-list">
							<?php if ( 'yes' === $settings->show_date_meta ) : ?>
								<li class="xpro-post-grid-meta-date">
									<i class="<?php echo esc_attr( $settings->date_meta_icon ); ?>" aria-hidden="true"> </i>
									<?php the_time( 'F j, Y' ); ?>
								</li>
							<?php endif; ?>
							<?php if ( 'yes' === $settings->show_category_meta && get_the_category_list() ) : ?>
								<li class="xpro-post-grid-meta-category">
									<i class="<?php echo esc_attr( $settings->category_meta_icon ); ?>" aria-hidden="true"> </i>
									<span><?php echo get_the_category_list( ',' ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( 'yes' === $settings->show_comments_meta ) : ?>
								<li class="xpro-post-grid-meta-comments">
									<i class="<?php echo esc_attr( $settings->comments_meta_icon ); ?>" aria-hidden="true"> </i>
									<?php comments_number( esc_html__( 'No Comments', 'xpro-bb-addons' ), esc_html__( '1 Comment', 'xpro-bb-addons' ), esc_html__( '% Comments', 'xpro-bb-addons' ) ); ?>
								</li>
							<?php endif; ?>
						</ul>
					<?php } ?>

				</div>
			</div>
			<?php
			// Do shortcodes here so they are parsed in context of the current post.
			echo do_shortcode( ob_get_clean() );
		}

		?>
	</div>


</div>
	<?php
}

// Render the pagination.
if ( 'yes' === $settings->show_pagination && $query->have_posts() && $query->max_num_pages > 1 ) :
	$prev_icon_class = $settings->arrow;
	$next_icon_class = str_replace( 'left', 'right', $settings->arrow );

	$prev_text = '<i class="' . $prev_icon_class . '"></i><span class="xpro-elementor-post-pagination-prev-text">' . $settings->prev_label . '</span>';
	$next_text = '<span class="xpro-elementor-post-pagination-next-text">' . $settings->next_label . '</span><i class="' . $next_icon_class . '"></i>';

	$pagination_args = array(
		'type'      => 'array',
		'current'   => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ),
		'total'     => $query->max_num_pages,
		'prev_next' => true,
		'prev_text' => $prev_text,
		'next_text' => $next_text,
	);

	if ( is_singular() && ! is_front_page() ) {
		global $wp_rewrite;
		if ( $wp_rewrite->using_permalinks() ) {
			$paginate_args['format'] = user_trailingslashit( 'page%#%', 'single_paged' ); // Change Occurs For Fixing Pagination Issue.
		} else {
			$paginate_args['format'] = '?page=%#%';
		}
	}

	$links = paginate_links( $pagination_args );
	?>
	<nav class="xpro-elementor-post-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'xpro-bb-addons' ); ?>">
		<?php echo implode( PHP_EOL, $links ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</nav>

<?php endif; ?>
<?php

// Render the empty message.
if ( ! $query->have_posts() ) :

	?>
<div class="tnit-post-empty">
	<p><?php echo esc_attr( $settings->no_results_message ); ?></p>
	<?php if ( $settings->show_search ) : ?>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>

	<?php

endif;

wp_reset_postdata();
?>

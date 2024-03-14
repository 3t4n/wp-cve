<div class="<?php echo esc_attr( $masonry_grid_class . $post_columns_class );?>">
	<div class="theimran-post-layout-one<?php echo esc_attr( $thumbnail_container );?>">
		<?php if('true' === $post_thumbnail_show) : ?>
			<div class="theimran-post-layout-one__thumbnail<?php echo esc_attr( $thumbnail_wrapper );?>">
				<a href="<?php the_permalink();?>"><?php the_post_thumbnail( $image_size );?></a>
				<?php if('true' === $post_category_show && 'true' === $show_category_over_image) : ?>
				<div class="theimran-post-layout-one__categories">
					<?php
						$categories_list = get_the_category_list( esc_html__( ', ', BDFE_TEXT_DOMAIN ) );
						if ( $categories_list ) {
							/* translators: 1: list of categories. */
							printf( '<span class="cat-links">' . __( 'Posted in %1$s', BDFE_TEXT_DOMAIN ) . '</span>', $categories_list ); // WPCS: XSS OK.
						}
						?>
				</div>
				<?php endif;?>
			</div>
		<?php endif;?>
		<div class="theimran-post-layout-one__content-wrapper<?php echo esc_attr( $content_wrapper );?>">
			<?php if('true' === $post_category_show && empty($show_category_over_image)) : ?>
			<div class="theimran-post-layout-one__categories position-static">
				<?php
					$categories_list = get_the_category_list( esc_html__( ', ', BDFE_TEXT_DOMAIN ) );
					if ( $categories_list ) {
						/* translators: 1: list of categories. */
						printf( '<span class="cat-links">' . __( '%1$s', BDFE_TEXT_DOMAIN ) . '</span>', $categories_list ); // WPCS: XSS OK.
					}
					?>
			</div>
			<?php endif;
			if('true' === $post_title_show):
			?>
			<div class="theimran-post-layout-one__title">
				<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
			</div>
			<?php endif; ?>
			<?php
				if('true' === $post_meta_show) :
				$post_meta_data_array = explode(',', $post_meta_data);
			?>
			<div class="theimran-post-layout-one__blog-meta">
				<ul>
					<?php if(in_array('author', $post_meta_data_array)) : ?>
					<li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><span class="fa fa-user-o"></span> <?php echo esc_html( get_the_author() ); ?></a></li>
					<?php endif;
					if(in_array('date', $post_meta_data_array)) :
					?>
					<li><a href="#"> <span class="fa fa-calendar-o"></span><?php bdfe_posted_on(); ?></a></li>
					<?php endif;
					if(in_array('comments', $post_meta_data_array)) :
					?>
					<li><span class="fa fa-comment-o"></span> <?php bdfe_comment_popuplink(); ?></li>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if('true' === $post_excerpt_show) : ?>
				<div class="theimran-post-layout-one__excerpt">
					<p>
						<?php echo esc_html( bdfe_get_excerpt( $post_excerpt_length ) );?>
					</p>
				</div>
			<?php endif;
			if('true' === $read_button_show):
			?>
			<div class="theimran-post-layout-one__read-more">
				<a href="<?php the_permalink();?>"><?php echo esc_html($read_button_text); ?></a>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
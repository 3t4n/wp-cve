<?php if ( ! function_exists( 'hester_section_blog' ) ) {
	function hester_section_blog() {

		$show_section   = hester()->options->get( 'hester_enable_blog' );
		$numberOfPosts  = (int) hester()->options->get( 'hester_blog_posts_number' );
		$fromCategories = hester()->options->get( 'hester_blog_posts_category' );
		$args           = array(
			'post_type'           => 'post',
			'posts_per_page'      => $numberOfPosts,
			'suppress_filters'    => 0,
			'ignore_sticky_posts' => true,
		);
		if ( ! empty( $fromCategories ) ) {
			$args['category_name'] = implode( ',', $fromCategories );
		}
		$posts         = new WP_Query( $args );
		$section_style = '';
		if ( (bool) $show_section === false || ! $posts->have_posts() ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}
		$section_style = 'style="' . $section_style . '"';
		$sub_heading   = hester()->options->get( 'hester_blog_sub_heading' );
		$heading       = hester()->options->get( 'hester_blog_heading' );
		$description   = hester()->options->get( 'hester_blog_description' );
		$column        = hester()->options->get( 'hester_blog_column' );

		do_action( 'hester_before_blog_start' ); ?>
		<section id="hester-blog" class="hester_home_section hester_section_blog" <?php echo wp_kses_post( $section_style ); ?>>
			<?php hester_display_customizer_shortcut( 'hester_enable_blog', true ); ?>
			<div class="hester_bg hester-py-default">
				<div class="hester-container">
					<?php if ( $heading != '' || $sub_heading != '' || $description != '' ) { ?>
						<div class="hester-flex-row">
							<div class="col-md-7 col-xs-12 mx-md-auto mb-h center-xs">
								<div class="starter__heading-title">
									<?php
									if ( $sub_heading != '' ) {
										?>
										<div id="hester-blog-sub-heading" class="h6 sub-title text-primary">
											<?php echo esc_html( $sub_heading ); ?>
										</div>
										<?php
									}
									?>

									<?php
									if ( $heading != '' ) {
										?>
										<div id="hester-blog-heading" class="h2 title">
											<?php echo esc_html( $heading ); ?>
										</div>
										<?php
									}
									?>

									<?php
									if ( $description != '' ) {
										?>
										<div id="hester-blog-description" class="description">
											<?php echo wp_kses_post( $description ); ?>
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="hester-flex-row gy-md-4 gy-4">
						<?php

						if ( $posts->have_posts() ) :
							while ( $posts->have_posts() ) :
								$posts->the_post();
								?>
								<div class="col-md<?php echo esc_attr( $column ); ?> col-sm-6 col-xs-12">
									<?php get_template_part( 'template-parts/content/content', hester_get_article_feed_layout() ); ?>
								</div>
								<?php
							endwhile;

						else :
							?>
							<div class="col-md<?php echo esc_attr( $column ); ?> col-sm-6 col-xs-12">
								<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
							</div>
							<?php

						endif;

						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</section>
		<!-- .starter__blog-section -->

		<?php
		do_action( 'hester_after_blog_end' );
	}
}

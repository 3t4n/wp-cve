<?php
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' );

$running_year = get_option('running_year');

?>

<div class="sakolawp-event-page skwp-content-inner skwp-clearfix">

	<div class="skwp-container">
		<div class="grid-loop skwp-clearfix">
			<div class="skwp-row grid-masonry-wrap">
			<?php 
			if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
			elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
			else { $paged = 1; }
			$sakolawp_event_args = array(
			'post_type'			=> 'sakolawp-event',
			'posts_per_page'	=> -1,
			'paged'          	=> $paged,
			'ignore_sticky_posts' => true,
			);
			$sakolawp_event = new WP_Query($sakolawp_event_args);
			if ($sakolawp_event->have_posts()) : while($sakolawp_event->have_posts()) : $sakolawp_event->the_post(); 
			$img_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');

			$category_name = array();
			$category_slugs = array();
			$category_terms = get_the_terms($post->ID, 'event-category');
			if(!empty($category_terms)){
				if(!is_wp_error( $category_terms )){
				$category_slugs = array();
					foreach($category_terms as $term){
						$category_name[] = $term->name;
						$category_slugs[] = $term->slug;
					}
			$porto_comas =  join( ", ", $category_name );
			$porto_space =  join( " ", $category_slugs );
				}
			}
			else {
				$porto_comas =  "";
				$porto_space =  "";
			}

			$sakolawp_date_event = esc_html( get_post_meta( $post->ID, 'sakolawp_event_date', true ) );
			$sakolawp_hour_event = esc_html( get_post_meta( $post->ID, 'sakolawp_event_date_clock', true ) ); ?>

				<div class="grid-item-loop skwp-column skwp-column-2">
					<div class="loop-wrapper">
						<div class="image-news">
							<?php if(has_post_thumbnail()) {
								if(!empty($sakolawp_date_event)) { ?>
									<div class="event-meta-time has-thumb skwp-clearfix">
										<div class="event-meta-txt skwp-column skwp-column-3">
											<?php esc_html_e('Event Start', 'sakolawp' ); ?>
										</div>
										<div class="event-meta-detail skwp-column skwp-column-2of3">
											<div class="event-meta-date">
												<?php echo esc_html( $sakolawp_date_event ); ?>
											</div>
											<div class="event-meta-hour">
												<?php echo esc_html( $sakolawp_hour_event ); ?>
											</div>
										</div>
									</div>
								<?php }

								the_post_thumbnail(); ?>
								<div class="sakolawp-overlay"></div>
							<?php } ?>
				 		 </div>

						<div class="wrapper-isi-loop">
							<?php if(!has_post_thumbnail()) {
								if(!empty($sakolawp_date_event)) {  ?>
									<div class="event-meta-time skwp-clearfix">
										<div class="event-meta-txt">
											<?php esc_html_e('Event Start', 'sakolawp' ); ?>
										</div>
										<div class="event-meta-detail">
											<div class="event-meta-date">
												<?php echo esc_html( $sakolawp_date_event ); ?>
											</div>
											<div class="event-meta-hour">
												<?php echo esc_html( $sakolawp_hour_event ); ?>
											</div>
										</div>
									</div>
							<?php }
							} ?>
							
							<?php if(!empty($porto_comas)) { ?>
							<div class="category-news skwp-news-meta">
								<?php echo esc_html($porto_comas); ?>
							</div>
							<?php } ?>

							<div class="date-excerpt skwp-news-meta">
								<span class="thedate"><?php echo get_the_date('d'); ?></span>
								<span class="month"><?php echo get_the_date('M'); ?></span>
								<span class="year"><?php echo get_the_date('Y'); ?></span>
							</div>
							
							<div class="title-news">
								<h2 class="title-name">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>
							</div>
							<div class="news-excerpt">
								<p>
									<?php $excerpt = get_the_excerpt();
									$excerpt = substr($excerpt, 0, 70);
									$result = substr($excerpt, 0, strrpos($excerpt, ' '));
									echo esc_html($result); ?>
								</p>
							</div>
							<div class="read-article">
								<a href="<?php the_permalink(); ?>">
									<?php esc_html_e('Read More', 'sakolawp'); ?>
								</a>
							</div>

						</div>
					</div>
				</div>

			<?php endwhile; 
			else : ?>
				<div class="grid-item-loop skwp-column skwp-column-1">
					<?php esc_html_e('There is no post yet.', 'sakolawp' ); ?>
				</div>
			<?php endif; ?>
			</div>
		</div> <!-- Grid Loop end -->
	</div>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();

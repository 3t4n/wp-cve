<?php
/**
** The template for displaying single store post type
** Current version: 1.5.12
**/

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();
			$store_id = get_the_ID();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<?php if(has_post_thumbnail()) : ?>
					<div class="post-thumbnail">
						<?php the_post_thumbnail(); ?>
					</div><!-- .post-thumbnail -->
				<?php endif; ?>

				<div class="entry-content">
					<?php do_action('wps_before_single_content', $store_id) ?>

					<div class="store-details">
						<?php
							$city = wps_get_post_meta($store_id, 'city');
							$phone = wps_get_post_meta($store_id, 'phone');
							$address = wps_get_post_meta($store_id, 'address');
							$description = wps_get_post_meta($store_id, 'description');
							$waze = wps_get_post_meta($store_id, 'waze');
							$waze_icon = apply_filters('wps_store_get_waze_icon', wps_store_get_waze_icon(120));
						?>
						<?php echo !empty($city) ? '<p class="city">' . $city . '</p>' : ''; ?>
						<?php echo !empty($phone) ? '<p class="phone"><a href="' . esc_url('tel:' . $phone) . '">' . $phone . '</a></p>' : ''; ?>
						<?php echo !empty($address) ? '<div class="address">' . apply_filters('the_content', $address) . '</div>' : ''; ?>
						<?php echo !empty($description) ? '<div class="description">' . apply_filters('the_content', $description) . '</div>' : ''; ?>
					</div>

					<?php the_content(); ?>
					
					<?php echo (!empty($waze) && !empty($waze_icon)) ? '<p class="waze"><a href="' . esc_url($waze) . '" target="_blank">' . $waze_icon . '</a></p>' : ''; ?>
					
					<?php do_action('wps_after_single_content', $store_id) ?>
				</div><!-- .entry-content -->
			</article><!-- #post-## -->

			<?php
			the_post_navigation(
				array(
					'next_text' => '<span class="post-title">%title</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
						'<span class="meta-nav" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true"><i class="fa fa-chevron-left"></i></span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				)
			);

			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>

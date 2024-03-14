<?php
/**
** The template for displaying archive store post type
** Current version: 1.5.13
**/

get_header(); ?>

	<div id="primary" class="content-area">
		<header class="entry-header">
			<h1 class="wps-title page-title"><?= post_type_archive_title() ?></h1>
		</header><!-- .page-header -->

		<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<div class="archive-store-wrapper">
					<?php
					while ( have_posts() ) : the_post();
						$city = wps_get_post_meta(get_the_ID(), 'city');
						$phone = wps_get_post_meta(get_the_ID(), 'phone');
						$description = wps_get_post_meta(get_the_ID(), 'description');
						
						?>
						<div class="store-box-content">
							
							<?php do_action('wps_before_archive_store_content', get_the_ID()) ?>

							<?php if(has_post_thumbnail()) : ?>
								<div class="store-image">
									<?php the_post_thumbnail('thumbnail') ?>
								</div>
							<?php endif; ?>
							<div class="store-content">
								<?php if(!empty($city)) : ?>
									<h4><a href="<?php the_permalink() ?>"><?= sanitize_text_field($city) ?></a></h4>
								<?php endif; ?>

								<?php if(!empty($phone)) : ?>
									<p><?= sanitize_text_field($phone) ?></p>
								<?php endif; ?>

								<?= apply_filters('the_content', $description) ?>
							</div>

							<?php do_action('wps_after_archive_store_content', get_the_ID()) ?>

						</div>
						<?php
						
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<h3><?= __('No stores available', 'wp-pickup-store'); ?></h3>
			<?php endif; ?>
		</main><!-- .site-main -->

	</div><!-- .content-area -->

<?php get_footer(); ?>

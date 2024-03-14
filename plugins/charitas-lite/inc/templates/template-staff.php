<?php
/**
 * Template Name: Staff Page
 *
 * @package WordPress
 * @subpackage Charitas
 * @since Charitas 1.0
 */
?>

<?php get_header(); ?>

	<div class="item teaser-page-list">
		<div class="container_16">
			<aside class="grid_10">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</aside>
			<div class="clear"></div>
		</div>
	</div>

	<div id="main" class="site-main container_16">
		<div class="inner">
			<div id="primary" class="grid_16">
				<div class="no-mb">
					<?php if ( $post->post_content != '' ) { ?>
						<div class="item-content staff-template">
							<?php while ( have_posts() ) : the_post(); // start of the loop.?>
								<?php the_content(); ?>
							<?php endwhile; // end of the loop. ?>
							<div class="clear"></div>
						</div>
					<?php } ?>
				</div>
				<div class="clear"></div>
				<div class="js-masonry no-mt">

					<?php $args = array( 'post_type' => 'post_staff','post_status' => 'publish', 'paged'=> $paged); ?>
					<?php $wp_query = null; ?>
					<?php $wp_query = new WP_Query( $args ); ?>
					<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

						<div class="candidate radius grid_4">
							<div class="candidate-margins">
								<a href="<?php the_permalink(); ?>">
									<?php if ( has_post_thumbnail() ) {?>
											<?php the_post_thumbnail('candidate-thumb'); ?>
									<?php } ?>
									<div class="name"><?php the_title(); ?></div>
								</a>
							</div>
						</div>

					<?php endwhile;?>
					<?php else : ?>
						<p><?php _e('Sorry, no Staff matched your criteria.', 'charitas-lite'); ?></p>
					<?php endif; ?>

					<div class="clear"></div>

				</div>
				<?php charitas_lite_content_navigation('postnav' ) ?>
			</div>


			<div class="clear"></div>
		</div>
	</div>
<?php get_footer(); ?>

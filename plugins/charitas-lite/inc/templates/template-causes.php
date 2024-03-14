<?php
/**
 * Template Name: Causes Page
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
			<div id="primary" class="grid_11 suffix_1">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class("single"); ?>>
							<div class="entry-content">
								<?php the_content(); ?>
							</div>
						</article>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php $args = array( 'post_type' => 'post_causes','post_status' => 'publish', 'paged'=> $paged ); ?>
				<?php $wp_query = null; ?>
				<?php $wp_query = new WP_Query( $args ); ?>
				<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<article class="list">
						<div class="short-content">
							<?php if ( has_post_thumbnail() ) {?>
								<figure>
									<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('small-thumb'); ?>
										<div class="mask radius">
											<div class="mask-square"><i class="icon-heart"></i></div>
										</div>
									</a>
								</figure>
							<?php } ?>
							<h1 class="entry-header">
								<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h1>
							<div class="short-description">
								<p><?php echo the_excerpt();?></p>
							</div>
							<div class="entry-meta">
								<a class="buttons fright " href="<?php the_permalink(); ?>" title="<?php _e('Read more about this Cause', 'charitas-lite'); ?>"><?php _e('Support The Cause', 'charitas-lite'); ?></a>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</article>

				<?php endwhile; wp_reset_postdata(); ?>
				<?php else : ?>
					<p><?php _e('Sorry, no Causes matched your criteria.', 'charitas-lite'); ?></p>
				<?php endif; ?>
				<?php charitas_lite_content_navigation('postnav' ) ?>
			</div>
			<?php get_sidebar(); ?>
			<div class="clear"></div>
		</div>
	</div>
<?php get_footer(); ?>

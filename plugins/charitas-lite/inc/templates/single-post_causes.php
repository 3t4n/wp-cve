<?php
/**
 * The default template for displaying Single causes
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
			<h1 class="page-title"><?php the_title() ?></h1>
		</aside>
		<div class="clear"></div>
	</div>
</div>

<div id="main" class="site-main container_16">
	<div class="inner">
		<div id="primary" class="grid_11 suffix_1">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article class="single">
					<div class="entry-content">
						<?php if ( has_post_thumbnail()  ) { ?>
							<figure class="featured-image">
								<?php the_post_thumbnail('big-thumb'); ?>
							</figure>
						<?php } ?>

						<div class="long-description">
							<?php the_content(); ?>
						</div>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; endif; ?>
		</div><!-- #content -->
		<?php get_sidebar(); ?>
		<div class="clear"></div>
	</div><!-- #primary -->
</div>
<?php get_footer(); ?>

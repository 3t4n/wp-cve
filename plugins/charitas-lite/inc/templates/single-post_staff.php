<?php
/**
 * The default template for displaying Single Candidate
 *
 * @package WordPress
 * @subpackage Charitas
 * @since Charitas 1.0
 */
?>

<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); // start of the loop.?>
	<div class="item teaser-page-list">
		<div class="container_16">
			<aside class="grid_10">
				<h1 class="page-title"><?php the_title() ?></h1>
			</aside>
			<div class="clear"></div>
		</div>
	</div>
<?php endwhile; // end of the loop. ?>

<div id="main" class="site-main container_16">
	<div class="inner">
		<div id="primary" class="grid_11 suffix_1">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article class="single">
					<div class="candidate radius grid_6">
						<div class="candidate-margins">
							<?php if ( has_post_thumbnail() ) {?>
									<?php the_post_thumbnail('candidate-thumb'); ?>
							<?php } ?>
							<div class="name"><?php the_title(); ?></div>
						</div>
					</div>
					<div class="candidate-about fright">
						<?php the_content(); ?>
					</div>
					<div class="clear"></div>
				</article>
			<?php endwhile; endif; ?>
		</div><!-- #content -->
		<?php get_sidebar(); ?>
		<div class="clear"></div>
	</div><!-- #primary -->
</div>

<?php get_footer(); ?>

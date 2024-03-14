<?php
/**
 * The template for displaying recipe archive pages
 *
 * This template can be overridden by copying it to yourtheme/archive-blossom-recipe.php.
 */
get_header(); ?>

<div class="wrap">

		<?php
		if ( have_posts() ) :
			?>
			<header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
			</header><!-- .page-header -->

			<div id="primary" class="content-area" itemscope itemtype="http://schema.org/ItemList">
				<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) :
				the_post();

				do_action( 'br_recipe_archive_action' );

			endwhile;

			?>
				</main><!-- #main -->

			<?php
			the_posts_pagination(
				array(
					'prev_text'          => __( 'Previous', 'blossom-recipe-maker' ),
					'next_text'          => __( 'Next', 'blossom-recipe-maker' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'blossom-recipe-maker' ) . ' </span>',
				)
			);

			?>
			</div><!-- #primary -->
					
			<?php
		endif;

		?>
</div><!-- .wrap -->

<?php
get_footer();

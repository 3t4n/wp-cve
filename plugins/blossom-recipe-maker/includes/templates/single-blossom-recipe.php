<?php
/**
 * The template for displaying recipe single pages
 *
 * This template can be overridden by copying it to yourtheme/single-blossom-recipe.php.
 */
get_header(); ?>
 
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header blog-header">
						
						<h1 class="entry-title"><?php the_title(); ?></h1>
						
					</header><!-- .entry-header -->

				<div class="entry-content">

					
					<?php

					do_action( 'br_recipe_rating_action' );
					?>
					
					<?php

					do_action( 'br_recipe_category_links_action' );
					?>

					<?php

					do_action( 'br_recipe_gallery_action' );
					?>

					<?php

					do_action( 'br_recipe_details_action' );
					?>

					<?php

					do_action( 'br_recipe_description_action' );
					?>
						

					<?php

					do_action( 'br_recipe_call_to_action' );
					?>

					<?php

					do_action( 'br_recipe_ingredients_action' );
					?>

					<?php

					do_action( 'br_recipe_instructions_action' );
					?>

					<?php

					do_action( 'br_recipe_notes_action' );

					?>
					
					<?php

					do_action( 'br_recipe_post_tags_action' );

					?>

					<?php

					do_action( 'blossom_recipe_maker_json_ld_action' );

					?>


				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php
					edit_post_link(
						sprintf(
								/* translators: %s: Name of current post */
							__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'blossom-recipe-maker' ),
							esc_html( get_the_title() )
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
				</footer><!-- .entry-footer -->

				</article>

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template( '', true );
					}

				endwhile; // End of the loop.
				?>
	
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->
<?php get_footer(); ?>

<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package TP Education
 * @since 1.0
 */

get_header(); ?>
<div id="content" class="site-content background-image-properties">
	<div class="container page-section">
		<div id="primary" class="content-area os-animation" data-os-animation="fadeIn">
			<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); 

				/**
				 * tp_education_single_content_action hook
				 *
				 * @hooked tp_education_single_content -  10
				 *
				 */
				do_action( 'tp_education_single_content_action' );

				if ( has_action( 'tp_education_post_pagination_action' ) ) :
					/**
					 * Hook - tp_education_post_pagination_action.
					 */
					do_action( 'tp_education_post_pagination_action' );
				else :
					the_post_navigation();
				endif;
				
				/**
				 * tp_education_related_posts_content_action hook
				 *
				 * @hooked tp_education_related_posts_content -  10
				 *
				 */
				do_action( 'tp_education_related_posts_content_action' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php if ( apply_filters( 'tp_education_is_sidebar_enable_filter', true ) ) :
			get_sidebar();
		endif; ?>
	</div><!--end .page-section-->
</div><!--end .site-content-->
<?php
get_footer();

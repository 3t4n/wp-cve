<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Reuse_Builder
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

    <div class="rq-page-content">
        <div class="blog-post-single-wrapper rq-doc-law-list listing-detials-content">
            <?php
                /* Start the Loop */
                while ( have_posts() ) : the_post();

                    // get_template_part( 'template-parts/post/content', get_post_format() );
                    reuse_builder_get_template_part( 'content', 'single-reuse-page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    // if ( comments_open() || get_comments_number() ) :
                    //     comments_template();
                    // endif;

                    the_post_navigation( array(
                        'prev_text'             => __( 'prev chapter: %title' ),
                        'next_text'             => __( 'next chapter: %title' ),
                        'in_same_term'          => true,
                        'taxonomy'              => __( 'post_tag' ),
                        'screen_reader_text'    => __( 'Continue Reading' ),
                    ) );

                endwhile; // End of the loop.
            ?>
        </div> <!-- end .blog-post-single-wrapper .listing-detials-content -->
    </div> <!-- end .rq-page-content -->

<?php get_footer();

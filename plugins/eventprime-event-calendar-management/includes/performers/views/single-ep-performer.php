<?php
/**
 * View: Single Performer
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/single-ep-performer.php
 *
 */
defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php while ( have_posts() ) : the_post(); ?>

            <?php
            $performers = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
            echo wp_kses_post( $performers->render_post_content() );
            ?>
            
        <?php endwhile; // end of the loop. ?>

        <?php comments_template(); ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
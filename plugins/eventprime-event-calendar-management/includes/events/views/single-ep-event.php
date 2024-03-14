<?php
/**
 * View: Single Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/single-ep-event.php
 *
 */
defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php while ( have_posts() ) : the_post();
        
            $events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
            echo $events->render_post_content();
            
        endwhile; // end of the loop. ?>

        <?php comments_template(); ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
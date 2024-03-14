<?php
/**
 * View: Single Venue
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/single-ep-venue.php
 *
 */
defined( 'ABSPATH' ) || exit;

get_header(); ?>
	
    <section id="<?php echo apply_filters('ep_venue_page_html_id', 'main-content'); ?>" class="<?php echo apply_filters('ep_venue_page_html_class', 'ep-container'); ?>">

        <?php do_action('ep_before_main_content'); ?>

        <?php do_action('ep_before_events_loop'); ?>

            <?php
            $venue = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
            echo wp_kses_post( $venue->render_term_content() );
            ?>

        <?php do_action('ep_after_events_loop'); ?>

    </section>

    <?php do_action('ep_after_main_content'); ?>

<?php get_footer();
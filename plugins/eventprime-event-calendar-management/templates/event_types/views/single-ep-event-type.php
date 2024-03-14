<?php
/**
 * View: Single Event Type
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/single-ep-event-type.php
 *
 */
defined( 'ABSPATH' ) || exit;

get_header(); ?>
	
    <section id="<?php echo apply_filters('ep_event_type_page_html_id', 'main-content'); ?>" class="<?php echo apply_filters('ep_event_type_page_html_class', 'ep-container'); ?>">

        <?php do_action('ep_before_main_content'); ?>

        <?php do_action('ep_before_events_loop'); ?>

            <?php
            $event_type = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List' );
            echo wp_kses_post( $event_type->render_term_content() );
            ?>

        <?php do_action('ep_after_events_loop'); ?>

    </section>

    <?php do_action('ep_after_main_content'); ?>

<?php get_footer();
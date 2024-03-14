<?php
/**
 * View: Single Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/single-event.php
 *
 */
defined( 'ABSPATH' ) || exit;
if( isset($args->event->em_id) && post_password_required( $args->event->em_id ) ){
    // if events are password protected
    echo get_the_password_form();
}else{
?>
<div class="emagic" id="ep_single_event_detail_page_content">
    <?php if( ! empty( $args ) && ! empty( $args->post ) ) {?>
        <div class="ep-main-container ep-position-relative ep-box-wrap">
            <?php do_action( 'ep_before_single_event_contant');?>
            <?php
            // Load image template
            ep_get_template_part( 'events/single-event/image', null, $args );
            ?>
            
            <!-- Event Loader -->
            <?php do_action( 'ep_add_loader_section' );?>
            <?php
            // Load icon template
            ep_get_template_part( 'events/single-event/icons', null, $args );

            // Load result template
            ep_get_template_part( 'events/single-event/result', null, $args );
            ?>
            <div class="ep-box-row ep-gx-5">
                <div class="ep-box-col-8" id="ep-sl-left-area">
                    <div class="ep-box-row">
                        <?php
                        // Load date time template
                        ep_get_template_part( 'events/single-event/date-time', null, $args );

                        // Load title template
                        ep_get_template_part( 'events/single-event/title', null, $args );

                        // Load venue template
                        ep_get_template_part( 'events/single-event/venue', null, $args );

                        // Load organizers template
                        ep_get_template_part( 'events/single-event/organizers', null, $args );

                        // Load performers template
                        ep_get_template_part( 'events/single-event/performers', null, $args );?>
                    </div> 
                    <?php
                    // Load description template
                    ep_get_template_part( 'events/single-event/description', null, $args );
                    ?>
                    
                    <?php do_action( 'ep_after_single_events_description', $args );?>
                </div>
                <?php
                // Load tickets template
                ep_get_template_part( 'events/single-event/tickets', null, $args );?> 
            </div>
        </div>

        <?php do_action( 'ep_after_single_event_contant', $args );

    } else{?>
        <div class="ep-alert ep-alert-warning ep-mt-3">
            <?php echo esc_html_e( 'No event found.', 'eventprime-event-calendar-management' ); ?>
        </div><?php
    }?>
</div>
<?php } ?>
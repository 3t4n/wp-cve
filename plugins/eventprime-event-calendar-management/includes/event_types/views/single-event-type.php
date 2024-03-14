<?php
/**
 * View: Single Event Type
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/single-event-type.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="emagic">
    <?php if( ! empty( $args ) && ! empty( $args->term ) ) {?>
        <div class="ep-single-frontend-view-container ep-mb-5" id="ep_single_frontend_view_container">
            <div class="ep-view-container">

                <?php do_action( 'ep_before_event_types_contant');?>

                <!-- box wrapper -->
                <div class="ep-box-wrap ep-details-info-wrap">
                    <div class="ep-box-row">
                        <?php
                        // Load single event type image template
                        ep_get_template_part( 'event_types/single-event-type/image', null, $args );
                        ?>
                        <?php
                        // Load single event type image template
                        ep_get_template_part( 'event_types/single-event-type/detail', null, $args );
                        ?>
                    </div>
                </div>

                <?php do_action( 'ep_after_event_types_contant');?>
                <?php
                if( $args->event_args['show_events'] == 1 ) {
                    // Load upcoming event template
                    ep_get_template_part( 'event_types/single-event-type/upcoming-events', null, $args );
                }?>
            </div>
        </div><?php
    } else{?>
        <div class="ep-alert ep-alert-warning ep-mt-3">
            <?php echo esc_html_e( 'No event-type found.', 'eventprime-event-calendar-management' ); ?>
        </div><?php
    }?>
</div>
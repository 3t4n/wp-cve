<?php
/**
 * View: Event Types List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/list.php
 *
 */
?>
<div class="emagic">
    <div class="ep-event-types-container ep-mb-5" id="ep-event-types-container">
        <?php
        // Load performer search template
        ep_get_template_part( 'event_types/list/search', null, $args );
        ?>

        <?php do_action( 'ep_event_types_list_before_content', $args ); ?>
        
        <?php
        if( isset( $args->event_types ) && !empty( $args->event_types ) ) {?>
            <div class="ep-event-type ep-event-type-<?php echo $args->display_style;?>-container ep-box-wrap"> 
                <div id="ep-event-types-loader-section" class="ep-box-row ep-box-top ep-event_type-<?php echo $args->display_style;?>-wrap">
                    <?php
                    switch ( $args->display_style ) {
                        case 'card':
                        case 'grid':
                            ep_get_template_part( 'event_types/list/views/card', null, $args );
                            break;
                        case 'box':
                        case 'colored_grid':
                            ep_get_template_part( 'event_types/list/views/box', null, $args );
                            break;
                        case 'list':
                        case 'rows':
                            ep_get_template_part( 'event_types/list/views/list', null, $args );
                            break;
                        default: 
                            ep_get_template_part( 'event_types/list/views/card', null, $args ); // Loading card view by default
                    }?>
                </div>
            </div><?php
        } else{?>
            <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                <?php ( isset( $_GET['ep_search'] ) ) ? esc_html_e( 'No Event Type found related to your search.', 'eventprime-event-calendar-management' ) : esc_html_e( 'Currently, there are no event type. Please check back later.', 'eventprime-event-calendar-management' ); ?>
            </div><?php
        }?>
    
        <?php
        // Load performer load more template
        ep_get_template_part( 'event_types/list/load_more', null, $args );
        ?>
        <?php do_action( 'ep_event_types_list_after_content', $args ); ?>

    </div>
</div>
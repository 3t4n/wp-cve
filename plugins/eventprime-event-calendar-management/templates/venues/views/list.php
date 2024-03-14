<?php
/**
 * View: Venue List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/list.php
 *
 */
?>

<div class="emagic">
    <div class="ep-event-venues-container ep-mb-5" id="ep-event-venues-container">
        <?php
        // Load performer search template
        ep_get_template_part( 'venues/list/search', null, $args );
        ?>

        <?php do_action( 'ep_venues_list_before_content', $args ); ?>

        <?php
        if( isset( $args->venues ) && !empty( $args->venues ) ) {?>
            <div class="em_venues dbfl">
                <div class="ep-box-wrap ep-event-venues-<?php echo $args->display_style;?>-container">
                    <div id="ep-event-venues-loader-section" class="ep-box-row ep-box-top ep-venue-<?php echo $args->display_style;?>-wrap ep-box-mx-0">
                        <?php
                        switch ( $args->display_style ) {
                            case 'card':
                            case 'grid': 
                                ep_get_template_part( 'venues/list/views/card', null, $args );
                                break;
                            case 'box': 
                            case 'colored_grid':
                                ep_get_template_part( 'venues/list/views/box', null, $args );
                                break;
                            case 'list': 
                            case 'rows': 
                                ep_get_template_part( 'venues/list/views/list', null, $args );
                                break;
                            default: 
                                ep_get_template_part( 'venues/list/views/card', null, $args ); // Loading card view by default
                        }?>
                    </div>
                </div>
            </div><?php
        } else{?>
            <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                <?php ( isset( $_GET['ep_search'] ) ) ? esc_html_e( 'No Venue found related to your search.', 'eventprime-event-calendar-management' ) : esc_html_e( 'Currently, there are no venue. Please check back later.', 'eventprime-event-calendar-management' ); ?>
            </div><?php
        }?>

        <?php
        // Load performer load more template
        ep_get_template_part( 'venues/list/load_more', null, $args );
        ?>

        <?php do_action( 'ep_venues_list_after_content', $args ); ?>
    </div>
</div>
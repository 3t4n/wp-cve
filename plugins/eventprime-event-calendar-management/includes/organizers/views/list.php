<?php
/**
 * View: Organizers List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/organizers/list.php
 *
 */
?>

<div class="emagic">
    <div class="ep-organizers-container ep-mb-5" id="ep-organizers-container">
        <?php
        // Load performer search template
        ep_get_template_part( 'organizers/list/search', null, $args );
        ?>

        <?php do_action( 'ep_organizers_list_before_content', $args ); ?>

        <?php
        if( isset( $args->organizers ) && !empty( $args->organizers ) ) {?>
            <div class="em_organizers dbfl">
                <div class="ep-box-wrap ep-event-organizers-<?php echo $args->display_style;?>-container ep-box-wrap" >
                    <div id="ep-event-organizers-loader-section" class="ep-box-row ep-box-top ep-organizer-<?php echo $args->display_style;?>-wrap ">
                        <?php
                        switch ( $args->display_style ) {
                            case 'card':
                            case 'grid':
                                ep_get_template_part( 'organizers/list/views/card', null, $args );
                                break;
                            case 'box': 
                            case 'colored_grid':
                                ep_get_template_part( 'organizers/list/views/box', null, $args );
                                break;
                            case 'list': 
                            case 'rows':
                                ep_get_template_part( 'organizers/list/views/list', null, $args );
                                break;
                            default: 
                                ep_get_template_part( 'organizers/list/views/card', null, $args ); // Loading card view by default
                        }?>
                    </div>
                </div>
            </div><?php
        } else{?>
            <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                <?php ( isset( $_GET['ep_search'] ) ) ? esc_html_e( 'No organizers found related to your search.', 'eventprime-event-calendar-management' ) : esc_html_e( 'Currently, there are no organizer. Please check back later.', 'eventprime-event-calendar-management' ); ?>
            </div><?php
        }?>

        <?php
        // Load performer load more template
        ep_get_template_part( 'organizers/list/load_more', null, $args );
        ?>

        <?php do_action( 'ep_organizers_list_after_content', $args ); ?>
    </div> 
</div>
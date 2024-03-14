<?php
/**
 * View: Performers List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/list.php
 *
 */
?>
<div class="emagic">
    <div class="ep-performers-container ep-mb-5 ep-box-wrap" id="ep-performers-container">
        <?php
        // Load performer search template
        ep_get_template_part( 'performers/list/search', null, $args );
        ?>

        <?php do_action( 'ep_performers_list_before_content', $args ); ?>

        <?php
        if( isset( $args->performers ) && !empty( $args->performers ) ) {?>
            <div class="em_performers ep-event-performers-<?php echo $args->display_style;?>-container">
                <div id="ep-event-performers-loader-section" class="ep-box-row ep-box-top ep-performer-<?php echo $args->display_style;?>-wrap ">
                    <?php
                    switch ( $args->display_style ) {
                        case 'card':
                        case 'grid':
                            ep_get_template_part( 'performers/list/views/card', null, $args );
                            break;
                        case 'box': 
                        case 'colored_grid':
                            ep_get_template_part( 'performers/list/views/box', null, $args );
                            break;
                        case 'list':
                        case 'rows': 
                            ep_get_template_part( 'performers/list/views/list', null, $args );
                            break;
                        default: 
                            ep_get_template_part( 'performers/list/views/card', null, $args ); // Loading card view by default
                    }?>
                </div>
            </div><?php
        } else{?>
            <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                <?php ( isset( $_GET['ep_search'] ) ) ? esc_html_e( 'No performers found related to your search.', 'eventprime-event-calendar-management' ) : esc_html_e( 'Currently, there are no performer. Please check back later.', 'eventprime-event-calendar-management' ); ?>
            </div><?php
        }?>

        <?php
        // Load performer load more template
        ep_get_template_part( 'performers/list/load_more', null, $args );
        ?>

        <?php do_action( 'ep_performers_list_after_content', $args ); ?>

    </div>
</div>
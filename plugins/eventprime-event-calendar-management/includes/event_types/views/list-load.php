<?php
/**
 * View: Event Types List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/list.php
 *
 */
?>
<?php
if( isset( $args->event_types ) && !empty( $args->event_types ) ) {?>
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
    }
}?>

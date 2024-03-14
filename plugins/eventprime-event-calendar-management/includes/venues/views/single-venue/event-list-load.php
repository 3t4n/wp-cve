<?php
/**
 * View: Venues List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/single-venue/event-list-load.php
 *
 */
?>
<?php
if( isset( $args->events->posts ) && ! empty( $args->events->posts ) && count( $args->events->posts ) > 0 ) {?>
    <?php
    switch ( $args->event_args['event_style']) {
        case 'card':
        case 'grid':
            ep_get_template_part( 'events/upcoming-events/views/card', null, $args );
            break;
        case 'mini-list':
        case 'plain_list':
            ep_get_template_part( 'events/upcoming-events/views/mini-list', null, $args );
            break;
        case 'list':
        case 'rows':
            ep_get_template_part( 'events/upcoming-events/views/list', null, $args );
            break;
        default: 
            ep_get_template_part( 'events/upcoming-events/views/mini-list', null, $args );
    }    
}

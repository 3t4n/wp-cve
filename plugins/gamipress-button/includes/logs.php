<?php
/**
 * Logs
 *
 * @package GamiPress\Button\Logs
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Extended meta data for event trigger logging
 *
 * @since 1.0.6
 *
 * @param array 	$log_meta
 * @param integer 	$user_id
 * @param string 	$trigger
 * @param integer 	$site_id
 * @param array 	$args
 *
 * @return array
 */
function gamipress_button_log_event_trigger_meta_data( $log_meta, $user_id, $trigger, $site_id, $args ) {

    switch ( $trigger ) {
        case 'gamipress_button_click':
        case 'gamipress_specific_id_button_click':
        case 'gamipress_specific_class_button_click':
            // Add the button attributes
            $log_meta['button_type'] = $args[1];
            $log_meta['button_id'] = $args[2];
            $log_meta['button_class'] = $args[3];
            $log_meta['button_form'] = $args[4];
            $log_meta['button_name'] = $args[5];
            $log_meta['button_value'] = $args[6];
            break;

        case 'gamipress_user_button_click':
        case 'gamipress_user_specific_id_button_click':
        case 'gamipress_user_specific_class_button_click':
            // Add the button attributes and clicker
            $log_meta['button_type'] = $args[1];
            $log_meta['button_id'] = $args[2];
            $log_meta['button_class'] = $args[3];
            $log_meta['clicker_id'] = $args[4]; // User that perform the click
            $log_meta['button_form'] = $args[6];
            $log_meta['button_name'] = $args[7];
            $log_meta['button_value'] = $args[8];
            break;
    }

    return $log_meta;
}
add_filter( 'gamipress_log_event_trigger_meta_data', 'gamipress_button_log_event_trigger_meta_data', 10, 5 );

/**
 * Override the meta data to filter the logs count
 *
 * @since   1.0.6
 *
 * @param  array    $log_meta       The meta data to filter the logs count
 * @param  int      $user_id        The given user's ID
 * @param  string   $trigger        The given trigger we're checking
 * @param  int      $since 	        The since timestamp where retrieve the logs
 * @param  int      $site_id        The desired Site ID to check
 * @param  array    $args           The triggered args or requirement object
 *
 * @return array                    The meta data to filter the logs count
 */
function gamipress_button_get_user_trigger_count_log_meta( $log_meta, $user_id, $trigger, $since, $site_id, $args ) {

    switch( $trigger ) {

        // Specific id
        case 'gamipress_specific_id_button_click':
        case 'gamipress_user_specific_id_button_click':
            // Add the button id
            if( isset( $args[2] ))
                $log_meta['button_id'] = $args[2];

            // $args could be a requirement object
            if( isset( $args['button_id'] ) )
                $log_meta['button_id'] = $args['button_id'];
            break;

        // Specific class
        case 'gamipress_specific_class_button_click':
        case 'gamipress_user_specific_class_button_click':
            // Add the button class
            if( isset( $args[3] ))
                $button_class = $args[3];

            // $args could be a requirement object
            if( isset( $args['button_class'] ) )
                $button_class = $args['button_class'];

            // Since there are many classes, let's to add a log meta LIKE check per class
            if( isset( $button_class ) ) {
                $classes = explode( ' ', $button_class );

                foreach( $classes as $class ) {
                    $log_meta['button_class_' . $class] = array(
                        'key' => 'button_class',
                        'value' => '%' . $class . '%',
                        'compare' => 'LIKE',
                    );
                }
            }
            break;
    }

    return $log_meta;

}
add_filter( 'gamipress_get_user_trigger_count_log_meta', 'gamipress_button_get_user_trigger_count_log_meta', 10, 6 );

/**
 * Extra data fields
 *
 * @since 1.0.6
 *
 * @param array     $fields
 * @param int       $log_id
 * @param string    $type
 *
 * @return array
 */
function gamipress_button_log_extra_data_fields( $fields, $log_id, $type ) {

    $prefix = '_gamipress_';

    $log = ct_get_object( $log_id );
    $trigger = $log->trigger_type;

    if( $type !== 'event_trigger' ) {
        return $fields;
    }

    switch( $trigger ) {
        case 'gamipress_button_click':
        case 'gamipress_specific_id_button_click':
        case 'gamipress_specific_class_button_click':

        case 'gamipress_user_button_click':
        case 'gamipress_user_specific_id_button_click':
        case 'gamipress_user_specific_class_button_click':
            // Type
            $fields[] = array(
                'name' 	            => __( 'Button Type', 'gamipress-button' ),
                'desc' 	            => __( 'Button type attribute user clicked.', 'gamipress-button' ),
                'id'   	            => $prefix . 'button_type',
                'type' 	            => 'text',
            );

            // id
            $fields[] = array(
                'name' 	            => __( 'Button identifier', 'gamipress-button' ),
                'desc' 	            => __( 'Button id attribute user clicked.', 'gamipress-button' ),
                'id'   	            => $prefix . 'button_id',
                'type' 	            => 'text',
            );

            // Class
            $fields[] = array(
                'name' 	            => __( 'Button CSS classes', 'gamipress-button' ),
                'desc' 	            => __( 'Button class attribute user clicked.', 'gamipress-button' ),
                'id'   	            => $prefix . 'button_class',
                'type' 	            => 'text',
            );

            // Form
            $fields[] = array(
                'name' 	            => __( 'Button Form', 'gamipress-button' ),
                'desc' 	            => __( 'Button form attribute user clicked.', 'gamipress-button' ),
                'id'   	            => $prefix . 'button_form',
                'type' 	            => 'text',
            );

            // Name
            $fields[] = array(
                'name' 	            => __( 'Button Name', 'gamipress-button' ),
                'desc' 	            => __( 'Button name attribute user clicked.', 'gamipress-button' ),
                'id'   	            => $prefix . 'button_name',
                'type' 	            => 'text',
            );

            // Value
            $fields[] = array(
                'name' 	            => __( 'Button Value', 'gamipress-button' ),
                'desc' 	            => __( 'Button value attribute user clicked.', 'gamipress-button' ),
                'id'   	            => $prefix . 'button_value',
                'type' 	            => 'text',
            );

            break;
    }

    if( in_array( $trigger, array(
        'gamipress_user_button_click',
        'gamipress_user_specific_id_button_click',
        'gamipress_user_specific_class_button_click'
    ) ) ) {
        // Clicker
        $clicker_id = ct_get_object_meta( $log_id, $prefix . 'clicker_id', true );
        $clicker = get_userdata( $clicker_id );

        if( $clicker ) {
            $fields[] = array(
                'name' 	            => __( 'Clicker', 'gamipress-button' ),
                'desc' 	            => __( 'User that perform the click.', 'gamipress-button' ),
                'id'   	            => $prefix . 'clicker_id',
                'type' 	            => 'select',
                'options'           => array(
                    $clicker_id => $clicker->display_name . ' (#' . $clicker_id . ')'
                )
            );
        }
    }

    return $fields;

}
add_filter( 'gamipress_log_extra_data_fields', 'gamipress_button_log_extra_data_fields', 10, 3 );
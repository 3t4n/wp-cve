<?php
/**
 * Triggers
 *
 * @package GamiPress\Button\Triggers
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register plugin activity triggers
 *
 * @since  1.0.0
 *
 * @param array $activity_triggers
 * @return mixed
 */
function gamipress_button_activity_triggers( $activity_triggers ) {

    $activity_triggers[__( 'Button', 'gamipress-button' )] = array(

        // Triggers to the "clicker"
        'gamipress_button_click' 		                    => __( 'Click any button', 'gamipress-button' ),
        'gamipress_specific_id_button_click'  		        => __( 'Click a button with a specific identifier', 'gamipress-button' ),
        'gamipress_specific_class_button_click'  		    => __( 'Click a button with a specific CSS class', 'gamipress-button' ),

        // Triggers to the post author that receives clicks
        'gamipress_user_button_click' 		            => __( 'Get a click on any button', 'gamipress-button' ),
        'gamipress_user_specific_id_button_click'  		=> __( 'Get a click on a button with a specific identifier', 'gamipress-button' ),
        'gamipress_user_specific_class_button_click'  	=> __( 'Get a click on a button with a specific CSS class', 'gamipress-button' ),

    );

    return $activity_triggers;

}
add_filter( 'gamipress_activity_triggers', 'gamipress_button_activity_triggers' );

/**
 * Build custom activity trigger label
 *
 * @since  1.0.0
 *
 * @param string    $title
 * @param integer   $requirement_id
 * @param array     $requirement
 *
 * @return string
 */
function gamipress_button_activity_trigger_label( $title, $requirement_id, $requirement ) {

    $button_id = ( isset( $requirement['button_id'] ) ) ? $requirement['button_id'] : '';
    $button_class = ( isset( $requirement['button_class'] ) ) ? $requirement['button_class'] : '';

    switch( $requirement['trigger_type'] ) {

        case 'gamipress_specific_id_button_click':
            return sprintf( __( 'Click a button with the identifier %s', 'gamipress-button' ), $button_id );
            break;
        case 'gamipress_specific_class_button_click':
            return sprintf( __( 'Click a button of class %s', 'gamipress-button' ), $button_class );
            break;

        case 'gamipress_user_specific_id_button_click':
            return sprintf( __( 'Get a click on a button with the identifier %s', 'gamipress-button' ), $button_id );
            break;
        case 'gamipress_user_specific_class_button_click':
            return sprintf( __( 'Get a click on a button of class %s', 'gamipress-button' ), $button_class );
            break;

    }

    return $title;
}
add_filter( 'gamipress_activity_trigger_label', 'gamipress_button_activity_trigger_label', 10, 3 );

/**
 * Get user for a given trigger action.
 *
 * @since  1.0.0
 *
 * @param  integer $user_id user ID to override.
 * @param  string  $trigger Trigger name.
 * @param  array   $args    Passed trigger args.
 *
 * @return integer          User ID.
 */
function gamipress_button_trigger_get_user_id( $user_id, $trigger, $args ) {

    switch ( $trigger ) {

        case 'gamipress_button_click':
        case 'gamipress_specific_id_button_click':
        case 'gamipress_specific_class_button_click':

        case 'gamipress_user_button_click':
        case 'gamipress_user_specific_id_button_click':
        case 'gamipress_user_specific_class_button_click':
            $user_id = $args[0];
            break;

    }

    return $user_id;

}
add_filter( 'gamipress_trigger_get_user_id', 'gamipress_button_trigger_get_user_id', 10, 3 );
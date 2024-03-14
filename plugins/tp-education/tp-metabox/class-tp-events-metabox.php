<?php
/**
 * Events Options Metabox
 *
 * @class       TP_Education_Events_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Events_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_event_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_event_options_save' ) );
    }

    public function tp_education_event_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-event' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-event-options', __( 'Event Options', 'tp-education' ), array( $this, 'tp_education_event_options' ), $post_types, 'normal', 'high' );
        endif;
    }

    public function tp_education_event_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_event_options_nonce', 'event_options_nonce' );
        $tp_education_event_date = get_post_meta( $post->ID, 'tp_event_date_value', true );
        $event_date = ! empty( $tp_education_event_date ) ? $tp_education_event_date : '';
        $tp_education_event_time_from = get_post_meta( $post->ID, 'tp_event_time_from_value', true );
        $event_time_from = ! empty( $tp_education_event_time_from ) ? $tp_education_event_time_from : '';
        $tp_education_event_time_to = get_post_meta( $post->ID, 'tp_event_time_to_value', true );
        $event_time_to = ! empty( $tp_education_event_time_to ) ? $tp_education_event_time_to : '';
        $tp_education_event_location = get_post_meta( $post->ID, 'tp_event_location_value', true );
        $event_location = ! empty( $tp_education_event_location ) ? $tp_education_event_location : '';
        ?>

        <label class="tp-label" for="tp_event_date_value"><?php _e( 'Date', 'tp-education' ); ?>: </label><br>
        <input type="text" value="<?php echo esc_attr( $event_date ); ?>" id="event_date_id" name="tp_event_date_value"></p>
        <p><?php _e( 'Please select date for event' ); ?></p>

        <hr>

        <strong><?php _e( 'Time', 'tp-education' );?></strong><br />

        <label class="tp-label" for="tp_event_time_from_value"><?php _e( 'From', 'tp-education' ); ?>: </label>
        <input type="text" style="width:100px;" value="<?php echo esc_attr( $event_time_from ); ?>" id="event_time_from_id" name="tp_event_time_from_value">

        <label class="tp-label" for="tp_event_time_to_value"><?php _e( 'To', 'tp-education' ); ?>: </label>
        <input type="text" style="width:100px;" value="<?php echo esc_attr( $event_time_to ); ?>" id="event_time_to_id" name="tp_event_time_to_value">
        <p><?php _e( 'Please insert starting time and ending time.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_event_location_value"><?php _e( 'Location', 'tp-education' ); ?>: </label><br>
        <textarea type="text" id="event_location_id" style="width:300px;" name="tp_event_location_value"><?php echo esc_textarea( $event_location ); ?></textarea>
        <p><?php _e( 'Please insert the designated location for the event.', 'tp-education' ); ?></p>
        
        <?php    
    }

    public function tp_education_event_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['event_options_nonce'] ) || !wp_verify_nonce( $_POST['event_options_nonce'], 'tp_education_event_options_nonce' ) ) return;

         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_event_date_value'] ) ) :
            $value = ! empty($_POST['tp_event_date_value']) ? $_POST['tp_event_date_value'] : '';
            update_post_meta( $post_id, 'tp_event_date_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_event_time_from_value'] ) ) :
            $value = ! empty($_POST['tp_event_time_from_value']) ? $_POST['tp_event_time_from_value'] : '';
            update_post_meta( $post_id, 'tp_event_time_from_value', sanitize_text_field( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_event_time_to_value'] ) ) :
            $value = ! empty($_POST['tp_event_time_to_value']) ? $_POST['tp_event_time_to_value'] : '';
            update_post_meta( $post_id, 'tp_event_time_to_value', sanitize_text_field( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_event_location_value'] ) ) :
            $value = ! empty($_POST['tp_event_location_value']) ? $_POST['tp_event_location_value'] : '';
            update_post_meta( $post_id, 'tp_event_location_value', sanitize_text_field( $value ) );   
        endif;    
    }

}

new TP_Education_Events_Metabox();
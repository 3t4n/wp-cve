<?php
/**
 * Excursion Options Metabox
 *
 * @class       TP_Education_Excursion_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Excursion_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_excursion_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_excursion_options_save' ) );
    }

    public function tp_education_excursion_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-excursion' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-excursion-options', __( 'Excursion Options', 'tp-education' ), array( $this, 'tp_education_excursion_options' ), $post_types, 'normal', 'high' );
        endif;
    }

    public function tp_education_excursion_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_excursion_options_nonce', 'excursion_options_nonce' );
        $tp_education_excursion_start_date = get_post_meta( $post->ID, 'tp_excursion_start_date_value', true );
        $excursion_start_date = ! empty( $tp_education_excursion_start_date ) ? $tp_education_excursion_start_date : '';
        $tp_education_excursion_end_date = get_post_meta( $post->ID, 'tp_excursion_end_date_value', true );
        $excursion_end_date = ! empty( $tp_education_excursion_end_date ) ? $tp_education_excursion_end_date : '';
        $tp_education_excursion_location = get_post_meta( $post->ID, 'tp_excursion_location_value', true );
        $excursion_location = ! empty( $tp_education_excursion_location ) ? $tp_education_excursion_location : '';
        ?>

        <label class="tp-label" for="tp_excursion_start_date_value"><?php _e( 'Start Date', 'tp-education' ); ?>: </label><br>
        <input type="text" value="<?php echo esc_attr( $excursion_start_date ); ?>" id="excursion_start_date_id" name="tp_excursion_start_date_value"></p>
        <p><?php _e( 'Please select start date for excursion.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_excursion_end_date_value"><?php _e( 'End Date', 'tp-education' ); ?>: </label><br>
        <input type="text" value="<?php echo esc_attr( $excursion_end_date ); ?>" id="excursion_end_date_id" name="tp_excursion_end_date_value">
        <p><?php _e( 'Please select end date for excursion.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_excursion_location_value"><?php _e( 'Location', 'tp-education' ); ?>: </label><br>
        <textarea type="text" id="excursion_location_id" style="width:300px;" name="tp_excursion_location_value"><?php echo esc_textarea( $excursion_location ); ?></textarea>
        <p><?php _e( 'Please insert the designated location for the excursion.', 'tp-education' ); ?></p>
        
        <?php    
    }

    public function tp_education_excursion_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['excursion_options_nonce'] ) || !wp_verify_nonce( $_POST['excursion_options_nonce'], 'tp_education_excursion_options_nonce' ) ) return;
         
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_excursion_start_date_value'] ) ) :
            $value = ! empty($_POST['tp_excursion_start_date_value']) ? $_POST['tp_excursion_start_date_value'] : '';
            update_post_meta( $post_id, 'tp_excursion_start_date_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_excursion_end_date_value'] ) ) :
            $value = ! empty($_POST['tp_excursion_end_date_value']) ? $_POST['tp_excursion_end_date_value'] : '';
            update_post_meta( $post_id, 'tp_excursion_end_date_value', sanitize_text_field( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_excursion_location_value'] ) ) :
            $value = ! empty($_POST['tp_excursion_location_value']) ? $_POST['tp_excursion_location_value'] : '';
            update_post_meta( $post_id, 'tp_excursion_location_value', sanitize_text_field( $value ) );   
        endif;    
    }

}

new TP_Education_Excursion_Metabox();
<?php
/**
 * Testimonial Rating Metabox
 *
 * @class       TP_Education_Testimonial_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Testimonial_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_testimonial_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_testimonial_options_save' ) );
    }

    public function tp_education_testimonial_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-testimonial' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-testimonial-options', __( 'Testimonial Options', 'tp-education' ), array( $this, 'tp_education_testimonial_options' ), $post_type, 'normal', 'high' );
        endif;
    }

    public function tp_education_testimonial_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_testimonial_options_nonce', 'testimonial_rating_nonce' );
        $tp_education_testimonial_rating = get_post_meta( $post->ID, 'tp_testimonial_rating_value', true );
        $testimonial_rating = ! empty( $tp_education_testimonial_rating ) ? $tp_education_testimonial_rating : '';
        $tp_education_testimonial_designation = get_post_meta( $post->ID, 'tp_testimonial_designation_value', true );
        $testimonial_designation = ! empty( $tp_education_testimonial_designation ) ? $tp_education_testimonial_designation : '';
        ?>
        <label class="tp-label" for="tp_testimonial_rating_value"><?php _e( 'Rating', 'tp-education' ); ?></label><br>
        <select name="tp_testimonial_rating_value" id="testimonial_rating_id" style="width:100px;" >
            <option value="1" <?php echo ( $testimonial_rating == 1 ) ? 'selected' : ''; ?>><?php _e( '1 Star', 'tp-education' ); ?></option>
            <option value="2" <?php echo ( $testimonial_rating == 2 ) ? 'selected' : ''; ?>><?php _e( '2 Star', 'tp-education' ); ?></option>
            <option value="3" <?php echo ( $testimonial_rating == 3 ) ? 'selected' : ''; ?>><?php _e( '3 Star', 'tp-education' ); ?></option>
            <option value="4" <?php echo ( $testimonial_rating == 4 ) ? 'selected' : ''; ?>><?php _e( '4 Star', 'tp-education' ); ?></option>
            <option value="5" <?php echo ( $testimonial_rating == 5 ) ? 'selected' : ''; ?>><?php _e( '5 Star', 'tp-education' ); ?></option>
        </select>
        <p><?php _e( 'Please rate between 1 star and 5 star.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_testimonial_designation_value"><?php _e( 'Designation ', 'tp-education' ); ?></label><br>
        <input type="text" name="tp_testimonial_designation_value" id="tp_testimonial_designation_id" placeholder="<?php _e( 'Student', 'tp-education' ); ?>" value="<?php echo esc_attr( $testimonial_designation ); ?>" />
        <p><?php _e( 'Please input the designation of the personel.', 'tp-education' ); ?></p>
        
        <?php    
    }


    public function tp_education_testimonial_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['testimonial_rating_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_rating_nonce'], 'tp_education_testimonial_options_nonce' ) ) return;
         
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_testimonial_rating_value'] ) ) :
            $value = ! empty($_POST['tp_testimonial_rating_value']) ? $_POST['tp_testimonial_rating_value'] : '';
            update_post_meta( $post_id, 'tp_testimonial_rating_value', absint( $value ) );   
        endif;    

         // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_testimonial_designation_value'] ) ) :
            $value = ! empty($_POST['tp_testimonial_designation_value']) ? $_POST['tp_testimonial_designation_value'] : '';
            update_post_meta( $post_id, 'tp_testimonial_designation_value', sanitize_text_field( $value ) );   
        endif;   
    }

}

new TP_Education_Testimonial_Metabox();
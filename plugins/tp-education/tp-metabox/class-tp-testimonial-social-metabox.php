<?php
/**
 * testimonial Metabox
 *
 * @class       TP_Education_Testimonial_Social_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Testimonial_Social_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_testimonial_social_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_testimonial_social_options_save' ) );
    }

    public function tp_education_testimonial_social_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-testimonial' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-testimonial-social-options', __( 'Testimonial Social Options', 'tp-education' ), array( $this, 'tp_education_social_options' ), $post_type, 'normal', 'high' );
        endif;
    }

    public function tp_education_social_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_social_options_nonce', 'tp_education_testimonial_social_nonce' );
        $tp_education_testimonial_social_count = get_post_meta( $post->ID, 'tp_testimonial_social_count_value', true );
        $testimonial_social_count = ! empty( $tp_education_testimonial_social_count ) ? $tp_education_testimonial_social_count : 4;

        for ( $i = 1; $i <= $testimonial_social_count; $i++ ) :
            $tp_education_social[$i] = get_post_meta( $post->ID, 'tp_testimonial_social_value_' . $i, true );
            $tp_education_social[$i] = ! empty( $tp_education_social[$i] ) ? $tp_education_social[$i] : '';
        endfor;
        
        ?>
        <label class="tp-label" for="tp_testimonial_social_count_value"><?php _e( ' No of Social Links', 'tp-education' ); ?>: </label><br>
        <input type="number" min="1" max="8" name="tp_testimonial_social_count_value" id="testimonial_social_count_id" value="<?php echo esc_attr( $testimonial_social_count ); ?>" />
        <p><?php _e( 'Please insert number of social links you need to display. Press Publish or Update button to have number of social input fields you have input.', 'tp-education' ); ?></p>

        <hr>

        <?php for ( $i = 1; $i <= $testimonial_social_count; $i++ ) : ?>

            <label class="tp-label" for="tp_testimonial_social_value"><?php printf( __( 'Social Link %d', 'tp-education' ), $i ); ?>: </label><br>
            <input type="url" name="<?php echo 'tp_testimonial_social_value_' . $i; ?>" id="<?php echo 'tp_testimonial_social_' . $i; ?>" value="<?php echo esc_attr( $tp_education_social[$i] ); ?>" />
            <p><?php _e( 'Please input your social links or leave empty.', 'tp-education' ); ?></p>

        <?php endfor;
    }


    public function tp_education_testimonial_social_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        
        $tp_education_testimonial_social_count = get_post_meta( $post_id, 'tp_testimonial_social_count_value', true );
        $testimonial_social_count = ! empty( $tp_education_testimonial_social_count ) ? $tp_education_testimonial_social_count : 4;

        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['tp_education_testimonial_social_nonce'] ) || !wp_verify_nonce( $_POST['tp_education_testimonial_social_nonce'], 'tp_education_social_options_nonce' ) ) return;
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_testimonial_social_count_value'] ) ) :
            $value = ! empty($_POST['tp_testimonial_social_count_value']) ? $_POST['tp_testimonial_social_count_value'] : '';
            update_post_meta( $post_id, 'tp_testimonial_social_count_value', absint( $value ) );   
        endif;      

        for ( $i = 1; $i <= $testimonial_social_count; $i++ ) :
            // Make sure your data is set before trying to save it
            if( isset( $_POST['tp_testimonial_social_value_' . $i] ) ) :
                $value = ! empty($_POST['tp_testimonial_social_value_' . $i]) ? $_POST['tp_testimonial_social_value_' . $i] : '';
                update_post_meta( $post_id, 'tp_testimonial_social_value_' . $i, esc_url_raw( $value ) );   
            endif;  
        endfor;

    }

}

new TP_Education_Testimonial_Social_Metabox();
<?php
/**
 * Affiliation link Metabox
 *
 * @class       TP_Education_Affiliation_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Affiliation_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_options_save' ) );
    }

    public function tp_education_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-affiliation' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-affiliation-options', __( 'Affiliation Options', 'tp-education' ), array( $this, 'tp_education_options' ), $post_type, 'normal', 'high' );
        endif;
    }

    public function tp_education_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_options_nonce', 'affiliation_link_nonce' );
        $tp_education_affiliation_link = get_post_meta( $post->ID, 'tp_affiliation_link_value', true );
        $affiliation_link = ! empty( $tp_education_affiliation_link ) ? $tp_education_affiliation_link : '';
        ?>
        <label class="tp-label" for="tp_affiliation_link_value"><?php _e( 'Link', 'tp-education' ); ?>: </label><br>
        <input type="url" name="tp_affiliation_link_value" id="affiliation_link_id" placeholder="<?php _e( 'Input Url', 'tp-education' ); ?>" value="<?php echo esc_attr( $affiliation_link ); ?>" />
        <p><?php _e( 'Please insert the link.', 'tp-education' ); ?></p>
        
        <?php    
    }


    public function tp_education_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['affiliation_link_nonce'] ) || !wp_verify_nonce( $_POST['affiliation_link_nonce'], 'tp_education_options_nonce' ) ) return;
         
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_affiliation_link_value'] ) ) :
            $value = ! empty($_POST['tp_affiliation_link_value']) ? $_POST['tp_affiliation_link_value'] : '';
            update_post_meta( $post_id, 'tp_affiliation_link_value', esc_url_raw( $value ) );   
        endif;      
    }

}

new TP_Education_Affiliation_Metabox();
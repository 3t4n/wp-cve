<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Ajax Class
 */
class WPB_GQB_Ajax {

    /**
     * Bind actions
     */
    function __construct() {

        add_action( 'wp_ajax_fire_contact_form', array( $this, 'fire_contact_form' ) );
        add_action( 'wp_ajax_nopriv_fire_contact_form', array( $this, 'fire_contact_form' ) );
    }

    /**
     * Form Content
     */

    public function fire_contact_form() {
        $form                   = '';
        $contact_form_id        = isset( $_POST['contact_form_id'] ) ? sanitize_text_field( $_POST['contact_form_id'] ) : '';

        if( $contact_form_id ){

            $form = do_shortcode( '[contact-form-7 id="'.esc_attr($contact_form_id).'"]' );
        }


        if( $form ){
            wp_send_json_success($form);
        }
    }
}

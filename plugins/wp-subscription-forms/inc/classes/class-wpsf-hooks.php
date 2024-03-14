<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
if ( !class_exists( 'WPSF_Hooks' ) ) {

    class WPSF_Hooks extends WPSF_Library {

        function __construct() {
            add_action( 'template_redirect', array( $this, 'form_preview' ) );
            add_action( 'template_redirect', array( $this, 'verify_subscription_confirmation' ) );
            add_action( 'wp_footer', array( $this, 'add_popup_wrapper' ) );
        }

        function form_preview() {
            if ( isset( $_GET['wpsf_preview'], $_GET['_wpnonce'] ) && $_GET['wpsf_preview'] && wp_verify_nonce( $_GET['_wpnonce'], 'wpsf_form_preview_nonce' ) && is_user_logged_in() ) {
                wp_enqueue_style( 'wpsf-preview', WPSF_URL . 'css/wpsf-preview.css', array(), WPSF_VERSION );
                include(WPSF_PATH . 'inc/views/frontend/form-preview.php');
                die();
            }
        }

        function verify_subscription_confirmation() {
            if ( isset( $_GET['wpsf_subscription_confirmation'], $_GET['confirmation_verification_key'] ) ) {
                $confirmation_verification_key = sanitize_text_field( $_GET['confirmation_verification_key'] );
                if ( !empty( $_COOKIE['wpsf_email'] ) && !empty( $_COOKIE['wpsf_alias'] ) ) {
                    $subscriber_email = sanitize_email( $_COOKIE['wpsf_email'] );
                    $subscriber_name = (!empty( $_COOKIE['wpsf_name'] )) ? sanitize_text_field( $_COOKIE['wpsf_name'] ) : '';
                    if ( md5( $subscriber_email ) == $confirmation_verification_key ) {
                        $form_alias = sanitize_text_field( $_COOKIE['wpsf_alias'] );
                        $form_row = $this->get_form_row_by_alias( $form_alias );
                        $form_details = maybe_unserialize( $form_row->form_details );
                        $form_data['wpsf_email'] = $subscriber_email;
                        $form_data['wpsf_name'] = $subscriber_name;
                        include(WPSF_PATH . '/inc/cores/subscribe-action.php');
                        echo $this->sanitize_html( $form_details['general']['optin_confirmation_message'] );
                        die();
                    }
                }
            }
        }

        function add_popup_wrapper() {
            ?>
            <div class="wpsf-temp-popup-wrapper"></div>
            <?php
        }

    }

    new WPSF_Hooks();
}

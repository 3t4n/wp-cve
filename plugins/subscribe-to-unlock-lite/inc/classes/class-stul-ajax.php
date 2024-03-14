<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('STUL_Ajax')) {

    class STUL_Ajax extends STUL_Library {

        function __construct() {
            /**
             * Form process ajax
             */
            add_action('wp_ajax_stul_form_process_action', array($this, 'form_process_action'));
            add_action('wp_ajax_nopriv_stul_form_process_action', array($this, 'form_process_action'));

            /**
             * Subscriber Verification Status update ajax
             */
            add_action('wp_ajax_stul_verify_status_action', array($this, 'update_verification_status'));
            add_action('wp_ajax_nopriv_stul_verify_status_action', array($this, 'update_verification_status'));
        }

        /**
         * Process subscription form
         *
         * @since 1.0.0
         */
        function form_process_action() {
            include(STUL_PATH . 'inc/cores/subscription-process.php');
        }

        function update_verification_status() {
            if ($this->ajax_nonce_verify()) {
                $unlock_key = sanitize_text_field($_POST['unlock_key']);
                $this->change_verification_status($unlock_key);
                die();
            } else {
                $this->permission_denied();
            }
        }

    }

    new STUL_Ajax();
}

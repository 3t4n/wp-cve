<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
if ( !class_exists( 'WPSF_Ajax' ) ) {

    class WPSF_Ajax extends WPSF_Library {

        function __construct() {
            /**
             * Form process ajax
             */
            add_action( 'wp_ajax_wpsf_form_process_action', array( $this, 'form_process_action' ) );
            add_action( 'wp_ajax_nopriv_wpsf_form_process_action', array( $this, 'form_process_action' ) );
        }

        /**
         * Process subscription form
         *
         * @since 1.0.0
         */
        function form_process_action() {
            include(WPSF_PATH . 'inc/cores/subscription-process.php');
        }

    }

    new WPSF_Ajax();
}

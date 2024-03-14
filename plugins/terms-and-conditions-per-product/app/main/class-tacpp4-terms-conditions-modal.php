<?php
/**
 * Class for terms-modal.
 *
 * @package Terms_Conditions_Modal
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If class exists, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Modal' ) ) {

    /**
     * Class for transxen core.
     */
    class TACPP4_Terms_Conditions_Modal {

        /**
         * Constructor for class.
         */
        public function __construct() {
            // Enqueue front-end scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_scripts' ),
                100, true );
        }

        /**
         * Enqueue style/script.
         *
         * @return void
         */
        public function enqueue_style_scripts() {

            // Bail out if in admin or not checkout page
            if ( is_admin() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
                return;
            }

            // Bail out if the modal setting is off
            if ( ! $this->is_terms_modal_enabled() && ! $this->is_wc_terms_modal_enabled() ) {
                return;
            }

            // Custom plugin script.
            wp_enqueue_style(
                'terms-per-product-modal',
                TACPP4_PLUGIN_URL . 'assets/css/terms-modal.css',
                '',
                TACPP4_PLUGIN_VERSION
            );

            wp_enqueue_style(
                'jquery-modal',
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css',
                '',
                TACPP4_PLUGIN_VERSION
            );

            // Register plugin's JS script
            wp_register_script(
                'terms-per-product-modal',
                TACPP4_PLUGIN_URL . 'assets/js/terms-modal.js',
                array(
                    'jquery',
                ),
                TACPP4_PLUGIN_VERSION,
                true
            );

            // Provide a global object to our JS file containing the AJAX url and security nonce
            $args = array(
                'modal_html' => ( $this->get_modal_html() ),
            );

            if ( $this->is_terms_modal_enabled() ) {
                $args['terms_modal'] = true;
            }

            if ( $this->is_wc_terms_modal_enabled() ) {
                $args['wc_terms_modal'] = true;
            }

            wp_localize_script( 'terms-per-product-modal', 'tacppModalObj', $args );

            wp_enqueue_script( 'terms-per-product-modal' );


            wp_enqueue_script( 'jquery-modal',
                'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js',
                array(), TACPP4_PLUGIN_VERSION, true );

        }

        /**
         * Check if the Modal is enabled
         * @return bool
         */
        public function is_terms_modal_enabled() {
            $tacpp_options = get_option(
                TACPP4_Terms_Conditions_Settings::$tacpp_option_name );

            $is_enabled = false;

            if ( isset( $tacpp_options['terms_modal'] ) &&
                 $tacpp_options['terms_modal'] === 1 &&
                 tacppp_fs()->is_paying_or_trial()
            ) {
                $is_enabled = true;
            }

            return $is_enabled;
        }

        /**
         * Check if the Modal is enabled
         * @return bool
         */
        public function is_wc_terms_modal_enabled() {
            $tacpp_options = get_option(
                TACPP4_Terms_Conditions_Settings::$tacpp_option_name );

            $is_enabled = false;

            if ( isset( $tacpp_options['wc_terms_modal'] ) &&
                 $tacpp_options['wc_terms_modal'] === 1 &&
                 tacppp_fs()->is_paying_or_trial()
            ) {
                $is_enabled = true;
            }

            return $is_enabled;
        }

        /**
         * Get the terms modal HTML template
         *
         * @return HTML
         */
        public function get_modal_html() {
            $modal_html = '<div id="product-terms-modal" class="modal bb-modal">
                <h2>
                    ' . __( 'Terms and Conditions', 'terms-and-conditions-per-product' ) . '                </h2>
                <iframe src="[TERMS_URL]"></iframe>
            </div>';

            return $modal_html;
        }
    }

    new TACPP4_Terms_Conditions_Modal();
}

<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Enqueue')) {

    class WPSF_Enqueue {

        function __construct() {
            add_action('wp_enqueue_scripts', array($this, 'register_frontend_assets'));
            add_action('admin_enqueue_scripts', array($this, 'register_admin_assets'));
        }

        function register_frontend_assets() {
            $wpsf_frontend_obj = array('ajax_url' => admin_url('admin-ajax.php'), 'ajax_nonce' => wp_create_nonce('wpsf_frontend_ajax_nonce'));
            $wpsf_settings = get_option('wpsf_settings');
            if (empty($wpsf_settings['extra']['disable_fontawesome'])) {
                wp_enqueue_style('fontawesome', WPSF_URL . 'fontawesome/css/all.min.css', array(), WPSF_VERSION);
            }
            wp_enqueue_script('wpsf-frontend-script', WPSF_JS_DIR . '/wpsf-frontend.js', array('jquery'), WPSF_VERSION);

            wp_enqueue_style('wpsf-frontend-style', WPSF_CSS_DIR . '/wpsf-frontend.css', array(), WPSF_VERSION);
            if (is_rtl()) {
                wp_enqueue_style('wpsf-frontend-style-rtl', WPSF_CSS_DIR . '/wpsf-frontend-rtl.css', array(), WPSF_VERSION);
            }
            wp_localize_script('wpsf-frontend-script', 'wpsf_frontend_obj', $wpsf_frontend_obj);
        }

        function register_admin_assets() {
            $translation_strings = array(
                'ajax_message' => esc_html__('Please wait', 'wp-subscription-forms'),
                'upload_button_text' => esc_html__('Upload File', 'wp-subscription-forms'),
                'delete_form_confirm' => esc_html__('Are you sure you want to delete this form?', 'wp-subscription-forms'),
                'copy_form_confirm' => esc_html__('Are you sure you want to copy this form?', 'wp-subscription-forms'),
                'clipboad_copy_message' => esc_html__('Shortcode copied to clipboard.', 'wp-subscription-forms'),
                'invalid_api_key' => esc_html__('Invalid API Key.', 'wp-subscription-forms'),
                'mc_connect' => esc_html__('Connected', 'wp-subscription-forms'),
                'mc_disconnect' => esc_html__('Disconnected', 'wp-subscription-forms'),
                'mc_reset' => esc_html__('Are you sure you want to reset the Mailchimp connection?', 'wp-subscription-forms'),
                'cc_reset' => esc_html__('Are you sure you want to reset the Constant Contact connection?', 'wp-subscription-forms'),
                'delete_subscriber_confirm' => esc_html__('Are you sure you want to delete this subscriber?', 'wp-subscription-forms'),
            );
            $wpsf_backend_obj = array('ajax_url' => admin_url('admin-ajax.php'), 'plugin_url' => WPSF_URL, 'translation_strings' => $translation_strings, 'ajax_nonce' => wp_create_nonce('wpsf_ajax_nonce'));
            wp_enqueue_media();
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('fontawesome', WPSF_URL . 'fontawesome/css/all.min.css', array(), WPSF_VERSION);
            wp_enqueue_style('wpsf-backend-style', WPSF_CSS_DIR . '/wpsf-backend.css', array(), WPSF_VERSION);
            wp_enqueue_script('wpsf-backend-script', WPSF_JS_DIR . '/wpsf-backend.js', array('jquery', 'wp-color-picker', 'wp-util'), WPSF_VERSION);
            wp_localize_script('wpsf-backend-script', 'wpsf_backend_obj', $wpsf_backend_obj);
        }

    }

    new WPSF_Enqueue();
}
<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('STUL_Enqueue')) {

    class STUL_Enqueue {

        function __construct() {
            add_action('wp_enqueue_scripts', array($this, 'register_frontend_assets'));
            add_action('admin_enqueue_scripts', array($this, 'register_admin_assets'));
        }

        function register_frontend_assets() {
            $stul_frontend_obj = array('ajax_url' => admin_url('admin-ajax.php'), 'ajax_nonce' => wp_create_nonce('stul_frontend_ajax_nonce'));
            $stul_settings = get_option('stul_settings');
            if (empty($stul_settings['extra']['disable_fontawesome'])) {
                wp_enqueue_style('fontawesome', STUL_URL . 'fontawesome/css/all.min.css', array(), STUL_VERSION);
            }

            wp_enqueue_script('stul-frontend-script', STUL_JS_DIR . '/stul-frontend.js', array('jquery'), STUL_VERSION);
            wp_enqueue_style('stul-frontend-style', STUL_CSS_DIR . '/stul-frontend.css', array(), STUL_VERSION);
            if (is_rtl()) {
                wp_enqueue_style('stul-frontend-rtl-style', STUL_CSS_DIR . '/stul-rtl.css', array(), STUL_VERSION);
            }
            wp_localize_script('stul-frontend-script', 'stul_frontend_obj', $stul_frontend_obj);
        }

        function register_admin_assets() {
            $translation_strings = array(
                'ajax_message' => esc_html__('Please wait', 'subscribe-to-unlock-lite'),
                'upload_button_text' => esc_html__('Upload File', 'subscribe-to-unlock-lite'),
                'delete_form_confirm' => esc_html__('Are you sure you want to delete this form?', 'subscribe-to-unlock-lite'),
                'copy_form_confirm' => esc_html__('Are you sure you want to copy this form?', 'subscribe-to-unlock-lite'),
                'clipboad_copy_message' => esc_html__('Shortcode copied to clipboard.', 'subscribe-to-unlock-lite'),
                'invalid_api_key' => esc_html__('Invalid API Key.', 'subscribe-to-unlock-lite'),
                'mc_connect' => esc_html__('Connected', 'subscribe-to-unlock-lite'),
                'mc_disconnect' => esc_html__('Disconnected', 'subscribe-to-unlock-lite'),
                'mc_reset' => esc_html__('Are you sure you want to reset the Mailchimp connection?', 'subscribe-to-unlock-lite'),
                'cc_reset' => esc_html__('Are you sure you want to reset the Constant Contact connection?', 'subscribe-to-unlock-lite'),
                'delete_subscriber_confirm' => esc_html__('Are you sure you want to delete this subscriber?', 'subscribe-to-unlock-lite'),
            );
            $stul_backend_obj = array('ajax_url' => admin_url('admin-ajax.php'), 'plugin_url' => STUL_URL, 'translation_strings' => $translation_strings, 'ajax_nonce' => wp_create_nonce('stul_ajax_nonce'));
            wp_enqueue_media();
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('fontawesome', STUL_URL . 'fontawesome/css/all.min.css', array(), STUL_VERSION);
            wp_enqueue_style('stul-backend-style', STUL_CSS_DIR . '/stul-backend.css', array(), STUL_VERSION);
            wp_enqueue_style('stul-tinymce-style', STUL_CSS_DIR . '/stul-tinymce.css', array(), STUL_VERSION);
            wp_enqueue_script('stul-backend-script', STUL_JS_DIR . '/stul-backend.js', array('jquery', 'wp-color-picker', 'wp-util'), STUL_VERSION);
            wp_localize_script('stul-backend-script', 'stul_backend_obj', $stul_backend_obj);
        }

    }

    new STUL_Enqueue();
}
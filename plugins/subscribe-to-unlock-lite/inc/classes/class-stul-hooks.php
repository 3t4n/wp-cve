<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('STUL_Hooks')) {

    class STUL_Hooks extends STUL_Library {

        function __construct() {
            add_action('template_redirect', array($this, 'form_preview'));
            add_action('template_redirect', array($this, 'verify_link'));
        }

        function form_preview() {
            if (isset($_GET['stul_preview'], $_GET['_wpnonce']) && $_GET['stul_preview'] && wp_verify_nonce($_GET['_wpnonce'], 'stul_form_preview_nonce') && is_user_logged_in()) {
                wp_enqueue_style('stul-preview', STUL_URL . 'css/stul-preview.css', array(), STUL_VERSION);
                include(STUL_PATH . 'inc/views/frontend/form-preview.php');
                die();
            }
        }

        function verify_link() {
            if (isset($_GET['stul_unlock_key'], $_COOKIE['stul_unlock_key']) && $_COOKIE['stul_unlock_key'] == $_GET['stul_unlock_key']) {
                $unlock_key = sanitize_text_field($_GET['stul_unlock_key']);
                $unlock_link_message = $this->get_unlock_link_message($unlock_key);
                setcookie('stul_unlock_check', 'yes', time() + (86400 * 30 * 365), "/");
                echo $this->sanitize_html($unlock_link_message);
                $this->change_verification_status($unlock_key);
                die();
            }
        }

    }

    new STUL_Hooks();
}

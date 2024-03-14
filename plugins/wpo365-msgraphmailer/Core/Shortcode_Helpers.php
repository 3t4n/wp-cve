<?php

namespace Wpo\Core;

use \Wpo\Core\Extensions_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Error_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\Shortcode_Helpers')) {

    class Shortcode_Helpers
    {

        /**
         * Helper method to ensure that short codes are initialized
         * 
         * @since 7.0
         * 
         * @return void
         */
        public static function ensure_pintra_short_code()
        {

            if (!shortcode_exists('pintra')) {
                add_shortcode('pintra', '\Wpo\Core\Shortcode_Helpers::add_pintra_shortcode');
            }
        }

        /**
         * Adds a pintra app launcher into the page 
         * 
         * @since 5.0
         * 
         * @param array short code parameters according to Wordpress codex
         * @param string content found in between the short code start and end tag
         * @param string text domain
         */
        public static function add_pintra_shortcode($atts = array(), $content = null, $tag = '')
        {
            $atts = array_change_key_case((array)$atts, CASE_LOWER);
            $props = '[]';

            if (
                isset($atts['props'])
                && strlen(trim($atts['props'])) > 0
            ) {
                $result = array();
                $props = html_entity_decode($atts['props']);
                $prop_kv_pairs = explode(';', $props);

                foreach ($prop_kv_pairs as  $prop_kv_pair) {
                    $first_separator = WordPress_Helpers::stripos($prop_kv_pair, ',');

                    if (false === $first_separator) {
                        continue;
                    }

                    $result[\substr($prop_kv_pair, 0, $first_separator)] = \substr($prop_kv_pair, $first_separator + 1);
                }

                $props = json_encode($result);
            }

            $script_url = isset($atts['script_url']) ? html_entity_decode($atts['script_url']) : '';

            ob_start();
            include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/pintra.php');
            $content = ob_get_clean();
            return wp_kses($content, WordPress_Helpers::get_allowed_html());
        }

        /**
         * Helper method to ensure that short codes are initialized
         * 
         * @since 8.0
         * 
         * @return void
         */
        public static function ensure_login_button_short_code_V2()
        {

            if ((class_exists('\Wpo\Premium') || class_exists('\Wpo\Intranet')) && !shortcode_exists('wpo365-sign-in-with-microsoft-v2-sc')) {
                add_shortcode('wpo365-sign-in-with-microsoft-v2-sc', '\Wpo\Core\Shortcode_Helpers::add_sign_in_with_microsoft_shortcode_V2');
            }
        }

        /**
         * Adds the Sign in with Microsoft short code V2
         * 
         * @since 8.0
         * 
         * @param array short code parameters according to Wordpress codex
         * @param string content found in between the short code start and end tag
         * @param string text domain
         */
        public static function add_sign_in_with_microsoft_shortcode_V2($params = array(), $content = null, $tag = '')
        {

            if (empty($content)) {
                return $content;
            }

            $site_url = $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'];

            // Load the js dependency
            ob_start();
            include(Extensions_Helpers::get_active_extension_dir(array('wpo365-login-premium/wpo365-login.php', 'wpo365-sync-5y/wpo365-sync-5y.php', 'wpo365-login-intranet/wpo365-login.php', 'wpo365-intranet-5y/wpo365-intranet-5y.php')) . '/templates/openid-ssolink.php');
            $js_lib = ob_get_clean();

            // Sanitize the HTML template
            $dom = new \DOMDocument();
            @$dom->loadHTML($content);
            $script = $dom->getElementsByTagName('script');
            $remove = array();

            foreach ($script as $item)
                $remove[] = $item;

            foreach ($remove as $item)
                $item->parentNode->removeChild($item);

            // Concatenate the two
            $output = $js_lib . $dom->saveHTML();
            return str_replace("__##PLUGIN_BASE_URL##__", $GLOBALS['WPO_CONFIG']['plugin_url'], $output);
        }

        /**
         * Helper method to ensure that short code for login button is initialized
         * 
         * @since 11.0
         */
        public static function ensure_login_button_short_code()
        {

            if (!shortcode_exists('wpo365-login-button')) {
                add_shortcode('wpo365-login-button', '\Wpo\Core\Shortcode_Helpers::login_button');
            }
        }

        /**
         * Helper to display the Sign in with Microsoft button on a login form.
         * 
         * @since 10.6
         * 
         * @param   
         * 
         * @return void
         */
        public static function login_button()
        {
            // Don't render a login button when sso is disabled
            if (Options_Service::get_global_boolean_var('no_sso')) {
                return;
            }

            // Used by the template that is rendered
            $hide_login_button = Options_Service::get_global_boolean_var('hide_login_button');

            $sign_in_with_microsoft = Options_Service::get_global_string_var('sign_in_with_microsoft');

            if (empty($sign_in_with_microsoft) || $sign_in_with_microsoft == 'Sign in with Microsoft') {
                $sign_in_with_microsoft = __('Sign in with Microsoft', 'wpo365-login');
            }

            ob_start();
            include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/login-button.php');
            $content = ob_get_clean();
            echo wp_kses($content, WordPress_Helpers::get_allowed_html());
        }

        /**
         * Helper method to ensure that short code for displaying errors is initialized
         * 
         * @since 7.8
         */
        public static function ensure_display_error_message_short_code()
        {

            if ((class_exists('\Wpo\Professional') || class_exists('\Wpo\Customers') || class_exists('\Wpo\Premium') || class_exists('\Wpo\Intranet')) && !shortcode_exists('wpo365-display-error-message-sc'))
                add_shortcode('wpo365-display-error-message-sc', '\Wpo\Core\Shortcode_Helpers::add_display_error_message_shortcode');
        }

        /**
         * Adds the error message encapsulated in a div into the page 
         * 
         * @since 7.8
         * 
         * @param array short code parameters according to Wordpress codex
         * @param string content found in between the short code start and end tag
         * @param string text domain
         */
        public static function add_display_error_message_shortcode($atts = array(), $content = null, $tag = '')
        {
            $error_code = isset($_GET['login_errors'])
                ? sanitize_text_field($_GET['login_errors'])
                : '';

            $error_message = Error_Service::get_error_message($error_code);

            if (empty($error_message)) {
                return;
            }

            ob_start();
            include(Extensions_Helpers::get_active_extension_dir(array('wpo365-login-professional/wpo365-login.php', 'wpo365-customers/wpo365-customers.php', 'wpo365-login-premium/wpo365-login.php', 'wpo365-sync-5y/wpo365-sync-5y.php', 'wpo365-login-intranet/wpo365-login.php', 'wpo365-intranet-5y/wpo365-intranet-5y.php', 'wpo365-customers/wpo365-customers.php')) . '/templates/error-message.php');
            $content = ob_get_clean();
            return wp_kses($content, WordPress_Helpers::get_allowed_html());
        }
    }
}

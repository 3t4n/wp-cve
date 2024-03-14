<?php

namespace Wpo\Pages;

use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Pages\Wizard_Page')) {

    class Wizard_Page
    {

        /**
         * Definition of the Options page (following default Wordpress practice).
         * 
         * @since 2.0
         * 
         * @return void
         */
        public static function add_management_page()
        {
            /**
             * @since   21.9    Administrators can restrict access to the WPO365 configuration
             */

            if (defined('WPO_ADMINS')) {
                $admins = constant('WPO_ADMINS');

                if (!is_array($admins)) {
                    return;
                }

                $admins = array_flip($admins);
                $admins = array_change_key_case($admins);
                $current_user = wp_get_current_user();

                if (!($current_user instanceof \WP_User)) {
                    return;
                }

                $user_login = strtolower($current_user->user_login);

                if (!array_key_exists($user_login, $admins)) {
                    return;
                }
            }

            // Don't add the WPO365 wizard in the subsite admin when subsite options has not been configured
            if (
                is_multisite()
                && !is_network_admin()
                && false === Options_Service::mu_use_subsite_options()
            ) {
                return;
            }

            add_menu_page(
                'WPO365',
                'WPO365',
                'delete_users',
                'wpo365-wizard',
                '\Wpo\Pages\Wizard_Page::wpo365_wizard_page'
            );
        }

        /**
         * 
         */
        public static function wpo365_wizard_page()
        {
            ob_start();
            include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/wizard.php');
            $content = ob_get_clean();
            echo '' . wp_kses($content, WordPress_Helpers::get_allowed_html());
        }
    }
}

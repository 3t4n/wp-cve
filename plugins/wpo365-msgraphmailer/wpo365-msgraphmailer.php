<?php

/**
 *  Plugin Name: WPO365 | MICROSOFT 365 GRAPH MAILER
 *  Plugin URI: https://wordpress.org/plugins/wpo365-msgraphmailer
 *  Description: WPO365 | MS GRAPH MAILER re-configures your WordPress website to send transactional emails from one of your Microsoft 365 Exchange Online / Mail enabled accounts using Microsoft Graph instead of - for example - using SMTP.
 *  Version: 2.27
 *  Author: marco@wpo365.com
 *  Author URI: https://www.wpo365.com
 *  License: GPL2+
 */

namespace Wpo;

require __DIR__ . '/vendor/autoload.php';

use \Wpo\Core\Globals;
use \Wpo\Core\Permissions_Helpers;
use \Wpo\Services\Dependency_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;
use \Wpo\Services\Router_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\MsGraphMailer')) {

    class MsGraphMailer
    {

        private $dependencies;

        private $no_conflict = true;

        public function __construct()
        {
            $this->deactivation_hooks();
            add_action('plugins_loaded', array($this, 'load'), 1);
        }

        public function load()
        {

            // If WPO365 | LOGIN exists and is active then do not initialize this plugin.
            if (\file_exists(dirname(__DIR__) . '/wpo365-login')) {
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');

                if (is_plugin_active('wpo365-login/wpo365-login.php')) {
                    add_action('admin_notices', array($this, 'ensure_wpo365_login'), 10, 0);
                    return;
                }
            }

            Globals::set_global_vars(__FILE__, __DIR__);
            $this->cache_dependencies();
            $this->add_wp_hooks();

            $has_route = Router_Service::has_route();
        }

        public function ensure_wpo365_login()
        {
            echo '<div class="notice notice-error" style="margin-left: 2px;"><p>'
                . 'The <strong>WPO365 | MS GRAPH MAILER</strong> plugin has detected that you have also installed the <strong>WPO365 | LOGIN</strong> plugin. '
                . 'Since that plugin offers the same functionality <a href="https://www.wpo365.com/features/" target="_blank">plus a lot more</a> and uses the '
                . 'same configuration-source, the <em>Microsoft Graph Mailer</em> of the <strong>WPO365 | LOGIN</strong> plugin will be used instead. '
                . '<br><br>'
                . 'See the <a href="https://docs.wpo365.com/article/141-send-email-using-microsoft-graph-mailer" target="_blank">online documentation</a> for details.'
                . '</p></div>';
        }

        private function cache_dependencies()
        {
            $this->dependencies = Dependency_Service::get_instance();
            $this->dependencies->add('Request_Service', Request_Service::get_instance(true));
        }

        private function add_wp_hooks()
        {
            // Plugin updater and license checker
            // Plugin updater and license checker
            if (class_exists('\Wpo\Core\Plugin_Updater')) {
                \Wpo\Core\Plugin_Updater::add_hooks();
            }

            // Do super admin stuff
            if ((is_admin() || is_network_admin()) && Permissions_Helpers::user_is_admin(\wp_get_current_user())) {

                // Add and hide wizard (page)
                add_action('admin_menu', '\Wpo\Pages\Wizard_Page::add_management_page');
                add_action('network_admin_menu', '\Wpo\Pages\Wizard_Page::add_management_page');

                new \Wpo\Pages\License_Page();

                // Show admin notification when WPO365 not properly configured
                add_action('admin_notices', '\Wpo\Services\Notifications_Service::show_admin_notices', 10, 0);
                add_action('network_admin_notices', '\Wpo\Services\Notifications_Service::show_admin_notices', 10, 0);
                add_action('admin_init', '\Wpo\Services\Notifications_Service::dismiss_admin_notices', 10, 0);

                // Wire up AJAX backend services
                add_action('wp_ajax_wpo365_delete_settings', '\Wpo\Services\Ajax_Service::delete_settings');
                add_action('wp_ajax_wpo365_delete_tokens', '\Wpo\Services\Ajax_Service::delete_tokens');
                add_action('wp_ajax_wpo365_get_settings', '\Wpo\Services\Ajax_Service::get_settings');
                add_action('wp_ajax_wpo365_update_settings', '\Wpo\Services\Ajax_Service::update_settings');
                add_action('wp_ajax_wpo365_get_log', '\Wpo\Services\Ajax_Service::get_log');

                // Graph mailer
                add_action('wp_ajax_wpo365_send_test_mail', '\Wpo\Services\Ajax_Service::send_test_mail');
                add_action('wp_ajax_wpo365_get_mail_authorization_url', '\Wpo\Services\Ajax_Service::get_mail_authorization_url');
                add_action('wp_ajax_wpo365_get_mail_auth_configuration', '\Wpo\Services\Ajax_Service::get_mail_auth_configuration');
                add_action('wp_ajax_wpo365_try_migrate_mail_app_principal_info', '\Wpo\Services\Ajax_Service::try_migrate_mail_app_principal_info');

                // Graph mailer auditing
                if (class_exists('\Wpo\Mail\Mail_Db')) {
                    // Wire up AJAX backend services
                    add_action('wp_ajax_wpo365_get_mail_log', '\Wpo\Mail\Mail_Ajax_Service::get_mail_log');
                    add_action('wp_ajax_wpo365_send_mail_again', '\Wpo\Mail\Mail_Ajax_Service::send_mail_again');
                    add_action('wp_ajax_wpo365_truncate_mail_log', '\Wpo\Mail\Mail_Ajax_Service::truncate_mail_log');

                    if (Options_Service::get_global_boolean_var('mail_auto_retry') && method_exists('\Wpo\Mail\Mail_Ajax_Service', 'mail_auto_retry')) {
                        add_action('wp_ajax_wpo365_mail_auto_retry', '\Wpo\Mail\Mail_Ajax_Service::mail_auto_retry');
                    }
                }

                // Show settings link
                add_filter((is_network_admin() ? 'network_admin_' : '') . 'plugin_action_links_' . $GLOBALS['WPO_CONFIG']['plugin'], '\Wpo\Core\Plugin_Helpers::get_configuration_action_link', 10, 1);

                // Add license related messages to WP Admin
                \Wpo\Core\Plugin_Updater::show_license_notices();

                //  Ensure WP Cron job to check for each registered application whether its secret will epxire soon is added.

                if (class_exists('\Wpo\Services\Password_Credentials_Service')) {
                    \Wpo\Services\Password_Credentials_Service::ensure_check_password_credentials_expiration();
                }
            } // End of admin stuff

            //  WP Cron job triggered action to check for each registered application whether its secret will epxire soon.
            add_action('wpo_check_password_credentials_expiration', '\Wpo\Services\Password_Credentials_Service::check_password_credentials_expiration');

            // Add custom cron schedule for user sync
            add_filter('cron_schedules', '\Wpo\Core\Cron_Helpers::add_cron_schedules', 10, 1);

            // Clean up on shutdown
            add_action('shutdown', '\Wpo\Services\Request_Service::shutdown');

            add_action('admin_enqueue_scripts', '\Wpo\Core\Script_Helpers::enqueue_wizard', 10, 0);

            // Replace phpmailer with Graph Mailer (but only if configured)
            // According to https://developer.wordpress.org/reference/functions/wp_mail/ wp_mail is available after plugins_loaded
            add_action('phpmailer_init', '\Wpo\Mail\Mailer::init', PHP_INT_MAX);
            add_filter('wp_mail_from', '\Wpo\Mail\Mailer::mail_from', 10, 1);

            if (Options_Service::get_global_boolean_var('mail_log', false) && method_exists('\Wpo\Mail\Mail_Db', 'add_mail_log')) {
                // Log each wp_mail in the wpo365_table
                add_filter('wp_mail', '\Wpo\Mail\Mail_Db::add_mail_log', 10, 1);
            }

            if (Options_Service::get_global_boolean_var('mail_throttling_enabled') && method_exists('\Wpo\Mail\Mail_Db', 'check_message_rate_limit')) {
                add_filter('wpo365/mail/before', '\Wpo\Mail\Mail_Db::check_message_rate_limit');
            }

            if (Options_Service::get_global_boolean_var('mail_auto_retry') && method_exists('\Wpo\Mail\Mail_Db', 'process_unsent_messages')) {
                add_action('wpo_process_unsent_messages', '\Wpo\Mail\Mail_Db::process_unsent_messages');
            }

            // Add safe style css
            add_filter('safe_style_css', '\Wpo\Core\WordPress_Helpers::safe_css', 10, 1);

            // Admin menu bar notifications
            if (Options_Service::get_global_boolean_var('mail_staging_mode', false) && class_exists('\Wpo\Mail\Mail_Notifications')) {
                add_action('admin_bar_menu', '\Wpo\Mail\Mail_Notifications::staging_mode_active', 100);
                add_action('wp_enqueue_scripts', '\Wpo\Core\Script_Helpers::add_admin_bar_styles');
                add_action('admin_enqueue_scripts', '\Wpo\Core\Script_Helpers::add_admin_bar_styles');
            }
        }

        private function deactivation_hooks()
        {
            if (\class_exists('\Wpo\Mail\Mail_Db')) {
                // Delete possible cron jobs
                register_deactivation_hook(__FILE__, function () {
                    wp_clear_scheduled_hook('wpo_process_unsent_messages');
                    Options_Service::add_update_option('mail_auto_retry', false);
                });
            }
        }
    }
}

$wpo365_msgraphmailer = new MsGraphMailer();

<?php

namespace Wpo\Services;

use \Wpo\Core\Plugin_Helpers;
use \Wpo\Core\Url_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Notifications_Service')) {

    class Notifications_Service
    {

        /**
         * Shows admin notices when the plugin is not configured correctly
         * 
         * @since 2.3
         * 
         * @return void
         */
        public static function show_admin_notices()
        {

            if (!is_admin() && !is_network_admin()) {
                return;
            }

            if (is_super_admin() && (!is_multisite() || Options_Service::mu_use_subsite_options() || (!Options_Service::mu_use_subsite_options() && is_network_admin()))) {

                if (!empty($_REQUEST['send-to-azure'])) {
                    $users_sent = (int) $_REQUEST['send-to-azure'];
                    $target_ciam = Options_Service::get_global_boolean_var('use_b2c') ? 'Azure AD B2C' : (Options_Service::get_global_boolean_var('use_ciam') ? 'Entra External ID' : '');
                    printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Created / updated %d users in %s', 'wpo365-login') . '</p></div>', $users_sent, $target_ciam);
                }

                if (!empty($_REQUEST['re-activate-users'])) {
                    $users_reactivated = (int) $_REQUEST['re-activate-users'];
                    printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Reactivated %d users', 'wpo365-login') . '</p></div>', $users_reactivated);
                }

                if (false === Options_Service::get_global_boolean_var('hide_error_notice')) {
                    $cached_errors = Wpmu_Helpers::mu_get_transient('wpo365_errors');

                    if (is_array($cached_errors)) {

                        $title = __('WPO365 health status', 'wpo365-login');
                        $dismiss_button = sprintf(
                            '</p><p><a class="button button-primary" href="#" onclick="javascript:window.location.href = window.location.href.replace(\'page=wpo365-wizard\', \'page=wpo365-wizard&wpo365_errors_dismissed=true\')">%s</a>',
                            __('Dismiss', 'wpo365-login')
                        );
                        $footer = '- Marco van Wieren | Downloads by van Wieren | <a href="https://www.wpo365.com/">https://www.wpo365.com/</a>';
                        $notice_type = 'error';
                        $hide_image = true;

                        if (isset($_GET['page']) && WordPress_Helpers::trim($_GET['page']) == 'wpo365-wizard') {
                            $wpo365_errors = '';

                            array_map(function ($log_item) use (&$wpo365_errors) {
                                $wpo365_errors .= '<li><strong>[' . date('Y-m-d H:i:s', esc_html($log_item['time'])) . ']</strong> ' . wp_kses($log_item['body'], WordPress_Helpers::get_allowed_html()) . '</li>';
                            }, $cached_errors);

                            $message = sprintf(
                                __('The %s plugin detected the following three errors that you should address:%s%s%s%s%s', 'wpo365-login'),
                                '<strong>WPO365 | LOGIN</strong>',
                                '<p></p><ul style="list-style: initial; padding-left: 20px;">' . wp_kses($wpo365_errors, WordPress_Helpers::get_allowed_html()) . '</ul><p>',
                                __('Please take the time to review those errors. Once errors have been addressed you can safely dismiss this notice for now or hide this notification on the plugin\'s Debug configuration page to hide this notice permanently.', 'wpo365-login'),
                                $dismiss_button,
                                '</p><p>',
                                sprintf(
                                    __('Please check the %s for help or alternatively %s whenever you are unable to resolve the error reported.', 'wpo365-login'),
                                    sprintf(
                                        '<a href="https://docs.wpo365.com/" target="_blank">%s</a>',
                                        __('online documentation', 'wpo365-login')
                                    ),
                                    sprintf(
                                        '<a href="https://www.wpo365.com/contact/" target="_blank">%s</a>',
                                        __('contact WPO365 support', 'wpo365-login')
                                    )
                                )
                            );
                        } else {
                            $message = sprintf(
                                __('The %s plugin detected errors that you should address. Please %s to review and address these errors.', 'wpo365-login'),
                                '<strong>WPO365 | LOGIN</strong>',
                                sprintf(
                                    '<a href="admin.php?page=wpo365-wizard">%s</a>',
                                    __('click here', 'wpo365-login')
                                )
                            );
                        }

                        ob_start();
                        include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/admin-notifications.php');
                        $content = ob_get_clean();
                        echo '' . wp_kses($content, WordPress_Helpers::get_allowed_html());
                    }
                }

                if ($GLOBALS['WPO_CONFIG']['plugin'] == 'wpo365-login/wpo365-login.php') {

                    if (isset($_GET['page']) && WordPress_Helpers::trim($_GET['page']) == 'wpo365-wizard' && false === Options_Service::get_global_boolean_var('no_sso') && false === Options_Service::is_wpo365_configured()) {

                        $title = __('Getting started', 'wpo365-login');
                        $message = sprintf(
                            __('Check out our %s documentation to start integrating your WordPress website with %s. For example:%s', 'wpo365-login'),
                            sprintf(
                                '<a href="https://docs.wpo365.com/article/154-aad-single-sign-for-wordpress-using-auth-code-flow" target="_blank">%s</a>',
                                __('Getting started'),
                                'wpo365-login'
                            ),
                            '<strong>Microsoft 365 / Azure AD</strong>',
                            sprintf(
                                '<ul style="list-style: initial; padding-left: 20px;"><li><a href="https://docs.wpo365.com/article/154-aad-single-sign-for-wordpress-using-auth-code-flow" target="_blank">%s</a></li><li><a href="https://docs.wpo365.com/article/100-single-sign-on-with-saml-2-0-for-wordpress" target="_blank">%s</a></li><li><a href="https://docs.wpo365.com/article/108-sending-wordpress-emails-using-microsoft-graph" target="_blank">%s</a></li></ul>',
                                __('OpenID based Single Sign-on', 'wpo365-login'),
                                __('SAML 2.0 based Single Sign-on', 'wpo365-login'),
                                __('Sending WordPress mail using Microsoft Graph', 'wpo365-login')
                            )
                        );
                        $footer = '- Marco van Wieren | Downloads by van Wieren | <a href="https://www.wpo365.com/">https://www.wpo365.com/</a>';
                        $notice_type = 'info';

                        ob_start();
                        include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/admin-notifications.php');
                        $content = ob_get_clean();
                        echo '' . wp_kses($content, WordPress_Helpers::get_allowed_html());
                    }
                }

                // review
                if (
                    $GLOBALS['WPO_CONFIG']['plugin'] == 'wpo365-login/wpo365-login.php'
                    && isset($_GET['page'])
                    && WordPress_Helpers::trim($_GET['page']) == 'wpo365-wizard'
                    && !Options_Service::get_global_boolean_var('review_stop', false)
                    && true === Options_Service::is_wpo365_configured()
                    && false === Wpmu_Helpers::mu_get_transient('wpo365_review_dismissed')
                    && false === Wpmu_Helpers::mu_get_transient('wpo365_user_created')
                    && !Plugin_Helpers::is_premium_edition_active()
                ) {
                    $title = __('Sharing is caring', 'wpo365-login');
                    $buttons = sprintf(
                        '<a class="button button-primary" href="http://wordpress.org/support/view/plugin-reviews/wpo365-login?filter=5#postform" target="_blank">%s</a> <a class="button" href="./?wpo365_review_dismissed">%s</a> <a class="button" href="./?wpo365_review_stop">%s</a></p>',
                        __('Yes, here we go!', 'wpo365-login'),
                        __('Remind me later', 'wpo365-login'),
                        __('No thanks', 'wpo365-login')
                    );
                    $message = sprintf(
                        __('Many thanks for using the %s plugin! Could you please spare a minute and give it a review over at WordPress.org?%s%s', 'wpo365-login'),
                        '<strong>WPO365 | LOGIN</strong>',
                        '</p><p>',
                        $buttons
                    );
                    $footer = '- Marco van Wieren | Downloads by van Wieren | <a href="https://www.wpo365.com/">https://www.wpo365.com/</a>';
                    $notice_type = 'info';

                    ob_start();
                    include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/admin-notifications.php');
                    $content = ob_get_clean();
                    echo '' . wp_kses($content, WordPress_Helpers::get_allowed_html());
                }

                // upgrade
                if (
                    $GLOBALS['WPO_CONFIG']['plugin'] == 'wpo365-login/wpo365-login.php'
                    && isset($_GET['page'])
                    && WordPress_Helpers::trim($_GET['page']) == 'wpo365-wizard'
                    && true === Options_Service::is_wpo365_configured()
                    && false !== Wpmu_Helpers::mu_get_transient('wpo365_user_created')
                    && false === Wpmu_Helpers::mu_get_transient('wpo365_upgrade_dismissed')
                    && !Plugin_Helpers::is_premium_edition_active()
                ) {
                    $title = __('Sharing is caring', 'wpo365-login');
                    $buttons = sprintf(
                        '<a class="button button-primary" href="http://wordpress.org/support/view/plugin-reviews/wpo365-login?filter=5#postform" target="_blank">%s</a> <a class="button" href="./?wpo365_upgrade_dismissed">%s</a></p>',
                        __('Yes, here we go!', 'wpo365-login'),
                        __('Remind me later', 'wpo365-login')
                    );
                    $message = sprintf(
                        __('The %s plugin just created a new WordPress user for you! Could you please spare a minute and give it a review over at WordPress.org?%s%s', 'wpo365-login'),
                        '<strong>WPO365 | LOGIN</strong>',
                        '</p><p>',
                        $buttons
                    );
                    $footer = '- Marco van Wieren | Downloads by van Wieren | <a href="https://www.wpo365.com/">https://www.wpo365.com/</a>';
                    $notice_type = 'info';

                    ob_start();
                    include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/admin-notifications.php');
                    $content = ob_get_clean();
                    echo '' . wp_kses($content, WordPress_Helpers::get_allowed_html());
                }
            }
        }

        /**
         * Helper to configure a transient to surpress admoin notices when the user clicked dismiss.
         * 
         * @since 7.18
         * 
         * @return void
         */
        public static function dismiss_admin_notices()
        {

            if (isset($_GET['wpo365_errors_dismissed'])) {
                Wpmu_Helpers::mu_delete_transient('wpo365_errors');
                Url_Helpers::force_redirect(remove_query_arg('wpo365_errors_dismissed'));
            }

            if (isset($_GET['wpo365_review_dismissed'])) {
                Wpmu_Helpers::mu_set_transient('wpo365_review_dismissed', date('d'), 1209600);
                Url_Helpers::force_redirect(remove_query_arg('wpo365_review_dismissed'));
            }

            if (isset($_GET['wpo365_review_stop'])) {
                Wpmu_Helpers::mu_delete_transient('wpo365_review_dismissed');
                Options_Service::add_update_option('review_stop', true);
                Url_Helpers::force_redirect(remove_query_arg('wpo365_review_stop'));
            }

            if (isset($_GET['wpo365_upgrade_dismissed'])) {
                Wpmu_Helpers::mu_delete_transient('wpo365_user_created');
                Wpmu_Helpers::mu_set_transient('wpo365_upgrade_dismissed', date('d'), 1209600);
                Url_Helpers::force_redirect(remove_query_arg('wpo365_upgrade_dismissed'));
            }
        }
    }
}

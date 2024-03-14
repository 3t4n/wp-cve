<?php

namespace Wpo\Services;

use \Wpo\Core\Extensions_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Id_Token_Service;
use \Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Error_Service')) {

    class Error_Service
    {

        const AADAPPREG_ERROR   = 'AADAPPREG_ERROR';
        const BASIC_VERSION     = 'BASIC_VERSION';
        const CHECK_LOG         = 'CHECK_LOG';
        const DEACTIVATED       = 'DEACTIVATED';
        const DUAL_LOGIN        = 'DUAL_LOGIN';
        const DUAL_LOGIN_V2     = 'DUAL_LOGIN_V2';
        const ID_TOKEN_ERROR    = 'ID_TOKEN_ERROR';
        const ID_TOKEN_AUD      = 'ID_TOKEN_AUD';
        const LOGGED_OUT        = 'LOGGED_OUT';
        const PRIVATE_PAGE      = 'PRIVATE_PAGE';
        const NOT_CONFIGURED    = 'NOT_CONFIGURED';
        const NOT_FROM_DOMAIN   = 'NOT_FROM_DOMAIN';
        const NOT_IN_GROUP      = 'NOT_IN_GROUP';
        const SAML2_ERROR       = 'SAML2_ERROR';
        const TAMPERED_WITH     = 'TAMPERED_WITH';
        const USER_NOT_FOUND    = 'USER_NOT_FOUND';

        /**
         * Checks for errors in the login messages container and display and unset immediatly after if any
         *
         * @since   1.0
         * @return  void
         */
        public static function check_for_login_messages($message)
        {

            if (!isset($_GET['login_errors'])) {
                return $message;
            }

            // Using $_GET here since wp_query is not loaded on login page
            $login_error_codes = sanitize_text_field($_GET['login_errors']);

            $result = '';

            foreach (explode(',', $login_error_codes) as $login_error_code) {

                $error_message = self::get_error_message($login_error_code);

                if (empty($error_message)) {
                    continue;
                }

                $result .= '<p class="message">' . $error_message . '</p><br />';
            }

            // Return messages to display to hook
            return $result;
        }

        /**
         * Tries to get an error message for the error code provided either from
         * the options or else from the hard coded backup dictionary provided.
         * 
         * @since 0.1
         * 
         * @param string $error_code Error code
         * @return string Error message
         */
        public static function get_error_message($error_code)
        {

            $deprecated_error_messages = array(
                self::CHECK_LOG         => 'Please contact your System Administrator and check log file.',
                self::DEACTIVATED       => 'Account deactivated.',
                self::DUAL_LOGIN        => 'Alternatively, you can click the following link to sign into this website with your corporate <a href="__##OAUTH_URL##__">network login (Office 365)</a>',
                self::DUAL_LOGIN_V2     => 'Alternatively, you can click the following link to sign into this website with your corporate <span class="wpo365-dual-login-notice" style="cursor: pointer; text-decoration: underline; color: #000CD" onclick="window.wpo365.pintraRedirect.toMsOnline()">network login (Office 365)</span>',
                self::ID_TOKEN_ERROR    => 'Your ID token could not be processed. Please contact your System Administrator.',
                self::ID_TOKEN_AUD      => 'The ID token is intended for a different audience. Please contact your System Administrator.',
                self::LOGGED_OUT        => 'You are now logged out.',
                self::NOT_CONFIGURED    => 'Wordpress + Office 365 login not configured yet. Please contact your System Administrator.',
                self::NOT_FROM_DOMAIN   => 'Access Denied. Please contact your System Administrator.',
                self::NOT_IN_GROUP      => 'User not in group. Please contact your System Administrator.',
                self::SAML2_ERROR       => 'SAML authentication error',
                self::TAMPERED_WITH     => 'Your login might be tampered with. Please contact your System Administrator.',
                self::USER_NOT_FOUND    => 'Could not create or retrieve your login. Please contact your System Administrator.',
            );

            $error_messages = array(
                self::AADAPPREG_ERROR   => __('Could not create or retrieve your login. Most likely the authentication response received from Microsoft does not contain an email address. Consult the <a target="_blank" href="https://www.wpo365.com/troubleshooting-the-wpo365-login-plugin/#PARSING_ERROR">online documentation</a> for details.', 'wpo365-login'),
                self::BASIC_VERSION     => __('The BASIC edition of the WordPress + Office 365 plugin does not automatically create new users. See the following <a href="https://www.wpo365.com/basic-edition/">online documentation</a> for more info.', 'wpo365-login'),
                self::CHECK_LOG         => __('Please contact your System Administrator and check log file.', 'wpo365-login'),
                self::DEACTIVATED       => __('Account deactivated.', 'wpo365-login'),
                self::DUAL_LOGIN        => __('Alternatively, you can click the following link to sign into this website with your corporate <a href="__##OAUTH_URL##__">network login (Office 365)</a>', 'wpo365-login'),
                self::DUAL_LOGIN_V2     => __('Alternatively, you can click the following link to sign into this website with your corporate <span class="wpo365-dual-login-notice" style="cursor: pointer; text-decoration: underline; color: #000CD" onclick="window.wpo365.pintraRedirect.toMsOnline()">network login (Office 365)</span>', 'wpo365-login'),
                self::ID_TOKEN_ERROR    => __('Your ID token could not be processed. Please contact your System Administrator.', 'wpo365-login'),
                self::ID_TOKEN_AUD      => __('The ID token is intended for a different audience. Please contact your System Administrator.', 'wpo365_login'),
                self::LOGGED_OUT        => __('You are now logged out.', 'wpo365-login'),
                self::PRIVATE_PAGE      => __('The page you requested requires you to sign in first.', 'wpo365-login'),
                self::NOT_CONFIGURED    => __('Wordpress + Office 365 login not configured yet. Please contact your System Administrator.', 'wpo365-login'),
                self::NOT_FROM_DOMAIN   => __('Access Denied. Please contact your System Administrator.', 'wpo365-login'),
                self::NOT_IN_GROUP      => __('Access Denied. Please contact your System Administrator.', 'wpo365-login'),
                self::SAML2_ERROR       => __('SAML authentication error.', 'wpo365-login'),
                self::TAMPERED_WITH     => __('Your login might be tampered with. Please contact your System Administrator.', 'wpo365-login'),
                self::USER_NOT_FOUND    => __('Could not create or retrieve your login. Please contact your System Administrator.', 'wpo365-login'),
            );

            if (class_exists('\Wpo\Services\Options_Service')) {
                $error_message = Options_Service::get_global_string_var('wpo_error_' . strtolower($error_code));
            }

            // Backward compatible with the now deprecated possibility to update the error message through the configuration instead l18n
            if (empty($error_message) || empty($deprecated_error_messages[$error_code]) || $deprecated_error_messages[$error_code] == $error_message) {
                $error_message = !empty($error_messages[$error_code])
                    ? $error_messages[$error_code]
                    : '';
            }

            // Optionally replace template tokens when error is DUAL_LOGIN or DUAL_LOGINV2
            if (class_exists('\Wpo\Services\Options_Service') && WordPress_Helpers::stripos($error_code, 'DUAL_LOGIN') === 0) {

                if (Options_Service::get_global_boolean_var('hide_sso_link')) {
                    return '';
                }

                $site_url = $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'];

                if (false !== WordPress_Helpers::stripos($error_message, '__##OAUTH_URL##__')) {
                    $redirect_to = !empty($_GET['redirect_to'])
                        ? esc_url_raw(strtolower(trim($_GET['redirect_to'])))
                        : null;

                    if (Options_Service::get_global_boolean_var('use_b2c') &&  \class_exists('\Wpo\Services\Id_Token_Service_B2c')) {
                        $oauth_url = \Wpo\Services\Id_Token_Service_B2c::get_openidconnect_url(null, $redirect_to);
                    } else if (Options_Service::get_global_boolean_var('use_ciam')) {
                        $oauth_url = \Wpo\Services\Id_Token_Service_Ciam::get_openidconnect_url(null, $redirect_to);
                    } else {
                        $oauth_url = Id_Token_Service::get_openidconnect_url(null, $redirect_to);
                    }

                    $error_message = str_replace("__##OAUTH_URL##__", $oauth_url, $error_message);
                }
            }

            return $error_message;
        }
    }
}

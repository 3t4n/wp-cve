<?php

namespace Wpo\Services;

use Error;
use \Wpo\Core\Url_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Authentication_Service;
use \Wpo\Services\Error_Service;
use \Wpo\Services\Id_Token_Service;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;

use \Wpo\Tests\Self_Test;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Router_Service')) {

    class Router_Service
    {

        public static function has_route()
        {
            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            if (
                ((!empty($_REQUEST['id_token']) && !empty($_REQUEST['state']))
                    || (!empty($_REQUEST['code']) && !empty($_REQUEST['state']))
                    || (!empty($_REQUEST['error']) && !empty($_REQUEST['state'])))
                && !Router_Service::skip_processing_oidc_payload()
            ) {
                if (isset($_REQUEST['error'])) {
                    $error_string = sanitize_text_field($_REQUEST['error']) . (isset($_REQUEST['error_description']) ? \sanitize_text_field($_REQUEST['error_description']) : '');
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> ' . $error_string);
                    add_action('init', '\Wpo\Services\Router_Service::route_openidconnect_error');
                    return true;
                }

                if (!empty($_REQUEST['id_token'])) {
                    $id_token = sanitize_text_field($_REQUEST['id_token']);

                    if (true === Id_Token_Service::check_audience($id_token)) {
                        $request->set_item('encoded_id_token', $id_token);
                        unset($_REQUEST['id_token']);
                    } else {
                        return;
                    }
                }

                $state = self::process_state_url($_REQUEST['state'], $request);
                $request->set_item('state', $state);

                if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service')) {
                    $state = \Wpo\Services\Pkce_Service::process_state_with_verifier();
                    $request->set_item('state', $state);
                }

                unset($_REQUEST['state']);

                if (!empty($_REQUEST['code'])) {
                    $request->set_item('code', sanitize_text_field($_REQUEST['code']));
                    unset($_REQUEST['code']);
                }
            } elseif (false !== WordPress_Helpers::stripos($GLOBALS['WPO_CONFIG']['url_info']['request_uri'], 'mode=selfTest')) {
                $request->set_item('mode', 'selfTest');
            }

            if (!empty($_POST['RelayState'])) {
                $relay_state = self::process_state_url($_POST['RelayState'], $request);
                $request->set_item('relay_state', $relay_state); // -> Cannot be unset because there dependies relying on it
            }

            // check for user sync start request via external link
            if (!empty($_REQUEST['wpo365_sync_run']) && $_REQUEST['wpo365_sync_run'] == 'start') {

                if (!empty($_REQUEST['job_id'])) {

                    if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'wpToAad') {

                        if (class_exists('\Wpo\Sync\Sync_Wp_To_Aad_Service')) {
                            \Wpo\Sync\Sync_Wp_To_Aad_Service::sync_users(sanitize_text_field($_REQUEST['job_id']));
                            exit();
                        }

                        Log_Service::write_log('WARN', sprintf('%s -> Could not start a WP to AAD User synchronization job because the required classes are not installed', __METHOD__));
                        exit();
                    }

                    if (class_exists('\Wpo\Sync\SyncV2_Service')) {
                        \Wpo\Sync\SyncV2_Service::sync_users(sanitize_text_field($_REQUEST['job_id']));
                        exit();
                    }
                }
            }

            if ($request->get_item('mode') == 'mailAuthorize') {
                add_action('init', '\Wpo\Services\Router_Service::route_mail_authorize');
                return true;
            }

            if ($request->get_item('mode') == 'selfTest') {
                add_action('init', '\Wpo\Services\Router_Service::route_plugin_selftest');
                return true;
            }

            // Check if SSO is enabled
            if (Options_Service::get_global_boolean_var('no_sso', false)) {
                return false;
            }

            // initiate openidconnect / saml flow
            if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'openidredirect') {
                add_action('init', '\Wpo\Services\Router_Service::route_initiate_user_authentication');
                return true;
            }

            // process openid connect id token (hybrid flow)
            if (!empty($request->get_item('state'))) {

                if (!empty($request->get_item('encoded_id_token'))) {
                    add_action('init', '\Wpo\Services\Router_Service::route_openidconnect_token');
                    return true;
                }

                if (!empty($request->get_item('code'))) {
                    add_action('init', '\Wpo\Services\Router_Service::route_openidconnect_code');
                    return true;
                }
            }

            // process saml response
            if (!empty($_REQUEST['SAMLResponse']) && Options_Service::get_global_boolean_var('use_saml')) {
                add_action('init', '\Wpo\Services\Router_Service::route_saml2_response');
                return true;
            }

            return false;
        }

        /**
         * Route to initialize user authentication with the option to do
         * so with OpenID Connect or with SAML.
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_initiate_user_authentication()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (Options_Service::get_global_boolean_var('use_saml')) {
                self::route_saml2_initiate();
            } else {
                self::route_openidconnect_initiate();
            }
        }

        /**
         * Route to redirect user to login.microsoftonline.com
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_openidconnect_initiate()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $login_hint = !empty($_REQUEST['login_hint'])
                ? $_REQUEST['login_hint']
                : null;

            $redirect_to = !empty($_POST['redirect_to'])
                ? esc_url_raw($_POST['redirect_to'])
                : null;

            $b2c_policy = !empty($_POST['b2c_policy'])
                ? sanitize_text_field($_POST['b2c_policy'])
                : null;

            if (Options_Service::is_wpo365_configured()) {

                if (Options_Service::get_global_boolean_var('use_b2c') &&  \class_exists('\Wpo\Services\Id_Token_Service_B2c')) {
                    $authUrl = \Wpo\Services\Id_Token_Service_B2c::get_openidconnect_url($login_hint, $redirect_to, $b2c_policy);
                } else if (Options_Service::get_global_boolean_var('use_ciam')) {
                    $authUrl = \Wpo\Services\Id_Token_Service_Ciam::get_openidconnect_url($login_hint, $redirect_to);
                } else {
                    $authUrl = Id_Token_Service::get_openidconnect_url($login_hint, $redirect_to);
                }

                Url_Helpers::force_redirect($authUrl);
                exit();
            }
        }

        /**
         * Route to redirect user to the configured SAML 2.0 IdP
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_saml2_initiate()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $redirect_to = !empty($_POST['redirect_to'])
                ? esc_url_raw($_POST['redirect_to'])
                : null;

            $params = array();

            if (!empty($_POST['domain_hint'])) {
                $params['whr'] = sanitize_text_field(\strtolower(\trim($_POST['domain_hint'])));
            }

            if (Options_Service::is_wpo365_configured()) {
                \Wpo\Services\Saml2_Service::initiate_request($redirect_to, $params);
                exit();
            }
        }

        /**
         * Route to redirect user to the configured SAML 2.0 IdP
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_saml2_response()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (Options_Service::is_wpo365_configured()) {
                try {
                    $wpo_usr = Authentication_Service::authenticate_saml2_user();
                    Url_Helpers::goto_after($wpo_usr);
                } catch (\Exception $e) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Could not process SAML 2.0 response (' . $e->getMessage() . ')');
                    Authentication_Service::goodbye(Error_Service::SAML2_ERROR);
                    exit();
                }
            }
        }

        /**
         * Route to process an incoming id token
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_openidconnect_token()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (Options_Service::get_global_boolean_var('use_id_token_parser_v2') && \class_exists('\Wpo\Services\Id_Token_Service_Deprecated')) {
                \Wpo\Services\Id_Token_Service_Deprecated::process_openidconnect_token();
            } else {
                Id_Token_Service::process_openidconnect_token();
            }

            $wpo_usr = Authentication_Service::authenticate_oidc_user();
            Url_Helpers::goto_after($wpo_usr);
        }

        /**
         * Route to process an incoming authorization code
         * 
         * @since 18.0
         * 
         * @return void
         */
        public static function route_openidconnect_code()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (strcasecmp(Options_Service::get_global_string_var('oidc_flow'), 'code') === 0) {
                if (Options_Service::get_global_boolean_var('use_b2c')) {
                    \Wpo\Services\Id_Token_Service_B2c::process_openidconnect_code();
                } else if (Options_Service::get_global_boolean_var('use_ciam')) {
                    \Wpo\Services\Id_Token_Service_Ciam::process_openidconnect_code();
                } else {
                    \Wpo\Services\Id_Token_Service::process_openidconnect_code();
                }
            } else {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> An authorization code was received but support for the "authorization code flow" has not been configured.');
                Authentication_Service::goodbye(Error_Service::CHECK_LOG);
                exit();
            }

            $wpo_usr = Authentication_Service::authenticate_oidc_user();
            Url_Helpers::goto_after($wpo_usr);
        }

        /**
         * Route to sign user out of WordPress and redirect to login page
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_openidconnect_error()
        {
            Authentication_Service::goodbye(Error_Service::CHECK_LOG);
            exit();
        }

        /**
         * Route to execute plugin selftest and then redirect user back to results (or landing page)
         * 
         * @since 11.0
         * 
         * @return void
         */
        public static function route_plugin_selftest()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            // Perform a self test
            new Self_Test();

            // Get redirect target (default / wpo365 not configured)
            $redirect_to = !empty($_REQUEST['redirect_to'])
                ? esc_url_raw($_REQUEST['redirect_to'])
                : $GLOBALS['WPO_CONFIG']['url_info']['current_url'];

            $redirect_to = Url_Helpers::get_redirect_url($redirect_to);

            $redirect_to = remove_query_arg('flushPermaLinks', $redirect_to);
            $redirect_to = remove_query_arg('mode', $redirect_to);

            Url_Helpers::force_redirect($redirect_to);
        }

        /**
         * Route to execute mail authorization with delegated permissions.
         * 
         * @since 19.0
         * 
         * @return void
         */
        public static function route_mail_authorize()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            // Get redirect target (default / wpo365 not configured)
            $redirect_to = !empty($_REQUEST['redirect_to'])
                ? esc_url_raw($_REQUEST['redirect_to'])
                : $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'];

            // Try update redirect target (wpo365 configured)
            $redirect_url = Url_Helpers::get_redirect_url($redirect_to);

            // Check if wp_mail has been plugged
            if (false === \Wpo\Mail\Mailer::check_wp_mail()) {
                Url_Helpers::force_redirect($redirect_url);
            }

            // Process the incoming authorization code and request an ID token, access and refresh token.
            $scope = Options_Service::get_global_boolean_var('mail_send_shared') ? 'https://graph.microsoft.com/Mail.Send.Shared' : 'https://graph.microsoft.com/Mail.Send';
            \Wpo\Services\Id_Token_Service::process_openidconnect_code($scope, false);

            // Uses the tokens received to create a mail user object
            $authorization_result = \Wpo\Mail\Mail_Authorization_Helpers::authorize_mail_user();

            if (is_wp_error($authorization_result)) {
                Log_Service::write_log('ERROR', sprintf(
                    '%s -> Mail authorization failed. [%s]',
                    __METHOD__,
                    $authorization_result->get_error_message()
                ));
            }

            Url_Helpers::force_redirect($redirect_url);
        }

        /**
         * Analyzes the status URL.
         * 
         * @since 19.0
         * 
         * @param mixed $url 
         * @param mixed $request 
         * @return string 
         */
        private static function process_state_url($url, $request)
        {
            $url = urldecode($url);
            $url = wp_sanitize_redirect($url);
            $query = parse_url($url, PHP_URL_QUERY);

            if (empty($query)) {
                $result = array();
            } else {
                parse_str($query, $result);
            }

            if (isset($result['mode'])) {
                $mode = $result['mode'];
                $request->set_item('mode', $mode);
                $url = remove_query_arg('mode', $url);
            }

            if (isset($result['tfp'])) {
                $tfp = $result['tfp'];
                $request->set_item('tfp', $tfp);
                $url = remove_query_arg('tfp', $url);
            }

            // If state is a relative URL then try to fix it.
            if (WordPress_Helpers::strpos($url, '/') === 0) {
                $url = sprintf(
                    '%s%s',
                    WordPress_Helpers::rtrim($GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'], '/'),
                    $url
                );
            }

            return $url;
        }

        /**
         * Checks if WPO365 should process an OIDC payload that it has detected by comparing the current URL with the Redirect URI.
         * 
         * @since 25.0
         * 
         * @return bool True if WPO365 should continue processing OIDC payload it has detected.
         */
        private static function skip_processing_oidc_payload()
        {
            if (!Options_Service::get_global_boolean_var('redirect_url_strict')) {
                return false;
            }

            $home_url = get_option('home');
            $redirect_uri = Options_Service::get_global_string_var('redirect_url');

            if (empty($home_url) || empty($redirect_uri)) {
                Log_Service::write_log('WARN', sprintf(
                    '%s -> The administrator has configured Redirect URI "strict mode" but either the home address URL (%s) or the AAD redirect URI (%s) appears to be empty and "strict mode" can therefore not be enforced.',
                    __METHOD__,
                    $home_url,
                    $redirect_uri
                ));
                return false;
            }

            $home_url = untrailingslashit($home_url);
            $home_url = str_replace('https://', '', $home_url);
            $home_url = str_replace('http://', '', $home_url);

            $redirect_uri = untrailingslashit($redirect_uri);
            $redirect_uri = str_replace('https://', '', $redirect_uri);
            $redirect_uri = str_replace('http://', '', $redirect_uri);

            if (0 === strcasecmp($home_url, $redirect_uri)) {
                Log_Service::write_log('WARN', sprintf(
                    '%s -> The administrator has configured Redirect URI "strict mode" but the home address URL (%s) and the AAD redirect URI (%s) appear to be equal and therefore "strict mode" cannot be enforced. For "strict mode", the Redirect URI must end with a specific path e.g. %s',
                    __METHOD__,
                    $home_url,
                    $redirect_uri,
                    sprintf('%s/oidc_auth/', $home_url)
                ));
                return false;
            }

            $current_url = $GLOBALS['WPO_CONFIG']['url_info']['current_url'];

            if (empty($current_url)) {
                Log_Service::write_log('WARN', sprintf(
                    '%s -> The administrator has configured Redirect URI "strict mode" but WPO365 cannot determine the current URL and therefore "strict mode" cannot be enforced.',
                    __METHOD__
                ));
                return false;
            }

            $current_url = strtok($current_url, '?');
            $current_url = untrailingslashit($current_url);
            $current_url = str_replace('https://', '', $current_url);
            $current_url = str_replace('http://', '', $current_url);

            if (0 === strcasecmp($current_url, $redirect_uri)) {
                Log_Service::write_log('DEBUG', sprintf(
                    '%s -> The administrator has configured Redirect URI "strict mode" and the current URL (%s) is equal to the redirect URI (%s) and therefore WPO365 will process the OIDC payload.',
                    __METHOD__,
                    $current_url,
                    $redirect_uri
                ));
                return false;
            }

            Log_Service::write_log('DEBUG', sprintf(
                '%s -> The administrator has configured Redirect URI "strict mode" and the current URL (%s) is not equal to the redirect URI (%s) and therefore WPO365 will not process the OIDC payload.',
                __METHOD__,
                $current_url,
                $redirect_uri
            ));

            return true;
        }
    }
}

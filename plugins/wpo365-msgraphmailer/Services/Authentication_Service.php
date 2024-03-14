<?php

namespace Wpo\Services;

use WP_Error;
use \Wpo\Core\Domain_Helpers;
use \Wpo\Core\Url_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Error_Service;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;
use \Wpo\Services\Saml2_Service;
use \Wpo\Services\User_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Authentication_Service')) {

    class Authentication_Service
    {

        const USR_META_WPO365_AUTH = 'WPO365_AUTH';

        /**
         * @param   $force  boolean If true the test whether authentication can be skipped will be skipped.
         */
        public static function authenticate_request($force = false)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            /**
             * @since   23.0    If the audience check fails and the administrator has configured the plugin 
             *                  not to exit when the audience check fails.
             */
            if (true === $request->get_item('skip_authentication')) {
                return;
            }

            $wp_usr_id = get_current_user_id();

            $wpo_auth_value = get_user_meta(
                $wp_usr_id,
                self::USR_META_WPO365_AUTH,
                true
            );

            $request->set_item('wpo_auth_value', $wpo_auth_value);
            $request->set_item('wp_usr_id', $wp_usr_id);

            if (!$force && self::skip_authentication()) {
                return;
            }

            // Logged-on WP-only user
            if (is_user_logged_in() && empty($wpo_auth_value)) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> User is a Wordpress-only user so no authentication is required');
                return;
            }

            // User not logged on
            if (empty($wpo_auth_value)) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> User is not logged in and therefore sending the user to Microsoft to sign in');
                $login_hint = isset($_REQUEST['login_hint'])
                    ? \sanitize_text_field($_REQUEST['login_hint'])
                    : null;
                self::redirect_to_microsoft($login_hint);
                return;
            }

            // Check if user has expired 
            $wpo_auth = json_decode($wpo_auth_value);

            // If 0 then session expiration check is skipped
            if (Options_Service::get_global_numeric_var('session_duration') > 0) {
                $auth_expired = !isset($wpo_auth->expiry) || $wpo_auth->expiry < time();

                if ($auth_expired) {

                    $upn = User_Service::try_get_user_principal_name($wp_usr_id);

                    $login_hint = !empty($upn) ? $upn : null;

                    do_action('destroy_wpo365_session');

                    // Don't call wp_logout because it may be extended
                    wp_destroy_current_session();
                    wp_clear_auth_cookie();
                    wp_set_current_user(0);

                    unset($_COOKIE[AUTH_COOKIE]);
                    unset($_COOKIE[SECURE_AUTH_COOKIE]);
                    unset($_COOKIE[LOGGED_IN_COOKIE]);

                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> User logged out because current login not valid anymore (' . $auth_expired . ')');

                    self::redirect_to_microsoft($login_hint);

                    exit();
                }
            } else {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Session expiration ignored because the administrator configured a duration of 0');
            }

            $current_blog_id = \get_current_blog_id();

            // WPMU Dedicated
            if (is_multisite() && Options_Service::mu_use_subsite_options() && !\is_user_member_of_blog($wp_usr_id, $current_blog_id)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User  with ID ' . $wp_usr_id . ' is not a member of the current site with ID ' . $current_blog_id . '.');
                Authentication_Service::goodbye(Error_Service::USER_NOT_FOUND);
                exit();
            }
        }

        /**
         * @since 11.0
         */
        public static function authenticate_oidc_user()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            /**
             * Switch the blog context if WPMU is detected and the user is trying to access
             * a subsite but landed at the main site because of Microsoft redirecting the
             * user there immediately after successful authentication.
             */
            $state = $request->get_item('state');
            $id_token = $request->get_item('id_token');

            Wpmu_Helpers::switch_blog($state);

            if (empty($id_token)) {
                $error_message = sprintf('%s -> ID token could not be extracted from request storage.', __METHOD__);
                Log_Service::write_log('ERROR', $error_message);
                Authentication_Service::goodbye(Error_Service::ID_TOKEN_ERROR);
                exit();
            }

            $wpo_usr = Options_Service::get_global_boolean_var('use_b2c') && \class_exists('\Wpo\Services\Id_Token_Service_B2c')
                ? User_Service::user_from_b2c_id_token($id_token)
                : User_Service::user_from_id_token($id_token);

            self::user_in_group($wpo_usr);

            do_action(
                'wpo365/oidc/authenticating',
                $wpo_usr->preferred_username,
                $wpo_usr->email,
                $wpo_usr->groups
            );

            /**
             * Authenticate but don't sign in Azure AD users.
             * 
             * @since   16.0
             */

            if (true === apply_filters('wpo365/cookie/set', $wpo_usr, $state)) {
                return $wpo_usr;
            }

            $wp_usr = User_Service::ensure_user($wpo_usr);

            if (empty($wp_usr)) {
                $error_message = sprintf('%s -> Multiple errors occurred: please check debug log for previous errors', __METHOD__);
                Log_Service::write_log('ERROR', $error_message);
                Authentication_Service::goodbye(Error_Service::CHECK_LOG);
                exit();
            }

            // Now log on the user
            wp_set_auth_cookie($wp_usr->ID, Options_Service::get_global_boolean_var('remember_user'));  // Both log user on
            wp_set_current_user($wp_usr->ID);       // And set current user

            // Session valid until
            $session_duration = Options_Service::get_global_numeric_var('session_duration');
            $session_duration = empty($session_duration) ? 3480 : $session_duration;
            $expiry = time() + intval($session_duration);

            // Obfuscated user's wp id
            $obfuscated_user_id = $expiry + $wp_usr->ID;
            $wpo_auth = new \stdClass();
            $wpo_auth->expiry = $expiry;
            $wpo_auth->ouid = $obfuscated_user_id;
            $wpo_auth->upn = $wpo_usr->upn;
            $wpo_auth->url = $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'];

            update_user_meta(
                $wp_usr->ID,
                self::USR_META_WPO365_AUTH,
                json_encode($wpo_auth)
            );

            $request->set_item('wpo_auth_value', $wpo_auth);

            /**
             * Fires after the user has successfully logged in.
             *
             * @since 7.1
             *
             * @param string  $user_login Username.
             * @param WP_User $user       WP_User object of the logged-in user.
             */
            if (false === Options_Service::get_global_boolean_var('skip_wp_login_action')) {
                do_action('wp_login', $wp_usr->user_login, $wp_usr);
            }

            /**
             * @since 10.6
             * 
             * The wpo365_openid_token_processed action hook signals to its subscribers
             * that a user has just signed in successfully with Microsoft. As arguments
             * it provides the WordPress user ID and the user's Azure AD group IDs
             * as an one-dimensional array of GUIDs (as strings).
             */

            do_action('wpo365_openid_token_processed', $wp_usr->ID, $wpo_usr->groups, $id_token);

            /**
             * @since 15.0
             */

            do_action('wpo365/oidc/authenticated', $wp_usr->ID);

            return $wpo_usr;
        }

        /**
         * 
         */
        public static function authenticate_saml2_user()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            /**
             * Switch the blog context if WPMU is detected and the user is trying to access
             * a subsite but landed at the main site because of Microsoft redirecting the
             * user there immediately after successful authentication.
             */
            $state = $request->get_item('relay_state');
            Wpmu_Helpers::switch_blog($state);

            require_once($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/OneLogin/_toolkit_loader.php');

            $saml_settings = Saml2_Service::saml_settings();
            $auth = new \OneLogin_Saml2_Auth($saml_settings);
            $auth->processResponse();

            // Check for errors
            $errors = $auth->getErrors();

            if (!empty($errors)) {
                $error_reason = $auth->getLastErrorReason();
                Log_Service::write_log('ERROR', __METHOD__ . ' -> Could not process SAML 2.0 response (See log for errors [' . $error_reason . '])');
                Log_Service::write_log('WARN', $errors);
                Authentication_Service::goodbye(Error_Service::SAML2_ERROR);
                exit();
            }

            // Check if authentication was successful
            if (!$auth->isAuthenticated()) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User is not authenticated');
                Authentication_Service::goodbye(Error_Service::SAML2_ERROR);
                exit();
            }

            // Check against replay attack
            Saml2_Service::check_message_id($auth->getLastMessageId());

            // Abstraction to WPO365 User
            $saml_attributes = $auth->getAttributes();
            $saml_name_id = $auth->getNameId();
            $wpo_usr = User_Service::user_from_saml_response($saml_name_id, $saml_attributes);

            self::user_in_group($wpo_usr);

            do_action(
                'wpo365/saml2/authenticating',
                $wpo_usr->preferred_username,
                $wpo_usr->email,
                $wpo_usr->groups
            );

            /**
             * Authenticate but don't sign in Azure AD users.
             * 
             * @since   16.0
             */

            if (true === apply_filters('wpo365/cookie/set', $wpo_usr, $state)) {
                return $wpo_usr;
            }

            $wp_usr = User_Service::ensure_user($wpo_usr);

            if (empty($wp_usr)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> Multiple errors occurred: please check debug log for previous errors');
                Authentication_Service::goodbye(Error_Service::CHECK_LOG);
                exit();
            }

            // Now log on the user
            wp_set_auth_cookie($wp_usr->ID, Options_Service::get_global_boolean_var('remember_user'));  // Both log user on
            wp_set_current_user($wp_usr->ID);       // And set current user

            // Session valid until
            $session_duration = Options_Service::get_global_numeric_var('session_duration');
            $session_duration = empty($session_duration) ? 3480 : $session_duration;
            $expiry = time() + intval($session_duration);

            // Obfuscated user's wp id
            $obfuscated_user_id = $expiry + $wp_usr->ID;
            $wpo_auth = new \stdClass();
            $wpo_auth->expiry = $expiry;
            $wpo_auth->ouid = $obfuscated_user_id;
            $wpo_auth->upn = $wpo_usr->upn;
            $wpo_auth->url = $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'];

            update_user_meta(
                $wp_usr->ID,
                self::USR_META_WPO365_AUTH,
                json_encode($wpo_auth)
            );

            $request->set_item('wpo_auth_value', $wpo_auth);

            /**
             * Fires after the user has successfully logged in.
             *
             * @since 7.1
             *
             * @param string  $user_login Username.
             * @param WP_User $user       WP_User object of the logged-in user.
             */
            if (false === Options_Service::get_global_boolean_var('skip_wp_login_action')) {
                do_action('wp_login', $wp_usr->user_login, $wp_usr);
            }

            /**
             * @since 15.0
             */

            do_action('wpo365/saml/authenticated', $wp_usr->ID);

            return $wpo_usr;
        }

        /**
         * Redirects the user either back to site with an HTTP POST or when dual login
         * is configured to the (custom) login form. The data POSTed tells the plugin
         * to initiate the Sign in with Microsoft flow (both OpenID Connect + SAML).
         * 
         * @since 8.0
         * 
         * @param $login_hint string Login hint that will be added to the Open ID Connect link if present
         * 
         * @return void
         */
        public static function redirect_to_microsoft($login_hint = null)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (class_exists('\Wpo\Services\Dual_Login_Service')) {
                \Wpo\Services\Dual_Login_Service::redirect();
            }

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Forwarding the user to Microsoft to get fresh ID and access token(s)');

            // Default redirection -> the names are legacy and it used for SAML2.0 redirection
            ob_start();
            include($GLOBALS['WPO_CONFIG']['plugin_dir'] . '/templates/openid-redirect.php');
            $content = ob_get_clean();
            echo $content;
            exit();
        }

        /**
         * @since 11.0
         */
        private static function user_in_group($wpo_usr)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            // Check whether allowed (Office 365 or Security) Group Ids have been configured
            $allowed_groups_ids = Options_Service::get_global_list_var('groups_whitelist');

            if (sizeof($allowed_groups_ids) > 0) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Group policy has been defined');

                if (empty($wpo_usr->groups) || !(count(
                    array_intersect_key(
                        array_flip($allowed_groups_ids),
                        $wpo_usr->groups
                    )
                )) >= 1) {
                    $express_login = Options_Service::get_global_boolean_var('express_login');

                    if ($express_login) {
                        Log_Service::write_log('ERROR', __METHOD__ . ' -> Access denied error because the administrator has restricted
                        access to a limited number of Azure AD (security) groups but also enabled Express Login. As a result the plugin
                        can possibly not retrieve all Azure AD (security) groups that a user is a member of.');
                    } else {
                        Log_Service::write_log('WARN', __METHOD__ . ' -> Access denied error because the administrator has restricted
                        access to a limited number of Azure AD (security) groups and the user trying to log on 
                        is not in one of these groups.');
                    }

                    self::goodbye(Error_Service::NOT_IN_GROUP);
                    exit();
                }
            }
        }

        /**
         * @since 11.0
         */
        public static function user_from_domain($preferred_username, $email)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            // Check whether the user's domain is white listed (if empty this check is skipped)
            $domain_white_list = Options_Service::get_global_list_var('domain_whitelist');

            if (sizeof($domain_white_list) > 0) {
                $white_listed_domains = implode(';', $domain_white_list);
                $login_domain = Domain_Helpers::get_smtp_domain_from_email_address($preferred_username);
                $email_domain = Domain_Helpers::get_smtp_domain_from_email_address($email);

                if ((empty($login_domain) || false === WordPress_Helpers::stripos($white_listed_domains, $login_domain))
                    && (empty($email_domain) || false === WordPress_Helpers::stripos($white_listed_domains, $email_domain))
                ) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Access denied error because the administrator has restricted
                                access to a limited number of domains and the user trying to log on is not from one of these domains.');
                    self::goodbye(Error_Service::NOT_FROM_DOMAIN);
                    exit();
                }
            }
        }

        /**
         * Destroys any session and authenication artefacts and hooked up with wpo365_logout and should
         * therefore never be called directly to avoid endless loops etc.
         *
         * @since   1.0
         *
         * @return  void 
         */
        public static function destroy_session()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $wp_usr_id = get_current_user_id();

            if (empty($wp_usr_id)) {
                $request_service = Request_Service::get_instance();
                $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
                $wp_usr_id = $request->get_item('wp_usr_id');
            }

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Destroying session ' . strtolower(basename($_SERVER['PHP_SELF'])));

            if (!empty($wp_usr_id)) {
                delete_user_meta($wp_usr_id, self::USR_META_WPO365_AUTH);
                delete_user_meta($wp_usr_id, Access_Token_Service::USR_META_WPO365_AUTH_CODE);
            }
        }

        /**
         * Same as destroy_session but with redirect to login page (but only if the 
         * login page isn't the current page).
         *
         * @since   1.0
         * 
         * @param   string  $login_error_code   Error code that is added to the logout url as query string parameter.
         * @return  void
         */
        public static function goodbye($login_error_code)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $error_page_url = Options_Service::get_global_string_var('error_page_url');
            $error_page_path = WordPress_Helpers::rtrim(parse_url($error_page_url, PHP_URL_PATH), '/');

            $redirect_to = (empty($error_page_url) || $error_page_path === $GLOBALS['WPO_CONFIG']['url_info']['wp_site_path'])
                ? Url_Helpers::get_preferred_login_url()
                : $error_page_url;

            if (empty($_SERVER['PHP_SELF'])) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> $_SERVER[PHP_SELF] is empty. Please review your server configuration.');
            }

            do_action('destroy_wpo365_session');

            wp_destroy_current_session();
            wp_clear_auth_cookie();
            wp_set_current_user(0);

            unset($_COOKIE[AUTH_COOKIE]);
            unset($_COOKIE[SECURE_AUTH_COOKIE]);
            unset($_COOKIE[LOGGED_IN_COOKIE]);

            $redirect_to = add_query_arg('login_errors', $login_error_code, $redirect_to);
            Url_Helpers::force_redirect($redirect_to);
        }

        /**
         * Helper hooked up to the wp_authenticate trigger to check if the user has been deactivated or not.
         * 
         * @since   10.1
         * 
         * @param   $user_login     string  The user's login name
         * 
         * @return  void
         */
        public static function is_deactivated($login = '', $kill_session = false)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $wp_usr = \get_user_by('login', $login);

            if (!empty($wp_usr) && \get_user_meta($wp_usr->ID, 'wpo365_active', true) == 'deactivated') {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Account ' . $wp_usr->login . ' is deactivated');

                if ($kill_session) {
                    Authentication_Service::goodbye(Error_Service::DEACTIVATED);
                    exit();
                }

                $error_page_url = Options_Service::get_global_string_var('error_page_url');
                $error_page_path = WordPress_Helpers::rtrim(parse_url($error_page_url, PHP_URL_PATH), '/');

                $redirect_to = (empty($error_page_url) || $error_page_path === $GLOBALS['WPO_CONFIG']['url_info']['wp_site_path'])
                    ? Url_Helpers::get_preferred_login_url()
                    : $error_page_url;

                $redirect_to = add_query_arg('login_errors', Error_Service::DEACTIVATED, $redirect_to);
                Url_Helpers::force_redirect($redirect_to);
                exit();
            }
        }

        /**
         * Checks the configured scenario and the pages black list settings to
         * decide whether or not authentication of the current page is needed.
         * 
         * @since 5.0
         * 
         * @return  boolean     True if validation should be skipped, otherwise false.
         */
        private static function skip_authentication()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            /**
             * @since   21.9    Skip authentication when wp-cli is detected.
             */

            if (defined('WP_CLI') && constant('WP_CLI') === true && Options_Service::get_global_boolean_var('use_wp_cli')) {
                Log_Service::write_log('DEBUG', sprintf('%s -> Skipping authentication [reason: wp-cli]', __METHOD__));
                return true;
            }

            // Skip when a basic authentication header is detected
            if (
                true === Options_Service::get_global_boolean_var('skip_api_basic_auth_request')
                && Url_Helpers::is_basic_auth_api_request()
            ) {
                return true;
            }

            // Not logged on and not configured => log in as WP Admin first
            if (!is_user_logged_in() && (false === Options_Service::is_wpo365_configured())) {
                return true;
            }

            /**
             * @since   16.0    If this is login and an wpo365 auth cookie is found then try
             *                  to trick any page caching mechanism.
             */
            do_action('wpo365/cookie/redirect');

            /**
             * @since   12.x
             * 
             * Administrator enabled SSO for the login page and dual login is not enabled.
             */

            if (true === Options_Service::get_global_boolean_var('redirect_on_login')  && Url_Helpers::is_wp_login()) {

                $dual_login_enabled = Options_Service::get_global_boolean_var('redirect_to_login_v2');
                $bypass_key = Options_Service::get_aad_option('redirect_on_login_secret');
                $error_page = Options_Service::get_global_string_var('error_page_url');

                // Admin has configured to enable SSO for the login page but pre-requisites are not fulfulled.
                if ($dual_login_enabled) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Administrator has enabled SSO for the login page but has also enabled the contradicting Dual Login feature');
                } elseif (empty($bypass_key)) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Administrator has enabled SSO for the login page but has not configured a mandatory secret key to bypass SSO');
                } elseif (strlen($bypass_key) < 32) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Administrator has enabled SSO for the login page but the length of the mandatory secret key to bypass SSO is less than 32 characters');
                } elseif (empty($error_page)) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Administrator has enabled SSO for the login page but has not configured a mandatory error page');
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> A POST request for the login page has been detected and will be allowed pass-thru');
                    return true;
                }
                // Admin has configured to enable SSO for the login page but no secret key has been detected.
                elseif (!isset($_GET[$bypass_key])) {
                    return false;
                }
            }

            // Check if current page is homepage and can be skipped
            $public_homepage = Options_Service::get_global_boolean_var('public_homepage');

            if (true === $public_homepage && ($GLOBALS['WPO_CONFIG']['url_info']['wp_site_path'] ===  $GLOBALS['WPO_CONFIG']['url_info']['request_uri'])) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Cancelling session validation for home page because public homepage is selected');
                return true;
            }

            // Check if current page is blacklisted and can be skipped
            $black_listed_pages = Options_Service::get_global_list_var('pages_blacklist');

            // Always add Error Page URL (if configured)
            $error_page_url = Options_Service::get_global_string_var('error_page_url');

            if (!empty($error_page_url) && WordPress_Helpers::stripos($error_page_url, WordPress_Helpers::rtrim($GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'], '/')) === 0) {
                $error_page_url = WordPress_Helpers::rtrim(strtolower($error_page_url), '/');
                $error_page_path = WordPress_Helpers::rtrim(parse_url($error_page_url, PHP_URL_PATH), '/');

                if (empty($error_page_path) || $error_page_path === $GLOBALS['WPO_CONFIG']['url_info']['wp_site_path']) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Error page URL must be a page and cannot be the root of the current website (' . $error_page_path . ')');
                } else {
                    $black_listed_pages[] = $error_page_path;
                }
            }

            // Always add Custom Login URL (if configured)
            $custom_login_url = Options_Service::get_global_string_var('custom_login_url');

            if (!empty($custom_login_url)) {
                $custom_login_url = WordPress_Helpers::rtrim(strtolower($custom_login_url), '/');
                $custom_login_path = WordPress_Helpers::rtrim(parse_url($custom_login_url, PHP_URL_PATH), '/');

                if (empty($custom_login_path) || $custom_login_path === $GLOBALS['WPO_CONFIG']['url_info']['wp_site_path']) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Custom Login URL must be a page and cannot be the root of the current website (' . $custom_login_path . ')');
                } else {
                    $black_listed_pages[] = $custom_login_path;
                }
            }

            // Ensure default login path
            $default_login_url_path = parse_url(wp_login_url(), PHP_URL_PATH);

            if (false === array_search($default_login_url_path, $black_listed_pages)) {
                $black_listed_pages[] = $default_login_url_path;
            }

            // Ensure admin-ajax.php
            $admin_ajax_path = 'admin-ajax.php';

            if (false === array_search($admin_ajax_path, $black_listed_pages)) {
                $black_listed_pages[] = $admin_ajax_path;
            }

            // Ensure wp-cron.php
            $wp_cron_path = 'wp-cron.php';

            if (false === array_search($wp_cron_path, $black_listed_pages)) {
                $black_listed_pages[] = $wp_cron_path;
            }

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Pages Blacklist after error page / custom login has verified');
            Log_Service::write_log('DEBUG', $black_listed_pages);

            // Check if current page is blacklisted and can be skipped
            foreach ($black_listed_pages as $black_listed_page) {

                $black_listed_page = WordPress_Helpers::rtrim(strtolower($black_listed_page), '/');

                // Filter out empty or mis-configured black page entries
                if (empty($black_listed_page) || $black_listed_page == '/' || $black_listed_page == $GLOBALS['WPO_CONFIG']['url_info']['wp_site_path']) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> Black listed page page must be a page and cannot be the root of the current website (' . $black_listed_page . ')');
                    continue;
                }

                // Correction after the plugin switched from basename to path based comparison
                $starts_with = substr($black_listed_page, 0, 1);
                $black_listed_page = $starts_with == '/' || $starts_with == '?' ? $black_listed_page : '/' . $black_listed_page;

                // Filter out any attempt to illegally bypass authentication
                $illegal_stripos = WordPress_Helpers::stripos($GLOBALS['WPO_CONFIG']['url_info']['request_uri'], '?/');
                if ($illegal_stripos !== false && strlen($GLOBALS['WPO_CONFIG']['url_info']['request_uri']) > ($illegal_stripos + 2)) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Serious attempt to try to bypass authentication using an illegal query string combination "?/" (path used: ' . $GLOBALS['WPO_CONFIG']['url_info']['request_uri'] . ')');
                    break;
                } elseif (WordPress_Helpers::stripos($GLOBALS['WPO_CONFIG']['url_info']['request_uri'], $black_listed_page) !== false) {
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Found [' . $black_listed_page . '] thus cancelling session validation for path ' . $GLOBALS['WPO_CONFIG']['url_info']['request_uri']);
                    return true;
                }
            }

            $scenario = Options_Service::get_global_string_var('auth_scenario');

            if (!is_admin() && !is_network_admin() && ($scenario === 'internet' || $scenario === 'internetAuthOnly')) {
                $private_pages = Options_Service::get_global_list_var('private_pages');
                $login_urls = Url_Helpers::get_login_urls();

                // Check if current page is private and cannot be skipped
                foreach ($private_pages as $private_page) {
                    $private_page = WordPress_Helpers::rtrim(strtolower($private_page), '/');

                    if (empty($private_page)) {
                        continue;
                    }

                    if ($private_page === $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url']) {
                        Log_Service::write_log('ERROR', __METHOD__ . ' -> The following entry in the Private Pages list is illegal because it is the site url: ' . $private_page);
                        continue;
                    }

                    /**
                     * @since 9.0
                     * 
                     * Prevent users from hiding the login page.
                     */

                    if ((!empty($login_urls['default_login_url']) && WordPress_Helpers::stripos($private_page, $login_urls['default_login_url']) !== false) || (!empty($login_urls['custom_login_url']) && WordPress_Helpers::stripos($private_page, $login_urls['custom_login_url']) !== false)) {
                        Log_Service::write_log('ERROR', __METHOD__ . ' -> The following entry in the Private Pages list is illegal because it is a login url: ' . $private_page);
                        continue;
                    }

                    if (WordPress_Helpers::stripos($GLOBALS['WPO_CONFIG']['url_info']['current_url'], $private_page) === 0) {

                        /**
                         * @since   17.0
                         * 
                         * Authentication may still be skipped when custom rules apply.
                         */

                        if (true === apply_filters('wpo365_skip_authentication', false)) {
                            return true;
                        }

                        return false;
                    }
                }

                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Cancelling session validation for page ' . strtolower(basename($_SERVER['PHP_SELF'])) . ' because selected scenario is \'Internet\'');
                return true;
            }

            /**
             * @since   10.6
             * 
             * The wpo365_skip_authentication filter hook signals allows its 
             * subscribers to dynamically add rules that would allow the plugin
             * to skip authentication.
             */

            if (true === apply_filters('wpo365_skip_authentication', false)) {
                return true;
            }

            return false;
        }

        /**
         * Instead of showing a 404 for private page the user is requested to sign in.
         * 
         * @since 12.x
         */
        public static function check_private_pages()
        {

            if (!Options_Service::get_global_boolean_var('redirect_on_private_page', false)) {
                return;
            }

            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            // Check is scenario is 'internet' and validation of current page can be skipped
            $scenario = Options_Service::get_global_string_var('auth_scenario');

            if (!is_admin() && ($scenario === 'internet' || $scenario === 'internetAuthOnly') && !is_user_logged_in()) {

                $query_result = \get_queried_object();

                if (isset($query_result->post_status) && $query_result->post_status == 'private') {
                    self::authenticate_request(true);
                }
            }
        }
    }
}

<?php

namespace Wpo\Mail;

use \WP_Error;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Access_Token_Service;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;
use \stdClass;
use \Wpo\Services\Nonce_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Mail\Mail_Authorization_Helpers')) {

    class Mail_Authorization_Helpers
    {
        /**
         * Will get the authorization URL where the user will be redirected to
         * when attempting to acquire an access and refresh token for sending
         * WordPress using Microsoft Graph using delegated permissions.
         * 
         * @since 19.0
         * 
         * @return WP_Error|string 
         */
        public static function get_mail_authorization_url()
        {
            Log_Service::write_log('DEBUG', sprintf('##### -> %s', __METHOD__));

            $admin_url = Wpmu_Helpers::mu_is_network_admin()
                ? network_admin_url()
                : get_admin_url();

            $directory_id = Options_Service::get_aad_option('mail_tenant_id');
            $application_id = Options_Service::get_aad_option('mail_application_id');
            $redirect_url = Options_Service::get_aad_option('mail_redirect_url');
            $redirect_to = sprintf('%s/admin.php?mode=mailAuthorize&page=wpo365-wizard#mail', WordPress_Helpers::rtrim($admin_url, '/'));
            $redirect_to = urlencode($redirect_to);
            $scope = Options_Service::get_global_boolean_var('mail_send_shared') ? 'Mail.Send.Shared' : 'Mail.Send';

            $params = array(
                'client_id'             => $application_id,
                'nonce'                 => Nonce_Service::create_nonce(),
                'redirect_uri'          => $redirect_url,
                'response_mode'         => 'form_post',
                'response_type'         => 'code',
                'scope'                 => $scope,
                'state'                 => $redirect_to,
            );

            // Add Proof Key for Code Exchange challenge if required
            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service')) {
                \Wpo\Services\Pkce_Service::add_and_memoize_verifier($params);
            }

            $mail_from = Options_Service::get_global_string_var('mail_from');

            if (!empty($mail_from)) {
                $params['login_hint'] = $mail_from;
            }

            $auth_url = 'https://login.microsoftonline.com/'
                . $directory_id
                . '/oauth2'
                . '/v2.0'
                . '/authorize?'
                . http_build_query($params, '', '&');

            Log_Service::write_log('DEBUG', __METHOD__ . " -> Mail Authorization URL: $auth_url");

            return $auth_url;
        }


        /**
         * Authenticates the mail_from user and retrieves an access and refresh token for sending
         * WordPress mail using Microsoft Graph.
         * 
         * @since   19.0
         */
        public static function authorize_mail_user()
        {
            Log_Service::write_log('DEBUG', sprintf('##### -> %s', __METHOD__));

            /**
             * Let's clean any existing user and application-level tokens.
             */

            delete_option(Access_Token_Service::SITE_META_ACCESS_TOKEN);

            if (Options_Service::mu_use_subsite_options() && !Wpmu_Helpers::mu_is_network_admin()) {
                delete_option('wpo365_mail_authorization');
            } else {
                delete_site_option('wpo365_mail_authorization');
            }

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            $state = $request->get_item('state');
            $id_token = $request->get_item('id_token');
            $authorization_code = $request->get_item('code');
            $access_tokens = $request->get_item('access_tokens');
            $refresh_token = $request->get_item('refresh_token');
            $pkce_code_verifier = $request->get_item('pkce_code_verifier');

            /**
             * Check to see if all tokens have been retrieved successfully.
             */


            if (empty($access_tokens)) {
                $error_message = sprintf('%s -> Access token could not be extracted from request storage.', __METHOD__);
                return new \WP_Error('AccessTokenNotFoundException', $error_message);
            }

            if (empty($refresh_token)) {
                $error_message = sprintf('%s -> Refresh token could not be extracted from request storage.', __METHOD__);
                return new \WP_Error('RefreshTokenNotFoundException', $error_message);
            }

            $mail_from = Options_Service::get_global_string_var('mail_from');

            if (!Options_Service::get_global_boolean_var('mail_skip_all_checks')) {

                if (empty($id_token)) {
                    $error_message = sprintf('%s -> ID token could not be extracted from request storage.', __METHOD__);
                    return new \WP_Error('IdTokenNotFoundException', $error_message);
                }

                /**
                 * Ensure that the ID token is for the user account identified in the mail configuration.
                 */

                $upn = isset($id_token->upn)
                    ? WordPress_Helpers::trim(strtolower($id_token->upn))
                    : '';

                $email = isset($id_token->email)
                    ? WordPress_Helpers::trim(strtolower($id_token->email))
                    : '';

                $preferred_username = isset($id_token->preferred_username)
                    ? WordPress_Helpers::trim(strtolower($id_token->preferred_username))
                    : '';

                if (
                    strcasecmp($mail_from, $upn) !== 0
                    && strcasecmp($mail_from, $email) !== 0
                    && strcasecmp($mail_from, $preferred_username) !== 0
                ) {
                    $error_message = sprintf(
                        '%s -> Information in the ID token does not match with the configured "From" User Mailbox / account information [Default from: %s, upn: %s, email: %s, preferred username: %s].',
                        __METHOD__,
                        $mail_from,
                        $upn,
                        $email,
                        $preferred_username
                    );
                    return new \WP_Error('IdTokenNotFoundException', $error_message);
                }
            }

            /**
             * Switch the blog context if WPMU is detected and the user is trying to access
             * a subsite but landed at the main site because of Microsoft redirecting the
             * user there immediately after successful authentication.
             */

            Wpmu_Helpers::switch_blog($state);

            /**
             * Create a mail authorization object and save it in the site's options table.
             */

            $mail_auth = new \stdClass();
            $mail_auth->mail_from = $mail_from;

            if (!empty($id_token)) {
                $request->remove_item('id_token');
            }

            if (!empty($authorization_code)) {
                $request->remove_item('authorization_code');
            }

            if (!empty($access_tokens) && sizeof($access_tokens) === 1) {
                $mail_auth->access_token = $access_tokens[0];
                $request->remove_item('access_tokens');
            }

            if (!empty($refresh_token)) {
                $mail_auth->refresh_token = $refresh_token;
                $request->remove_item('refresh_token');
            }

            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service') && !empty($pkce_code_verifier)) {
                $mail_auth->pkce_code_verifier = $pkce_code_verifier;
                $request->remove_item('pkce_code_verifier');
            }

            if (Options_Service::mu_use_subsite_options() && !Wpmu_Helpers::mu_is_network_admin()) {
                update_option(
                    'wpo365_mail_authorization',
                    json_encode($mail_auth)
                );
            } else {
                update_site_option(
                    'wpo365_mail_authorization',
                    json_encode($mail_auth)
                );
            }
        }

        /**
         * Retrieve the current mail configuration (if any).
         * 
         * @since   19.0
         * 
         * @param   bool        $delete_delegated  If true the current delegated configuration will be deleted.
         * 
         * @return  stdClass    Calls with two boolean members indicating whether app-only and delegated access have been configured.
         */
        public static function get_mail_auth_configuration($delete_delegated, $delete_app_only)
        {
            Log_Service::write_log('DEBUG', sprintf('##### -> %s', __METHOD__));

            /**
             * 0. Clean up if requested
             */

            if ($delete_delegated) {

                if (Options_Service::mu_use_subsite_options() && !Wpmu_Helpers::mu_is_network_admin()) {
                    delete_option('wpo365_mail_authorization');
                } else {
                    delete_site_option('wpo365_mail_authorization');
                }
            }

            if ($delete_app_only) {
                delete_option(Access_Token_Service::SITE_META_ACCESS_TOKEN);
            }

            $mail_config = new \stdClass();

            /**
             * 1. Check for application level permissions
             */

            $app_only_access_token_send = Access_Token_Service::get_app_only_access_token(
                'https://graph.microsoft.com/.default',
                'Mail.Send',
                true
            );

            $app_only_access_token_readwrite = Access_Token_Service::get_app_only_access_token(
                'https://graph.microsoft.com/.default',
                'Mail.ReadWrite',
                true
            );

            $mail_config->app_only_authorized = !is_wp_error($app_only_access_token_send) && property_exists($app_only_access_token_send, 'access_token');
            $mail_config->app_only_last_error = is_wp_error($app_only_access_token_send)
                ? $app_only_access_token_send->get_error_message()
                : null;

            $mail_config->app_only_readwrite_authorized = !is_wp_error($app_only_access_token_readwrite) && property_exists($app_only_access_token_readwrite, 'access_token');
            $mail_config->app_only_readwrite_last_error = is_wp_error($app_only_access_token_readwrite) && $app_only_access_token_readwrite->get_error_code() != '1041'
                ? $app_only_access_token_readwrite->get_error_message()
                : null;

            /**
             * 2. Check for delegated permissions
             */

            $mail_config->delegated_authorized = false;
            $mail_config->delegated_shared_authorized = false;
            $mail_config->delegated_readwrite_authorized = false;
            $mail_config->delegated_readwrite_shared_authorized = false;

            if (Options_Service::mu_use_subsite_options() && !Wpmu_Helpers::mu_is_network_admin()) {
                $mail_auth_value = get_option('wpo365_mail_authorization');
            } else {
                $mail_auth_value = get_site_option('wpo365_mail_authorization');
            }

            $mail_auth = empty($mail_auth_value) ? null : json_decode($mail_auth_value);

            if (!empty($mail_auth) && property_exists($mail_auth, 'access_token')) {
                $scope = explode(' ', $mail_auth->access_token->scope);
                $scope = array_map(function ($item) {
                    $item = strtolower($item);
                    $item = false === WordPress_Helpers::stripos($item, 'https://graph.microsoft.com/') ? ('https://graph.microsoft.com/' . $item) : $item;
                    return $item;
                }, $scope);
                $scope = array_map('strtolower', $scope);
                $mail_config->delegated_authorized = in_array('https://graph.microsoft.com/mail.send', $scope);
                $mail_config->delegated_shared_authorized = in_array('https://graph.microsoft.com/mail.send.shared', $scope);
                $mail_config->delegated_readwrite_authorized = in_array('https://graph.microsoft.com/mail.readwrite', $scope);
                $mail_config->delegated_readwrite_shared_authorized = in_array('https://graph.microsoft.com/mail.readwrite.shared', $scope);
            }

            $mail_config->has_refresh_token = !empty($mail_auth) && property_exists($mail_auth, 'refresh_token');

            return $mail_config;
        }

        /**
         * Copies the app-principal info from the mail-integration-365 plugin into their
         * corresponding options for the WPO365 Graph Mailer.
         * 
         * @since   22.3
         * 
         * @return  bool|WP_Error
         */
        public static function try_migrate_mail_app_principal_info($delete = false)
        {
            Log_Service::write_log('DEBUG', sprintf('##### -> %s', __METHOD__));

            $app_principal_info = get_option('mail_integration_365_plugin_ops');

            if (
                !is_array($app_principal_info)
                || empty($app_principal_info['mail_integration_365_plugin_ops_client_id_field'])
                || empty($app_principal_info['mail_integration_365_plugin_ops_tenant_id_field'])
                || empty($app_principal_info['mail_integration_365_plugin_ops_client_secret_field'])
                || empty($app_principal_info['mail_integration_365_plugin_ops_redirect_uri_field'])
            ) {
                $message = sprintf(
                    '%s -> Cannot migrate app-principal information from mail-integration-365 to WPO365 because no valid information was found',
                    __METHOD__
                );
                Log_Service::write_log('WARN', $message);
                return new WP_Error('InvalidConfigurationException', $message);
            }

            if (Options_Service::get_global_boolean_var('use_wp_config')) {
                $message = sprintf(
                    '%s -> Cannot migrate app-principal information from mail-integration-365 to WPO365 because the Administrator has choosen to store Azure AD secrets in the wp-config.php file',
                    __METHOD__
                );
                Log_Service::write_log('WARN', $message);
                return new WP_Error('InvalidConfigurationException', $message);
            }

            // Delete the access token
            delete_option('mail_integration_365_plugin_ops_access_token');

            // Update the mail integration settings to keep oauth credentials if $delete is false
            $options = get_option('mail_integration_365_plugin_ops');

            if (is_array($options)) {
                $options['mail_integration_365_plugin_ops_keep_oauth_credentials_field'] = ($delete ? '' : 'on');
                update_option('mail_integration_365_plugin_ops', $options);
            }

            // Deactivate "their" plugin (this will also delete the options unless keep oauth credentials is true)
            if (false === function_exists('deactivate_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            deactivate_plugins('mail-integration-365/mail_integration_365.php');

            // Delete "our" mail authorization configuration
            self::get_mail_auth_configuration(true, false);

            Options_Service::add_update_option('mail_tenant_id', $app_principal_info['mail_integration_365_plugin_ops_tenant_id_field']);
            Options_Service::add_update_option('mail_application_id', $app_principal_info['mail_integration_365_plugin_ops_client_id_field']);
            Options_Service::add_update_option('mail_application_secret', $app_principal_info['mail_integration_365_plugin_ops_client_secret_field']);
            Options_Service::add_update_option('mail_redirect_url', $app_principal_info['mail_integration_365_plugin_ops_redirect_uri_field']);

            return true;
        }

        /**
         * Gets an access token to send email with delegated permissions and if not found it will
         * try to return an access token to send email with application-level permissions.
         * 
         * @since   19.0
         * 
         * @return  array|WP_Error An access token or otherwise a WP_Error object
         */
        public static function get_mail_access_token($scope)
        {
            Log_Service::write_log('DEBUG', sprintf('##### -> %s', __METHOD__));

            $mail_access_token_delegated = self::get_mail_access_token_delegated($scope);
            $error_message = array();

            if (is_wp_error($mail_access_token_delegated)) {
                $error_message[] = sprintf(
                    '%s -> [Requesting delegated permissions for %s] %s',
                    __METHOD__,
                    $scope,
                    $mail_access_token_delegated->get_error_message()
                );
            } else {
                return array(
                    'type' => 'delegated',
                    'access_token' => $mail_access_token_delegated
                );
            }

            if (false !== WordPress_Helpers::stripos($scope, 'Mail.Send.Shared')) {
                $scope = 'Mail.Send'; // There is no application-level equivalent for Mail.Send.Shared
            } elseif (false !== WordPress_Helpers::stripos($scope, 'Mail.ReadWrite.Shared')) {
                $scope = 'Mail.ReadWrite'; // There is no application-level equivalent for Mail.ReadWrite.Shared
            }

            $mail_access_token_app_only = Access_Token_Service::get_app_only_access_token(
                'https://graph.microsoft.com/.default',
                $scope,
                true
            );

            if (is_wp_error($mail_access_token_app_only)) {
                $error_message[] = sprintf(
                    '%s ->  [Fallback: Requesting application-level permissions for %s] %s',
                    __METHOD__,
                    $scope,
                    $mail_access_token_app_only->get_error_message()
                );
            } elseif (!empty($mail_access_token_app_only->access_token)) {
                return array(
                    'type' => 'application',
                    'access_token' => $mail_access_token_app_only->access_token
                );
            }

            $error_messages = sprintf(
                'Could not get an access token to send WordPress emails using Microsoft Graph: [%s]',
                implode('][', $error_message)
            );

            return new \WP_Error('AccessTokenException', $error_messages);
        }

        /**
         * Gets the cached access token if not yet expired or otherwise tries to refresh it using a 
         * refresh token.
         * 
         * @since   19.0
         * 
         * @return  string|WP_Error An access token or otherwise a WP_Error object
         */
        private static function get_mail_access_token_delegated($scope)
        {
            Log_Service::write_log('DEBUG', sprintf('##### -> %s', __METHOD__));

            if (Options_Service::mu_use_subsite_options() && !Wpmu_Helpers::mu_is_network_admin()) {
                $mail_auth_value = get_option('wpo365_mail_authorization');
            } else {
                $mail_auth_value = get_site_option('wpo365_mail_authorization');
            }

            $mail_auth = empty($mail_auth_value) ? null : json_decode($mail_auth_value);

            if (empty($mail_auth)) {
                return new \WP_Error('MailAuthCacheNotFound', sprintf(
                    '%s -> No mail authorization object was found. Most likely the administrator has not initiated the authorization to send WordPress emails using Microsoft Graph.',
                    __METHOD__
                ));
            }

            if (
                !empty($mail_auth->access_token)
                && time() < $mail_auth->access_token->expiry
                && false !== WordPress_Helpers::stripos($mail_auth->access_token->scope, $scope)
            ) {
                return $mail_auth->access_token->access_token;
            }

            if (empty($mail_auth->refresh_token)) {
                return new \WP_Error('MailAuthRefreshTokenNotFound', sprintf(
                    '%s -> The mail authorization object does not provide a refresh token and therefore the WPO365 plugin cannot refresh the access token to send email using Microsoft Graph. Most likely the administrator has not consented the "offline_access" permission for the Azure AD App registration with ID %s.',
                    __METHOD__,
                    Options_Service::get_aad_option('application_id')
                ));
            }

            $redirect_url = Options_Service::get_aad_option('mail_redirect_url');

            /**
             * Some older configuration may have relied on using the redirect_url.
             */

            if (empty($redirect_url)) {
                $redirect_url = Options_Service::get_aad_option('redirect_url');
            }

            $scope = sprintf(
                'offline_access %s',
                $scope
            );

            $params = array(
                'client_id' => Options_Service::get_aad_option('mail_application_id'),
                'client_secret' => Options_Service::get_aad_option('mail_application_secret'),
                'redirect_uri' => $redirect_url,
                'scope' =>  $scope,
                'grant_type' => 'refresh_token',
                'refresh_token' => $mail_auth->refresh_token->refresh_token,
            );

            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service')) {

                if (!empty($mail_auth->pkce_code_verifier)) {
                    $params['code_verifier'] = $mail_auth->pkce_code_verifier;
                } else {
                    return new \WP_Error(
                        'PkceException',
                        sprintf(
                            '%s -> The administrator has enabled the use of the Proof Key for Code Exchange (PKCE) but the WPO365 could not find the PKCE code verifier. Therefore it cannot refresh the access token for sending WordPress emails using Microsoft Graph.',
                            __METHOD__
                        )
                    );
                }
            }

            $directory_id = Options_Service::get_aad_option('mail_tenant_id');
            $authorize_url = "https://login.microsoftonline.com/$directory_id/oauth2/v2.0/token";
            $skip_ssl_verify = !Options_Service::get_global_boolean_var('skip_host_verification');

            $response = wp_remote_post(
                $authorize_url,
                array(
                    'body' => $params,
                    'timeout' => 15,
                    'sslverify' => $skip_ssl_verify,
                    'headers' => array('Expect' => ''),
                )
            );

            if (is_wp_error($response)) {
                $error_message = sprintf(
                    '%s -> Error occured whilst getting an access token for sending WordPress emails using Microsoft Graph: %s',
                    __METHOD__,
                    $response->get_error_message()
                );

                return new \WP_Error(
                    'AccessTokenException',
                    $error_message
                );
            }

            $body = wp_remote_retrieve_body($response);

            // Validate the access token and return it
            $access_token = json_decode($body);
            $access_token = Access_Token_Service::validate_access_token($access_token);

            if (is_wp_error($access_token)) {
                $error_message = sprintf(
                    '%s -> Error occured whilst getting an access token for sending WordPress emails using Microsoft Graph: %s',
                    __METHOD__,
                    $access_token->get_error_message()
                );

                return new \WP_Error(
                    'AccessTokenException',
                    $error_message
                );
            }

            $access_token->expiry = time() + intval($access_token->expires_in);

            $mail_auth->access_token = $access_token;

            if (property_exists($access_token, 'refresh_token')) {
                $refresh_token = new \stdClass();
                $refresh_token->refresh_token = $access_token->refresh_token;
                $refresh_token->scope = $access_token->scope;
                $mail_auth->refresh_token = $refresh_token;
            }

            if (Options_Service::mu_use_subsite_options() && !Wpmu_Helpers::mu_is_network_admin()) {
                update_option(
                    'wpo365_mail_authorization',
                    json_encode($mail_auth)
                );
            } else {
                update_site_option(
                    'wpo365_mail_authorization',
                    json_encode($mail_auth)
                );
            }

            return $access_token->access_token;
        }
    }
}

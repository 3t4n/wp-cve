<?php

namespace Wpo\Services;

use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Authentication_Service;
use \Wpo\Services\Error_Service;
use \Wpo\Services\Jwt_Token_Service;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Nonce_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Id_Token_Service')) {

    class Id_Token_Service
    {

        /**
         * Constructs the oauth authorize URL that is the end point where the user will be sent for authorization.
         * 
         * @since 4.0
         * 
         * @since 11.0 Dropped support for the v1 endpoint.
         * 
         * @param $login_hint string Login hint that will be added to Open Connect ID link
         * @param $redirect_to string Link where the user will be redirected to
         * 
         * @return string if everthing is configured OK a valid authorization URL
         */
        public static function get_openidconnect_url($login_hint = null, $redirect_to = null)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $application_id = Options_Service::get_aad_option('application_id');
            $directory_id = Options_Service::get_aad_option('tenant_id');
            $oidc_flow = Options_Service::get_global_string_var('oidc_flow');
            $multi_tenanted = Options_Service::get_global_boolean_var('multi_tenanted') && !Options_Service::get_global_boolean_var('use_b2c');

            /**
             * @since 24.0 Filters the AAD Redirect URI e.g. to set it dynamically to the current host.
             */

            $redirect_uri = Options_Service::get_aad_option('redirect_url');
            $redirect_uri = apply_filters('wpo365/aad/redirect_uri', $redirect_uri);

            $redirect_to = !empty($redirect_to)
                ? $redirect_to
                : (
                    (isset($_SERVER['HTTP_REFERER'])
                        ? $_SERVER['HTTP_REFERER']
                        : $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'])
                );

            /**
             * @since   16.0    Filters the redirect_to url
             */
            $redirect_to = apply_filters('wpo365/cookie/remove/url', $redirect_to);
            $redirect_to = urlencode($redirect_to);

            /**
             * @since   21.9    Premium extensions of the WPO365 plugin require User.Read to update core WP user fields.
             */

            if (class_exists('\Wpo\Services\User_Create_Update_Service')) {
                $scope = 'https://graph.microsoft.com/user.read openid email profile';
            } else {
                $scope = 'openid email profile';
            }

            if (empty($response_mode = Options_Service::get_global_string_var('oidc_response_mode')) || $oidc_flow != 'code') {
                $response_mode = 'form_post';
            }

            $params = array(
                'client_id'             => $application_id,
                'redirect_uri'          => $redirect_uri,
                'response_mode'         => $response_mode,
                'scope'                 => $scope,
                'state'                 => $redirect_to,
                'nonce'                 => Nonce_Service::create_nonce(),
            );

            $params['response_type'] = $oidc_flow == 'code' ? 'code' : 'id_token code';

            // Add Proof Key for Code Exchange challenge if required
            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service')) {
                \Wpo\Services\Pkce_Service::add_and_memoize_verifier($params);
            }

            /**
             * @since 9.4
             * 
             * Add ability to configure a domain hint to prevent Microsoft from
             * signing in users that are already logged in to a different O365 tenant.
             */
            $domain_hint = Options_Service::get_global_string_var('domain_hint');

            if (!empty($domain_hint)) {
                $params['domain_hint'] = $domain_hint;
            }

            if (!empty($login_hint)) {
                $params['login_hint'] = $login_hint;
            }

            if (true === Options_Service::get_global_boolean_var('add_select_account_prompt')) {
                $params['prompt'] = 'select_account';
            } else if (true === Options_Service::get_global_boolean_var('add_create_account_prompt')) {
                $params['prompt'] = 'create';
            }

            if (true === $multi_tenanted) {
                $directory_id = 'common';
                $multi_tenanted_api_permissions = Options_Service::get_global_list_var('multi_tenanted_api_permissions');

                foreach ($multi_tenanted_api_permissions as $key => $permission) {

                    $permission = strtolower(trim($permission));

                    if (!empty($permission)) {
                        $params['scope'] = $params['scope'] . " $permission";
                    }
                }
            }

            $auth_url = 'https://login.microsoftonline.com/'
                . $directory_id
                . '/oauth2'
                . '/v2.0'
                . '/authorize?'
                . http_build_query($params, '', '&');

            Log_Service::write_log('DEBUG', __METHOD__ . " -> Open ID Connect URL: $auth_url");

            return $auth_url;
        }

        /**
         * Processes the ID token and caches it for the current request. If an authorization code is sent
         * along, it will be saved so the it can be used when requesting access tokens for the current
         * user (if integration is configured).
         * 
         * @return  void
         */
        public static function process_openidconnect_token($force_goodbye = true)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            // Decode the id_token
            $id_token = self::decode_id_token();

            // Handle if token could not be processed
            if ($id_token === false) {

                if (true === $force_goodbye) {
                    Log_Service::write_log('ERROR', __METHOD__ . ' -> ID token could not be processed and user will be redirected to default Wordpress login.');
                    Authentication_Service::goodbye(Error_Service::ID_TOKEN_ERROR);
                    exit();
                }

                return;
            }

            // Handle if nonce is invalid 
            if (!Options_Service::get_global_boolean_var('skip_nonce_verification')) {

                if (!Nonce_Service::verify_nonce($id_token->nonce)) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Could not successfully validate oidc nonce with value ' . $id_token->nonce);
                }
            }

            // Log id token if configured
            if (true === Options_Service::get_global_boolean_var('debug_log_id_token')) {
                Log_Service::write_log('DEBUG', $id_token);
            }

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $request->set_item('id_token', $id_token);

            if (property_exists($id_token, 'tfp')) {
                $request->set_item('tfp', $id_token->tfp);
            } elseif (property_exists($id_token, 'acr')) {
                $request->set_item('tfp', $id_token->acr);
            }
        }

        /**
         * Helper to process the authorization code which is then used to request an ID and access token.
         * 
         * @since   18.0
         * 
         * @return void 
         */
        public static function process_openidconnect_code($scope = '', $force_goodbye = true)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            $code = $request->get_item('code');
            $mode = $request->get_item('mode');

            if (empty($code)) {
                Log_Service::write_log('ERROR', sprintf('%s -> Authorization code not found', __METHOD__));
                return;
            }

            $use_mail_config = $mode == 'mailAuthorize';

            $directory_id = $use_mail_config ? Options_Service::get_aad_option('mail_tenant_id') : Options_Service::get_aad_option('tenant_id');
            $application_id = $use_mail_config ? Options_Service::get_aad_option('mail_application_id') : Options_Service::get_aad_option('application_id');
            $application_secret = $use_mail_config ? Options_Service::get_aad_option('mail_application_secret') : Options_Service::get_aad_option('application_secret');
            $multi_tenanted = Options_Service::get_global_boolean_var('multi_tenanted') && !$use_mail_config && !Options_Service::get_global_boolean_var('use_b2c');

            /**
             * @since 24.0 Filters the AAD Redirect URI e.g. to set it dynamically to the current host.
             */

            if ($use_mail_config) {
                $redirect_uri = Options_Service::get_aad_option('mail_redirect_url');
            } else {
                $redirect_uri = Options_Service::get_aad_option('redirect_url');
                $redirect_uri = apply_filters('wpo365/aad/redirect_uri', $redirect_uri);
            }

            if (true === $multi_tenanted) {
                $directory_id = 'common';
            }

            $params = array(
                'client_id'     => $application_id,
                'response_type' => 'token',
                'redirect_uri'  => $redirect_uri,
                'response_mode' => 'form_post',
                'grant_type'    => 'authorization_code',
                'scope'         => 'openid email profile offline_access' . (empty($scope) ? '' : (' ' . $scope)),
                'code'          => $code,
                'client_secret' => $application_secret,
            );

            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service')) {
                $pkce_code_verifier = \Wpo\Services\Pkce_Service::get_personal_pkce_code_verifier();

                if (!empty($pkce_code_verifier)) {
                    $params['code_verifier'] = $pkce_code_verifier;
                } else {
                    $warning = 'Cannot retrieve an (ID) token because the Administrator 
                        has configured the use of a Proof Key for Code Exchange but a code verifier for the current
                        user cannot be found. See the <a href="https://docs.wpo365.com/article/149-require-proof-key-for-code-exchange-pkce" target="_blank">online documentation</a> 
                        for detailed step-by-step instructions on how to configure the WPO365 | LOGIN plugin to use a Proof Key for Code Exchange.';
                    Log_Service::write_log('ERROR', __METHOD__ . " -> $warning");

                    $access_token_errors = $request->get_item('access_token_errors') ?: array();
                    $access_token_errors[] = $warning;
                    $request->set_item('access_token_errors', $access_token_errors);

                    return;
                }
            }

            $skip_ssl_verify = !Options_Service::get_global_boolean_var('skip_host_verification');

            $token_url = 'https://login.microsoftonline.com/'
                . $directory_id
                . '/oauth2'
                . '/v2.0'
                . '/token';

            $response = wp_remote_post($token_url, array(
                'sslverify' => $skip_ssl_verify,
                'timeout' => 15, // timeout in seconds
                'body' => $params,
                'headers' => array('Expect' => ''),
            ));

            if (is_wp_error($response)) {
                Log_Service::write_log('ERROR', sprintf('%s -> Error occured whilst fetching from %s: %s', __METHOD__, $token_url, $response->get_error_message()));
                return;
            }

            $body = json_decode(wp_remote_retrieve_body($response));

            if (empty($body)) {
                Log_Service::write_log('ERROR', sprintf('%s -> Error occured whilst fetching from %s: See next line for details.', __METHOD__, $token_url));
                Log_Service::write_log('ERROR', $response);
                return;
            }

            if (property_exists($body, 'error')) {
                $message = property_exists($body, 'error_description') ? $body->error_description : $body->error;
                Log_Service::write_log('ERROR', sprintf('%s -> Error occured whilst fetching from %s: %s', __METHOD__, $token_url, $message));
                return;
            }

            if (property_exists($body, 'access_token')) {
                $access_token = new \stdClass();
                $access_token->access_token = $body->access_token;

                if (property_exists($body, 'expires_in')) {
                    $access_token->expiry = time() + intval($body->expires_in);
                }

                if (property_exists($body, 'scope')) {
                    $access_token->scope = $body->scope;
                }

                $access_tokens = $request->get_item('access_tokens');

                if (empty($access_tokens)) {
                    $access_tokens = array();
                }

                // Save access token as request variable -> will be saved on shutdown
                $access_tokens[] = $access_token;
                $request->set_item('access_tokens', $access_tokens);
            }

            if (property_exists($body, 'refresh_token')) {
                $refresh_token = new \stdClass();
                $refresh_token->refresh_token = $body->refresh_token;

                if (property_exists($body, 'scope')) {
                    $refresh_token->scope = $body->scope;
                }

                $request->set_item('refresh_token', $refresh_token);
            }

            if ($mode == 'mailAuthorize' && Options_Service::get_global_boolean_var('mail_skip_all_checks')) {
                return;
            }

            if (property_exists($body, 'id_token')) {
                $request->set_item('encoded_id_token', $body->id_token);
                self::process_openidconnect_token($force_goodbye);
                return;
            }

            Log_Service::write_log('ERROR', sprintf('%s -> ID token not found in data retrieved from token endpoint [see next line for response body]', __METHOD__));
            Log_Service::write_log('DEBUG', $body);
        }

        /**
         * Unraffles the incoming JWT id_token with the help of Firebase\JWT and the tenant specific public keys available from Microsoft.
         * 
         * @since   1.0
         *
         * @return  object|boolean 
         */
        public static function decode_id_token()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $id_token = $request->get_item('encoded_id_token');

            // Get the token and get it's header for a first analysis
            if (empty($id_token)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> ID token not found in posted data.');
                return false;
            }

            $claims = Jwt_Token_Service::validate_signature($id_token);

            if (is_wp_error($claims)) {
                Log_Service::write_log('ERROR', $claims->get_error_message());
                return false;
            }

            return $claims;
        }

        /**
         * Helper to check if the ID token was issued by the "home" tenant and whether the ID token
         * was indeed isssued for the requesting application.
         * 
         * @since   21.x    Initially as part of decode_id_token
         * @since   23.0    Moved into its own function
         * 
         * @param   mixed   $id_token 
         * 
         * @return  bool 
         */
        public static function check_audience($id_token)
        {
            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            if ((!($multi_tenanted = Options_Service::get_global_boolean_var('multi_tenanted')) || ($multi_tenanted && !empty($allowed_tenants = Options_Service::get_global_list_var('allowed_tenants')))) && !Options_Service::get_global_boolean_var('skip_id_token_verification')) {

                /**
                 * @since 23.0  Either let request bypass WPO365 logic (= exit) if the audience check 
                 *              fails or send user with error to login / error page.
                 */
                $on_error = function () use ($request) {

                    if (!Options_Service::get_global_boolean_var('exit_on_audience_error')) {
                        Authentication_Service::goodbye(Error_Service::ID_TOKEN_AUD);
                        exit();
                    }

                    $request->set_item('skip_authentication', true);
                    return false;
                };

                $token_arr = explode('.', $id_token);

                // Token should explored in three segments header, body, signature
                if (sizeof($token_arr) != 3) {
                    return $on_error(); // Either exit or return false
                }

                // Payload (claims)
                $claims_enc = $token_arr[1];
                $claims = \json_decode(WordPress_Helpers::base64_url_decode($claims_enc));

                if (!empty($claims->iss)) {
                    $tenant_id = $request->get_item('mode') == 'mailAuthorize'
                        ? Options_Service::get_aad_option('mail_tenant_id')
                        : Options_Service::get_aad_option('tenant_id');

                    $user_name = !empty($claims->unique_name)
                        ? $claims->unique_name
                        : (!empty($claims->preferred_username)
                            ? $claims->preferred_username
                            : '???'
                        );

                    // At this point we already verified that the list of allowed tenants is not empty
                    if ($multi_tenanted) {
                        $allowed_tenants[] = $tenant_id;

                        foreach ($allowed_tenants as $allowed_tenant) {

                            if (false !== WordPress_Helpers::stripos($claims->iss, $allowed_tenant)) {
                                return true;
                            }
                        }

                        $error_message = sprintf(
                            '%s -> The ID token that has been received for [%s] has been issued by a tenant [%s] that has not been allow-listed on the plugin\'s "User registration" configuration page.',
                            __METHOD__,
                            $user_name,
                            $claims->iss
                        );
                        Log_Service::write_log('ERROR', $error_message);
                        return $on_error(); // Either exit or return false
                    } elseif (empty($tenant_id) || false === WordPress_Helpers::stripos($claims->iss, $tenant_id)) {
                        $error_message = sprintf(
                            '%s -> The ID token that has been received for [%s] has been issued by another tenant [%s] (vs. your tenant [%s]). If you believe this error to be a false-positive, then you can check the option to <em>Skip the ID token verification</em> on the plugin\'s <em>Miscellaneous</em> configuration page.',
                            __METHOD__,
                            $user_name,
                            $claims->iss,
                            $tenant_id
                        );
                        Log_Service::write_log('ERROR', $error_message);
                        return $on_error(); // Either exit or return false
                    }
                }

                if (!empty($claims->aud)) {
                    $application_id = $request->get_item('mode') == 'mailAuthorize'
                        ? Options_Service::get_aad_option('mail_application_id')
                        : Options_Service::get_aad_option('application_id');

                    if (false === WordPress_Helpers::stripos($claims->aud, $application_id)) {
                        $error_message = sprintf(
                            '%s -> The ID token that has been received is intended for another audience [%s] (vs. your registered application [%s]). If you believe this error to be a false-positive, then you can check the option to <em>Skip the ID token verification</em> on the plugin\'s <em>Miscellaneous</em> configuration page.',
                            __METHOD__,
                            $application_id,
                            $claims->aud
                        );
                        Log_Service::write_log('ERROR', $error_message);
                        return $on_error(); // Either exit or return false
                    }
                }
            }

            return true;
        }
    }
}

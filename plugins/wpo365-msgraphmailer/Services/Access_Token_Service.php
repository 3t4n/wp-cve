<?php

namespace Wpo\Services;

use \Wpo\Core\Permissions_Helpers;
use Wpo\Core\WordPress_Helpers;

use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;


// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Access_Token_Service')) {

    class Access_Token_Service
    {

        const SITE_META_ACCESS_TOKEN = 'wpo_app_only_access_tokens';
        const USR_META_REFRESH_TOKEN = 'wpo_refresh_token';
        const USR_META_ACCESS_TOKEN = 'wpo_access_tokens';
        const USR_META_WPO365_AUTH_CODE = 'WPO365_AUTH_CODE';

        /**
         * Gets an access token in exchange for an authorization token that was received prior when getting
         * an OpenId Connect token or for a fresh code in case available. This method is only compatible with 
         * AAD v2.0
         *
         * @since   5.2
         * 
         * @param $scope string Scope for AAD v2.0 e.g. https://graph.microsoft.com/user.read
         *
         * @return mixed(stdClass|WP_Error) access token as object or WP_Error
         */
        public static function get_access_token($scope)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            $scope = \urldecode($scope);
            $client_secret = Options_Service::get_aad_option('application_secret');
            $current_user_id = \get_current_user_id();

            $user_is_logging_in = !empty($request->get_item('id_token')) || !empty($request->get_item('encoded_id_token'));
            $access_token_errors = $request->get_item('access_token_errors') ?: array();

            if (!empty($access_token_errors)) {
                $scope_role = str_replace('https://graph.microsoft.com/', '', $scope);

                foreach ($access_token_errors as $key => $access_token_error) {

                    if (false !== WordPress_Helpers::stripos($access_token_error, $scope_role)) {
                        $warning = 'Cannot retrieve an access token for scope ' . $scope . ' [' . $access_token_error . ']';
                        Log_Service::write_log('WARN', __METHOD__ . " -> $warning");
                        return new \WP_Error('1025', $warning);
                    }
                }
            }

            if (empty($current_user_id) && !$user_is_logging_in) {
                $warning = 'Cannot retrieve an access token for scope ' . $scope . ' when no logged-on user is detected and the use of an app-only access token has not been configured. See <a href="https://docs.wpo365.com/article/23-integrationn" target="_blank">https://docs.wpo365.com/article/23-integration</a> for more information.';
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");

                $access_token_errors = $request->get_item('access_token_errors') ?: array();
                $access_token_errors[] = $warning;
                $request->set_item('access_token_errors', $access_token_errors);

                return new \WP_Error('1020', $warning);
            }

            $cached_access_token = self::get_cached_access_token($scope);

            if (!empty($cached_access_token)) {
                return $cached_access_token;
            }

            if (empty($client_secret)) {
                $warning = 'Cannot retrieve an access token for scope ' . $scope . ' because the Administrator 
                    has not configured a client secret. Please check the 
                    <a href="https://docs.wpo365.com/article/23-integration" target="_blank">documentation</a> 
                    for detailed step-by-step instructions on how to configure integration with Microsoft Graph and
                    other 365 services';
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");

                $access_token_errors = $request->get_item('access_token_errors') ?: array();
                $access_token_errors[] = $warning;
                $request->set_item('access_token_errors', $access_token_errors);

                return new \WP_Error('1025', $warning);
            }

            /**
             * @since 24.0 Filters the AAD Redirect URI e.g. to set it dynamically to the current host.
             */

            $redirect_uri = Options_Service::get_aad_option('redirect_url');
            $redirect_uri = apply_filters('wpo365/aad/redirect_uri', $redirect_uri);

            if (WordPress_Helpers::stripos($scope, 'https://analysis.windows.net/powerbi/api/.default') === 0) {
                $params = array(
                    'client_id' => Options_Service::get_aad_option('application_id'),
                    'client_secret' => Options_Service::get_aad_option('application_secret'),
                    'client_info' => 1,
                    'scope' =>  $scope,
                    'grant_type' => 'client_credentials',
                );
            } else {
                $params = array(
                    'client_id' => Options_Service::get_aad_option('application_id'),
                    'client_secret' => Options_Service::get_aad_option('application_secret'),
                    'redirect_uri' => $redirect_uri,
                    'scope' =>  'offline_access ' . $scope,
                );
            }

            // Check if we have a refresh token and if not fallback to the auth code

            if (!isset($params['grant_type'])) {

                $authorization_code = self::get_authorization_code();
                $refresh_token = self::get_refresh_token();

                if (empty($authorization_code) && empty($refresh_token)) {

                    $warning = 'No authorization code and refresh token found when trying to get an access token 
                            for ' . $scope . '. The current user must sign out of the WordPress website and log back in again to 
                            retrieve a fresh authorization code that can be used in exchange for access tokens. If
                            this error occurs regularly, then please check the 
                            <a href="https://docs.wpo365.com/article/23-integration" target="_blank">documentation</a> 
                            for detailed step-by-step instructions on how to configure integration with Microsoft Graph and
                            other 365 services.';
                    Log_Service::write_log('WARN', __METHOD__ . " -> $warning");

                    $access_token_errors = $request->get_item('access_token_errors') ?: array();
                    $access_token_errors[] = $warning;
                    $request->set_item('access_token_errors', $access_token_errors);

                    return new \WP_Error('1030', $warning);
                }

                if (!empty($refresh_token)) {
                    $params['grant_type'] = 'refresh_token';
                    $params['refresh_token'] = $refresh_token->refresh_token;
                } else {

                    if (!empty($authorization_code)) {
                        $params['grant_type'] = 'authorization_code';
                        $params['code'] = $authorization_code;
                    }
                }
            }

            if (Options_Service::get_global_boolean_var('use_pkce') && class_exists('\Wpo\Services\Pkce_Service')) {
                $pkce_code_verifier = \Wpo\Services\Pkce_Service::get_personal_pkce_code_verifier();

                if (!empty($pkce_code_verifier)) {
                    $params['code_verifier'] = $pkce_code_verifier;
                } else {
                    $warning = 'Cannot retrieve an access token for scope ' . $scope . ' because the Administrator 
                        has configured the use of a Proof Key for Code Exchange but a code verifier for the current
                        user cannot be found. See the <a href="https://docs.wpo365.com/article/149-require-proof-key-for-code-exchange-pkce" target="_blank">online documentation</a> 
                        for detailed step-by-step instructions on how to configure the WPO365 | LOGIN plugin to use a Proof Key for Code Exchange.';
                    Log_Service::write_log('WARN', __METHOD__ . " -> $warning");

                    $access_token_errors = $request->get_item('access_token_errors') ?: array();
                    $access_token_errors[] = $warning;
                    $request->set_item('access_token_errors', $access_token_errors);

                    return new \WP_Error('1026', $warning);
                }
            }

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Requesting access token for ' . $scope);

            $directory_id = Options_Service::get_aad_option('tenant_id');
            $multi_tenanted = Options_Service::get_global_boolean_var('multi_tenanted') && !Options_Service::get_global_boolean_var('use_b2c');

            if (true === $multi_tenanted) {
                $directory_id = 'common';
            }

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
                $warning = 'Error occured whilst getting an access token: ' . $response->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");

                $access_token_errors = $request->get_item('access_token_errors') ?: array();
                $access_token_errors[] = $warning;
                $request->set_item('access_token_errors', $access_token_errors);

                return new \WP_Error('1040', $warning);
            }

            $body = wp_remote_retrieve_body($response);

            // Validate the access token and return it
            $access_token = json_decode($body);
            $access_token = self::validate_access_token($access_token);

            if (is_wp_error($access_token)) {
                $warning = 'Access token for ' . $scope . ' is not valid: ' . $access_token->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");

                $access_token_errors = $request->get_item('access_token_errors') ?: array();
                $access_token_errors[] = $warning;
                $request->set_item('access_token_errors', $access_token_errors);

                return new \WP_Error($access_token->get_error_code(), $warning);
            }

            $access_token->expiry = time() + intval($access_token->expires_in);
            $access_tokens = $request->get_item('access_tokens');

            if (empty($access_tokens)) {
                $access_tokens = array();
            }

            // Save access token as request variable -> will be saved on shutdown
            $access_tokens[] = $access_token;
            $request->set_item('access_tokens', $access_tokens);

            // Save refresh token as request variable -> will be saved on shutdown
            if (property_exists($access_token, 'refresh_token')) {
                $refresh_token = new \stdClass();
                $refresh_token->refresh_token = $access_token->refresh_token;
                $refresh_token->scope = $access_token->scope;
                $refresh_token->expiry = time() + 1209600;
                $request->set_item('refresh_token', $refresh_token);
            }

            /**
             * @since 10.6
             * 
             * The wpo365_access_token_processed action hook signals to its subscribers
             * that a user has just received a fresh access token. As arguments
             * it provides the WordPress user ID and the (bearer) access token.
             */

            do_action('wpo365_access_token_processed', $current_user_id, $access_token->access_token);

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Successfully obtained a valid access token for ' . $scope);

            return $access_token;
        }

        /**
         * @since 11.0
         */
        private static function get_cached_access_token($scope)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $access_tokens = $request->get_item('access_tokens');
            $wp_usr_id = get_current_user_id(); // 0 if user is not (yet) logged in

            if (empty($access_tokens)) {
                $access_tokens = array();
            }

            // Tokens are stored by default as user metadata
            $cached_access_tokens_json = get_user_meta(
                $wp_usr_id,
                self::USR_META_ACCESS_TOKEN,
                true
            );

            if (!empty($cached_access_tokens_json)) {
                $cached_access_tokens = json_decode($cached_access_tokens_json);

                // json_decode returns null or it isn't an array if an "old" token is found
                if (empty($cached_access_tokens) || !is_array($cached_access_tokens)) {
                    delete_user_meta($wp_usr_id, self::USR_META_ACCESS_TOKEN);
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an access token that is no longer supported.');
                    Log_Service::write_log('DEBUG', $cached_access_tokens);
                    $cached_access_tokens = array();
                }

                foreach ($cached_access_tokens as $key => $cached_access_token) {

                    if (isset($cached_access_token->expiry) && intval($cached_access_token->expiry) < time()) {
                        unset($cached_access_tokens[$key]);
                        update_user_meta(
                            $wp_usr_id,
                            self::USR_META_ACCESS_TOKEN,
                            json_encode($cached_access_tokens)
                        );
                        Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an expired access token.');
                    }
                }

                $access_tokens = array_merge($access_tokens, $cached_access_tokens);
            }

            foreach ($access_tokens as $key => $access_token) {

                if (!isset($access_token->scope) || empty($scope)) {
                    continue;
                }

                if (false !== WordPress_Helpers::stripos($access_token->scope, $scope)) {
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Found a previously saved access token for ( ' . $scope . ' ) ' . $access_token->scope . ' that is still valid');
                    return $access_token;
                }
            }

            return null;
        }

        /**
         * @since 11.0
         */
        public static function save_access_tokens($access_tokens)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $wp_usr_id = get_current_user_id();

            if (empty($wp_usr_id)) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Cannot save access tokens for user that is not logged in.');
                return;
            }

            // Tokens are stored by default as user metadata
            $cached_access_tokens_json = get_user_meta(
                $wp_usr_id,
                self::USR_META_ACCESS_TOKEN,
                true
            );

            $cached_access_tokens = array();

            if (!empty($cached_access_tokens_json)) {
                $cached_access_tokens = json_decode($cached_access_tokens_json);

                // json_decode returns null or it isn't an array if an "old" token is found
                if (empty($cached_access_tokens) || !is_array($cached_access_tokens)) {
                    delete_user_meta($wp_usr_id, self::USR_META_ACCESS_TOKEN);
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an access token that is no longer supported.');
                    $cached_access_tokens = array();
                }

                foreach ($cached_access_tokens as $key => $cached_access_token) {

                    if (isset($cached_access_token->expiry) && intval($cached_access_token->expiry) < time()) {
                        unset($cached_access_tokens[$key]);
                        update_user_meta(
                            $wp_usr_id,
                            self::USR_META_ACCESS_TOKEN,
                            json_encode($cached_access_tokens)
                        );
                        Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an expired access token.');
                    }
                }
            }

            $cached_access_tokens = array_merge($cached_access_tokens, $access_tokens);
            $unique_access_tokens = array();

            // From the newest to the oldest
            for ($i = sizeof($cached_access_tokens) - 1; $i >= 0; $i--) {
                $scope = isset($cached_access_tokens[$i]->scope) ? $cached_access_tokens[$i]->scope : '';

                if (empty($scope)) {
                    continue;
                }

                $is_unique = true;

                foreach ($unique_access_tokens as $key => $unique_access_token) {

                    if (strlen($unique_access_token->scope) === strlen($scope)) {
                        $is_unique = false;
                        break;
                    }
                }

                if ($is_unique) {
                    array_unshift($unique_access_tokens, $cached_access_tokens[$i]);
                }
            }

            update_user_meta(
                $wp_usr_id,
                self::USR_META_ACCESS_TOKEN,
                json_encode($unique_access_tokens)
            );

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Successfully saved access tokens');
        }

        /**
         * Gets an app only access token. This method is only compatible with AAD v2.0
         *
         * @since   10.0
         * 
         * @param   string  $scope  Scope e.g. https://graph.microsoft.com/.default
         * @param   string  $role   Role e.g. Mail.Send (or as scope e.g. https://graph.microsoft.com/Mail.Send)
         *
         * @return mixed(stdClass|WP_Error) access token as object or WP_Error
         */
        public static function get_app_only_access_token($scope = 'https://graph.microsoft.com/.default', $role = null, $use_mail_config = false)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (
                $use_mail_config || (Options_Service::get_global_boolean_var('use_graph_mailer')
                    && !empty($role)
                    && (false !== WordPress_Helpers::stripos($role, 'Mail.Send') || false !== WordPress_Helpers::stripos($role, 'Mail.ReadWrite'))
                )
            ) {
                $mail_directory_id = Options_Service::get_aad_option('mail_tenant_id');
                $mail_application_id = Options_Service::get_aad_option('mail_application_id');
                $mail_application_secret = Options_Service::get_aad_option('mail_application_secret');

                if (!empty($mail_directory_id) && !empty($mail_application_id) && !empty($mail_application_secret)) {
                    $use_mail_config = true;
                }
            }

            $directory_id = $use_mail_config ? $mail_directory_id : Options_Service::get_aad_option('tenant_id');
            $application_id = $use_mail_config ? $mail_application_id : Options_Service::get_aad_option('app_only_application_id');
            $application_secret = $use_mail_config ? $mail_application_secret : Options_Service::get_aad_option('app_only_application_secret');

            // Tokens are stored by default as user metadata
            $cached_access_tokens_json = get_option(self::SITE_META_ACCESS_TOKEN);

            // Valid access token was saved previously
            if (!empty($cached_access_tokens_json)) {
                $cached_access_tokens = json_decode($cached_access_tokens_json);

                // json_decode returns NULL or it isn't an array if an "old" token is found
                if (empty($cached_access_tokens) || !is_array($cached_access_tokens)) {
                    delete_option(self::SITE_META_ACCESS_TOKEN);
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an invalid app-only access token.');
                } else {
                    $cached_access_token_result = null;

                    foreach ($cached_access_tokens as $key => $cached_access_token) {
                        if (!\property_exists($cached_access_token, 'audience')) {
                            unset($cached_access_tokens[$key]);
                            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an app-only access token without an audience.');
                            continue;
                        }

                        if (strcasecmp($cached_access_token->audience, $application_id) !== 0) {
                            Log_Service::write_log('DEBUG', __METHOD__ . ' -> App-only access token for a different audience.');
                            continue;
                        }

                        // Valid app only token is expired
                        if (isset($cached_access_token->expiry) && intval($cached_access_token->expiry) < time()) {
                            unset($cached_access_tokens[$key]);
                            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an expired app-only access token.');
                            continue;
                        }

                        // Ensure we can test against scope member
                        if (empty($cached_access_token->scope)) {
                            unset($cached_access_tokens[$key]);
                            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an app-only access token without scope');
                            continue;
                        }

                        if (false !== WordPress_Helpers::stripos($cached_access_token->scope, $scope)) {

                            if (!empty($role)) {

                                if (self::token_has_role($cached_access_token, $role)) {
                                    $cached_access_token_result = $cached_access_token;
                                }
                            } else {
                                $cached_access_token_result = $cached_access_token;
                            }
                        }
                    }

                    update_option(
                        self::SITE_META_ACCESS_TOKEN,
                        json_encode($cached_access_tokens)
                    );

                    if (!empty($cached_access_token_result)) {
                        Log_Service::write_log('DEBUG', __METHOD__ . ' -> Found cached app-only access token for ' . $scope);
                        return $cached_access_token_result;
                    }
                }
            }

            $params = array(
                'client_id' => $application_id,
                'client_secret' => $application_secret,
                'grant_type' => 'client_credentials',
                'scope' => $scope,
            );

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Requesting app-only access token');

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
                $warning = 'Error occured whilst getting an app-only access token: ' . $response->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");
                return new \WP_Error('1040', $warning);
            }

            $body = wp_remote_retrieve_body($response);

            // Validate the access token and return it
            $access_token = json_decode($body);
            $access_token = self::validate_access_token($access_token);

            if (is_wp_error($access_token)) {
                $warning = 'App-only access token is not valid: ' . $access_token->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");
                return new \WP_Error($access_token->get_error_code(), $warning);
            }

            // Store the new token as a site option with the shorter ttl of both auth and token
            $access_token->audience = $application_id;
            $access_token->expiry = time() + intval($access_token->expires_in);
            $access_token->scope = $scope;
            $access_token->roles = self::get_application_roles($access_token->access_token);

            if (!empty($role)) {

                if (!self::token_has_role($access_token, $role)) {
                    return new \WP_Error('1041', sprintf('Access token with application level permissions for scope %s does not has the role requested (%s)', $scope, $role));
                }
            }

            $cached_access_tokens_json = get_option(self::SITE_META_ACCESS_TOKEN, '[]');
            $cached_access_tokens = json_decode($cached_access_tokens_json);

            if (!is_array($cached_access_tokens)) {
                $cached_access_tokens = array();
            }

            $cached_access_tokens[] = $access_token;

            update_option(
                self::SITE_META_ACCESS_TOKEN,
                json_encode($cached_access_tokens)
            );

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Successfully obtained a valid app-only access token');

            return $access_token;
        }

        /**
         * Tries to retrieve the application roles for the token provided.
         * 
         * @since   18.0
         * 
         * @param   mixed   $token  Access token 
         * @return  array   Array with applications roles
         */
        public static function get_application_roles($token)
        {

            $roles = array();

            if (empty($token) || !is_string($token)) {
                return $roles;
            }

            $token_arr = explode('.', $token);

            if (sizeof($token_arr) != 3) {
                return $roles;
            }

            $claims = \json_decode(WordPress_Helpers::base64_url_decode($token_arr[1]));
            $json_error = json_last_error();

            if ($json_error == JSON_ERROR_NONE) {

                if (property_exists($claims, 'roles')) {
                    return $claims->roles;
                }
            }

            return $roles;
        }

        /**
         * Simple helper to search the roles of an access token with application level permissions
         * for the role provided.
         * 
         * @since   18.0
         * 
         * @param   mixed $access_token 
         * @param   mixed $role 
         * @return  bool 
         */
        public static function token_has_role($access_token, $role)
        {
            if (empty($access_token)) {
                return false;
            }

            if (!is_wp_error($access_token) && !property_exists($access_token, 'roles')) {
                $access_token->roles = self::get_application_roles($access_token->access_token);
            }

            foreach ($access_token->roles as $key => $access_token_role) {

                if (false !== WordPress_Helpers::stripos($access_token_role, $role)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Simple helper to delete all access and refresh tokens.
         * 
         * @param mixed $wp_usr The user requesting to delete the tokens.
         * @return int|bool The result of $wpdb->query
         */
        public static function delete_tokens($wp_usr)
        {

            if (false === Permissions_Helpers::user_is_admin($wp_usr)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to delete all Microsoft access and refresh tokens');
                return false;
            }

            global $wpdb;

            delete_option(Access_Token_Service::SITE_META_ACCESS_TOKEN);

            $query_result = $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $wpdb->usermeta
                        WHERE meta_key like %s 
                        OR meta_key like %s",
                    'wpo_access%',
                    'wpo_refresh%'
                )
            );

            return $query_result;
        }

        /**
         * Helper to check if the current user has delegated permissions to access Microsoft 
         * Service such as Microsoft Graph.
         * 
         * @since   18.0
         * 
         * @param   mixed   $wp_usr_id 
         * @return  bool 
         */
        public static function user_has_delegated_access($wp_usr_id)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            if (!empty($request->get_item('refresh_token'))) {
                return true;
            } elseif (!empty($request->get_item('access_tokens'))) {
                return true;
            } elseif (!empty($request->get_item('code'))) {
                return true;
            } elseif (!empty(get_user_meta(
                $wp_usr_id,
                self::USR_META_REFRESH_TOKEN,
                true
            ))) {
                return true;
            } elseif (!empty(get_user_meta(
                $wp_usr_id,
                self::USR_META_WPO365_AUTH_CODE,
                true
            ))) {
                return true;
            } elseif (!empty(get_user_meta(
                $wp_usr_id,
                self::USR_META_ACCESS_TOKEN,
                true
            ))) {
                return false;
            }
            return false;
        }

        /**
         * Helper to validate an oauth access token
         *
         * @since   5.0
         *
         * @param   object  access token as PHP std object
         * @return  mixed(stdClass|WP_Error) Access token as standard object or WP_Error when invalid   
         * @todo    make by reference instead by value
         */
        public static function validate_access_token($access_token_obj)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (isset($access_token_obj->error)) {

                return new \WP_Error(implode(',', $access_token_obj->error_codes), $access_token_obj->error_description);
            }

            if (
                empty($access_token_obj)
                || $access_token_obj === false
                || !isset($access_token_obj->access_token)
                || !isset($access_token_obj->expires_in)
                || !isset($access_token_obj->token_type)
                || strtolower($access_token_obj->token_type) != 'bearer'
            ) {

                Log_Service::write_log('DEBUG', $access_token_obj);
                return new \WP_Error('0', 'Unknown error occurred');
            }

            return $access_token_obj;
        }

        /**
         * Tries and find a refresh token for an AAD resource stored as user meta in the form "expiration,token"
         * In case an expired token is found it will be deleted
         *
         * @since   5.2
         * 
         * @param   string  $resource   Name for the resource key used to store that resource in the site options
         * @return  (stdClass|NULL)  Refresh token or an empty string if not found or when expired
         */
        private static function get_refresh_token()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $refresh_token = $request->get_item('refresh_token');
            $wp_usr_id = get_current_user_id(); // 0 if user is not (yet) logged in

            if (empty($refresh_token)) {
                $cached_refresh_token_json = get_user_meta(
                    get_current_user_id(),
                    self::USR_META_REFRESH_TOKEN,
                    true
                );

                if (empty($cached_refresh_token_json)) {
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Could not find a refresh token for user with ID ' . $wp_usr_id);
                    return null;
                }

                $refresh_token = json_decode($cached_refresh_token_json);

                if (empty($refresh_token)) {
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Could not parse cached refresh token for user with ID ' . $wp_usr_id);
                    return null;
                }
            }

            if (!\property_exists($refresh_token, 'refresh_token')) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an invalid refresh token');
                delete_user_meta(get_current_user_id(), self::USR_META_REFRESH_TOKEN);
                return null;
            }

            if (isset($refresh_token->expiry) && intval($refresh_token->expiry) < time()) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted an expired refresh token');
                delete_user_meta(get_current_user_id(), self::USR_META_REFRESH_TOKEN);
                return null;
            }

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Found a previously saved valid refresh token');
            return $refresh_token;
        }

        /**
         * Helper method to persist a refresh token as user meta.
         * 
         * @since 5.1
         * 
         * @param stdClass $access_token Access token as standard object (from json)
         * @return void
         */
        public static function save_refresh_token($refresh_token)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $wp_usr_id = get_current_user_id();

            if (empty($wp_usr_id)) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Cannot save refresh token for user that is not logged in.');
                return;
            }

            update_user_meta(
                $wp_usr_id,
                self::USR_META_REFRESH_TOKEN,
                json_encode($refresh_token)
            );

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Successfully saved refresh token');
        }

        /**
         * Tries and find an authorization code stored as user meta
         * In case an expired token is found it will be deleted
         * 
         * @since 5.2
         * 
         * @return (stdClass|NULL)
         */
        private static function get_authorization_code()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $authorization_code = $request->get_item('code');

            if (!empty($authorization_code)) {
                $request->remove_item('code');
                return $authorization_code;
            }

            $wp_usr_id = get_current_user_id(); // 0 if user is not (yet) logged in

            $cached_authorization_code = get_user_meta(
                $wp_usr_id,
                self::USR_META_WPO365_AUTH_CODE,
                true
            );

            if (empty($cached_authorization_code)) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Could not find an authorization code for user with ID ' . $wp_usr_id);
                return null;
            }

            // Authorization code can only be used once
            delete_user_meta($wp_usr_id, self::USR_META_WPO365_AUTH_CODE);
            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Found a previously saved authorization code');
            return $cached_authorization_code;
        }

        /**
         * Helper method to persist a refresh token as user meta.
         * 
         * @since 5.1
         * 
         * @param stdClass $access_token Access token as standard object (from json)
         * @return void
         */
        public static function save_authorization_code($authorization_code)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $wp_usr_id = get_current_user_id();

            if (empty($wp_usr_id)) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Cannot save authorization code for user that is not logged in.');
                return;
            }

            if (!empty($wp_usr_id)) {
                update_user_meta(
                    $wp_usr_id,
                    self::USR_META_WPO365_AUTH_CODE,
                    $authorization_code
                );
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Successfully saved authorization code');
            }
        }
    }
}

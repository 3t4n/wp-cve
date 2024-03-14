<?php

namespace Wpo\Services;

use WP_Error;

use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Access_Token_Service;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\User_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Graph_Service')) {

    class Graph_Service
    {

        const REST_API = "https://graph.microsoft.com/";
        const GRAPH_VERSION = "v1.0";
        const GRAPH_VERSION_BETA = "beta";

        /**
         * Connects to Microsoft Graph REST api to get retrieve data on the basis of the query presented
         *
         * @since 0.1
         *
         * @param   string  $query  query part of the Graph query e.g. '/me/photo/$'
         * @param   string  $method HTTP Method (default GET)
         * @param   boolean $binary Get binary data e.g. when getting user profile image
         * @param   array   $headers
         * @param   boolean $use_delegated
         * @param   boolean $prefetch Is deprecated since 11.0 when the method will figure out what it can use to obtain a delegated token
         * @param   string  $post_fields
         * @param   string  $scope
         * @return  array|WP_Error JSON string as associative array or false
         *
         */
        public static function fetch($query, $method = 'GET', $binary = false, $headers = array(), $use_delegated = false, $prefetch = false, $post_fields = "", $scope = 'https://graph.microsoft.com/user.read')
        {
            Log_Service::write_log('DEBUG', sprintf('%s -> Requesting data from Microsoft Graph using query "%s" and scope "%s"', __METHOD__, $query, $scope));

            /**
             * @since 10.0 it is possible to request data from Microsoft Graph
             * using an app-only context.
             */

            $use_b2c = Options_Service::get_global_boolean_var('use_b2c');
            $use_saml = Options_Service::get_global_boolean_var('use_saml');
            $multi_tenanted = Options_Service::get_global_boolean_var('multi_tenanted');

            if ($multi_tenanted) {
                $request_service = Request_Service::get_instance();
                $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
                $user_is_logging_in = !empty($request->get_item('id_token')) || !empty($request->get_item('encoded_id_token'));

                if ($user_is_logging_in) {
                    $wp_usr_id = \get_current_user_id();
                    $home_tenant_id = Options_Service::get_aad_option('tenant_id');
                    $user_tenant_id = User_Service::try_get_user_tenant_id($wp_usr_id);

                    if (strcasecmp($home_tenant_id, $user_tenant_id) !== 0) {
                        $use_delegated = true;
                    }
                }
            }

            /**
             * @since 24.3 To read the application secret expiration date MsGraphMailer may also fetch from Microsoft Graph.
             *             When MsGraphMailer fetches from Microsoft Graph using this generic class it will be always using 
             *             application permissions because a delegated scenario is not possible. Therefore use_app_only will
             *             also be set as true.
             */

            $use_mail_config = !class_exists('\Wpo\Login') && class_exists('\Wpo\MsGraphMailer');
            $use_app_only = Options_Service::get_global_boolean_var('use_app_only_token') || ($use_mail_config);
            $no_sso = Options_Service::get_global_boolean_var('no_sso');
            $uses_me = false !== WordPress_Helpers::stripos($query, '/me/');
            $user_has_delegated_access = Access_Token_Service::user_has_delegated_access(get_current_user_id());

            if (!$user_has_delegated_access && !$use_app_only) {
                $warning = 'Could not retrieve an access token for (scope|query) ' . $scope . '|' . $query . '.  Error details: The current user is either not logged in or did not sign in with Microsoft and the use of application-level API permissions is not configured).';
            }

            if ($use_delegated && $no_sso) {
                $warning = 'Could not retrieve an access token for (scope|query) ' . $scope . '|' . $query . '.  Error details: The plugin cannot retrieve a token with delegated API permissions when SSO has been disabled (request application-level permissions instead).';
            }

            if ($uses_me && $no_sso) {
                $warning = 'Could not retrieve an access token for (scope|query) ' . $scope . '|' . $query . '.  Error details: The plugin cannot execute a query for the /me endpoint when SSO has been disabled (request application-level permissions instead).';
            }

            if (!empty($warning)) {
                Log_Service::write_log('WARN', __METHOD__ . ' -> ' . $warning);
                return new \WP_Error('GraphFetchError', $warning);
            }

            if ($use_delegated) {
                $access_token = Access_Token_Service::get_access_token($scope);
            } elseif ($use_app_only) {
                $scope_host = false !== WordPress_Helpers::stripos($scope, 'https://') ? parse_url($scope, PHP_URL_HOST) : 'graph.microsoft.com';
                $app_only_scope = "https://$scope_host/.default";
                $scope_segments = explode('/', $scope);
                $role = array_pop($scope_segments);

                if (strcasecmp($role, 'User.Read') === 0) {
                    $role = 'User.Read.All';
                }

                $access_token = Access_Token_Service::get_app_only_access_token($app_only_scope, $role, $use_mail_config);

                if (is_wp_error($access_token) && $user_has_delegated_access) {
                    Log_Service::write_log('WARN', sprintf('%s -> not application role found to match scope %s therefore falling back to delegated permissions', __METHOD__, $scope));
                    $access_token = Access_Token_Service::get_access_token($scope);
                }
            } elseif ($use_b2c || $use_saml) {
                $access_token = new WP_Error('TokenError', 'Could not retrieve a token with application-level permissions which is required when using "Azure AD B2C" or "SAML 2.0" based Single Sign-on and the administrator has configured features that request data from Microsoft Graph.');
            } elseif ($no_sso) {
                $access_token = new WP_Error('TokenError', 'Could not retrieve a token with application-level permissions which is required when the Single Sign-on feature has been disabled and the administrator has configured features that request data from Microsoft Graph.');
            } else {
                $access_token = Access_Token_Service::get_access_token($scope);
            }

            if (is_wp_error($access_token)) {
                $warning = 'Could not retrieve an access token for (scope|query) ' . $scope . '|' . $query . '.  Error details: ' . $access_token->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . ' -> ' . $warning);
                return new \WP_Error($access_token->get_error_code(), $warning);
            }

            $_headers = array();

            foreach ($headers as $header) {
                $splitted = explode(':', $header);

                if (count($splitted) == 2) {
                    $_headers[WordPress_Helpers::trim($splitted[0])] = WordPress_Helpers::trim($splitted[1]);
                }
            }

            $_headers['Authorization'] = sprintf('Bearer %s', $access_token->access_token);
            $_headers['Expect'] = '';

            /**
             * @since 13.0
             */

            if (WordPress_Helpers::stripos($query, '$count=true') !== false) {
                $_headers['ConsistencyLevel'] = 'eventual';
            }

            $graph_version = Options_Service::get_global_string_var('graph_version');
            $graph_version = empty($graph_version) || $graph_version == 'current'
                ? self::GRAPH_VERSION
                : ($graph_version == 'beta'
                    ? self::GRAPH_VERSION_BETA
                    : self::GRAPH_VERSION
                );

            $url = self::REST_API . $graph_version . $query;

            $skip_ssl_verify = !Options_Service::get_global_boolean_var('skip_host_verification');

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Fetching from ' . $url);

            if (WordPress_Helpers::stripos($method, 'GET') === 0) {
                $response = wp_remote_get(
                    $url,
                    array(
                        'method' => 'GET',
                        'timeout' => 15,
                        'headers' => $_headers,
                        'sslverify' => $skip_ssl_verify,
                    )
                );
            } elseif (WordPress_Helpers::stripos($method, 'POST') === 0) {
                $response = wp_remote_post(
                    $url,
                    array(
                        'body' => $post_fields,
                        'timeout' => 15,
                        'headers' => $_headers,
                        'sslverify' => $skip_ssl_verify,
                    )
                );
            } elseif (WordPress_Helpers::stripos($method, 'PATCH') === 0) {
                $response = wp_remote_post(
                    $url,
                    array(
                        'body' => $post_fields,
                        'method' => 'PATCH',
                        'timeout' => 15,
                        'headers' => $_headers,
                        'sslverify' => $skip_ssl_verify,
                    )
                );
            } else {
                return new \WP_Error('NotImplementedException', 'Error occured whilst fetching from Microsoft Graph: Method ' . $method . ' not implemented');
            }

            if (is_wp_error($response)) {
                $warning = 'Error occured whilst fetching from Microsoft Graph: ' . $response->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");
                return new \WP_Error('1040', $warning);
            }

            $body = wp_remote_retrieve_body($response);

            if (!$binary) {
                $body = json_decode($body, true);
            }

            $http_code = wp_remote_retrieve_response_code($response);

            return array('payload' => $body, 'response_code' => $http_code);
        }

        /**
         * Quick test to see if the result fetched from Microsoft Graph is valid.
         * 
         * @since 7.17
         * 
         * @param $fetch_result mixed(array|wp_error)
         * 
         * @return bool True if valid otherwise false
         */
        public static function is_fetch_result_ok($fetch_result, $message, $level = 'ERROR', $on_error = null)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (is_wp_error($fetch_result)) {
                $log_message = $message . ' [Error: ' . $fetch_result->get_error_message() . ']';
                Log_Service::write_log($level, __METHOD__ . ' -> ' . $log_message);

                if (!empty($on_error)) {
                    $on_error($log_message);
                }

                return false;
            }

            if ($fetch_result['response_code'] < 200 || $fetch_result['response_code'] > 299) {

                if (is_array($fetch_result) && isset($fetch_result['payload']) && isset($fetch_result['payload']['error']) && isset($fetch_result['payload']['error']['message'])) {
                    $log_message = $message . ' [Error: ' . $fetch_result['payload']['error']['message'] . ']';
                    Log_Service::write_log($level, __METHOD__ . ' -> ' . $log_message);

                    if (!empty($on_error)) {
                        $on_error($log_message);
                    }

                    return false;
                }

                Log_Service::write_log($level, __METHOD__ . ' -> ' . $message . ' [See log for details]');
                Log_Service::write_log('WARN', $fetch_result);
                return false;
            }

            return true;
        }
    }
}

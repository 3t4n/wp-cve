<?php

namespace Wpo\Services;

use \Wpo\Core\Permissions_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;

use \Wpo\Mail\Mail_Authorization_Helpers;
use \Wpo\Mail\Mailer;

use \Wpo\Services\Access_Token_Service;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Saml2_Service;

if (!class_exists('\Wpo\Services\Ajax_Service')) {

    class Ajax_Service
    {

        /**
         * Gets the tokencache with all available bearer tokens
         *
         * @since 5.0
         *
         * @return void
         */
        public static function get_tokencache()
        {
            if (false === Options_Service::get_global_boolean_var('enable_token_service')) {
                wp_die();
            }

            // Verify AJAX request
            $current_user = self::verify_ajax_request('to get the tokencache for a user');

            self::verify_POSTed_data(array('action', 'scope')); // -> wp_die()

            $access_token = Access_Token_Service::get_access_token(esc_url_raw($_POST['scope']));

            if (is_wp_error($access_token)) {
                self::AJAX_response('NOK', $access_token->get_error_code(), $access_token->get_error_message(), null);
            }

            $result = new \stdClass();
            $result->accessToken = $access_token->access_token;

            if (property_exists($access_token, 'expiry')) {
                $result->expiry = $access_token->expiry;
            }

            self::AJAX_response('OK', '', '', json_encode($result));
        }

        /**
         * Delete all access and refresh tokens.
         *
         * @since xxx
         */
        public static function delete_tokens()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to delete access and refresh tokens');

            if (false === Access_Token_Service::delete_tokens($current_user)) {
                self::AJAX_response('NOK', '', '', null);
            } else {
                delete_site_option('wpo365_msft_key');
                self::AJAX_response('OK', '', '', null);
            }
        }

        /**
         * Gets the tokencache with all available bearer tokens
         *
         * @since 6.0
         *
         * @return void
         */
        public static function get_settings()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to get the wpo365-login settings');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to get wpo365_options from AJAX service');
                wp_die();
            }

            $camel_case_options = Options_Service::get_options();

            if (array_key_exists('curlProxy', $camel_case_options)) {
                unset($camel_case_options['curlProxy']);
            }

            if (array_key_exists('graphAllowGetToken', $camel_case_options)) {

                if (!array_key_exists('graphAllowTokenRetrieval', $camel_case_options)) {
                    $camel_case_options['graphAllowTokenRetrieval'] = $camel_case_options['graphAllowGetToken'];
                }

                unset($camel_case_options['graphAllowGetToken']);
            }

            $options_as_json = json_encode($camel_case_options);
            self::AJAX_response('OK', '', '', $options_as_json);
        }

        /**
         * Gets the tokencache with all available bearer tokens
         *
         * @since 9.6
         *
         * @return void
         */
        public static function get_self_test_results()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to get the wpo365-login self-test results');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to get self-test results from AJAX service');
                wp_die();
            }

            $self_test_results = Wpmu_Helpers::mu_get_transient('wpo365_self_test_results');

            if (!empty($self_test_results)) {
                self::AJAX_response('OK', '', '', json_encode($self_test_results));
            } else {
                self::AJAX_response('OK', '', '', json_encode(array()));
            }
        }

        /**
         * Gets the tokencache with all available bearer tokens
         *
         * @since 6.0
         *
         * @return void
         */
        public static function update_settings()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to update the wpo365-login settings');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to get wpo365_options from AJAX service');
                wp_die();
            }

            self::verify_POSTed_data(array('settings')); // -> wp_die()
            $reset = isset($_POST['reset']) && $_POST['reset'] == 'true' ? true : false;
            $updated = Options_Service::update_options($_POST['settings'], false, $reset);
            self::AJAX_response(true === $updated ? 'OK' : 'NOK', '', '', null);
        }

        /**
         * Gets the tokencache with all available bearer tokens
         *
         * @since 11.18
         *
         * @return void
         */
        public static function delete_settings()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to delete the wpo365-login settings');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to delete wpo365_options from AJAX service');
                wp_die();
            }

            $deleted = Options_Service::delete_options();
            $camel_case_options = Options_Service::get_options();

            self::AJAX_response(true === $deleted ? 'OK' : 'NOK', '', '', ($deleted ? json_encode($camel_case_options) : null));
        }

        /**
         * Gets the debug log
         *
         * @since 7.11
         *
         * @return void
         */
        public static function get_log()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to get the wpo365-login debug log');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to get wpo365_log from AJAX service');
                wp_die();
            }

            $log = Wpmu_Helpers::mu_get_transient('wpo365_debug_log');

            if (empty($log)) {
                $log = array();
            }

            $log = array_reverse($log);
            self::AJAX_response('OK', '', '', json_encode($log));
        }

        /**
         * Used to proxy a request from the client-side to another O365 service e.g. yammer 
         * to circumvent CORS issues.
         *
         * @since 10.0
         *
         * @return void
         */
        public static function cors_proxy()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to proxy a request');

            self::verify_POSTed_data(array('url', 'method', 'bearer', 'accept', 'binary')); // -> wp_die()
            $url = \esc_url_raw($_POST['url']);
            $method = sanitize_text_field($_POST['method']);
            $headers = array(
                'Authorization' => sprintf('Bearer %s', $_POST['bearer']),
                'Expect' => '',
            );
            $binary = filter_var($_POST['binary'], FILTER_VALIDATE_BOOLEAN);

            $skip_ssl_verify = !Options_Service::get_global_boolean_var('skip_host_verification');

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Fetching from ' . $url);

            if (WordPress_Helpers::stripos($method, 'GET') === 0) {
                $response = wp_remote_get(
                    $url,
                    array(
                        'method' => 'GET',
                        'timeout' => 15,
                        'headers' => $headers,
                        'sslverify' => $skip_ssl_verify,
                    )
                );
            } elseif (WordPress_Helpers::stripos($method, 'POST') === 0 && array_key_exists('post_fields', $_POST)) {
                $response = wp_remote_post(
                    $url,
                    array(
                        'body' => $_POST['post_fields'],
                        'timeout' => 15,
                        'headers' => $headers,
                        'sslverify' => $skip_ssl_verify,
                    )
                );
            } else {
                return new \WP_Error('NotImplementedException', 'Error occured whilst fetching from ' . $url . ':  Method ' . $method . ' not implemented');
            }

            if (is_wp_error($response)) {
                $warning = 'Error occured whilst fetching from ' . $url . ': ' . $response->get_error_message();
                Log_Service::write_log('WARN', __METHOD__ . " -> $warning");
                self::AJAX_response('NOK', '', $warning, null);
                // -> die()
            }

            $body = wp_remote_retrieve_body($response);

            if ($binary) {
                self::AJAX_response('OK', '', '', base64_encode($body));
                // -> die()
            }

            json_decode($body);
            $json_error = json_last_error();

            if ($json_error == JSON_ERROR_NONE) {
                self::AJAX_response('OK', '', '', $body);
            }

            self::AJAX_response('NOK', '', $json_error, null);
        }

        /**
         * Send an email to test the Microsoft Graph Mailer configuration.
         *
         * @since 11.7
         *
         * @return void
         */
        public static function send_test_mail()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to send a test mail to a user');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to send test mail to a user from AJAX service');
                wp_die();
            }

            self::verify_POSTed_data(array('to', 'cc', 'bcc', 'attachment')); // -> wp_die()
            $attachment = filter_var($_POST['attachment'], FILTER_VALIDATE_BOOLEAN);
            $sent = Mailer::send_test_mail($_POST['to'], $_POST['cc'], $_POST['bcc'], $attachment);

            if (!$sent) {
                $request_service = Request_Service::get_instance();
                $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
                $mail_error = $request->get_item('mail_error');

                if (empty($mail_error)) {
                    $mail_error = 'Check log for errors';
                }

                self::AJAX_response('NOK', '', $mail_error, null);
            }

            self::AJAX_response('OK', '', '', null);
        }

        /**
         * Gets the URL where to redirect the current user to authorize sending email using his account (delegated).
         *
         * @since 19.0
         *
         * @return void
         */
        public static function get_mail_authorization_url()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to authorize sending email using Microsoft Graph');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to authorize sending email using Microsoft Graph');
                wp_die();
            }

            $auth_url = Mail_Authorization_Helpers::get_mail_authorization_url();
            $is_error = is_wp_error($auth_url);
            $error_message = $is_error ? $auth_url->get_error_message() : null;
            self::AJAX_response($is_error ? 'NOK' : 'OK', '', $error_message, $is_error ? null : $auth_url);
        }

        /**
         * Gets the URL where to redirect the current user to authorize sending email using his account (delegated).
         *
         * @since 19.0
         *
         * @return void
         */
        public static function get_mail_auth_configuration()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to retrieve the current mail configuration');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to retrieve the current mail configuration');
                wp_die();
            }

            self::verify_POSTed_data(array('deleteDelegated', 'deleteAppOnly')); // -> wp_die()
            $delete_delegated = filter_var($_POST['deleteDelegated'], FILTER_VALIDATE_BOOLEAN);
            $delete_app_only =  filter_var($_POST['deleteAppOnly'], FILTER_VALIDATE_BOOLEAN);
            $mail_config = Mail_Authorization_Helpers::get_mail_auth_configuration($delete_delegated, $delete_app_only);
            self::AJAX_response('OK', '', '', $mail_config);
        }

        /**
         * Will copy the App principal info from the mail-integration-365 plugin to initialize the WPO365
         * mail configuration. If found, the AJAX response will return OK otherwise NOK.
         *
         * @since 22.3
         *
         * @return void
         */
        public static function try_migrate_mail_app_principal_info()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to migrate the App principal info used for sending mail');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to migrate the App principal info used for sending mail');
                wp_die();
            }

            self::verify_POSTed_data(array('copyMode')); // -> wp_die()
            $result = Mail_Authorization_Helpers::try_migrate_mail_app_principal_info($_POST['copyMode'] == 'copyDelete');

            if (is_wp_error($result)) {
                self::AJAX_response('NOK', $result->get_error_code(), $result->get_error_message(), null);
            } else {
                self::AJAX_response('OK', '', '', null);
            }
        }

        /**
         * Tries to read the SAML IdP configuration from the App Federation Metadata URL
         *
         * @since 24.4
         *
         * @return void
         */
        public static function import_idp_meta()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to import the SAML 2.0 IdP metadata');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to import the SAML 2.0 IdP metadata');
                wp_die();
            }

            $result = Saml2_Service::import_idp_meta();

            if (is_wp_error($result)) {
                self::AJAX_response('NOK', '', $result->get_error_message(), null);
            }


            $camel_case_options = Options_Service::get_options();
            self::AJAX_response('OK', '', '', json_encode($camel_case_options));
        }

        /**
         * Tries to read the SAML IdP configuration from the App Federation Metadata URL
         *
         * @since 24.4
         *
         * @return void
         */
        public static function export_sp_meta()
        {
            // Verify AJAX request
            $current_user = self::verify_ajax_request('to export the SAML 2.0 service provider metadata');

            if (false === Permissions_Helpers::user_is_admin($current_user)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> User has no permission to export the SAML 2.0 service provider metadata');
                wp_die();
            }

            $result = Saml2_Service::export_sp_meta();

            if (is_wp_error($result)) {
                self::AJAX_response('NOK', '', $result->get_error_message(), null);
            }

            $camel_case_options = Options_Service::get_options();

            $result = array(
                'xml' => base64_encode($result),
                'settings' => json_encode($camel_case_options),
            );

            self::AJAX_response('OK', '', '',  $result);
        }

        /**
         * Checks for valid nonce and whether user is logged on and returns WP_User if OK or else
         * writes error response message and return it to requester
         *
         * @since 5.0
         *
         * @param   string      $error_message_fragment used to write a specific error message to the log
         * @return  WP_User if verified or else error response is returned to requester
         */
        public static function verify_ajax_request($error_message_fragment)
        {
            $error_message = '';

            if (!is_user_logged_in())
                $error_message = 'Attempt ' . $error_message_fragment . ' by a user that is not logged on';

            if (
                Options_Service::get_global_boolean_var('enable_nonce_check')
                && (!isset($_POST['nonce'])
                    || !wp_verify_nonce($_POST['nonce'], 'wpo365_fx_nonce'))
            )
                $error_message = 'Request ' . $error_message_fragment . ' has been tampered with (invalid nonce)';

            if (strlen($error_message) > 0) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> ' . $error_message);

                $response = array('status' => 'NOK', 'message' => $error_message, 'result' => array());
                wp_send_json($response);
                wp_die();
            }

            return wp_get_current_user();
        }

        /**
         * Stops the execution of the program flow when a key is not found in the the global $_POST
         * variable and returns a given error message
         *
         * @since 5.0
         *
         * @param   array   $keys array of keys to search for
         * @return void
         */
        public static function verify_POSTed_data($keys, $sanitize = true)
        {

            foreach ($keys as $key) {

                if (!array_key_exists($key, $_POST)) {
                    self::AJAX_response('NOK', '1000', 'Incomplete data posted to complete request: ' . implode(', ', $keys), array());
                }

                if ($sanitize) {
                    $_POST[$key] = sanitize_text_field($_POST[$key]);
                }
            }
        }

        /**
         * Helper method to standardize response returned from a Pintra AJAX request
         *
         * @since 5.0
         *
         * @param   string  $status OK or NOK
         * @param   string  $message customer message returned to requester
         * @param   mixed   $result associative array that is parsed as JSON and returned
         * @return void
         */
        public static function AJAX_response($status, $error_codes, $message, $result)
        {
            Log_Service::write_log('DEBUG', __METHOD__ . " -> Sending an AJAX response with status $status and message $message");
            wp_send_json(array('status' => $status, 'error_codes' => $error_codes, 'message' => $message, 'result' => $result));
            wp_die();
        }
    }
}

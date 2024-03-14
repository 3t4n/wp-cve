<?php

namespace Wpo\Services;

use Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Password_Credentials_Service')) {

    class Password_Credentials_Service
    {
        /**
         * Checks for each registered application whether its secret will soon expire.
         * 
         * @since 24.3
         * 
         * @return void 
         */
        public static function check_password_credentials_expiration()
        {
            // Check if this test should be skipped

            if (Options_Service::get_global_boolean_var('skip_password_credentials_expired_check')) {
                return;
            }

            // Check for any secret

            $secrets = array();

            if (!empty($id = Options_Service::get_aad_option('application_id'))) {
                $secrets[] = array(
                    'id' => $id,
                    'secret' => Options_Service::get_aad_option('application_secret'),
                );
            }

            if (!empty($id = Options_Service::get_aad_option('app_only_application_id'))) {
                $secrets[] = array(
                    'id' => $id,
                    'secret' => Options_Service::get_aad_option('app_only_application_secret'),
                );
            }

            if (!empty($id = Options_Service::get_aad_option('mail_application_id'))) {
                $secrets[] = array(
                    'id' => $id,
                    'secret' => Options_Service::get_aad_option('mail_application_secret'),
                );
            }

            $flagged_app_ids = array();

            foreach ($secrets as $secret) {

                if (in_array($secret['id'], $flagged_app_ids)) {
                    continue;
                }

                $query = sprintf('/applications?$filter=appId eq \'%s\'&$select=passwordCredentials', $secret['id']);

                $result = Graph_Service::fetch($query, 'GET', 'false', array(), false, false, '', 'Application.Read.All');

                if (\is_wp_error($result)) {
                    $warning = $result->get_error_message();

                    // Bail out when WPO365 does not have app-only permissions
                    if (WordPress_Helpers::stripos($warning, 'use of application-level API permissions is not configured')) {
                        Log_Service::write_log('WARN', sprintf('%s -> WPO365 cannot check if secrets for your App registration(s) in Azure Active Directory will expire soon because you did not configure application-level access (on the Integration configuration page). See https://www.wpo365.com/article/client-secret-expiration-notification/ for details.', __METHOD__));
                        break;
                    }

                    if (WordPress_Helpers::stripos($warning, 'does not has the role requested')) {
                        $app_only_app_id = Options_Service::get_aad_option('app_only_application_id');
                        Log_Service::write_log('WARN', sprintf('%s -> WPO365 cannot check if secrets for your App registration(s) in Azure Active Directory will expire soon because you did not configure application permissions for Application.Read.All (in Azure AD on the API Permissions page for the App registration with ID %s). See https://www.wpo365.com/article/client-secret-expiration-notification/ for details.', __METHOD__, $app_only_app_id));
                        break;
                    }

                    return;
                }

                if ($result['response_code'] < 200 || $result['response_code'] > 299) {
                    $json_encoded_result = \json_encode($result);
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Could not fetch data from Microsoft Graph [' . $json_encoded_result . '].');
                    return;
                }

                if (isset($result['payload'])) {
                    $payload = json_decode($result['payload'], true);

                    if (
                        is_array($payload['value'])
                        && sizeof($payload['value']) === 1
                        && isset($payload['value'][0]['passwordCredentials'])
                    ) {
                        $credentials = $payload['value'][0]['passwordCredentials'];

                        foreach ($credentials as $credential) {

                            if (isset($credential['hint']) && false !== WordPress_Helpers::stripos($secret['secret'], $credential['hint']) && !empty($credential['endDateTime'])) {
                                $end_date_time = strtotime($credential['endDateTime']);

                                if (($end_date_time - 2592000) < time()) {
                                    $date_as_string = date('d F Y', $end_date_time);
                                    Log_Service::write_log('ERROR', sprintf(
                                        '%s -> The Application (client) secret (hint: %s***) for the Azure AD App registration with ID %s will expire on %s. After this date WPO365 may no longer work as expected and - for example - you may not be able to sign in with Microsoft anymore. Please update this secret as soon as possible!',
                                        __METHOD__,
                                        $credential['hint'],
                                        $secret['id'],
                                        $date_as_string
                                    ));
                                    self::send_secret_expired_notification($secret['id'], $credential['hint'], $date_as_string);
                                }

                                $flagged_app_ids[] = $secret['id'];
                            }
                        }
                    }
                }
            }
        }

        /**
         * Ensures that the WP Cron job to check for each registered application whether its secret will epxire soon.
         * 
         * @since 24.3
         * 
         * @return void 
         */
        public static function ensure_check_password_credentials_expiration()
        {
            $last_time = get_site_transient('wpo365_secrets_expiration_hook_ensured');

            if (empty($last_time) && !wp_next_scheduled('wpo_check_password_credentials_expiration')) {
                wp_schedule_event(strtotime('12:00:00'), 'daily', 'wpo_check_password_credentials_expiration');
                set_site_transient('wpo365_secrets_expiration_hook_ensured', array('last_checked' => time()));
            }
        }

        /**
         * Helper to send an email notification to the administration email address.
         * 
         * @since 24.3
         * 
         * @param mixed $id 
         * @param mixed $hint 
         * @param mixed $date_as_string 
         * 
         * @return void 
         */
        private static function send_secret_expired_notification($id, $hint, $date_as_string)
        {
            if (empty($admin_email = get_option('admin_email'))) {
                return;
            }

            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $subject = sprintf('[%s] One ore more App Secrets are about to expire for your site', $blogname);
            $message = sprintf(
                '<p>Dear website administrator</p>
                <p>The <strong>Application (client) secret</strong> (hint: <strong>%s***</strong>) 
                for the <em>Azure AD App registration</em> with ID <strong>%s</strong> will expire 
                on <strong>%s</strong>. After this date, WPO365 may no longer work as expected and 
                - for example - your users may not be able to sign in with Microsoft anymore.</p>
                <p>Please update this secret as soon as possible!</p>
                <p>Marco van Wieren, Downloads by van Wieren</p>
                <p><strong>WPO365</strong> - Connecting WordPress and Microsoft Office 365 / Azure AD</p>
                <p>Zurich, Switzerland</p>
                <p>l https://www.linkedin.com/company/downloads-by-van-wieren</p>
                <p>w https://www.wpo365.com</p>
                <p>e support@wpo365.com</p>',
                $hint,
                $id,
                $date_as_string
            );

            @wp_mail(
                $admin_email,
                $subject,
                $message,
                array('Content-Type: text/html')
            );
        }
    }
}

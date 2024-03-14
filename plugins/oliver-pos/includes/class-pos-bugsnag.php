<?php

defined( 'ABSPATH' ) || exit;

/**
 * pos bridge Bugsnag class.
 */

class Pos_Bridge_Bugsnag_Wordpress
{
    private static $OLIVER_POS_COMPOSER_AUTOLOADER = 'vendor/autoload.php';
    private static $OLIVER_POS_PACKAGED_AUTOLOADER = 'oliver-pos-bugsnag/Autoload.php';
    private static $OLIVER_POS_DEFAULT_NOTIFY_SEVERITIES = 'fatal,error,warning,info';

    private $oliver_pos_client;
    private $oliver_pos_apiKey;
    private $oliver_pos_notifySeverities;
    private $oliver_pos_filterFields;

    public function __construct()
    {
        $bugsnag_environment = include('environment.php');
        // Update variables
        $this->oliver_pos_apiKey = $bugsnag_environment['BUGSNAG_API_KEY'];
        $this->oliver_pos_notifySeverities = 'fatal,error,warning,info';
        $this->oliver_pos_filterFields = '';
        // Activate oliver pos bugsnag error monitoring
        $this->oliver_pos_activateBugsnag();

        // Run init actions (loading wp user)
        add_action('init', array($this, 'oliver_pos_initActions'));
    }

    private function oliver_pos_activateBugsnag()
    {
        $oliver_pos_is_load_success = $this->oliver_pos_requireBugsnagPhp();
        if (!$oliver_pos_is_load_success) {
            error_log("Oliver Pos Bugsnag Error: Couldn't activate due to missing Bugsnag library!");
            return;
        }

        $this->oliver_pos_constructBugsnag();
    }

    private function oliver_pos_constructBugsnag()
    {
        // Activate the bugsnag oliver_pos_client
        if (!empty($this->oliver_pos_apiKey)) {
            $this->oliver_pos_client = new Bugsnag_Client($this->oliver_pos_apiKey);

            $this->oliver_pos_client->setReleaseStage($this->oliver_pos_releaseStage())
                ->setErrorReportingLevel($this->oliver_pos_errorReportingLevel())
                ->setFilters($this->oliver_pos_filterFields());

            $this->oliver_pos_client->mergeDeviceData(['runtimeVersions' => ['wordpress' => get_bloginfo('version')]]);

            // Can be useful to see inline errors and traces with xdebug too.
            $oliver_pos_set_error_and_exception_handlers = apply_filters(
                'bugsnag_set_error_and_exception_handlers',
                defined('BUGSNAG_SET_EXCEPTION_HANDLERS') ? BUGSNAG_SET_EXCEPTION_HANDLERS : true
            );

            if ($oliver_pos_set_error_and_exception_handlers === true) {
                // Hook up automatic error handling
                set_error_handler(array($this->oliver_pos_client, 'errorHandler'));
                set_exception_handler(array($this->oliver_pos_client, 'exceptionHandler'));
            }
        }
    }

    private function oliver_pos_requireBugsnagPhp()
    {
        // Bugsnag-php was already loaded by some 3rd-party code, don't need to load it again.
        if (class_exists('Bugsnag_Client')) {
            return true;
        }

        // Try loading bugsnag-php with composer autoloader.
        $oliver_pos_composer_autoloader_path = $this->oliver_pos_relativePath(self::$OLIVER_POS_COMPOSER_AUTOLOADER);

        //C:\wamp\www\wordpress\wp-content\plugins\bugsnag/vendor/autoload.php
        if (file_exists($oliver_pos_composer_autoloader_path)) {
            require_once $oliver_pos_composer_autoloader_path;

            return true;
        }

        // Try loading bugsnag-php from packaged autoloader.
        $oliver_pos_packaged_autoloader_path = $this->oliver_pos_relativePath(self::$OLIVER_POS_PACKAGED_AUTOLOADER);

        if (file_exists($oliver_pos_packaged_autoloader_path)) {
            require_once $oliver_pos_packaged_autoloader_path;

            return true;
        }

        return false;
    }

    private function oliver_pos_relativePath($oliver_pos_path)
    {
        return dirname(__FILE__).'/'.$oliver_pos_path;
    }

    private function oliver_pos_errorReportingLevel()
    {
        $oliver_pos_notifySeverities = empty($this->oliver_pos_notifySeverities) ? self::$OLIVER_POS_DEFAULT_NOTIFY_SEVERITIES : $this->oliver_pos_notifySeverities;
        $oliver_pos_level = 0;

        $oliver_pos_severities = explode(',', $oliver_pos_notifySeverities);
        foreach ($oliver_pos_severities as $oliver_pos_severity) {
            $oliver_pos_level |= Bugsnag_ErrorTypes::getLevelsForSeverity($oliver_pos_severity);
        }

        return $oliver_pos_level;
    }

    private function oliver_pos_filterFields()
    {
        $oliver_pos_filter_fields = $this->oliver_pos_filterFields;

        // Array with empty string will break things.
        if ($oliver_pos_filter_fields === '') {
            return array();
        }

        return array_map('trim', explode("\n", $oliver_pos_filter_fields));
    }

    /**
     * Set Release Stage.
     *
     * @return $oliver_pos_release_stage_filtered Release Stage Filtered.
     */
    private function oliver_pos_releaseStage()
    {
        if (function_exists('wp_get_environment_type')) {
            $oliver_pos_release_stage = wp_get_environment_type(); // Defaults to production when not set.
        } else {
            $oliver_pos_release_stage = defined('WP_ENV') ? WP_ENV : 'production';
        }
        return $oliver_pos_release_stage;
    }

    // Action hooks
    public function oliver_pos_initActions()
    {
        // This should be handled on stage of initializing,
        // not even adding action if init failed.
        //
        // Leaving it here for now.
        if (empty($this->oliver_pos_client)) {
            return;
        }

        // Set the bugsnag user using WordPress admin user if available,
        // set as anonymous otherwise.
        $oliver_pos_user = array();
        $oliver_pos_args = array(
            'role' => 'administrator'
        );
        $oliver_pos_admin = get_users($oliver_pos_args);

        if(!empty($oliver_pos_admin))
        {
            $oliver_pos_user['id'] = $oliver_pos_admin[0]->user_login;
            $oliver_pos_user['email'] = $oliver_pos_admin[0]->user_email;
            $oliver_pos_user['name'] = $oliver_pos_admin[0]->display_name;


        } else {
            $oliver_pos_use_unsafe_spoofable_ip_address_getter = apply_filters('bugsnag_use_unsafe_spoofable_ip_address_getter', true);
            $oliver_pos_user['id'] = $oliver_pos_use_unsafe_spoofable_ip_address_getter ?
                $this->oliver_pos_getClientIpAddressUnsafe() :
                $this->oliver_pos_getClientIpAddress();
            $oliver_pos_user['name'] = 'anonymous';
        }

        $this->oliver_pos_client->setUser($oliver_pos_user);
    }

    // Unsafe: oliver_pos_client can spoof address.

    private function oliver_pos_getClientIpAddressUnsafe()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    // Can not be spoofed, but can show ip of NAT or proxies.
    private function oliver_pos_getClientIpAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

global $Pos_Bridge_bugsnagWordpress;
$Pos_Bridge_bugsnagWordpress = new Pos_Bridge_Bugsnag_Wordpress();
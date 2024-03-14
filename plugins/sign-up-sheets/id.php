<?php

namespace FDSUS;

if (!class_exists('\\' . __NAMESPACE__ . '\Id')):

    class Id
    {

        const PREFIX = 'dlssus';
        const NAME = 'Sign-up Sheets - WordPress Plugin';
        const AUTHOR = 'Fetch Designs';
        const URL = 'https://www.fetchdesigns.com';
        const DEBUG = false; // log detailed debug info to logs
        const DEBUG_DISPLAY = false; // display detailed debug info on screen
        const IS_LOCAL = false;
        CONST FREE_PLUGIN_BASENAME = 'sign-up-sheets/sign-up-sheets.php'; // Use Settings::getCurrentPluginBasename() to get current
        CONST PRO_PLUGIN_BASENAME = 'sign-up-sheets-pro/sign-up-sheets.php'; // Use Settings::getCurrentPluginBasename() to get current

        /**
         * @var int
         */
        public static $lastMemory = 0;

        /**
         * Get version from main PHP file `Version:` comment header
         *
         * @return string version number
         */
        public static function version()
        {
            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR
                    . 'plugin.php');
            }

            $baseName = self::isPro() ? self::PRO_PLUGIN_BASENAME : self::FREE_PLUGIN_BASENAME;
            $pluginData = get_plugin_data(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $baseName, false);

            return $pluginData && $pluginData['Version'] ? $pluginData['Version'] : '';
        }

        /**
         * Log
         *
         * @param string $msg
         * @param string|null $filename
         * @param string $emailSubject Leave blank to prevent email from sending
         */
        public static function log($msg, $filename = null, $emailSubject = '')
        {
            if (!is_null($filename)) {
                $filename = '-' . $filename;
            }

            if (self::DEBUG) {
                $currentMemory = memory_get_usage();
                $msg .= ' [current memory: ' . $currentMemory . ' - diff: ' . ($currentMemory - self::$lastMemory) . ']';
                self::$lastMemory = $currentMemory;
            }

            if (!empty($emailSubject)) {
                $msg .= PHP_EOL .
                    "Site URL: " . site_url() . PHP_EOL .
                    "IP: " . self::getClientIP() . PHP_EOL .
                    "Browser Data: " . $_SERVER['HTTP_USER_AGENT'];
            }

            error_log(
                date("Y-m-d H:i:s") . " - $msg" . PHP_EOL,
                3,
                WP_CONTENT_DIR . DIRECTORY_SEPARATOR . self::PREFIX . $filename . '.log'
            );

            if (!empty($emailSubject)) {
                wp_mail(
                    self::_getAlertRecipient(),
                    get_bloginfo('name') . ' - ' . $emailSubject,
                    $msg
                );
            }
        }

        /**
         * Log while debug mode is enabled
         *
         * @param string $msg
         * @param string|null $filename
         * @param string $emailSubject Leave blank to prevent email from sending
         */
        public static function debug($msg, $filename = null, $emailSubject = '')
        {
            if (!self::DEBUG) {
                return;
            }

            self::log($msg, $filename, $emailSubject);
        }

        /**
         * Get email alert recipient or default to admin value
         *
         * @return string email address
         */
        private static function _getAlertRecipient()
        {
            $email = get_option(self::PREFIX . '_alert_recipient');
            if (empty($email)) {
                $email = get_option('admin_email');
            }
            return $email;
        }

        /**
         * Get client IP
         *
         * @return string
         */
        public static function getClientIP()
        {
            $server_vars = array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR',
            );
            foreach ($server_vars as $key) {
                if (array_key_exists($key, $_SERVER) === true) {
                    foreach (explode(',', $_SERVER[$key]) as $ip) {
                        if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                            return $ip;
                        }
                    }
                }
            }
            return null;
        }

        /**
         * Get plugin path
         *
         * @return string
         */
        public static function getPluginPath()
        {
            return dirname(__FILE__) . '/';
        }

        /**
         * Is Pro?
         */
        public static function isPro()
        {
            return is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'pro.php');
        }

    }

endif;

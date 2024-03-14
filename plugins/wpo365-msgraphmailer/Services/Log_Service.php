<?php

namespace Wpo\Services;

use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Request_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Log_Service')) {

    class Log_Service
    {

        const VERBOSE = 0;
        const INFORMATION = 1;
        const WARNING = 2;
        const ERROR = 3;
        const CRITICAL = 4;

        /**
         * Writes a message to the Wordpress debug.log file
         *
         * @since   1.0
         * 
         * @param   string  level => The level to log e.g. DEBUG or ERROR
         * @param   string  log => Message to write to the log
         */
        public static function write_log($level, $log, $props = array())
        {
            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $request_id = $request->current_request_id();
            $request_log = $request->get_item('request_log');

            if ($level == 'DEBUG' && false === $request_log['debug_log']) {
                return;
            }

            $body = is_array($log) || is_object($log) ? print_r($log, true) : $log;
            $now = \DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));

            $log_item = array(
                'body' => $body,
                'now' => $now->format('m-d-Y H:i:s.u'),
                'time' => time(),
                'level' => $level,
                'request_id' => $request_id,
                'php_version' => \phpversion(),
                'props' => $props,
            );

            $request_log['log'][] = $log_item;
            $request->set_item('request_log', $request_log);

            // Used to show an admin notice - is set in case an error occurred
            if ($level == 'ERROR') {
                $cached_errors = Wpmu_Helpers::mu_get_transient('wpo365_errors');
                $cached_errors = is_array($cached_errors) ? $cached_errors : array();
                \array_unshift($cached_errors, $log_item);
                $cached_errors = array_slice($cached_errors, 0, 3);
                Wpmu_Helpers::mu_set_transient('wpo365_errors', $cached_errors, 259200);
            }
        }

        /**
         * Writes the log file to the defined output stream
         * 
         * @since 7.11
         * 
         * @return void
         */
        public static function flush_log()
        {
            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $request_log = $request->get_item('request_log');

            // Nothing to flush
            if (empty($request_log['log'])) {
                return;
            }

            // Flush to ApplicationInsights
            $log_location = Options_Service::get_global_string_var('debug_log_location', false);
            $ai_instrumentation_key = Options_Service::get_global_string_var('ai_instrumentation_key', false);

            if (!empty($log_location) && !empty($ai_instrumentation_key)) {
                if ($log_location == 'remotely' || $log_location == 'both') {
                    self::flush_log_to_ai();

                    if ($log_location == 'remotely') {
                        $request->remove_item('request_log');
                        return;
                    }
                }
            }

            // Save the last 500 entries
            $wpo365_log = Wpmu_Helpers::mu_get_transient('wpo365_debug_log');

            if (empty($wpo365_log)) {
                $wpo365_log = array();
            }

            $wpo365_log = array_merge($wpo365_log, $request_log['log']);
            $count = sizeof($wpo365_log);

            if ($count > 500) {
                $wpo365_log = array_slice($wpo365_log, ($count - 500));
            }

            Wpmu_Helpers::mu_set_transient('wpo365_debug_log', $wpo365_log, 604800);

            // Still also write it to default debug output
            if (defined('WP_DEBUG') && constant('WP_DEBUG') === true) {

                foreach ($request_log['log'] as $item) {
                    $log_message = '[' . $item['now'] . ' | ' . $item['request_id'] . '] ' . $item['level'] . ' ( ' . $item['php_version'] . ' ): ' . $item['body'];
                    error_log($log_message);
                }
            }

            $request->remove_item('request_log');
        }

        /**
         * Flushes the current request log buffer to ApplicationInsights as trace messages.
         * 
         * @since   10.1
         * 
         * @return  void
         */
        private static function flush_log_to_ai()
        {
            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);
            $request_log = $request->get_item('request_log');

            $ai_items = array_map('\Wpo\Services\Log_Service::to_ai', $request_log['log']);
            $body = \json_encode($ai_items, JSON_UNESCAPED_UNICODE);

            $headersArray = array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
                'Expect' => '',
            );

            $response = wp_remote_post('https://dc.services.visualstudio.com/v2/track', array(
                'timeout'    => 15,
                'blocking'   => false,
                'headers'    => $headersArray,
                'body'       => $body,
                'sslverify'  => false,
            ));
        }

        /**
         * Simple switch to be used from array_map
         * 
         * @since   10.1
         * 
         * @param   $log_item   array   Associative array with the information to be logged
         * 
         * @return  array Associative array formatted according to Microsoft.ApplicationInsights.Message / Microsoft.ApplicationInsights.Exception requirement
         */
        private static function to_ai($log_item)
        {

            if ($log_item['level'] == 'ERROR') {
                return self::to_ai_exception($log_item);
            }

            return self::to_ai_message($log_item);
        }

        /**
         * Helper to convert a WPO365 log item into an AI tracking message (trace).
         * 
         * @since   10.1
         * 
         * @param   $log_item   array   Associative array with the information to be logged
         * 
         * @return  array Associative array formatted according to Microsoft.ApplicationInsights.Message requirement
         * 
         */
        private static function to_ai_message($log_item)
        {
            $ai_instrumentation_key = Options_Service::get_global_string_var('ai_instrumentation_key', false);

            if ($log_item['level'] == 'ERROR') {
                $level = self::ERROR;
            } elseif ($log_item['level'] == 'WARN') {
                $level = self::WARNING;
            } else {
                $level = self::INFORMATION;
            }



            return array(
                'data' => array(
                    'baseData' => array(
                        'message' => $log_item['body'],
                        'ver' => 2,
                        'severityLevel' => $level,
                        'properties' => self::get_ai_props($log_item),
                    ),
                    'baseType' => 'MessageData',
                ),
                'ver' => 1,
                'time' => gmdate('c', $log_item['time']) . 'Z',
                'name' => 'Microsoft.ApplicationInsights.Message',
                'iKey' => $ai_instrumentation_key,
            );
        }

        /**
         * Helper to convert a WPO365 log item into an AI tracking message (trace).
         * 
         * @since   10.1
         * 
         * @param   $log_item   array   Associative array with the information to be logged
         * 
         * @return  array Associative array formatted according to Microsoft.ApplicationInsights.Exception requirement
         * 
         */
        private static function to_ai_exception($log_item)
        {
            $ai_instrumentation_key = Options_Service::get_global_string_var('ai_instrumentation_key', false);

            return array(
                'data' => array(
                    'baseData' => array(
                        'ver' => 2,
                        'properties' => self::get_ai_props($log_item),
                        'exceptions' => array(
                            array(
                                "typeName" => "Error",
                                "message" => $log_item['body'],
                                "hasFullStack" => false,
                            ),
                        ),
                    ),
                    'baseType' => 'ExceptionData',
                ),
                'ver' => 1,
                'time' => gmdate('c', $log_item['time']) . 'Z',
                'name' => 'Microsoft.ApplicationInsights.Exception',
                'iKey' => $ai_instrumentation_key,
            );
        }

        /**
         * Adds the custom properties being logged as custom properties for
         * ApplicationInsights.
         * 
         * @since   23.0
         * 
         * @param   mixed   $log_item 
         * @return  array 
         */
        private static function get_ai_props($log_item)
        {
            $props = array(
                'phpVersion' => $log_item['php_version'],
                'wpoVersion' => $GLOBALS['WPO_CONFIG']['version'],
                'wpoEdition' => implode(',', $GLOBALS['WPO_CONFIG']['extensions']),
                'wpoRequestId' => $log_item['request_id'],
                'wpoHost' => $GLOBALS['WPO_CONFIG']['url_info']['host'],
            );

            if (!empty($log_item['props']) && is_array($log_item['props'])) {

                foreach ($log_item['props'] as $key => $value) {
                    $props[$key] = $value;
                }
            }

            return $props;
        }
    }
}

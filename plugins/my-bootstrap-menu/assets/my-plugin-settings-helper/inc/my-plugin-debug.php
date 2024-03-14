<?php
/**
 * Created in PhpStorm.
 * Author: Michael Carder
 * Date: 1/06/2015
 * Time: 12:45
 * Useful links:
 *
 */

namespace My_Bootstrap_Menu_Plugin_Namespace {
    /*
     * Debug messages to a Log File - handles messages, provides call stack for functions and can print_r for variables
    */

    class My_Plugin_Debug
    {
        /**
         * The log file name - usually in the plugin folder
         * @var
         */
        private $log_file_name;
        /**
         * Typical show backtrace level
         * @var int
         */
        private $typical_backtrace_level;

        function __construct($log_file_name, $typical_backtrace_level = 0)
        {
            $this->log_file_name = $log_file_name;
            $this->typical_backtrace_level = $typical_backtrace_level;

            $this->init();
        }

        private function init()
        {
            //Exit if user is not admin
            if (!is_admin()) return;

            // Enable WP_DEBUG mode
            if (!defined('WP_DEBUG'))
                define('WP_DEBUG', true);

            // Enable Debug logging to the file
            if (!defined('WP_DEBUG_LOG'))
                define('WP_DEBUG_LOG', true);

            // Disable display of errors and warnings
            if (!defined('WP_DEBUG_DISPLAY'))
                define('WP_DEBUG_DISPLAY', false);

            // log php errors
            @ini_set('log_errors', 'On'); // enable or disable php error logging (use 'On' or 'Off')
            @ini_set('display_errors', 'On'); // enable or disable public display of errors (use 'On' or 'Off')
            @ini_set('error_log', $this->log_file_name); // path to server-writable log file

        }

        public function MSG($log_msg, $current_file = null, $line = null, $var_name = '', $show_backtrace_level = null)
        {
            //If debug not enabled or not logged in as admin, then exit -> could also check for admin || is_admin()
            if (!(true === WP_DEBUG))
                return;

            //Set the level of backtrace for function calls
            $show_backtrace_level = isset($show_backtrace_level) ? $show_backtrace_level : $this->typical_backtrace_level;
            $msg = '';

            //File name
            if (!is_null($current_file)) $msg .= 'File: [' . $current_file . "]\t";

            //Line number
            if (!is_null($line)) $msg .= 'Line: [' . $line . "]\t";

            //Append log message - if the message is an array or object, then print_r it...
            $msg .= "Msg: [";
            if (is_array($log_msg) || is_object($log_msg)) {
                if (!is_null($current_file) || !is_null($line)) $msg .= $var_name . "\n";
                $msg .= print_r($log_msg, true);
            } else {
                $msg .= $log_msg;
            }
            $msg .= "]\t";

            //Show Backtrace / Call stack
            if ($show_backtrace_level > 0) {
                $callers = debug_backtrace();
                $backtrace = "\nBacktrace: [ \n";
                for ($i = min($show_backtrace_level, count($callers) - 1); $i > 0; $i--) {
                    $backtrace .= "\t\t{$i}:: " . $callers[$i]['function'] . " > \n ";
                }
                $msg .= $backtrace . "]\t";
            }

            //Log the message
            error_log($msg);

        }

    }
}
<?php

namespace WPP {

    // phpcs:disable WordPress.PHP.DevelopmentFunctions

    /**
     * Class WPP\Logger
     *
     * A simple class namespace container for some helpful debugging methods
     * to drop in to WP. Based on a Stack Overflow post by {@author Sarfraz}.
     *
     * This class defines a logger you can instantiate, a shared log level
     * between all these loggers you can set (ie, app-wide setting), and
     * a static proerty that holds a default logger (instantiated directly
     * after the class).
     *
     * Here is an {@example} of setting the app-wide log level:
     *
     *     Logger::$log_level = 6; // ie, only log messages at alert or higher...
     *     // More semantic version:
     *     Logger::$log_level = Logger::LEVELS['alert'];
     *
     * Note: this is set to error by default, unless your application set-up
     * ensures otherwise.
     *
     * Here is an {@example} of using the default logger:
     *
     *     Logger::$log->warning( 'couldn\'t find value', false );
     *     // Or, more simply:
     *     Logger::log_warning( 'couldn\'t find value', false );
     *     // ... which is also useful for contextless callables:
     *     call_user_func( 'Logger::log_warning', 'couldn\'t find value', false );
     *
     * Finally, here is an {@example} of creating a logger:
     *
     *     $logger = new Logger( 'logger-name' );
     *     $logger->log( 'message', Logger::LEVELS['alert'], true, __FILE__, __LINE__ );
     *     > [timestamp] /var/www/web/.../class.phpL1: [logger-name] message
     *
     *     // You can also set a specific log level for a logger:
     *     $logger = new Logger( 'emergency-logger', Logger::LEVELS['emergency'] );
     *     $logger->log( 'message', Logger::LEVELS['alert'], true, __FILE__, __LINE__ );
     *     // ... logs nothing, regardless of the value of Logger::$log_level.
     *
     * @todo Test me!
     */
    class Logger {

        /**
         * @var Defines levels of verbosity for logging as integers, for
         *      simple comparison: if message level is greater than or
         *      equal to log level, then log the message.
         */
        const LEVELS = [
            'emergency' => 8,
            'alert'     => 7,
            'critical'  => 6,
            'error'     => 5,
            'warning'   => 4,
            'notice'    => 3,
            'info'      => 2,
            'debug'     => 1,
            'verbose'   => 0,
        ];

        /**
         * @var The shared, app-wide log level. Any message logged at a
         *      lower level than this (unless it is sent to a logger with
         *      a specific, lower level than this) will not be logged.
         */
        public static $log_level = self::LEVELS['error'];

        /**
         * @var The log level of the current logger instance. If null or
         *      not set, falls back to static::$log_level.
         */
        public $level;

        /**
         * @var The name of the current logger instance. This is prepended
         *      to logged messages for search / identification.
         */
        public $name;

        /**
         * The constructor is a simple setter for the name and log level
         * of the instance.
         *
         * @param string   $name      The logger's name.
         * @param int|null $log_level The logger's log level.
         */
        public function __construct( $name, $log_level = null ) {
            $this->level = $log_level;
            $this->name = $name;
        }

        /**
         * The fundamental method for logging, this accepts and then logs
         * some message.
         *
         * @param string  $message    Message to log.
         * @param int     $level      The log level of the message.
         * @param boolean $print_line Optional. Whether or not to log the file and line. Defaults to false.
         * @param string  $file       Optional. Filename. Defaults to __FILE__.
         * @param string  $line       Optional. Line number. Defaults to __LINE__.
         */
        public function log(
            $message,
            $level = 0,
            $print_line = false,
            $file = __FILE__,
            $line = __LINE__
        ) {
            if ( $this->should_log( $level ) ) {
                $log_message = $this->prefix() . $this->stringify( $message );
                if ( $print_line ) {
                    foreach (explode("\n", $log_message) as $msg) {
                        error_log( $this->annotate_with_line( $msg, $file, $line ) );
                    }
                } else {
                    foreach (explode("\n", $log_message) as $msg) {
                        error_log( $msg );
                    }
                }
            }
        }

        /**
         * Helper function that decides whether or not to log the message,
         * based on if debugging is turned on globally and the message meets
         * the log level requirements.
         *
         * @param int $message_level The log level of the message.
         *
         * @return boolean Whether or not to log it.
         */
        private function should_log( $message_level = 0 ) {
            $log_level = $this->level ?? static::$log_level;
            return WP_DEBUG && $message_level >= $log_level;
        }

        /**
         * Helper function for prepending the logger name to the message.
         *
         * @return string The message's prefix.
         */
        private function prefix() {
            return $this->name ? "[{$this->name}] " : '';
        }

        /**
         * Helper function for prepending the file/line to the message.
         *
         * @param string $message The message to log.
         * @param string $file    The file path.
         * @param string $line    The line in the file.
         *
         * @return string The updated message.
         */
        private function annotate_with_line( $message, $file, $line ) {
            return "{$file}L{$line}: {$message}";
        }

        /**
         * Helper function for logging objects as well as strings, doing
         * automatic conversion.
         *
         * @param mixed $message The message.
         *
         * @return string The message as a string.
         */
        private function stringify( $message ) {
            return ( is_array( $message ) || is_object( $message ) ) ?
                   print_r( $message, true ) :
                   $message;
        }

        /**
         * A shorthand function for calling ::log() with an emergency-level
         * message.
         */
        public function emergency() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['emergency'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with an alert-level
         * message.
         */
        public function alert() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['alert'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with a critical-level
         * message.
         */
        public function critical() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['critical'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with an error-level
         * message.
         */
        public function error() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['error'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with a warning-level
         * message.
         */
        public function warning() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['warning'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with a notice-level
         * message.
         */
        public function notice() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['notice'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with an info-level
         * message.
         */
        public function info() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['info'] );
            $this->log( ...$args );
        }

        /**
         * A shorthand function for calling ::log() with a debug-level
         * message.
         */
        public function debug() {
            $args = func_get_args();
            array_splice( $args, 1, 0, static::LEVELS['debug'] );
            $this->log( ...$args );
        }

        /*
         * =============== Static definitions ==============
         */

        /**
         * @var The static property that holds our default logger.
         */
        public static $log;

        /**
         * A shorthand function for calling a specific emergency-level
         * method on the default logger.
         */
        public static function log_emergency() {
            static::$log->emergency( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific alert-level
         * method on the default logger.
         */
        public static function log_alert() {
            static::$log->alert( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific critical-level
         * method on the default logger.
         */
        public static function log_critical() {
            static::$log->critical( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific error-level
         * method on the default logger.
         */
        public static function log_error() {
            static::$log->error( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific warning-level
         * method on the default logger.
         */
        public static function log_warning() {
            static::$log->warning( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific notice-level
         * method on the default logger.
         */
        public static function log_notice() {
            static::$log->notice( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific info-level
         * method on the default logger.
         */
        public static function log_info() {
            static::$log->info( ...func_get_args() );
        }

        /**
         * A shorthand function for calling a specific debug-level
         * method on the default logger.
         */
        public static function log_debug() {
            static::$log->debug( ...func_get_args() );
        }

        /**
         * A shorthand function for calling the log method on the
         * default logger.
         */
        public static function log_message() {
            static::$log->log( ...func_get_args() );
        }
    }

    // Ensure we set the default, nameless logger!
    Logger::$log = new Logger( null );
}

namespace {

    /**
     * A shorthand function for logging in development. Accepts all of
     * the parameters from WPP\WP_Debugger::log(), except the lovel,
     * which is set automatically to the current level, so these always
     * show.
     *
     * @param string  $message    Message to log.
     * @param boolean $print_line Optional. Whether or not to log the file and line. Defaults to false.
     * @param string  $file       Optional. Filename. Defaults to __FILE__.
     * @param string  $line       Optional. Line number. Defaults to __LINE__.
     */
    function wpp_log(
        $message,
        $print_line = false,
        $file       = __FILE__,
        $line       = __LINE__
    ) {
        $args = [ $message, \WPP\Logger::$log_level, $print_line, $file, $line ];
        call_user_func_array( '\WPP\Logger::log_message', $args );
    }
}

<?php

/**
 * WC_iZettle_Integration_Log
 *
 * @class           WC_iZettle_Integration_Log
 * @since           1.0.0
 * @package         WC_iZettle_Integration
 * @category        Class
 * @author          bjorntech
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Log', false)) {

    class WC_iZettle_Integration_Log
    {

        /* The domain handler used to name the log */
        private $_domain = 'woo-izettle-integration';

        /* The WC_Logger instance */
        private $_logger;

        private $_silent = true;

        private $_bt_pid;

        /**
         * __construct.
         *
         * @access public
         * @return void
         */
        public function __construct($silent = false)
        {
            $this->_logger = new WC_Logger();
            $this->_silent = $silent;
            $this->_bt_pid = rand(1, 999999);
        }

        /**
         * add function.
         *
         * Uses the build in logging method in WooCommerce.
         * Logs are available inside the System status tab
         *
         * @access public
         * @param  string|array|object
         * @return void
         */
        public function add($param, $force_log = false)
        {

            if(is_null($param)){
                $param = 'null param';
            }

            if (!$this->_silent || $force_log) {
                if (is_array($param) || is_object($param)) {
                    $param = print_r($param, true);
                }

                //     $caller = next(debug_backtrace())['function'];
                //     $this->_logger->add($this->_domain, $caller .' - '. $param);


                $this->_logger->log('-', $this->get_pid() . ' - ' . $param, array('source' => $this->get_domain()));

                //$this->_logger->add($this->_domain, $this->get_pid() . ' - ' . $param);
            }
        }

        public function get_pid()
        {
            $disabled_functions = ini_get("disable_functions");

            if (!$disabled_functions) {
                return getmypid();
            }

            if (strpos($disabled_functions, 'getmypid') !== false) {
                return $this->_bt_pid;
            }

            return getmypid();
        }

        /**
         * clear function.
         *
         * Clears the entire log file
         *
         * @access public
         * @return void
         */
        public function clear()
        {
            return $this->_logger->clear($this->_domain);
        }

        /**
         * separator function.
         *
         * Inserts a separation line for better overview in the logs.
         *
         * @access public
         * @return void
         */
        public function separator()
        {
            $this->add('--------------------');
        }

        /**
         * get_domain function.
         *
         * Returns the log text domain
         *
         * @access public
         * @return string
         */
        public function get_domain()
        {
            return $this->_domain;
        }

        /**
         * Returns a link to the log files in the WP backend.
         */
        public function get_admin_link()
        {
            $log_path = wc_get_log_file_path($this->_domain);
            $log_path_parts = explode('/', $log_path);
            return add_query_arg(array(
                'page' => 'wc-status',
                'tab' => 'logs',
                'log_file' => end($log_path_parts),
            ), admin_url('admin.php'));
        }
    }
}

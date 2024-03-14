<?php
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('CCPW_cronjob')) {
    class CCPW_cronjob
    {
        use CCPW_Helper_Functions;

        public function __construct()
        {
            // Update database only if required.
            add_action('init', array($this, 'ccpw_cron_coins_autoupdater'));
            // Register cron jobs
            add_filter('cron_schedules', array($this, 'ccpw_cron_schedules'));
            add_action('ccpw_coins_autosave', array($this, 'ccpw_cron_coins_autoupdater'));

        }

        /**
         * Cron status schedule(s).
         */
        public function ccpw_cron_schedules($schedules)
        {
            // 5 minute schedule for grabbing all coins
            if (!isset($schedules['5min'])) {
                $schedules['5min'] = array(
                    'interval' => 5 * 60,
                    'display' => __('Once every 5 minutes'),
                );
            }
            return $schedules;
        }

        /*
        |-----------------------------------------------------------
        |   This will update the database after a specific interval
        |-----------------------------------------------------------
        |   Always use this function to update the database
        |-----------------------------------------------------------
         */
        public function ccpw_cron_coins_autoupdater()
        {
            // Do not proceed further if
            if (!$this->ccpw_check_user()) {
                return;
            }

            // Determine the selected API
            $api = get_option('ccpw_options');
            $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
            $api_obj = new CCPW_api_data();

            // Fetch coin data based on the selected API
            $data = ($api == "coin_gecko") ? $api_obj->ccpw_get_coin_gecko_data() : $api_obj->ccpw_get_coin_paprika_data();

            // Check if 24 hours have passed since the last check
            $last_check_time = get_transient('ccpw_last_check_time');
            if (!$last_check_time) {
                // Call the function to update the coin list
                $obj = new ccpw_database();
                $obj->ccpw_check_coin_list();
                // Update the last check time in transient
                set_transient('ccpw_last_check_time', time(), 24 * 60 * 60);
            }

            // Reset option data once on the first day of the month
            $this->reset_option_data_once_on_first_of_month();
        }

        /**
         * Reset option data once on the first day of the month
         */
        public function reset_option_data_once_on_first_of_month()
        {
            // Check if it's the 1st day of the month
            $current_date = date('j');

            if ($current_date === '1') {
                // Check if a flag or option indicating the reset has already been performed
                $reset_flag = get_option('ccpw_reset_flag');

                // If the reset has not been performed (reset_flag is not set), perform the reset
                if (empty($reset_flag)) {
                    // Reset your option data
                    update_option('cmc_coingecko_api_hits', 0);

                    // Set a flag to indicate that the reset has been performed
                    update_option('ccpw_reset_flag', '1');
                }
            } else {
                // If it's not the first day of the month, delete the reset flag if it exists
                if (get_option('ccpw_reset_flag')) {
                    delete_option('ccpw_reset_flag');
                }
            }
        }
    }

    $cron_init = new CCPW_cronjob();
}

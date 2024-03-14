<?php

namespace Wpo\Core;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\Cron_Helpers')) {

    class Cron_Helpers
    {

        /**
         * Adds custom named cron schedules
         * 
         * @since 10.0
         * 
         * @param $schedules Array of already defined 
         */
        public static function add_cron_schedules($schedules)
        {
            $schedules['wpo_every_minute'] = array(
                'interval' => 60,
                'display' => __('Every minute', 'wpo365-login')
            );

            $schedules['wpo_five_minutes'] = array(
                'interval' => 300,
                'display' => __('Every 5 minutes', 'wpo365-login')
            );

            $schedules['wpo_daily'] = array(
                'interval' => 86400,
                'display' => __('WPO365 Daily', 'wpo365-login')
            );

            $schedules['wpo_weekly'] = array(
                'interval' => 604800,
                'display' => __('WPO365 Weekly', 'wpo365-login')
            );

            return $schedules;
        }
    }
}

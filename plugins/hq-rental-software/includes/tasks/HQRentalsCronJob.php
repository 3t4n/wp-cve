<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsCronJob
{
    public function __construct()
    {
        $this->scheduler = new HQRentalsScheduler();
        $this->pluginSettings = new HQRentalsSettings();
        add_filter('cron_schedules', array($this, 'addCustomScheduleTime'));
        add_action('refreshAllHQDataJob', array($this, 'refreshAllData'));
        if (!wp_next_scheduled('refreshAllHQDataJob')) {
            wp_schedule_event(time(), 'daily', 'refreshAllHQDataJob');
        }
    }

    public function refreshAllData()
    {
        if (!($this->pluginSettings->getDisableCronjobOption() == 'true')) {
            $this->scheduler->refreshHQData();
        }
    }

    public function addCustomScheduleTime($schedules)
    {
        $schedules['hqRefreshTime'] = array(
            'interval' => 86400,
            'display' => __('Once every half an hour')
        );
        $schedules['hqRefreshTimeHalfAnHour'] = array(
            'interval' => 1800,
            'display' => __('Once every half an hour')
        );
        return $schedules;
    }
}

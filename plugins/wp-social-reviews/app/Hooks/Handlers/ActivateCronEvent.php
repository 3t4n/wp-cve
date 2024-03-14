<?php

namespace WPSocialReviews\App\Hooks\Handlers;

use WPSocialReviews\Framework\Support\Arr;

class ActivateCronEvent
{
	public function activate()
    {
		if (!wp_next_scheduled('wpsr_cron_job')) {
			wp_schedule_event(time(), 'hourly', 'wpsr_cron_job');
		}

        $emailReportHook = 'wpsr_do_email_report_scheduled_tasks';
        if (!wp_next_scheduled($emailReportHook)) {
            wp_schedule_event(time(), 'daily', $emailReportHook);
        }

        $twicedailyHook = 'wpsr_scheduled_twicedaily';
        if (!wp_next_scheduled($twicedailyHook)) {
            wp_schedule_event(time(), 'twicedaily', $twicedailyHook);
        }

        $weeklyHook = 'wpsr_scheduled_weekly';
        if (!wp_next_scheduled($weeklyHook)) {
            wp_schedule_event(time(), '1week', $weeklyHook);
        }
    }
}

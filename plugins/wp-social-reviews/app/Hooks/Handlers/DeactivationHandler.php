<?php

namespace WPSocialReviews\App\Hooks\Handlers;

class DeactivationHandler
{
    public function handle()
    {
	    wp_clear_scheduled_hook('wpsr_cron_job');
	    wp_clear_scheduled_hook('wpsr_do_email_report_scheduled_tasks');
	    wp_clear_scheduled_hook('wpsr_scheduled_twicedaily');
	    wp_clear_scheduled_hook('wpsr_scheduled_weekly');
    }
}

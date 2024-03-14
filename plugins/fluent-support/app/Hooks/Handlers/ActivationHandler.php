<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\Database\DBMigrator;

class ActivationHandler
{
    public function handle($network_wide = false)
    {
        DBMigrator::run($network_wide);

        if (! wp_next_scheduled ( 'fluent_support_hourly_tasks' )) {
            wp_schedule_event( time(), 'hourly', 'fluent_support_hourly_tasks' );
        }

        if (! wp_next_scheduled ( 'fluent_support_daily_tasks' )) {
            wp_schedule_event( time(), 'daily', 'fluent_support_daily_tasks' );
        }

        if (! wp_next_scheduled ( 'fluent_support_weekly_tasks' )) {
            wp_schedule_event( time(), 'weekly', 'fluent_support_weekly_tasks' );
        }
    }
}

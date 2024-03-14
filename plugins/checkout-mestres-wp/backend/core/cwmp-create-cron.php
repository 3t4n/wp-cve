<?php

if (! wp_next_scheduled ( 'cwmp_cron_events' )) {
wp_schedule_event(time(), 'every_minute', 'cwmp_cron_events');
}
<?php

namespace BDroppy\Init;

use BDroppy\CronJob\CronJob;

if ( ! defined( 'ABSPATH' ) ) exit;

class DeActivator {

    public static function deActivate()
    {
        CronJob::unScheduleEvents();
    }

}

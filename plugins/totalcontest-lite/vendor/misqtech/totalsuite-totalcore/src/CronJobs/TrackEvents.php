<?php
/** @noinspection PhpUnused */

namespace TotalContestVendors\TotalCore\CronJobs;


use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Http\TrackingRequest;
use TotalContestVendors\TotalCore\Scheduler;
use TotalContestVendors\TotalCore\Scheduler\CronJob;

class TrackEvents extends CronJob
{

    public function execute()
    {
        $url = Application::getInstance()->env('api.tracking.events');
        $key = Application::getInstance()->env('tracking-key');

        $options = get_option($key);

        TrackingRequest::send($url, $options);

        update_option($key, [
            'screens' => [],
            'features' => []
        ]);

    }

    public function getRecurrence()
    {
        return Scheduler::SCHEDUL_DAILY;
    }

    public function getStartTime()
    {
        return time();
    }
}
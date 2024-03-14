<?php

namespace TotalContestVendors\TotalCore\Scheduler;


use TotalContestVendors\TotalCore\Scheduler;

abstract class CronJob
{
    abstract public function execute();

    /**
     * @return int
     */
    public function getStartTime()
    {
        return time();
    }

    /**
     * @return string
     */
    public function getRecurrence()
    {
        return Scheduler::SCHEDUL_HOURLY;
    }

    /**
     * @return void
     */
    public function __invoke() {
        $this->execute();
    }
}
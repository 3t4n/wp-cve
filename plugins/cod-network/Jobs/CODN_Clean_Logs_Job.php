<?php

namespace CODNetwork\Jobs;

use CODNetwork\Services\CODN_Logger_Service;
use WP_Queue\Job;

class CODN_Clean_Logs_Job extends Job
{
    /** @var CODN_Logger_Service */
    protected $logger;

    public function __construct()
    {
        $this->logger = new CODN_Logger_Service();
    }

    public function handle()
    {
        $this->logger->info('cleaning old logs');
        $this->logger->cleanLogFiles();
    }
}

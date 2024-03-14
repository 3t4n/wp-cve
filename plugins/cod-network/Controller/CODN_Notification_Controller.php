<?php

namespace CODNetwork\Controller;

use CODNetwork\Services\CODN_Cookie_Service;
use CODNetwork\Services\CODN_Email_Service;
use CODNetwork\Services\CODN_File_Log_Service;
use CODNetwork\Services\CODN_Logger_Service;
use CODNetwork\Services\CODN_Slack_Service;

class CODN_Notification_Controller
{
    /** @var CODN_Logger_Service */
    protected $codnLoggerService;

    /** @var CODN_File_Log_Service */
    protected $codnFileService;

    /** @var CODN_Cookie_Service */
    protected $codnCookieService;

    /** @var CODN_Slack_Service */
    protected $codnSlackService;

    /** @var CODN_Email_Service */
    protected $codnEmailService;

    public function __construct()
    {
        $this->codnLoggerService = new CODN_Logger_Service();
        $this->codnFileService = new CODN_File_Log_Service();
        $this->codnCookieService = new CODN_Cookie_Service();
        $this->codnSlackService = new CODN_Slack_Service();
        $this->codnEmailService = new CODN_Email_Service();
    }

    /**
     * @return bool|WP_Error
     */
    public function codn_send_via_slack(?string $filelog = null)
    {
        if ($this->codnCookieService->hasReachedReportLimit()) {
            return false;
        }

        $logs = $this->codnLoggerService->getLogs($filelog);
        if (empty($logs)) {
            return false;
        }

        $this->codnCookieService->decrementAttempts();

        return $this->codnSlackService->sendMessage($logs);
    }
}

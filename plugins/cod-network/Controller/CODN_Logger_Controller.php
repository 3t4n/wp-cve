<?php

namespace CODNetwork\Controller;

use CODNetwork\Services\CODN_File_Log_Service;
use CODNetwork\Services\CODN_Logger_Service;
use Throwable;

class CODN_Logger_Controller
{
    /** @var CODN_Logger_Service */
    private $loggerService;

    /** @var CODN_File_Log_Service */
    private $codnFileLogService;

    public function __construct()
    {
        $this->loggerService = new CODN_Logger_Service();
        $this->codnFileLogService = new CODN_File_Log_Service();
    }

    public function codn_delete_file_log(?string $filelog = null): bool
    {
        return $this->codnFileLogService->codn_delete_file_log($filelog);
    }
}

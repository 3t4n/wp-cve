<?php

namespace WcMipConnector\Controller;

defined('ABSPATH') || exit;

use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\LoggerService;
use WcMipConnector\View\Logger\LoggerView;

class LoggerController
{
    /** @var LoggerView  */
    private $loggerView;
    /** @var DirectoryService  */
    private $directoryService;

    /**
     * LoggerController constructor.
     */
    public function __construct()
    {
        $this->loggerView = new LoggerView();
        $this->directoryService = DirectoryService::getInstance();
    }

    public function getLogger(): void
    {
        $filesInLogDir = scandir($this->directoryService->getLogDir());

        $this->loggerView->showLoggerView($filesInLogDir);
    }

    /**
     * @param string $fileDate
     */
    public function getLoggerByDate(string $fileDate): void
    {
        $fileName = $this->directoryService->getLogDir() .'/'. $fileDate;

        if (!LoggerService::isLogFile($fileName)) {
            echo '';

            return;
        }

        $fileLog = file_get_contents($fileName);

        echo str_replace('[] []', '<br/>', $fileLog);
    }
}

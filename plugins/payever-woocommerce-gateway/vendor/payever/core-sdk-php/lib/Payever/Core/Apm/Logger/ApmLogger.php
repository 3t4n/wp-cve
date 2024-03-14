<?php

namespace Payever\Sdk\Core\Apm\Logger;

use Payever\Sdk\Core\Apm\ApmApiClient;
use Payever\Sdk\Core\ClientConfiguration;
use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Class ApmLogger
 */
class ApmLogger extends AbstractLogger
{
    use LoggerTrait;

    /** @var ApmApiClient  */
    protected $apmApiClient;

    public function __construct(LoggerInterface $logger, ClientConfiguration $clientConfiguration)
    {
        $this->logger = $logger;
        $this->apmApiClient = new ApmApiClient($clientConfiguration);
    }

    /**
     * @param $message
     * @param $logLevel
     * @return $this
     */
    protected function sendMessage($message, $logLevel)
    {
        if ($logLevel != LogLevel::CRITICAL && $logLevel != LogLevel::ERROR) {
            return $this;
        }

        try {
            $this->apmApiClient->sendLog($message, $logLevel);
        } catch (\Exception $e) {
            $this->logger->log(LogLevel::INFO, $e->getMessage());
        }

        return $this;
    }
}

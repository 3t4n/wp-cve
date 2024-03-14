<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Monolog\Handler\RotatingFileHandler;
use WcMipConnector\Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use WcMipConnector\Enum\WooCommerceErrorCodes;

/**
 * Class Logger
 *
 * @method emergency($message, $data = [])
 * @method alert($message, $data = [])
 * @method critical($message, $data = [])
 * @method error($message, $data = [])
 * @method warning($message, $data = [])
 * @method notice($message, $data = [])
 * @method info($message, $data = [])
 * @method debug($message, $data = [])
 */
class LoggerService
{
    private const MAX_FILES = 7;
    private const LOG_DATA = 'Logs WcMipConnector';
    private const LOG_FILE_NAME = 'wc-mipconnector';
    private const LOG_FILE_EXTENSION = 'log';
    public const CODE_IMAGE_NOT_FOUND = 'IMAGE_NOT_FOUND';
    public const CODE_IMAGE_INVALID_FOLDER_PERMISSIONS = 'IMAGE_INVALID_FOLDER_PERMISSIONS';
    public const CODE_IMAGE_TIMED_OUT = 'IMAGE_TIMED_OUT';

    /** @var LoggerService */
    private static $logger;
    /** @var LoggerInterface */
    private $appLogger;

    public function __construct()
    {
        $directoryService = DirectoryService::getInstance();
        $logDir = $directoryService->getLogDir();
        $logFile = self::LOG_FILE_NAME.'.'.self::LOG_FILE_EXTENSION;

        $directoryService->saveFileContent($logFile, $logDir, self::LOG_DATA);

        $oldPermissions = umask(0000);

        $this->appLogger = new MonologLogger(self::LOG_FILE_NAME);

        $this->appLogger->pushHandler(
            new RotatingFileHandler($logDir.'/'.$logFile, self::MAX_FILES, MonologLogger::ERROR)
        );
        umask($oldPermissions);

        $directoryService->createHtaccessIfNotExists();
    }

    /**
     * @return LoggerService
     */
    public function getInstance(): self
    {
        if (!self::$logger) {
            self::$logger = new self();
        }

        return self::$logger;
    }

    /**
     * @param $levelName
     * @param $arguments
     * @return mixed
     */
    public function __call($levelName, $arguments)
    {
        if (!$this->isValidLevel($levelName)) {
            throw new \InvalidArgumentException($levelName.'logging level is invalid');
        }

        $argumentsCount = count($arguments);

        if (0 === $argumentsCount) {
            throw new \InvalidArgumentException('Logger needs at least a message :'. $argumentsCount);
        }

        $message = $arguments[0];
        $data = [];

        if (2 === $argumentsCount) {
            $data = $arguments[1];

            if (!is_array($data)) {
                $data = [$data];
            }
        }

        return $this->appLogger->{$levelName}($message, $data);
    }

    /**
     * @param string $levelName
     * @return bool
     */
    private function isValidLevel($levelName): bool
    {
        $levels = MonologLogger::getLevels();

        if (!isset($levels[strtoupper($levelName)])) {
            return false;
        }

        return true;
    }

    public static function isLogFile(string $file): bool
    {
        return $file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === self::LOG_FILE_EXTENSION;
    }
}
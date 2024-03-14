<?php

namespace CODNetwork\Services;

use CODNetwork\Repositories\CodNetworkRepository;
use Throwable;
use WP_Error;
use WP_Http;

/**
 * Log all things!
 */
class CODN_Logger_Service
{
    const FILE_LOG_NAME = 'codnetwork';

    /** @var CodNetworkRepository */
    protected $codNetworkRepository;

    /** @var CODN_Slack_Service */
    protected $codnSlackService;

    /** @var CODN_File_Log_Service */
    protected $codnFileLogService;

    private static $instance;

    const KEEP_LAST_LOG_FILES = 3;

    public function __construct()
    {
        $this->codNetworkRepository = CodNetworkRepository::get_instance();
        $this->codnSlackService = new CODN_Slack_Service();
        $this->codnFileLogService = new CODN_File_Log_Service();
        $this->check_access_log();
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function check_access_log(): bool
    {
        if (!$this->codNetworkRepository->select_logs_status()) {
            return true;
        }

        return false;
    }

    public function writeMessage(string $type, string $message, array $context = null): string
    {
        if (is_array($context)) {
            $messageExtra = $context['extra.message'] ?? '';
            $trace = $context['trace'] ?? '';
            $context = array_merge(['message' => $message], $context, $this->extraInfo($type, $messageExtra, $trace));
        }

        $newLog = json_encode($context);

        $file = $this->codnFileLogService->getCurrentFillePath();
        if (!file_exists($file)) {
            $initLog = [
                'message' => 'init new log file',
                'level' => 'info',
                'date' => date('Y-m-d H:i:s'),
                'store.url' => get_site_url(),
                'version' => COD_PLUGIN_VERSION,
                'store.email' => get_option('admin_email')
            ];
            $fileRead = fopen($file, 'a');
            fwrite($fileRead, sprintf("%s%s", json_encode($initLog), PHP_EOL));
            fclose($fileRead);
        }

        $newLog .= sprintf('%s%s', PHP_EOL, file_get_contents($file));
        file_put_contents($file, sprintf("%s", $newLog));

        return $newLog;
    }

    public function log($message, array $context = []): string
    {
        return $this->writeMessage('log', $message, $context);
    }

    public function debug($message, array $context = []): string
    {
        return $this->writeMessage('debug', $message, $context);
    }

    public function alert($message, array $context = []): string
    {
        return $this->writeMessage('alert', $message, $context);
    }

    public function info($message, array $context = []): string
    {
        return $this->writeMessage('info', $message, $context);
    }

    public function error($message, array $context = []): string
    {
        return $this->writeMessage('error', $message, $context);
    }

    /**
     * function refactor extra info
     */
    public function extraInfo(string $type, string $message = null, string $trace = null): array
    {
        if (isset($message) & isset($trace) & !empty($message) & !empty($trace)) {
            return [
                'level' => $type,
                'date' => date('Y-m-d H:i:s'),
                'store.url' => get_site_url(),
                'extra.message' => $message,
                'trace' => $trace,
                'version' => COD_PLUGIN_VERSION,
                'store.email' => get_option('admin_email')
            ];
        }

        return [
            'level' => $type,
            'date' => date('Y-m-d H:i:s'),
            'store.url' => get_site_url(),
            'version' => COD_PLUGIN_VERSION,
            'store.email' => get_option('admin_email')
        ];
    }

    // TODO: this method should be removed
    public function codn_delete_file_log(?string $filelog = null): bool
    {
        if ($filelog === null) {
            return false;
        }

        $existFile = $this->verifyFileExists($filelog);
        if (!$existFile) {
            return false;
        }

        $path = $this->codnFileLogService->getDirectoryPath();
        $filePath = sprintf('%s/%s', $path, $filelog);
        unlink($filePath);

        return true;
    }

    /**
     * @return bool
     */
    public function cleanLogFiles(): bool
    {
        $path = $this->codnFileLogService->getDirectoryPath();
        $files = array_diff(scandir($path), ['.', '..']);
        $filesLength = count($files);
        $maxFileDeleted = $filesLength - self::KEEP_LAST_LOG_FILES;

        if ($filesLength === 0) {
            return false;
        }

        if ($filesLength <= self::KEEP_LAST_LOG_FILES) {
            return false;
        }

        $index = 0;
        foreach ($files as $file) {
            if ($index == $maxFileDeleted) {
                break;
            }

            $filePath = sprintf('%s/%s', $path, $file);
            if (file_exists($filePath)) {
                unlink($filePath);
                $index++;
            }
        }

        return true;
    }

    /**
     * get log content as array
     */
    public function getLogs(?string $filelog = null): array
    {
        $file = $this->codnFileLogService->getLogFile($filelog);
        if (!$file) {
            return [];
        }

        $logs = explode(PHP_EOL, $file);

        return $logs;
    }

    /**
     * Get Stander logs format
     */
    public function getDisplayStanderLogs(?array $logs = null): ?string
    {
        $output = '';

        if (!$logs) {
            $logs = $this->getLogs();
        }

        if (empty($logs)) {
            return null;
        }

        foreach ($logs as $log) {
            $log = json_decode($log, true);
            if (!is_array($log)) {
                return $output;
            }

            $level = $log['level'] ?? '';
            $date = $log['date'] ?? '';
            $message = $log['message'] ?? '';
            $trace = $log['trace'] ?? '';
            $output .= sprintf('%s %s: %s %s%s %s%s', $date, $level, $message, PHP_EOL, $trace, PHP_EOL, PHP_EOL);
        }

        return $output;
    }
}

<?php

namespace Flamix\Plugin\General;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Exception;

class Helpers
{
    /**
     * When saving email - check.
     *
     * @param string $url
     * @return string
     */
    public static function parseDomain(string $url): string
    {
        if (!str_contains($url, 'http'))
            $url = 'https://' . $url;

        $tmp = parse_url($url);
        return $tmp['host'] ?? '';
    }

    /**
     * @param string|bool|int $message Log message
     * @param array $context Data
     * @param string|null $chanel Folder
     * @return Logger
     * @throws Exception
     */
    public static function log($message, array $context = [], ?string $chanel = null): Logger
    {
        $date = date('Y-m-d');
        if (!defined('FLAMIX_LOGS_PATH'))
            throw new Exception('Logs path not defined, use setLogsPath() when init plugin!');

        $log = new Logger('commerceml');
        $log->pushHandler(new StreamHandler(FLAMIX_LOGS_PATH . '/logs/' . ($chanel ? $chanel . '/' : '') . $date . '-info-' . md5($date) . '.log', Logger::DEBUG));
        $log->info($message, $context);
        return $log;
    }
}
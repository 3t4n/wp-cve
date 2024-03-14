<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

use InvalidArgumentException;
use WC_Log_Handler_File;
class Woocommerce_Logger
{
    const LEVEL_DEBUG = 'debug';
    const LEVEL_ALERT = 'alert';
    const LEVEL_CRITICAL = 'critical';
    const LEVEL_ERROR = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_NOTICE = 'notice';
    const LEVEL_INFO = 'info';
    const LEVEL_EMERGENCY = 'emergency';
    /**
     * @var string
     */
    private $log_file_name;
    /**
     * @var bool
     */
    private $null_logger;
    private $available_log_levels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
    /**
     * @param string $log_file_name
     */
    public function __construct(string $log_file_name)
    {
        $this->log_file_name = sanitize_file_name($log_file_name);
    }
    public function log_debug(string $message)
    {
        $this->log(self::LEVEL_DEBUG, $message);
    }

    public function log_info(string $message)
    {
        $this->log(self::LEVEL_INFO, $message);
    }
    public function log_notice(string $message)
    {
        $this->log(self::LEVEL_NOTICE, $message);
    }
    public function log_warning(string $message)
    {
        $this->log(self::LEVEL_WARNING, $message);
    }
    public function log_error(string $message)
    {
        $this->log(self::LEVEL_ERROR, $message);
    }
    public function log_critical(string $message)
    {
        $this->log(self::LEVEL_CRITICAL, $message);
    }
    public function log_alert(string $message)
    {
        $this->log(self::LEVEL_ALERT, $message);
    }
    public function log_emergency(string $message)
    {
        $this->log(self::LEVEL_EMERGENCY, $message);
    }
    public function log($level, string $message)
    {
        if ($this->null_logger) {
            return;
        }

        if (\class_exists('WC_Log_Handler_File')) {
            if (!\in_array($level, $this->available_log_levels, \true)) {
                throw new InvalidArgumentException("Invalid log level: {$level}");
            }
            $logHandler = new WC_Log_Handler_File();
            $context = ['source' => $this->log_file_name];
            $logHandler->handle(\time(), $level, $message, $context);
        }
    }
    public function is_null_logger() : bool
    {
        return $this->null_logger;
    }
    public function set_null_logger(bool $null_logger) : void
    {
        $this->null_logger = $null_logger;
    }
}

<?php

namespace Paygreen\Module;

use DateTimeImmutable;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Log all things!
 *
 * @since 0.1.0
 */
class WC_Paygreen_Payment_Logger
{
    public static function debug($message)
    {
        self::log($message, 'debug');
    }

    public static function info($message)
    {
        self::log($message);
    }

    public static function notice($message)
    {
        self::log($message, 'notice');
    }

    public static function warning($message)
    {
        self::log($message, 'warning');
    }

    public static function error($message)
    {
        self::log($message, 'error');
    }

    public static function critical($message)
    {
        self::log($message, 'critical');
    }

    public static function alert($message)
    {
        self::log($message, 'alert');
    }

    public static function emergency($message)
    {
        self::log($message, 'emergency');
    }

    private static function log($message, $level = 'info')
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');
        $message = (new DateTimeImmutable())->format('u') . ' - ' . $message;

        if (isset($settings['detailed_logs']) && $settings['detailed_logs'] === 'yes'
            || in_array($level, ['alert', 'emergency', 'error', 'critical'])
        ) {
            wc_get_logger()->$level($message);
        }
    }
}

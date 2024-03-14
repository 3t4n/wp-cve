<?php

class WC_Gateway_Conotoxia_Pay_Logger
{
    /**
     * @type string
     */
    private const WC_LOG_FILENAME = 'woocommerce-gateway-conotoxiapay';

    /**
     * @var WC_Logger
     */
    private static $logger;

    /**
     * @param string $format
     * @param mixed ...$arguments
     * @return void
     */
    public static function log(string $format, ...$arguments): void
    {
        if (!class_exists('WC_Logger') || !isset(self::$logger)) {
            return;
        }
        $message = vsprintf($format, $arguments);
        if (self::is_old_wc_version()) {
            self::$logger->add(self::WC_LOG_FILENAME, $message, WC_Log_Levels::DEBUG);
        } else {
            self::$logger->debug($message, ['source' => self::WC_LOG_FILENAME]);
        }
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        if (!class_exists('WC_Logger')) {
            return;
        }
        if (self::is_old_wc_version()) {
            self::$logger = new WC_Logger();
        } else {
            self::$logger = wc_get_logger();
        }
    }

    /**
     * @return bool|int
     */
    private static function is_old_wc_version()
    {
        return version_compare(WC_VERSION, '3.0', '<');
    }
}

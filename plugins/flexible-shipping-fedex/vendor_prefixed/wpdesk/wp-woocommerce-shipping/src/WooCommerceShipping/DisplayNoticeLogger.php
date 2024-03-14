<?php

/**
 * WooCommerce Logger: WooCommerceLogger class.
 *
 * @package WPDesk\WooCommerceShipping
 */
namespace FedExVendor\WPDesk\WooCommerceShipping;

use FedExVendor\Psr\Log\LoggerInterface;
use FedExVendor\Psr\Log\LoggerTrait;
use FedExVendor\Psr\Log\LogLevel;
/**
 * Wants to show all logs using wc_add_notice
 */
class DisplayNoticeLogger implements \FedExVendor\Psr\Log\LoggerInterface
{
    const WC_NOTICE = 'notice';
    const WC_ERROR = 'error';
    const SERVICE_NAME = 'service_name';
    const DATA = 'data';
    const INSTANCE_ID = 'instance_id';
    use LoggerTrait;
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $service_name;
    /**
     * @var string
     */
    private $instance_id;
    /**
     * DisplayLogs constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger Logger.
     * @param string $service_name .
     * @param int $instance_id .
     */
    public function __construct(\FedExVendor\Psr\Log\LoggerInterface $logger, $service_name, $instance_id)
    {
        $this->logger = $logger;
        $this->service_name = $service_name;
        $this->instance_id = $instance_id;
    }
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level   Level.
     * @param string $message Message.
     * @param array  $context context.
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->logger->log($level, $message, $context);
        if (\in_array($level, [\FedExVendor\Psr\Log\LogLevel::DEBUG, \FedExVendor\Psr\Log\LogLevel::INFO], \true)) {
            $this->show($message, $context, self::WC_NOTICE);
        } else {
            $this->show($message, $context, self::WC_ERROR);
        }
    }
    /**
     * Show notices
     *
     * @param string $message Message.
     * @param array  $context context.
     * @param string $type    Type.
     *
     * @return void
     */
    private function show($message, array $context, $type)
    {
        $message = \sprintf('%1$s: %2$s', $this->service_name, $message);
        $dump = '';
        foreach ($context as $label => $value) {
            if (!\is_string($value)) {
                $value = \print_r($value, \true);
            }
            \ob_start();
            include __DIR__ . '/view/display-notice-context-single-value.php';
            $dump .= \ob_get_clean();
        }
        if (!\wc_has_notice($message . $dump, $type)) {
            \wc_add_notice($message . $dump, $type, [self::SERVICE_NAME => $this->service_name, self::INSTANCE_ID => $this->instance_id]);
        }
    }
}

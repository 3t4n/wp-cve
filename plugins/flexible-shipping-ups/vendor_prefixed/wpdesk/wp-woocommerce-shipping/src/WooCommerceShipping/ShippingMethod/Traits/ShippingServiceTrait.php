<?php

/**
 * Trait with ShippingService static injection
 *
 * @package WPDesk\WooCommerceShipping\ShippingMethod\Traits
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\Psr\Log\NullLogger;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingService;
use UpsFreeVendor\WPDesk\WooCommerceShipping\DisplayNoticeLogger;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod;
/**
 * Facilitates access to ShippingService abstract class with rates.
 *
 * @package WPDesk\WooCommerceShipping\ShippingMethod\Traits
 */
trait ShippingServiceTrait
{
    /**
     * @var LoggerInterface
     */
    private $service_logger;
    /**
     * @param ShippingMethod $shipping_method
     *
     * @return ShippingService
     */
    private function get_shipping_service(\UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod $shipping_method)
    {
        return $shipping_method->get_plugin_shipping_decisions()->get_shipping_service();
    }
    /**
     * Initializes and injects logger into service.
     *
     * @param ShippingService $service
     *
     * @return LoggerInterface
     */
    private function inject_logger_into(\UpsFreeVendor\WPDesk\AbstractShipping\ShippingService $service)
    {
        $logger = $this->get_service_logger($service);
        $service->setLogger($logger);
        return $logger;
    }
    /**
     * @param ShippingService $service
     *
     * @return LoggerInterface
     */
    private function get_service_logger(\UpsFreeVendor\WPDesk\AbstractShipping\ShippingService $service)
    {
        if (null === $this->service_logger) {
            if ($this->can_see_logs()) {
                $this->service_logger = new \UpsFreeVendor\WPDesk\WooCommerceShipping\DisplayNoticeLogger($this->get_logger($this), $service->get_name(), $this->instance_id);
            } else {
                $this->service_logger = new \UpsFreeVendor\Psr\Log\NullLogger();
            }
        }
        return $this->service_logger;
    }
}

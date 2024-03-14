<?php

/**
 * @package WPDesk\WooCommerceShipping
 */
namespace FedExVendor\WPDesk\WooCommerceShipping;

use FedExVendor\Psr\Log\LoggerInterface;
use FedExVendor\WPDesk\AbstractShipping\ShippingService;
use FedExVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
/**
 * Can provide plugin/service depending decisions for ShippingMethod.
 */
class PluginShippingDecisions
{
    /** @var ShippingService */
    private $service;
    /** @var LoggerInterface */
    private $logger;
    /**
     * AJAX handler for API status field.
     * API Status is checked asynchronously.
     * AJAX handler must be created in Plugin - it adds hooks.
     * Must be passed to ShippingMethod - it is used for nonce creation.
     *
     * @var FieldApiStatusAjax
     */
    private $field_api_status_ajax;
    /**
     * .
     *
     * @param ShippingService $service .
     * @param LoggerInterface $logger .
     *
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\ShippingService $service, \FedExVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }
    /**
     * @return ShippingService
     */
    public function get_shipping_service()
    {
        return $this->service;
    }
    /**
     * @return LoggerInterface
     */
    public function get_logger()
    {
        return $this->logger;
    }
    /**
     * @param FieldApiStatusAjax $field_api_status_ajax
     */
    public function set_field_api_status_ajax(\FedExVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax $field_api_status_ajax)
    {
        $this->field_api_status_ajax = $field_api_status_ajax;
    }
    /**
     * @return FieldApiStatusAjax
     */
    public function get_field_api_status_ajax()
    {
        return $this->field_api_status_ajax;
    }
}

<?php

/**
 * Custom fields: FieldApiStatusAjax class.
 *
 * @package WPDesk\WooCommerceShipping\CustomFields
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingService;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can handle ajax API status request.
 *
 * @package WPDesk\CustomFields
 */
class FieldApiStatusAjax implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Shipping service.
     *
     * @var ShippingService $shipping_service Shipping service.
     */
    private $shipping_service;
    /**
     * Settings.
     *
     * @var SettingsValues
     */
    private $settings;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * FieldApiStatusAjax constructor.
     *
     * @param ShippingService $shipping_service .
     * @param SettingsValues  $settings .
     * @param LoggerInterface $logger .
     */
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\ShippingService $shipping_service, \UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->shipping_service = $shipping_service;
        $this->settings = $settings;
        $this->logger = $logger;
    }
    public function hooks()
    {
        \add_action('wp_ajax_wpdesk_wc_shipping_api_status_' . $this->shipping_service->get_unique_id(), [$this, 'handle_api_status_ajax_request']);
    }
    /**
     * Handle ajax request.
     *
     * @param bool $return .
     *
     * @return void|string
     */
    public function handle_api_status_ajax_request($return = \false)
    {
        \check_ajax_referer($this->get_nonce_name(), 'security');
        $json_response = array('connected' => \true, 'status' => \__('OK', 'flexible-shipping-ups'), 'class_name' => 'wpdesk_wc_shipping_api_status_ok');
        $connection_errors = $this->check_connection_error();
        if ($connection_errors) {
            $json_response = array('connected' => \false, 'status' => $connection_errors, 'class_name' => 'wpdesk_wc_shipping_api_status_error');
        }
        if (!$return) {
            echo \json_encode($json_response);
            die;
        } else {
            return \json_encode($json_response);
        }
    }
    /**
     * Get nonce name.
     *
     * @return string
     */
    public function get_nonce_name()
    {
        return 'api-status-' . $this->shipping_service->get_unique_id();
    }
    /**
     * Get shipping service.
     *
     * @return ShippingService
     */
    protected function get_shipping_service()
    {
        return $this->shipping_service;
    }
    /**
     * Get settings;
     *
     * @return SettingsValues
     */
    protected function get_settings()
    {
        return $this->settings;
    }
    /**
     * Get logger.
     *
     * @return LoggerInterface
     */
    protected function get_logger()
    {
        return $this->logger;
    }
    /**
     * Check connection error.
     *
     * @return string
     */
    protected function check_connection_error()
    {
        if ($this->shipping_service instanceof \UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings) {
            return $this->shipping_service->check_connection($this->settings, $this->logger);
        }
        return \__('Shipping service should implements CanTestSettings interface! Or overwrite this method');
    }
}

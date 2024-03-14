<?php

/**
 * Tracker
 *
 * @package WPDesk\WooCommerceShipping\Fedex
 */
namespace FedExVendor\WPDesk\WooCommerceShipping\Fedex;

use FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition;
use FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod;
/**
 * Handles tracker actions.
 */
class Tracker implements \FedExVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const OPTION_VALUE_NO = 'no';
    const OPTION_VALUE_YES = 'yes';
    /**
     * Hooks.
     */
    public function hooks()
    {
        \add_filter('wpdesk_tracker_data', array($this, 'wpdesk_tracker_data_fedex'), 11);
    }
    /**
     * Prepare default plugin data.
     *
     * @param ShippingMethod $flexible_shipping_fedex Shipping method.
     *
     * @return array
     */
    protected function prepare_plugin_data(\FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod $flexible_shipping_fedex)
    {
        $custom_services = $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_ENABLE_CUSTOM_SERVICES, self::OPTION_VALUE_NO);
        return array('pro_version' => 'no', 'enable_shipping_method' => $flexible_shipping_fedex->get_option('enable_shipping_method', self::OPTION_VALUE_NO), 'title' => $flexible_shipping_fedex->get_option('title', 'FedEx'), 'fallback' => $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_FALLBACK, self::OPTION_VALUE_NO), 'custom_services' => $custom_services, 'insurance' => $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_INSURANCE, self::OPTION_VALUE_NO), 'request_type' => $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_REQUEST_TYPE, ''), 'destination_address_type' => $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_DESTINATION_ADDRESS_TYPE, ''), 'debug_mode' => $flexible_shipping_fedex->get_option('debug_mode', self::OPTION_VALUE_NO), 'units' => $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_UNITS, 'imperial'), 'origin_country' => $this->get_origin_country($flexible_shipping_fedex), 'fedex_services' => $this->prepare_custom_services($custom_services, $flexible_shipping_fedex->get_option(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_SERVICES_TABLE)));
    }
    /**
     * @param string $custom_services
     * @param array $services_table
     *
     * @return array
     */
    private function prepare_custom_services($custom_services, $services_table)
    {
        $service_array = [];
        if (self::OPTION_VALUE_YES === $custom_services) {
            foreach ($services_table as $key => $service) {
                if (isset($service['enabled'])) {
                    $service_array[$service['enabled']] = 1;
                }
            }
        }
        return $service_array;
    }
    /**
     * @param ShippingMethod $flexible_shipping_fedex Shipping method.
     *
     * @return string
     */
    protected function get_origin_country(\FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod $flexible_shipping_fedex)
    {
        list($origin_country) = \explode(':', \get_option('woocommerce_default_country', ''));
        return $origin_country;
    }
    /**
     * Add plugin data tracker.
     *
     * @param array $data Data.
     *
     * @return array
     */
    public function wpdesk_tracker_data_fedex(array $data)
    {
        $shipping_methods = \WC()->shipping()->get_shipping_methods();
        if (isset($shipping_methods['flexible_shipping_fedex'])) {
            /** @var ShippingMethod $flexible_shipping_fedex */
            $flexible_shipping_fedex = $shipping_methods['flexible_shipping_fedex'];
            $plugin_data = $this->prepare_plugin_data($flexible_shipping_fedex);
            $data['flexible_shipping_fedex'] = $plugin_data;
        }
        return $data;
    }
}

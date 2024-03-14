<?php

/**
 * Settings definitions.
 *
 * @package WPDesk\WooCommerceShipping\Fedex
 */
namespace FedExVendor\WPDesk\WooCommerceShipping\Fedex;

use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition;
use FedExVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use FedExVendor\WPDesk\WooCommerceShipping\ApiStatus\ApiStatusSettingsDefinitionDecorator;
use FedExVendor\WPDesk\WooCommerceShipping\CustomFields\FieldApiStatusAjax;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod;
/**
 * Can handle global and instance settings for WooCommerce shipping method.
 */
class FedexSettingsDefinitionWooCommerce extends \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition
{
    protected $global_method_fields = [\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FEDEX_HEADER, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::CREDENTIALS_HEADER, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_ACCOUNT_NUMBER, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_METER_NUMBER, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_API_KEY, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_API_PASSWORD, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::TESTING, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::SHIPPING_METHOD_HEADER, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::ENABLE_SHIPPING_METHOD, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::METHOD_TITLE, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::ADVANCED_OPTIONS_HEADER, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::DEBUG_MODE, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_UNITS, \FedExVendor\WPDesk\WooCommerceShipping\ApiStatus\ApiStatusSettingsDefinitionDecorator::API_STATUS];
    private $instance_and_method_fields = [\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::METHOD_TITLE];
    /**
     * Form fields.
     *
     * @var array
     */
    private $form_fields;
    /**
     * UpsSettingsDefinitionWooCommerce constructor.
     *
     * @param array $form_fields Form fields.
     */
    public function __construct(array $form_fields)
    {
        $this->form_fields = $form_fields;
    }
    /**
     * Get form fields.
     *
     * @return array
     */
    public function get_form_fields()
    {
        return $this->form_fields;
    }
    /**
     * Get instance form fields.
     *
     * @return array
     */
    public function get_instance_form_fields()
    {
        return $this->filter_instance_fields($this->form_fields, \true);
    }
    /**
     * Get global method fields.
     *
     * @return array
     */
    protected function get_global_method_fields()
    {
        return $this->global_method_fields;
    }
    /**
     * Filter instance form fields.
     *
     * @param array $all_fields .
     * @param bool  $instance_fields .
     *
     * @return array
     */
    private function filter_instance_fields(array $all_fields, $instance_fields)
    {
        $fields = array();
        foreach ($all_fields as $key => $field) {
            $is_instance_field = !\in_array($key, $this->get_global_method_fields(), \true) || \in_array($key, $this->instance_and_method_fields, \true);
            if ($instance_fields === $is_instance_field) {
                $fields[$key] = $field;
            }
        }
        return $fields;
    }
}

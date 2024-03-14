<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\CustomOrigin;

/**
 * Can replace fake custom_origin field with custom origin fields to shipping method settings fields.
 *
 * @package WPDesk\WooCommerceShipping\CustomOrigin
 */
class InstanceCustomOriginFields extends \FedExVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields
{
    const FIELD_TYPE_CUSTOM_ORIGIN = 'instance_custom_origin';
    const CUSTOM_ORIGIN = 'instance_custom_origin';
    const ORIGIN_ADDRESS = 'instance_origin_address';
    const ORIGIN_CITY = 'instance_origin_city';
    const ORIGIN_POSTCODE = 'instance_origin_postcode';
    const ORIGIN_COUNTRY = 'instance_origin_country';
    const OPTIONS_GENERATOR_COUNTRY_STATE_FOR_ORIGIN = 'country_state_for_origin';
    /**
     * @return string
     */
    protected function get_options_generator()
    {
        return self::OPTIONS_GENERATOR_COUNTRY_STATE_FOR_ORIGIN;
    }
    /**
     * @return string
     */
    public function get_custom_origin_section_title()
    {
        return \__('Origin Settings for this Shipping Method', 'flexible-shipping-fedex');
    }
    /**
     * @return string
     */
    protected function get_custom_origin_label()
    {
        return \__('Enable custom origin for this shipping method', 'flexible-shipping-fedex');
    }
    /**
     * @return string
     */
    protected function get_custom_origin_description()
    {
        return \__('Use this option to use a different shipper\'s address for this shipping method.', 'flexible-shipping-fedex');
    }
}

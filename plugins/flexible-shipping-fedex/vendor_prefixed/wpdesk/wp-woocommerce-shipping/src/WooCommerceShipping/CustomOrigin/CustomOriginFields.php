<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\CustomOrigin;

/**
 * Can replace fake custom_origin field with custom origin fields to shipping method settings fields.
 *
 * @package WPDesk\WooCommerceShipping\CustomOrigin
 */
class CustomOriginFields
{
    const FIELD_TYPE_CUSTOM_ORIGIN = 'custom_origin';
    const CUSTOM_ORIGIN = 'custom_origin';
    const ORIGIN_ADDRESS = 'origin_address';
    const ORIGIN_CITY = 'origin_city';
    const ORIGIN_POSTCODE = 'origin_postcode';
    const ORIGIN_COUNTRY = 'origin_country';
    const OPTIONS_GENERATOR_COUNTRY_STATE = 'country_state';
    /**
     * @var bool
     */
    private $has_instance_custom_origin = \false;
    /**
     * @param bool $has_instance_custom_origin
     */
    public function __construct(bool $has_instance_custom_origin)
    {
        $this->has_instance_custom_origin = $has_instance_custom_origin;
    }
    /**
     * Replace custom_origin fake field with checkbox and input fields in settings.
     *
     * @param array $settings
     *
     * @return array
     */
    public function replace_fallback_field_if_exists(array $settings)
    {
        $new_settings = [];
        foreach ($settings as $key => $field) {
            if ($field['type'] === static::FIELD_TYPE_CUSTOM_ORIGIN) {
                $new_settings[static::CUSTOM_ORIGIN] = ['title' => \__('Custom Origin', 'flexible-shipping-fedex'), 'label' => $this->get_custom_origin_label(), 'type' => 'checkbox', 'description' => $this->get_custom_origin_description(), 'desc_tip' => \true, 'default' => 'no', 'class' => 'custom_origin'];
                $new_settings[static::ORIGIN_ADDRESS] = ['title' => \__('Origin Address', 'flexible-shipping-fedex'), 'type' => 'text', 'custom_attributes' => ['required' => 'required', 'autocomplete' => 'off', 'list' => 'autocompleteOff', 'aria-autocomplete' => 'list'], 'default' => '', 'class' => 'custom_origin_field'];
                $new_settings[static::ORIGIN_CITY] = ['title' => \__('Origin City', 'flexible-shipping-fedex'), 'type' => 'text', 'custom_attributes' => ['required' => 'required', 'autocomplete' => 'off', 'list' => 'autocompleteOff', 'aria-autocomplete' => 'list'], 'default' => '', 'class' => 'custom_origin_field'];
                $new_settings[static::ORIGIN_POSTCODE] = ['title' => \__('Origin Postcode', 'flexible-shipping-fedex'), 'type' => 'text', 'custom_attributes' => ['required' => 'required', 'autocomplete' => 'off', 'list' => 'autocompleteOff', 'aria-autocomplete' => 'list'], 'default' => '', 'class' => 'custom_origin_field'];
                $new_settings[static::ORIGIN_COUNTRY] = ['title' => \__('Origin Country/State', 'flexible-shipping-fedex'), 'type' => 'select', 'options' => [], 'options_generator' => $this->get_options_generator(), 'custom_attributes' => ['required' => 'required', 'autocomplete' => 'off', 'list' => 'autocompleteOff', 'aria-autocomplete' => 'list'], 'default' => '', 'class' => 'custom_origin_field custom_origin_country'];
            } else {
                $new_settings[$key] = $field;
            }
        }
        return $new_settings;
    }
    /**
     * @return string
     */
    protected function get_options_generator()
    {
        return self::OPTIONS_GENERATOR_COUNTRY_STATE;
    }
    /**
     * @return string
     */
    public function get_custom_origin_section_title()
    {
        if ($this->has_instance_custom_origin) {
            return \__('Origin Settings for the whole store', 'flexible-shipping-fedex');
        } else {
            return \__('Origin Settings', 'flexible-shipping-fedex');
        }
    }
    /**
     * @return string
     */
    protected function get_custom_origin_label()
    {
        if ($this->has_instance_custom_origin) {
            return \__('Enable custom origin for the whole store', 'flexible-shipping-fedex');
        } else {
            return \__('Enable custom origin', 'flexible-shipping-fedex');
        }
    }
    /**
     * @param bool $has_instance_custom_origin
     *
     * @return string
     */
    protected function get_custom_origin_description()
    {
        if ($this->has_instance_custom_origin) {
            return \__('Use this option to use a different shipper\'s address than the one defined in the WooCommerce settings. If not enabled, the one you\'ve entered in WooCommerce → Settings → General → Store Address will be used by default. Each shipping method can have their own custom origin.', 'flexible-shipping-fedex');
        } else {
            return \__('Use this option to use a different shipper\'s address than the one defined in the WooCommerce settings. If not enabled, the one you\'ve entered in WooCommerce → Settings → General → Store Address will be used by default.', 'flexible-shipping-fedex');
        }
    }
}

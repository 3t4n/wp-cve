<?php

/**
 * Ups Shipping Method.
 *
 * @package WPDesk\WooCommerceShipping\Ups
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups;

use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthField;
use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthUrl;
use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsServices;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod;
/**
 * UPS Shipping Method.
 */
class UpsShippingMethod extends \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod implements \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasFreeShipping, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasCustomOrigin, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasRateCache
{
    /**
     * Supports.
     *
     * @var array
     */
    public $supports = ['settings', 'shipping-zones', 'instance-settings'];
    /**
     * @var FieldApiStatusAjax
     */
    protected static $api_status_ajax_handler;
    /**
     * Set api status field AJAX handler.
     *
     * @param FieldApiStatusAjax $api_status_ajax_handler .
     */
    public static function set_api_status_ajax_handler(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax $api_status_ajax_handler)
    {
        static::$api_status_ajax_handler = $api_status_ajax_handler;
    }
    /**
     * Prepare description.
     * Description depends on current page.
     *
     * @return string
     */
    private function prepare_description()
    {
        if ('pl_PL' === \get_locale()) {
            $docs_link = 'https://octol.io/ups-method-docs-pl';
        } else {
            $docs_link = 'https://octol.io/ups-method-docs';
        }
        return \sprintf(
            // Translators: docs URL.
            \__('Dynamically calculated UPS live rates based on the established UPS API connection. %1$sLearn more →%2$s', 'flexible-shipping-ups'),
            '<a target="_blank" href="' . $docs_link . '">',
            '</a>'
        );
    }
    /**
     * Init method.
     */
    public function init()
    {
        parent::init();
        $this->method_description = $this->prepare_description();
    }
    /**
     * Init form fields.
     */
    public function build_form_fields()
    {
        $default_api_type_xml = empty($this->get_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE, '')) && !empty($this->get_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::USER_ID, ''));
        $ups_settings_definition = new \UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\UpsSettingsDefinitionWooCommerce($this->form_fields, $default_api_type_xml);
        $this->form_fields = $ups_settings_definition->get_form_fields();
        $this->instance_form_fields = $ups_settings_definition->get_instance_form_fields();
    }
    /**
     * Create meta data builder.
     *
     * @return UpsMetaDataBuilder
     */
    protected function create_metadata_builder()
    {
        return new \UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\UpsMetaDataBuilder($this);
    }
    /**
     * Prepare settings fields for display.
     */
    private function prepare_settings_fields_for_display()
    {
        $this->instance_form_fields[\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::SERVICES]['options'] = \UpsFreeVendor\WPDesk\UpsShippingService\UpsServices::get_services_for_country($this->get_origin_country_code());
        $this->form_fields[\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ORIGIN_SETTINGS_TITLE]['title'] = (new \UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields($this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasInstanceCustomOrigin))->get_custom_origin_section_title();
    }
    /**
     * Render shipping method settings.
     */
    public function admin_options()
    {
        $this->prepare_settings_fields_for_display();
        parent::admin_options();
        include __DIR__ . '/view/shipping-method-script.php';
    }
    /**
     * Get enabled services.
     *
     * @return array
     */
    public function get_enabled_services()
    {
        $enabled_services = $this->get_available_services();
        foreach ($enabled_services as $service_code => $enabled_service) {
            if (!$enabled_service['enabled']) {
                unset($enabled_services[$service_code]);
            }
        }
        return $enabled_services;
    }
    /**
     * Get available UPS services.
     *
     * @param bool $get_current_services Get current services.
     *
     * @return array
     */
    private function get_available_services($get_current_services = \true)
    {
        $country_code = '';
        if ($this->is_custom_origin()) {
            $country_codes = \explode(':', $this->get_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ORIGIN_COUNTRY, ''));
            $country_code = $country_codes[0];
        } else {
            $woocommerce_default_country = \explode(':', \get_option('woocommerce_default_country', ''));
            if (!empty($woocommerce_default_country[0])) {
                $country_code = $woocommerce_default_country[0];
            }
        }
        $services_available = \UpsFreeVendor\WPDesk\UpsShippingService\UpsServices::get_services_for_country($country_code);
        $services = [];
        if ($get_current_services) {
            $current_services = $this->get_instance_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::SERVICES, []);
            foreach ($current_services as $service_code => $service) {
                $services[$service_code] = $service;
            }
        }
        foreach ($services_available as $service_code => $service_name) {
            if (empty($services[$service_code])) {
                $services[$service_code] = ['name' => $service_name, 'enabled' => \true];
            }
        }
        return $services;
    }
    public function generate_oauth_html($key, $data) : string
    {
        return (new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthField($key, $data, $this->get_field_key($key), new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption(), 'admin.php?page=wc-settings&tab=shipping&section=flexible_shipping_ups', (new \UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\OAuthUrl())->get_url()))->generate_oauth_html();
    }
}

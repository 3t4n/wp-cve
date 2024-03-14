<?php

/**
 * Decorator for api status settings field.
 *
 * @package WPDesk\WooCommerceShipping\ApiStatus
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\ApiStatus;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier\SettingsDefinitionModifierAfter;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatus;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
/**
 * Can decorate settings for estimated delivery field.
 */
class ApiStatusSettingsDefinitionDecorator extends \UpsFreeVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier\SettingsDefinitionModifierAfter
{
    const API_STATUS = 'api_status';
    /**
     * ApiStatusSettingsDefinitionDecorator constructor.
     *
     * @param SettingsDefinition $ups_settings_definition .
     * @param string $after_field API Status field will be added after this field.
     * @param FieldApiStatusAjax $api_status_ajax_handler .
     * @param string $service_id .
     */
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition $ups_settings_definition, $after_field, \UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax $api_status_ajax_handler, $service_id)
    {
        parent::__construct($ups_settings_definition, $after_field, self::API_STATUS, ['title' => \__('API Connection Status', 'flexible-shipping-ups'), 'type' => 'api_status', 'class' => 'flexible_shipping_api_status', 'default' => \__('Checking...', 'flexible-shipping-ups'), 'description' => \__('If you encounter any problems with establishing the API connection, the detailed information on its cause will be displayed here.', 'flexible-shipping-ups'), 'desc_tip' => \true, \UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatus::SECURITY_NONCE => \wp_create_nonce($api_status_ajax_handler->get_nonce_name()), \UpsFreeVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatus::SHIPPING_SERVICE_ID => $service_id]);
    }
}

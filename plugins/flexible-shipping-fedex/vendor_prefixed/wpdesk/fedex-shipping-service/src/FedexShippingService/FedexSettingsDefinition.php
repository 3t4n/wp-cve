<?php

namespace FedExVendor\WPDesk\FedexShippingService;

use FedExVendor\FedEx\RateService\SimpleType\RateTypeBasisType;
use FedExVendor\FedEx\RateService\SimpleType\ServiceType;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
/**
 * A class that defines the basic settings for the shipping method.
 *
 * @package WPDesk\FedexShippingService
 */
class FedexSettingsDefinition extends \FedExVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition
{
    const CUSTOM_SERVICES_CHECKBOX_CLASS = 'wpdesk_wc_shipping_custom_service_checkbox';
    const FIELD_TYPE_FALLBACK = 'fallback';
    /**
     * FedEx services.
     */
    const SERVICES = [\FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_2_DAY_AM => 'FedEx 2Day A.M.', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_2_DAY => 'FedEx 2Day', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_EXPRESS_SAVER => 'FedEx Express Saver', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_GROUND => 'FedEx Ground', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_GROUND_HOME_DELIVERY => 'FedEx Ground Home Delivery', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_SMART_POST => 'FedEx Ground Economy (Formerly known as SmartPost)', 'FEDEX_INTERNATIONAL_CONNECT_PLUS' => 'FedEx International Connect Plus', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_INTERNATIONAL_ECONOMY => 'FedEx International Economy', 'INTERNATIONAL_GROUND' => 'FedEx International Ground', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_EUROPE_FIRST_INTERNATIONAL_PRIORITY => 'FedEx Europe First International Priority', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FIRST_OVERNIGHT => 'FedEx Overnight', 'FEDEX_PRIORITY' => 'FedEx Priority', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_PRIORITY_OVERNIGHT => 'FedEx Priority Overnight', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_STANDARD_OVERNIGHT => 'FedEx Standard Overnight', 'FEDEX_REGIONAL_ECONOMY' => 'FedEx Regional Economy', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_INTERNATIONAL_FIRST => 'FedEx International First', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_INTERNATIONAL_PRIORITY => 'FedEx International Priority', 'FEDEX_INTERNATIONAL_PRIORITY' => 'FedEx International Priority', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_DISTANCE_DEFERRED => 'FedEx Distance Deferred', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_AFTERNOON => 'FedEx Next Day Afternoon', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_EARLY_MORNING => 'FedEx Next Day Early Morning', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_END_OF_DAY => 'FedEx Next Day End of Day', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_FREIGHT => 'FedEx Next Day Freight', \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_MID_MORNING => 'FedEx Next Day Mid Morning'];
    const UK_SERVICES = [\FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_DISTANCE_DEFERRED, \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_AFTERNOON, \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_EARLY_MORNING, \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_END_OF_DAY, \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_FREIGHT, \FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_NEXT_DAY_MID_MORNING];
    const FIELD_SERVICES_TABLE = 'services';
    const FIELD_ENABLE_CUSTOM_SERVICES = 'enable_custom_services';
    const FIELD_INSURANCE = 'insurance';
    const FIELD_REQUEST_TYPE = 'request_type';
    const FIELD_REQUEST_TYPE_VALUE_ALL = 'all';
    const FIELD_FALLBACK = 'fallback';
    const FIELD_UNITS = 'units';
    const UNITS_IMPERIAL = 'imperial';
    const UNITS_METRIC = 'metric';
    const RATE_ADJUSTMENTS_TITLE = 'rate_adjustments_title';
    const FIELD_DESTINATION_ADDRESS_TYPE = 'destination_address_type';
    const FIELD_API_PASSWORD = 'api_password';
    const FIELD_API_KEY = 'api_key';
    const FIELD_METER_NUMBER = 'meter_number';
    const FIELD_ACCOUNT_NUMBER = 'account_number';
    const ENABLE_SHIPPING_METHOD = 'enable_shipping_method';
    const FEDEX_HEADER = 'fedex_header';
    const CREDENTIALS_HEADER = 'credentials_header';
    const TESTING = 'testing';
    const SHIPPING_METHOD_HEADER = 'shipping_method_header';
    const METHOD_TITLE = 'title';
    const ADVANCED_OPTIONS_HEADER = 'advanced_options_header';
    const DEBUG_MODE = 'debug_mode';
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * FedexSettingsDefinition constructor.
     *
     * @param ShopSettings $shop_settings Shop settings.
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings)
    {
        $this->shop_settings = $shop_settings;
    }
    /**
     * Validate settings.
     *
     * @param SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function validate_settings(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return \true;
    }
    /**
     * Get units default.
     *
     * @return string
     */
    private function get_units_default()
    {
        $weight_unit = $this->shop_settings->get_weight_unit();
        if (\in_array($weight_unit, array('g', 'kg'), \true)) {
            return self::UNITS_METRIC;
        }
        return self::UNITS_IMPERIAL;
    }
    /**
     * Initialise Settings Form Fields.
     */
    public function get_form_fields()
    {
        $services = self::SERVICES;
        if ('CA' === $this->shop_settings->get_origin_country()) {
            $services[\FedExVendor\FedEx\RateService\SimpleType\ServiceType::_FEDEX_EXPRESS_SAVER] = 'FedEx Economy';
        }
        $locale = $this->shop_settings->get_locale();
        $is_pl = 'pl_PL' === $locale;
        $docs_link = $is_pl ? 'https://octol.io/fedex-settings-docs-pl' : 'https://octol.io/fedex-setting-docs';
        $credential_link = $is_pl ? 'https://octol.io/fedex-how-to-pl' : 'https://octol.io/fedex-how-to';
        $upgrade_url = $is_pl ? 'https://octol.io/fedex-up-method-pl' : 'https://octol.io/fedex-up-method';
        $debug_mode_link = $is_pl ? 'https://octol.io/fedex-debug-mode-pl' : 'https://octol.io/fedex-debug-mode';
        $debug_mode_info = \sprintf(\__('If you have encountered any issues with calculating or displaying the live rates properly enable the debug mode to be able to analyse the FedEx API requests and responses and to find out what\'s causing the problem. %1$sLearn more about the debug mode →%2$s', 'flexible-shipping-fedex'), '<a href="' . \esc_url($debug_mode_link) . '" target="_blank">', '</a>');
        $services_desc = \sprintf(\__('Services %1$s are available only for UK domestic shipments.', 'flexible-shipping-fedex'), \implode(', ', self::UK_SERVICES));
        $connection_fields = [self::FEDEX_HEADER => [
            'title' => \__('FedEx', 'flexible-shipping-fedex'),
            'type' => 'title',
            // translators: %1$s: fedex open URL, %2$s: fedex close URL.
            'description' => \sprintf(\__('These are a general settings of Flexible Shipping FedEx plugin. To learn more about configuration go to the %1$sinstruction manual →%2$s', 'flexible-shipping-fedex'), '<a href="' . \esc_url($docs_link) . '" target="_blank">', '</a>') . '<br/><br/>' . $debug_mode_info,
        ], self::CREDENTIALS_HEADER => [
            'title' => \__('Credentials', 'flexible-shipping-fedex'),
            'type' => 'title',
            // translators: %1$s: fedex open URL, %2$s: fedex close URL.
            'description' => \sprintf(\__('You need to provide FedEx account credentials to get live rates. Check out our tutorial on %1$show to obtain FedEx credentials →%2$s', 'flexible-shipping-fedex'), '<a href="' . \esc_url($credential_link) . '" target="_blank">', '</a>'),
        ], self::FIELD_ACCOUNT_NUMBER => ['title' => \__('FedEx Account Number ', 'flexible-shipping-fedex'), 'type' => 'text', 'custom_attributes' => ['required' => 'required']], self::FIELD_METER_NUMBER => ['title' => \__('FedEx Meter Number', 'flexible-shipping-fedex'), 'type' => 'text', 'custom_attributes' => ['required' => 'required']], self::FIELD_API_KEY => ['title' => \__('FedEx Web Services Key', 'flexible-shipping-fedex'), 'type' => 'text', 'custom_attributes' => ['required' => 'required']], self::FIELD_API_PASSWORD => ['title' => \__('FedEx Web Services Password ', 'flexible-shipping-fedex'), 'type' => 'password', 'custom_attributes' => ['required' => 'required']]];
        if ($this->shop_settings->is_testing()) {
            $connection_fields[self::TESTING] = ['title' => \__('Test Credentials', 'flexible-shipping-fedex'), 'type' => 'checkbox', 'label' => \__('Enable to use test credentials', 'flexible-shipping-fedex'), 'desc_tip' => \true];
        }
        $custom_fields = [self::SHIPPING_METHOD_HEADER => ['title' => \__('Method Settings', 'flexible-shipping-fedex'), 'type' => 'title', 'description' => \__('Set how FedEx services are displayed.', 'flexible-shipping-fedex')], self::ENABLE_SHIPPING_METHOD => ['title' => \__('Method Enable', 'flexible-shipping-fedex'), 'type' => 'checkbox', 'label' => \__('Enable FedEx global shipping method', 'flexible-shipping-fedex'), 'description' => \__('If you need to turn off FedEx rates display in the shop, just uncheck this option.', 'flexible-shipping-fedex'), 'custom_attributes' => ['data-description' => \sprintf(\__('Gain even more flexibility and add the FedEx Live Rates within specific shipping zones instead of using the Global shipping method. %1$sUpgrade your FedEx WooCommerce Live Rates plugin to PRO now →%2$s', 'flexible-shipping-fedex'), '<a href="' . $upgrade_url . '" target="_blank">', '</a>')], 'desc_tip' => \true, 'default' => 'yes'], self::METHOD_TITLE => ['title' => \__('Method Title', 'flexible-shipping-fedex'), 'type' => 'text', 'description' => \__('This controls the title which the user sees during checkout when fallback is used.', 'flexible-shipping-fedex'), 'desc_tip' => \true, 'default' => \__('FedEx', 'flexible-shipping-fedex')], self::FIELD_FALLBACK => ['title' => self::FIELD_FALLBACK, 'type' => self::FIELD_FALLBACK], self::FIELD_ENABLE_CUSTOM_SERVICES => ['title' => \__('Custom Services', 'flexible-shipping-fedex'), 'type' => 'checkbox', 'label' => \__('Enable custom services', 'flexible-shipping-fedex'), 'description' => \__('Enable if you want to select available services. By enabling a service, it does not guarantee that it will be offered, as the plugin will only offer the available rates based on the package weight, the origin and the destination.', 'flexible-shipping-fedex'), 'desc_tip' => \true, 'class' => self::CUSTOM_SERVICES_CHECKBOX_CLASS], self::FIELD_SERVICES_TABLE => ['title' => \__('Services Table', 'flexible-shipping-fedex'), 'type' => 'services', 'options' => $services, 'description' => $services_desc], self::RATE_ADJUSTMENTS_TITLE => ['title' => \__('Rates Adjustments', 'flexible-shipping-fedex'), 'description' => \__('Adjust these settings to get more accurate rates.', 'flexible-shipping-fedex'), 'type' => 'title'], self::FIELD_INSURANCE => ['title' => \__('Insurance', 'flexible-shipping-fedex'), 'type' => 'checkbox', 'label' => \__('Request insurance to be included in FedEx rates', 'flexible-shipping-fedex'), 'description' => \__('Enable if you want to include insurance in FedEx rates when it is available.', 'flexible-shipping-fedex'), 'desc_tip' => \true], self::FIELD_REQUEST_TYPE => ['title' => \__('Rate Type', 'flexible-shipping-fedex'), 'type' => 'select', 'default' => self::FIELD_REQUEST_TYPE_VALUE_ALL, 'class' => '', 'desc_tip' => \true, 'options' => array(self::FIELD_REQUEST_TYPE_VALUE_ALL => \__('All possible rates', 'flexible-shipping-fedex'), \FedExVendor\FedEx\RateService\SimpleType\RateTypeBasisType::_LIST => \__('List rates', 'flexible-shipping-fedex'), \FedExVendor\FedEx\RateService\SimpleType\RateTypeBasisType::_ACCOUNT => \__('Account rates', 'flexible-shipping-fedex')), 'description' => \__('List rates are set by default. In order to obtain the account rates on your FedEx account please contact FedEx support at fedex.com.', 'flexible-shipping-fedex')], self::FIELD_DESTINATION_ADDRESS_TYPE => ['title' => \__('Destination Address Type', 'flexible-shipping-fedex'), 'type' => 'select', 'description' => \__('Destination Type to use with this method.', 'flexible-shipping-fedex'), 'options' => ['0' => \__('Business', 'flexible-shipping-fedex'), '1' => \__('Residential', 'flexible-shipping-fedex')], 'desc_tip' => \true], self::ADVANCED_OPTIONS_HEADER => ['title' => \__('Advanced Options', 'flexible-shipping-fedex'), 'type' => 'title'], self::DEBUG_MODE => ['title' => \__('Debug Mode', 'flexible-shipping-fedex'), 'label' => \__('Enable debug mode', 'flexible-shipping-fedex'), 'type' => 'checkbox', 'description' => $debug_mode_info], self::FIELD_UNITS => ['title' => \__('Measurement Units', 'flexible-shipping-fedex'), 'type' => 'select', 'options' => array(self::UNITS_IMPERIAL => \__('LBS/IN', 'flexible-shipping-fedex'), self::UNITS_METRIC => \__('KG/CM', 'flexible-shipping-fedex')), 'description' => \__('By default store settings are used. If you see "This measurement system is not valid for the selected country" errors, switch units. Units in the store settings will be converted to units required by FedEx.', 'flexible-shipping-fedex'), 'desc_tip' => \true, 'default' => $this->get_units_default()]];
        return \array_replace($connection_fields, $custom_fields);
    }
}

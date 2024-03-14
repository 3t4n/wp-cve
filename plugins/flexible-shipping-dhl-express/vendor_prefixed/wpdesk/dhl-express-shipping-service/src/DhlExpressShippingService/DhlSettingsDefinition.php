<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService;

use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
/**
 * A class that defines the basic settings for the shipping method.
 *
 * @package WPDesk\DhlShippingService
 */
class DhlSettingsDefinition extends \DhlVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition
{
    const CUSTOM_SERVICES_CHECKBOX_CLASS = 'wpdesk_wc_shipping_custom_service_checkbox';
    const FIELD_TYPE_FALLBACK = 'fallback';
    const FIELD_SERVICES_TABLE = 'services';
    const FIELD_ENABLE_CUSTOM_SERVICES = 'enable_custom_services';
    const FIELD_INSURANCE = 'insurance';
    const FIELD_FALLBACK = 'fallback';
    const FIELD_UNITS = 'units';
    const UNITS_IMPERIAL = 'imperial';
    const UNITS_METRIC = 'metric';
    const RATE_ADJUSTMENTS_TITLE = 'rate_adjustments_title';
    const FIELD_API_PASSWORD = 'api_password';
    const FIELD_USE_PAYMENT_ACCOUNT_NUMBER = 'use_payment_account_number';
    const FIELD_PAYMENT_ACCOUNT_NUMBER = 'payment_account_number';
    const FIELD_SITE_ID = 'site_id';
    const FIELD_TESTING = 'testing';
    const FIELD_PACKING_METHOD = 'packing_method';
    const PACKING_METHOD_WEIGHT = 'weight';
    const PACKING_METHOD_SEPARATELY = 'separately';
    const IS_DUTIABLE = 'is_dutiable';
    const NEVER = 'never';
    const ALWAYS = 'always';
    const SELECTED_COUNTRIES = 'selected_countries';
    const EXCEPT_SELECTED_COUNTRIES = 'except_selected_countries';
    const DUTIABLE_SELECTED_COUNTRIES = 'dutiable_selected_countries';
    const ENABLE_SHIPPING_METHOD = 'enable_shipping_method';
    const ENABLE_SHIPPING_METHOD_DEFAULT = 'no';
    const DHL_HEADER = 'dhl_header';
    const CREDENTIALS_HEADER = 'credentials_header';
    const SHIPPING_METHOD_HEADER = 'shipping_method_header';
    const METHOD_TITLE = 'method_title';
    const DUTIES_OPTIONS_HEADER = 'duties_options_header';
    const ADVANCED_OPTIONS_HEADER = 'advanced_options_header';
    const DEBUG_MODE = 'debug_mode';
    const FIELD_API_TYPE = 'api_type';
    const API_TYPE_REST = 'rest';
    const API_TYPE_XML = 'xml';
    const FIELD_API_KEY = 'api_key';
    const FIELD_API_SECRET = 'api_secret';
    const CLASS_DHL_XML_API = 'dhl-api-field-xml dhl-api-field';
    const CLASS_DHL_REST_API = 'dhl-api-field-rest dhl-api-field';
    const FIELD_ACCOUNT_NUMBER = 'account_number';
    const PACKAGE_SETTINGS_TITLE = 'package_settings_title';
    const PACKAGE_LENGTH = 'package_length';
    const PACKAGE_WIDTH = 'package_width';
    const PACKAGE_HEIGHT = 'package_height';
    const PACKAGE_WEIGHT = 'package_weight';
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * @var SettingsValuesAsArray|null
     */
    private $dhl_settings;
    /**
     * DhlSettingsDefinition constructor.
     *
     * @param ShopSettings $shop_settings Shop settings.
     */
    public function __construct(\DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings, \DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray $dhl_settings = null)
    {
        $this->shop_settings = $shop_settings;
        $this->dhl_settings = $dhl_settings;
    }
    /**
     * Validate settings.
     *
     * @param SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function validate_settings(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
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
        $dhl_services = new \DhlVendor\WPDesk\DhlExpressShippingService\DhlServices();
        $services = $dhl_services->get_grouped_services();
        $locale = $this->shop_settings->get_locale();
        $is_pl = 'pl_PL' === $locale;
        $docs_link = 'https://octol.io/dhlexpress-settings-docs';
        $upgrade_url = $is_pl ? 'https://octol.io/dhlexpress-up-method-pl' : 'https://octol.io/dhlexpress-up-method';
        $default_api_type = self::API_TYPE_REST;
        if ($this->dhl_settings->get_value(self::FIELD_SITE_ID)) {
            $default_api_type = self::API_TYPE_XML;
        }
        $weight_unit = $this->dhl_settings->get_value(self::FIELD_UNITS, $this->get_units_default()) === self::UNITS_METRIC ? 'kg' : 'lbs';
        $dimension_unit = $this->dhl_settings->get_value(self::FIELD_UNITS, $this->get_units_default()) === self::UNITS_METRIC ? 'cm' : 'in';
        $connection_fields = [self::DHL_HEADER => ['title' => \__('DHL Express', 'flexible-shipping-dhl-express'), 'type' => 'title'], self::CREDENTIALS_HEADER => ['title' => \__('Credentials', 'flexible-shipping-dhl-express'), 'type' => 'title', 'description' => \sprintf(
            // Translators: link.
            \__('You need to provide DHL Express account credentials to get live rates. Learn %1$show to create a DHL Express account →%2$s', 'flexible-shipping-dhl-express'),
            '<a href=" ' . $this->prepare_create_account_docs_link() . ' " target="_blank">',
            '</a>'
        )], self::FIELD_API_TYPE => ['title' => \__('API Type', 'flexible-shipping-dhl-express'), 'type' => 'select', 'desc_tip' => \__('Select API type', 'flexible-shipping-dhl-express'), 'options' => [self::API_TYPE_REST => \__('REST API', 'flexible-shipping-dhl-express'), self::API_TYPE_XML => \__('XML API', 'flexible-shipping-dhl-express')], 'default' => $default_api_type], self::FIELD_SITE_ID => ['title' => \__('Site ID', 'flexible-shipping-dhl-express') . ' *', 'type' => 'text', 'custom_attributes' => ['required' => 'required'], 'class' => self::CLASS_DHL_XML_API], self::FIELD_API_PASSWORD => ['title' => \__('Password', 'flexible-shipping-dhl-express') . ' *', 'type' => 'password', 'custom_attributes' => ['required' => 'required'], 'description' => \sprintf(
            // Translators: HTML strong tag and link.
            \__('In order to get the %1$sSite ID%2$s log in to your DHL Express account %3$shere%4$s and copy it from %1$sMy profile%2$s or %1$sXML Services Status%2$s tabs. If you haven\'t received an email containing the %1$sSite ID%2$s and %1$spassword%2$s, please contact the DHL Express support directly.', 'flexible-shipping-dhl-express'),
            '<strong>',
            '</strong>',
            '<a href="https://xmlportal.dhl.com/login" target="_blank">',
            '</a>'
        ), 'class' => self::CLASS_DHL_XML_API], self::FIELD_ACCOUNT_NUMBER => ['title' => \__('Shipper account number', 'flexible-shipping-dhl-express') . ' *', 'type' => 'text', 'custom_attributes' => ['required' => 'required'], 'class' => self::CLASS_DHL_REST_API], self::FIELD_API_KEY => ['title' => \__('API Key', 'flexible-shipping-dhl-express') . ' *', 'type' => 'text', 'custom_attributes' => ['required' => 'required'], 'class' => self::CLASS_DHL_REST_API], self::FIELD_API_SECRET => ['title' => \__('API Secret', 'flexible-shipping-dhl-express') . ' *', 'type' => 'password', 'custom_attributes' => ['required' => 'required'], 'description' => \sprintf(
            // Translators: HTML strong tag and link.
            \__('In order to get the %1$saccount number, API Key and API Secret%2$s create application on %3$sDHL Developers portal%4$s.', 'flexible-shipping-dhl-express'),
            '<strong>',
            '</strong>',
            '<a href="https://developer.dhl.com/user/apps" target="_blank">',
            '</a>'
        ), 'class' => self::CLASS_DHL_REST_API]];
        $connection_fields[self::FIELD_TESTING] = ['title' => \__('Test Credentials', 'flexible-shipping-dhl-express'), 'type' => 'checkbox', 'label' => \__('Enable to use test credentials', 'flexible-shipping-dhl-express'), 'desc_tip' => \true];
        $custom_fields = [self::SHIPPING_METHOD_HEADER => ['title' => \__('Method Settings', 'flexible-shipping-dhl-express'), 'type' => 'title', 'description' => \__('Set how DHL Express services are displayed.', 'flexible-shipping-dhl-express')], self::ENABLE_SHIPPING_METHOD => ['title' => \__('Enable/Disable', 'flexible-shipping-dhl-express'), 'type' => 'checkbox', 'label' => \__('Enable DHL Express global shipping method', 'flexible-shipping-dhl-express'), 'description' => \sprintf(\__('If you need to turn off DHL Express rates display in the shop, just uncheck this option.', 'flexible-shipping-dhl-express')), 'custom_attributes' => ['data-description' => \sprintf(\__('Gain even more flexibility and add the DHL Express Live Rates within specific shipping zones instead of using the Global shipping method. %1$sUpgrade your DHL Express WooCommerce Live Rates plugin to PRO now →%2$s', 'flexible-shipping-dhl-express'), '<a href="' . $upgrade_url . '" target="_blank">', '</a>')], 'desc_tip' => \true, 'default' => 'yes'], self::METHOD_TITLE => ['title' => \__('Method Title', 'flexible-shipping-dhl-express'), 'type' => 'text', 'description' => \__('This controls the title which the user sees during checkout when fallback is used.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'default' => \__('DHL Express', 'flexible-shipping-dhl-express')], self::FIELD_FALLBACK => ['title' => self::FIELD_FALLBACK, 'type' => self::FIELD_FALLBACK], self::FIELD_ENABLE_CUSTOM_SERVICES => ['title' => \__('Custom Services', 'flexible-shipping-dhl-express'), 'type' => 'checkbox', 'label' => \__('Enable custom services', 'flexible-shipping-dhl-express'), 'description' => \__('Enable if you want to select available services. By enabling a service, it does not guarantee that it will be offered, as the plugin will only offer the available rates based on the package weight, the origin and the destination.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'class' => self::CUSTOM_SERVICES_CHECKBOX_CLASS], self::FIELD_SERVICES_TABLE => ['title' => \__('Services Table', 'flexible-shipping-dhl-express'), 'type' => 'services', 'options' => $services], self::RATE_ADJUSTMENTS_TITLE => ['title' => \__('Rates Adjustments', 'flexible-shipping-dhl-express'), 'description' => \__('Adjust these settings to get more accurate rates.', 'flexible-shipping-dhl-express'), 'type' => 'title'], self::FIELD_USE_PAYMENT_ACCOUNT_NUMBER => ['title' => \__('Discounted Rates', 'flexible-shipping-dhl-express'), 'type' => 'checkbox', 'label' => \__('Enable if you want to use discounted rates', 'flexible-shipping-dhl-express'), 'description' => \__('If you want to use the rates assigned to your account, use the ID assigned to the payer\'s account. Contact DHL Express for more information.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true], self::FIELD_PAYMENT_ACCOUNT_NUMBER => ['title' => \__('Payment Account Number', 'flexible-shipping-dhl-express'), 'type' => 'text'], self::FIELD_INSURANCE => ['title' => \__('Insurance', 'flexible-shipping-dhl-express'), 'type' => 'checkbox', 'label' => \__('Request insurance to be included in DHL Express rates', 'flexible-shipping-dhl-express'), 'description' => \__('Enable if you want to include insurance in DHL Express rates when it is available.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true], self::FIELD_PACKING_METHOD => ['title' => \__('Parcel Packing Method', 'flexible-shipping-dhl-express'), 'type' => 'select', 'options' => [self::PACKING_METHOD_WEIGHT => \__('Pack into one box by weight', 'flexible-shipping-dhl-express'), self::PACKING_METHOD_SEPARATELY => \__('Pack items separately', 'flexible-shipping-dhl-express')], 'description' => \__('This option allows you to achieve more accurate Shipping Rates.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'default' => self::PACKING_METHOD_WEIGHT], self::DUTIES_OPTIONS_HEADER => ['title' => \__('Duties & Taxes', 'flexible-shipping-dhl-express'), 'description' => \__('Determine the circumstances when the duties and taxes should be applied for international shipping.', 'flexible-shipping-dhl-express'), 'type' => 'title'], self::IS_DUTIABLE => ['title' => \__('Dutiable shipment', 'flexible-shipping-dhl-express'), 'type' => 'select', 'options' => [self::NEVER => \__('Never', 'flexible-shipping-dhl-express'), self::ALWAYS => \__('Always (except domestic shipping)', 'flexible-shipping-dhl-express'), self::SELECTED_COUNTRIES => \__('Only for selected countries', 'flexible-shipping-dhl-express'), self::EXCEPT_SELECTED_COUNTRIES => \__('Always, except selected countries and domestic shipping', 'flexible-shipping-dhl-express')], 'description' => \__('Define when the shipment you send should be considered as dutiable.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'default' => self::NEVER], self::DUTIABLE_SELECTED_COUNTRIES => ['title' => \__('Selected countries', 'flexible-shipping-dhl-express'), 'type' => 'multiselect', 'class' => 'wc-enhanced-select', 'options' => $this->shop_settings->get_countries(), 'description' => \__('Choose the countries which the option selected in the \'Dutiable shipment\' field will be applied to.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'default' => []], self::PACKAGE_SETTINGS_TITLE => array('title' => \__('Package Settings', 'flexible-shipping-dhl-express'), 'description' => \sprintf(\__('Define the package details including its dimensions and weight which will be used as default for this shipping method.', 'flexible-shipping-dhl-express')), 'type' => 'title', 'class' => self::CLASS_DHL_REST_API), self::PACKAGE_LENGTH => array('title' => \sprintf(\__('Length [%1$s] *', 'flexible-shipping-dhl-express'), $dimension_unit), 'type' => 'number', 'description' => \__('Enter only a numerical value, without a unit symbol.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.1, 'step' => 0.1), 'class' => self::CLASS_DHL_REST_API), self::PACKAGE_WIDTH => array('title' => \sprintf(\__('Width [%1$s] *', 'flexible-shipping-dhl-express'), $dimension_unit), 'type' => 'number', 'description' => \__('Enter only a numerical value, without a unit symbol.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.1, 'step' => 0.1), 'class' => self::CLASS_DHL_REST_API), self::PACKAGE_HEIGHT => array('title' => \sprintf(\__('Height [%1$s] *', 'flexible-shipping-dhl-express'), $dimension_unit), 'type' => 'number', 'description' => \__('Enter only a numerical value, without a unit symbol.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.1, 'step' => 0.1), 'class' => self::CLASS_DHL_REST_API), self::PACKAGE_WEIGHT => array('title' => \sprintf(\__('Default weight [%1$s] *', 'flexible-shipping-dhl-express'), $weight_unit), 'type' => 'number', 'description' => \__('Type in the package weight value which will be used as default if none of the products’ in the cart individual weight has been fill in or if the cart total weight equals 0 kg.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.001, 'step' => 0.001), 'class' => self::CLASS_DHL_REST_API), self::ADVANCED_OPTIONS_HEADER => ['title' => \__('Advanced Options', 'flexible-shipping-dhl-express'), 'type' => 'title'], self::DEBUG_MODE => ['title' => \__('Debug Mode', 'flexible-shipping-dhl-express'), 'label' => \__('Enable debug mode', 'flexible-shipping-dhl-express'), 'type' => 'checkbox', 'description' => \__('Enable debug mode to display messages in the cart/checkout. Only admins and shop managers will see all messages and data sent to DHL Express. The customer will only see messages from the DHL Express API.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true], self::FIELD_UNITS => ['title' => \__('Measurement Units', 'flexible-shipping-dhl-express'), 'type' => 'select', 'options' => [self::UNITS_IMPERIAL => \__('LBS/IN', 'flexible-shipping-dhl-express'), self::UNITS_METRIC => \__('KG/CM', 'flexible-shipping-dhl-express')], 'description' => \__('By default store settings are used. If you see "This measurement system is not valid for the selected country" errors, switch units. Units in the store settings will be converted to units required by DHL Express.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'default' => $this->get_units_default()]];
        return \array_replace($connection_fields, $custom_fields);
    }
    /**
     * Prepare create account docs link.
     */
    private function prepare_create_account_docs_link()
    {
        return \get_locale() === 'pl_PL' ? 'https://octol.io/dhl-express-how-to' : 'https://octol.io/dhl-express-how-to';
    }
}

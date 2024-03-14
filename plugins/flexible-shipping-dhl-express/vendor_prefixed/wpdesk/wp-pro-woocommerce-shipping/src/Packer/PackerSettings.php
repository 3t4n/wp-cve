<?php

namespace DhlVendor\WPDesk\WooCommerceShippingPro\Packer;

use DhlVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier\SettingsDefinitionModifierAfter;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use DhlVendor\WPDesk\Packer\Box;
use DhlVendor\WpDesk\WooCommerce\ShippingMethod\PackerBoxesFactory;
use DhlVendor\WPDesk\WooCommerceShippingPro\CustomFields\ShippingBoxes;
/**
 * Settings required for packer.
 *
 * @package WPDesk\WooCommerceShippingPro\Packer
 */
class PackerSettings
{
    const OPTION_PACKAGING_METHOD = 'packing_method';
    const OPTION_SHIPPING_BOXES = 'shipping_boxes';
    const PACKING_METHOD_WEIGHT = 'weight';
    const PACKING_METHOD_BOX = 'box';
    const PACKING_METHOD_BOX_3D = 'box_3d';
    const PACKING_METHOD_SEPARATELY = 'separately';
    /**
     * @var string
     */
    private $info_url;
    /**
     * @var string
     */
    private $description;
    /**
     * @var bool
     */
    private $shipping_service_id;
    /**
     * PackerSettings constructor.
     *
     * @param string $info_url Url with info about packages size.
     * @param string $description Description.
     * @param bool   $shipping_service_id Shipping service Id.
     */
    public function __construct($info_url, $description = '', $shipping_service_id = '')
    {
        $this->info_url = $info_url;
        $this->description = $description;
        $this->shipping_service_id = $shipping_service_id;
    }
    /**
     * @param \WC_Settings_API $settings
     *
     * @return string One of packaging method names
     */
    public function get_packaging_method(\WC_Settings_API $settings)
    {
        $packing_method = $settings->get_option(self::OPTION_PACKAGING_METHOD, self::PACKING_METHOD_WEIGHT);
        if (self::PACKING_METHOD_BOX_3D === $packing_method && !$this->is_3d_algorithm_available()) {
            $packing_method = self::PACKING_METHOD_BOX;
        }
        return $packing_method;
    }
    /**
     * @param Box[] $boxes
     *
     * @return Box[]
     */
    private function prepare_boxes_for_factory(array $boxes)
    {
        $prepared = [];
        foreach ($boxes as $box) {
            $prepared[\trim($box->get_internal_data()['id'], '_')] = $box;
        }
        return $prepared;
    }
    /**
     * Get shipping boxes saved data.
     *
     * @param \WC_Settings_API $settings .
     * @param Box[] $default_boxes
     *
     * @return Box[]
     */
    public function get_shipping_boxes(\WC_Settings_API $settings, array $default_boxes)
    {
        return \DhlVendor\WpDesk\WooCommerce\ShippingMethod\PackerBoxesFactory::create_packer_boxes_from_settings($settings->get_option(self::OPTION_SHIPPING_BOXES, '[]'), $default_boxes);
    }
    /**
     * Add packaging fields to instance settings.
     *
     * @param SettingsDefinition $definition
     * @param string $add_after Id of settings field after which add the settings.
     *
     * @return SettingsDefinition
     */
    public function add_packaging_fields(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition $definition, $add_after = 'fallback')
    {
        $description = '';
        if (!empty($this->info_url)) {
            $description = \sprintf(
                // Translators: link to packages.
                \__('Select the package type the ordered products will be matched to. You can choose one or as many different packagings as you need. If selected, filling in the products\' weight and dimensions fields is required. %1$sLearn more about the sizes and package types →%2$s', 'flexible-shipping-dhl-express'),
                '<a href="' . $this->info_url . '" target="_blank">',
                '</a>'
            );
        }
        if (!empty($this->description)) {
            $description .= '<br/>' . $this->description;
        }
        $definition = new \DhlVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier\SettingsDefinitionModifierAfter($definition, $add_after, self::OPTION_SHIPPING_BOXES, ['title' => \__('Shipping boxes', 'flexible-shipping-dhl-express'), 'type' => \DhlVendor\WPDesk\WooCommerceShippingPro\CustomFields\ShippingBoxes::get_type_name(), 'class' => 'no-flat-rate', 'description' => $description, 'desc_tip' => \false]);
        $packing_options = array(self::PACKING_METHOD_WEIGHT => \__('Pack into one box by weight', 'flexible-shipping-dhl-express'), self::PACKING_METHOD_BOX => \__('Pack into custom boxes', 'flexible-shipping-dhl-express'), self::PACKING_METHOD_SEPARATELY => \__('Pack items separately', 'flexible-shipping-dhl-express'));
        if ($this->is_3d_algorithm_available()) {
            unset($packing_options[self::PACKING_METHOD_SEPARATELY]);
            $packing_options[self::PACKING_METHOD_BOX_3D] = \__('Pack into custom boxes (3D bin packing)', 'flexible-shipping-dhl-express');
            $packing_options[self::PACKING_METHOD_BOX] = \__('Pack into custom boxes (volume packing)', 'flexible-shipping-dhl-express');
            $packing_options[self::PACKING_METHOD_SEPARATELY] = \__('Pack items separately', 'flexible-shipping-dhl-express');
        }
        return new \DhlVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier\SettingsDefinitionModifierAfter($definition, $add_after, self::OPTION_PACKAGING_METHOD, ['title' => \__('Parcel Packing Method', 'flexible-shipping-dhl-express'), 'type' => 'select', 'options' => $packing_options, 'description' => \__('Define the way how the ordered products should be packed. Changing your choice here may affect the rates.', 'flexible-shipping-dhl-express'), 'desc_tip' => \true, 'default' => self::PACKING_METHOD_WEIGHT]);
    }
    /**
     * @return false|mixed
     */
    private function is_3d_algorithm_available()
    {
        if ($this->shipping_service_id !== '') {
            return \apply_filters($this->shipping_service_id . '/packer/enable_3d_packer', \false);
        }
        return \false;
    }
}

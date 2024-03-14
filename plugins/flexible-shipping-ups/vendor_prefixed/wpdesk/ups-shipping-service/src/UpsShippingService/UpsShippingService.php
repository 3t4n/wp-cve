<?php

/**
 * UPS implementation: UpsShippingService class.
 *
 * @package WPDesk\UpsShippingService
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings;
/**
 * Ups main shipping class injected into WooCommerce shipping method.
 */
class UpsShippingService extends \UpsFreeVendor\WPDesk\UpsShippingService\AbstractUpsShippingService implements \UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint, \UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings
{
    const UNIQUE_ID = 'flexible_shipping_ups';
    /**
     * Is rate to collection point enabled?
     *
     * @param SettingsValues $settings .
     *
     * @return bool
     */
    public function is_rate_to_collection_point_enabled(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::DO_NOT_ADD_ACCESS_POINTS_TO_RATES !== $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCESS_POINT, \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::DO_NOT_ADD_ACCESS_POINTS_TO_RATES);
    }
    /**
     * Rate shipment.
     *
     * @param SettingsValues $settings Settings.
     * @param Shipment       $shipment Shipment.
     *
     * @return ShipmentRating
     * @throws InvalidSettingsException InvalidSettingsException.
     * @throws RateException RateException.
     * @throws UnitConversionException Weight exception.
     */
    public function rate_shipment(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        return $this->rate_shipment_for_ups($settings, $shipment, $this->get_services($settings), $this->create_sender($settings));
    }
    /**
     * Rate shipment to collection point.
     *
     * @param SettingsValues  $settings Settings.
     * @param Shipment        $shipment Shipment.
     * @param CollectionPoint $collection_point Collection point.
     *
     * @return ShipmentRating
     * @throws InvalidSettingsException InvalidSettingsException.
     * @throws RateException RateException.
     * @throws UnitConversionException Weight exception.
     */
    public function rate_shipment_to_collection_point(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point)
    {
        return $this->rate_shipment_for_ups($settings, $shipment, $this->get_services($settings), $this->create_sender($settings), $collection_point);
    }
    /**
     * Get unique ID.
     *
     * @return string
     */
    public function get_unique_id()
    {
        return self::UNIQUE_ID;
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function get_name()
    {
        return \__('UPS Live Rates', 'flexible-shipping-ups');
    }
    /**
     * Get description.
     *
     * @return string
     */
    public function get_description()
    {
        return \__('UPS integration', 'flexible-shipping-ups');
    }
}

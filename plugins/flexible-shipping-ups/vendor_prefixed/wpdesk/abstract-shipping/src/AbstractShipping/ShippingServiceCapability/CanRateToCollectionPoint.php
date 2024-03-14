<?php

/**
 * Capability: CanRateToCollectionPoint class
 *
 * @package WPDesk\AbstractShipping\ShippingServiceCapability
 */
namespace UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability;

use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
/**
 * Interface for rate shipment to collection point
 */
interface CanRateToCollectionPoint
{
    /**
     * Rate shipment to collection point.
     *
     * @param SettingsValues  $settings Settings.
     * @param Shipment        $shipment Shipment.
     * @param CollectionPoint $collection_point Collection point.
     *
     * @return ShipmentRating
     */
    public function rate_shipment_to_collection_point(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point);
    /**
     * Is rate to collection point enabled?
     *
     * @param SettingsValues $settings
     *
     * @return mixed
     */
    public function is_rate_to_collection_point_enabled(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings);
}

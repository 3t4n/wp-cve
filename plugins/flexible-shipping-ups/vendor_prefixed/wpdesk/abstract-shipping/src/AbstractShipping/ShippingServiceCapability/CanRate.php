<?php

/**
 * Capability: CanRate class
 *
 * @package WPDesk\AbstractShipping\Shipment
 */
namespace UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability;

use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
/**
 * Interface for rate shipment
 *
 * @package WPDesk\AbstractShipping\ShippingServiceCapability
 */
interface CanRate
{
    /**
     * Rate shipment.
     *
     * @param SettingsValues  $settings Settings.
     * @param Shipment        $shipment Shipment.
     *
     * @return ShipmentRating
     */
    public function rate_shipment(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment);
    /**
     * Is rate enabled?
     *
     * @param SettingsValues $settings .
     *
     * @return bool
     */
    public function is_rate_enabled(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings);
}

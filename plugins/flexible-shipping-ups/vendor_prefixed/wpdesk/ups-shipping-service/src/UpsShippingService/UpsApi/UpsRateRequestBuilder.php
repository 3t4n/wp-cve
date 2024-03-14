<?php

/**
 * UPS API: Build request.
 *
 * @package WPDesk\UpsShippingService\UpsApi
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService\UpsApi;

use UpsFreeVendor\Ups\Entity\Address as UpsAddressAlias;
use UpsFreeVendor\Ups\Entity\AlternateDeliveryAddress;
use UpsFreeVendor\Ups\Entity\Dimensions;
use UpsFreeVendor\Ups\Entity\InsuredValue;
use UpsFreeVendor\Ups\Entity\Package;
use UpsFreeVendor\Ups\Entity\PackageWeight;
use UpsFreeVendor\Ups\Entity\PickupType;
use UpsFreeVendor\Ups\Entity\RateInformation;
use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\ShipFrom;
use UpsFreeVendor\Ups\Entity\ShipmentIndicationType;
use UpsFreeVendor\Ups\Entity\UnitOfMeasurement;
use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Item;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Weight;
use UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension;
use UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * Build request for UPS rate
 */
class UpsRateRequestBuilder
{
    /**
     * WooCommerce shipment.
     *
     * @var Shipment
     */
    private $shipment;
    /**
     * Settings values.
     *
     * @var SettingsValues
     */
    private $settings;
    /**
     * Request
     *
     * @var RateRequest
     */
    private $request;
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * UpsRateRequestBuilder constructor.
     *
     * @param SettingsValues $settings Settings.
     * @param Shipment $shipment Shipment.
     * @param ShopSettings $helper Helper.
     */
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings $helper)
    {
        $this->settings = $settings;
        $this->shipment = $shipment;
        $this->shop_settings = $helper;
        $this->request = $this->prepare_rate_request();
    }
    /**
     * Prepare rate request.
     *
     * @return RateRequest
     */
    protected function prepare_rate_request()
    {
        $request = new \UpsFreeVendor\Ups\Entity\RateRequest();
        return $request;
    }
    /**
     * Set address data from ship from address.
     *
     * @param UpsAddressAlias $address .
     *
     * @return UpsAddressAlias
     */
    protected function set_address_data_from_ship_from(\UpsFreeVendor\Ups\Entity\Address $address)
    {
        $ship_from_addres = $this->shipment->ship_from->address;
        $address->setAddressLine1($ship_from_addres->address_line1);
        $address->setAddressLine2($ship_from_addres->address_line2);
        $address->setCity($ship_from_addres->city);
        $address->setPostalCode($ship_from_addres->postal_code);
        $address->setCountryCode($ship_from_addres->country_code);
        $address->setStateProvinceCode($ship_from_addres->state_code);
        return $address;
    }
    /**
     * Set shipper address
     */
    protected function set_shipper_address()
    {
        if ($this->shipment->ship_from->address instanceof \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address) {
            $ups_shipment = $this->request->getShipment();
            $shipper = $ups_shipment->getShipper();
            $shipper->setShipperNumber($this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCOUNT_NUMBER));
            $this->set_address_data_from_ship_from($shipper->getAddress());
        }
    }
    /**
     * Set ship from address
     */
    protected function set_ship_from_address()
    {
        if ($this->shipment->ship_from->address instanceof \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address) {
            $ups_shipment = $this->request->getShipment();
            $address = new \UpsFreeVendor\Ups\Entity\Address();
            $address = $this->set_address_data_from_ship_from($address);
            $ship_from_address = new \UpsFreeVendor\Ups\Entity\ShipFrom();
            $ship_from_address->setAddress($address);
            $ups_shipment->setShipFrom($ship_from_address);
        }
    }
    /**
     * Set recipient address
     */
    protected function set_recipient_address()
    {
        if ($this->shipment->ship_to->address instanceof \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address) {
            $ship_to_address = $this->shipment->ship_to->address;
            $ups_shipment = $this->request->getShipment();
            $ups_ship_to = $ups_shipment->getShipTo();
            $ups_ship_to->setCompanyName($this->shipment->ship_to->company_name);
            $ups_ship_to_address = $ups_ship_to->getAddress();
            $ups_ship_to_address->setAddressLine1($ship_to_address->address_line1);
            $ups_ship_to_address->setAddressLine2($ship_to_address->address_line2);
            $ups_ship_to_address->setCity($ship_to_address->city);
            $ups_ship_to_address->setPostalCode($ship_to_address->postal_code);
            $ups_ship_to_address->setCountryCode($ship_to_address->country_code);
            $ups_ship_to_address->setStateProvinceCode($ship_to_address->state_code);
        }
    }
    /**
     * Calculate package weight.
     *
     * @param \WPDesk\AbstractShipping\Shipment\Package $shipment_package .
     * @param string $weight_unit .
     *
     * @return float
     * @throws UnitConversionException Weight exception.
     */
    private function calculate_package_weight(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package, $weight_unit)
    {
        $package_weight = 0.0;
        foreach ($shipment_package->items as $item) {
            $item_weight = (new \UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight($item->weight->weight, $item->weight->weight_unit))->as_unit_rounded($weight_unit);
            $package_weight += $item_weight;
        }
        return $package_weight;
    }
    /**
     * Calculate package value.
     *
     * @param \WPDesk\AbstractShipping\Shipment\Package $shipment_package .
     *
     * @return float
     */
    protected function calculate_package_value(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package)
    {
        $total_value = 0.0;
        /** @var Item $item */
        // phpcs:ignore
        foreach ($shipment_package->items as $item) {
            $total_value += $item->declared_value->amount;
        }
        return $total_value;
    }
    /**
     * Get weight unit from  settings.
     *
     * @return string
     */
    protected function get_weight_unit_from_settings()
    {
        if ($this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::UNITS) === \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::UNITS_IMPERIAL) {
            return 'lb';
        }
        return 'kg';
    }
    /**
     * Get dimensions unit from  settings.
     *
     * @return string
     */
    protected function get_ups_dimensions_unit_from_settings()
    {
        if ($this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::UNITS) === \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::UNITS_IMPERIAL) {
            return \UpsFreeVendor\Ups\Entity\UnitOfMeasurement::UOM_IN;
        }
        return \UpsFreeVendor\Ups\Entity\UnitOfMeasurement::UOM_CM;
    }
    /**
     * Get UPS weight unit from settings.
     *
     * @return string
     */
    protected function get_ups_weight_unit_from_settings()
    {
        $settings_units = $this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::UNITS);
        if (\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::UNITS_IMPERIAL === $settings_units) {
            return \UpsFreeVendor\Ups\Entity\UnitOfMeasurement::UOM_LBS;
        }
        return \UpsFreeVendor\Ups\Entity\UnitOfMeasurement::UOM_KGS;
    }
    /**
     * Set insurance.
     *
     * @param Package $ups_package .
     * @param \WPDesk\AbstractShipping\Shipment\Package $shipment_package .
     */
    private function set_insurance_if_enabled(\UpsFreeVendor\Ups\Entity\Package $ups_package, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package)
    {
        if ('yes' === $this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::INSURANCE, 'no')) {
            $insured_value = new \UpsFreeVendor\Ups\Entity\InsuredValue();
            $insured_value->setMonetaryValue($this->calculate_package_value($shipment_package));
            $insured_value->setCurrencyCode($this->shop_settings->get_currency());
            $ups_package->getPackageServiceOptions()->setInsuredValue($insured_value);
        }
    }
    /**
     * Verifies minimal package weight and modifies it when needed.
     *
     * @param PackageWeight $ups_package_weight .
     */
    private function verify_minimal_package_weight($ups_package_weight)
    {
        if ((float) $ups_package_weight->getWeight() < 0.1) {
            $ups_package_weight->setWeight(0.1);
        }
    }
    /**
     * Set package weight if present.
     *
     * @param PackageWeight $ups_package_weight .
     * @param \WPDesk\AbstractShipping\Shipment\Package $shipment_package .
     *
     * @throws UnitConversionException .
     */
    private function set_package_weight_if_present(\UpsFreeVendor\Ups\Entity\PackageWeight $ups_package_weight, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package)
    {
        if (isset($shipment_package->weight)) {
            $ups_package_weight->setWeight((new \UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight($shipment_package->weight->weight, $shipment_package->weight->weight_unit))->as_unit_rounded($this->get_weight_unit_from_settings()));
        } else {
            $ups_package_weight->setWeight($this->calculate_package_weight($shipment_package, $this->get_weight_unit_from_settings()));
        }
        $this->verify_minimal_package_weight($ups_package_weight);
        $unit_of_measurements = new \UpsFreeVendor\Ups\Entity\UnitOfMeasurement();
        $unit_of_measurements->setCode($this->get_ups_weight_unit_from_settings());
        $ups_package_weight->setUnitOfMeasurement($unit_of_measurements);
    }
    /**
     * Set package dimensions if present.
     *
     * @param Package $ups_package .
     * @param \WPDesk\AbstractShipping\Shipment\Package $shipment_package .
     *
     * @return Package
     * @throws UnitConversionException .
     */
    private function set_dimensions_if_present(\UpsFreeVendor\Ups\Entity\Package $ups_package, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package)
    {
        if (isset($shipment_package->dimensions)) {
            $ups_dimensions = new \UpsFreeVendor\Ups\Entity\Dimensions();
            $ups_unit_of_measurement = new \UpsFreeVendor\Ups\Entity\UnitOfMeasurement();
            $target_dimension_unit = $this->get_ups_dimensions_unit_from_settings();
            $ups_unit_of_measurement->setCode($target_dimension_unit);
            $ups_dimensions->setUnitOfMeasurement($ups_unit_of_measurement);
            $width = new \UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension($shipment_package->dimensions->width, $shipment_package->dimensions->dimensions_unit);
            $height = new \UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension($shipment_package->dimensions->height, $shipment_package->dimensions->dimensions_unit);
            $length = new \UpsFreeVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension($shipment_package->dimensions->length, $shipment_package->dimensions->dimensions_unit);
            $ups_dimensions->setHeight($height->as_unit_rounded($target_dimension_unit));
            $ups_dimensions->setWidth($width->as_unit_rounded($target_dimension_unit));
            $ups_dimensions->setLength($length->as_unit_rounded($target_dimension_unit));
            $ups_package->setDimensions($ups_dimensions);
        }
        return $ups_package;
    }
    /**
     * Add package.
     *
     * @param \WPDesk\AbstractShipping\Shipment\Package $shipment_package .
     *
     * @throws UnitConversionException .
     */
    private function add_package(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package)
    {
        $ups_package = new \UpsFreeVendor\Ups\Entity\Package();
        $ups_package->getPackagingType()->setCode(\UpsFreeVendor\Ups\Entity\PackagingType::PT_PACKAGE);
        $this->set_package_weight_if_present($ups_package->getPackageWeight(), $shipment_package);
        $this->set_dimensions_if_present($ups_package, $shipment_package);
        $ups_shipment = $this->request->getShipment();
        $this->set_insurance_if_enabled($ups_package, $shipment_package);
        $ups_shipment->addPackage($ups_package);
    }
    /**
     * Set package;
     *
     * @throws UnitConversionException Weight exception.
     */
    protected function set_packages()
    {
        foreach ($this->shipment->packages as $package) {
            $this->add_package($package);
        }
    }
    /**
     * Set pickup type;
     */
    protected function set_pickup_type()
    {
        $pickup_type_code = $this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::PICKUP_TYPE, \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::DEFAULT_PICKUP_TYPE);
        if (\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::NOT_SET !== $pickup_type_code) {
            $pickup_type = new \UpsFreeVendor\Ups\Entity\PickupType();
            $pickup_type->setCode($pickup_type_code);
            $this->request->setPickupType($pickup_type);
        } else {
            $this->request->setPickupType(null);
        }
    }
    /**
     * Set shipment service options.
     */
    protected function set_negotiated_rates()
    {
        $negotiated_rates = 'yes' === $this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::NEGOTIATED_RATES);
        if ($negotiated_rates) {
            $ups_shipment = $this->request->getShipment();
            $rate_information = new \UpsFreeVendor\Ups\Entity\RateInformation();
            $rate_information->setNegotiatedRatesIndicator(1);
            $ups_shipment->setRateInformation($rate_information);
        }
    }
    /**
     * Set collection point;
     *
     * @param CollectionPoint $collection_point .
     */
    public function set_collection_point(\UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point)
    {
        $ups_shipment = $this->request->getShipment();
        $shipment_indication_type = new \UpsFreeVendor\Ups\Entity\ShipmentIndicationType();
        $shipment_indication_type->setCode(\UpsFreeVendor\Ups\Entity\ShipmentIndicationType::CODE_ACCESS_POINT_DELIVERY);
        $ups_shipment->setShipmentIndicationType($shipment_indication_type);
        $collection_point_address = $collection_point->collection_point_address;
        $alternate_delivery_address = new \UpsFreeVendor\Ups\Entity\AlternateDeliveryAddress();
        $access_point_address = $alternate_delivery_address->getAddress();
        $access_point_address->setAddressLine1($collection_point_address->address_line1);
        $access_point_address->setAddressLine2($collection_point_address->address_line2);
        $access_point_address->setCity($collection_point_address->city);
        $access_point_address->setPostalCode($collection_point_address->postal_code);
        $access_point_address->setCountryCode($collection_point_address->country_code);
        $ups_shipment->setAlternateDeliveryAddress($alternate_delivery_address);
    }
    private function set_service_code()
    {
        $this->request->getShipment()->setService($this->request->getShipment()->getService()->setCode(null));
    }
    /**
     * Build request.
     *
     * @throws UnitConversionException Weight exception.
     */
    public function build_request()
    {
        $this->set_pickup_type();
        $this->set_shipper_address();
        $this->set_ship_from_address();
        $this->set_recipient_address();
        $this->set_negotiated_rates();
        $this->set_packages();
        $this->set_service_code();
    }
    /**
     * Get request.
     *
     * @return RateRequest
     */
    public function get_build_request()
    {
        return $this->request;
    }
}

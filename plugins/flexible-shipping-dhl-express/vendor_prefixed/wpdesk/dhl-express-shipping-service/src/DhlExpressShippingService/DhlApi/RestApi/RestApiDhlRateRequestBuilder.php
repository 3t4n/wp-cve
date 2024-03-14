<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi;

use DhlVendor\Octolize\DhlExpress\RestApi\Services\RateService;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\ValueAddedService;
use DhlVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use DhlVendor\WPDesk\AbstractShipping\Shipment\Address;
use DhlVendor\WPDesk\AbstractShipping\Shipment\Dimensions;
use DhlVendor\WPDesk\AbstractShipping\Shipment\Package;
use DhlVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use DhlVendor\WPDesk\AbstractShipping\Shipment\Weight;
use DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
use DhlVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition;
/**
 * Build request for Dhl rate
 *
 * @package WPDesk\DhlExpressShippingService\DhlApi
 */
class RestApiDhlRateRequestBuilder
{
    const MINIMAL_PACKAGE_WEIGHT = 0.001;
    const WEIGHT_ROUNDING_PRECISION = 3;
    const DIMENSION_ROUNDING_PRECISION = 0;
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
     * @var RateService
     */
    private $request;
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * DhlRateRequestBuilder constructor.
     *
     * @param SettingsValues $settings Settings.
     * @param Shipment $shipment Shipment.
     * @param ShopSettings $helper Helper.
     */
    public function __construct(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \DhlVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings $helper)
    {
        $this->settings = $settings;
        $this->shipment = $shipment;
        $this->shop_settings = $helper;
        $this->request = new \DhlVendor\Octolize\DhlExpress\RestApi\Services\RateService();
    }
    private function set_shipper_account_number()
    {
        $this->request->addAccount(new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account('shipper', $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_ACCOUNT_NUMBER)));
    }
    /**
     * Set shipper address
     */
    private function set_shipper_address()
    {
        if ($this->shipment->ship_from->address instanceof \DhlVendor\WPDesk\AbstractShipping\Shipment\Address) {
            $ship_from = $this->shipment->ship_from->address;
            $this->request->setOriginAddress(new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress($ship_from->country_code, $ship_from->postal_code, $ship_from->city));
        }
    }
    /**
     * Set recipient address
     */
    private function set_recipient_address()
    {
        if ($this->shipment->ship_to->address instanceof \DhlVendor\WPDesk\AbstractShipping\Shipment\Address) {
            $ship_to = $this->shipment->ship_to->address;
            $this->request->setDestinationAddress(new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress($ship_to->country_code, $ship_to->postal_code, $ship_to->city));
        }
    }
    private function create_package(\DhlVendor\WPDesk\AbstractShipping\Shipment\Package $package)
    {
        if ($package->weight instanceof \DhlVendor\WPDesk\AbstractShipping\Shipment\Weight && $package->weight->weight) {
            $target_weight_unit = $this->get_target_weight_unit();
            $weight = (new \DhlVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight($package->weight->weight, $package->weight->weight_unit))->as_unit_rounded($target_weight_unit);
            $weight = $weight >= self::MINIMAL_PACKAGE_WEIGHT ? $weight : self::MINIMAL_PACKAGE_WEIGHT;
        } else {
            $weight = (float) $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::PACKAGE_WEIGHT);
        }
        return new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package(\round($weight, self::WEIGHT_ROUNDING_PRECISION), \round($package->dimensions->height ?? (float) $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::PACKAGE_HEIGHT), self::DIMENSION_ROUNDING_PRECISION), \round($package->dimensions->length ?? (float) $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::PACKAGE_LENGTH), self::DIMENSION_ROUNDING_PRECISION), \round($package->dimensions->width ?? (float) $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::PACKAGE_WIDTH), self::DIMENSION_ROUNDING_PRECISION));
    }
    /**
     * Set package item.
     *
     * @throws \Exception Measure converter exception.
     */
    private function set_items()
    {
        foreach ($this->shipment->packages as $package) {
            $this->request->addPackage($this->create_package($package));
        }
    }
    /**
     * Returns weight unit in which DHL request would be sent.
     *
     * @return string
     */
    private function get_target_weight_unit()
    {
        $unit = $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_UNITS, \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::UNITS_METRIC);
        return $unit === \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::UNITS_METRIC ? \DhlVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_KG : \DhlVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_LB;
    }
    /**
     * Set additional request data.
     */
    private function set_additional_data()
    {
        if ($this->shipment->ship_from->address instanceof \DhlVendor\WPDesk\AbstractShipping\Shipment\Address) {
            $this->request->setPayerCountryCode($this->shipment->ship_from->address->country_code);
        }
    }
    /**
     * Set payer account number.
     */
    private function set_payment_account_number()
    {
        if ('yes' === $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_USE_PAYMENT_ACCOUNT_NUMBER, 'no')) {
            $this->request->addAccount(new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account('payer', $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_PAYMENT_ACCOUNT_NUMBER)));
        }
    }
    /**
     * Set shipment date.
     */
    protected function set_shipment_date()
    {
        $this->request->setPlannedShippingDate(new \DateTimeImmutable('now'));
    }
    private function set_units()
    {
        $this->request->setUnitOfMeasurement($this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_UNITS, \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::UNITS_METRIC));
    }
    /**
     * Calculate shipment value.
     *
     * @return float
     */
    private function calculate_shipment_value()
    {
        $shipment_value = 0.0;
        foreach ($this->shipment->packages as $package) {
            foreach ($package->items as $item) {
                $shipment_value += $item->declared_value->amount;
            }
        }
        return \round($shipment_value, $this->shop_settings->get_price_rounding_precision());
    }
    /**
     * Set insurance.
     */
    private function set_insurance()
    {
        if ('yes' === $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_INSURANCE, 'no')) {
            $this->request->setInsuredValue($this->calculate_shipment_value(), $this->shop_settings->get_currency());
            $this->request->addValueAddedService(new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\ValueAddedService('II', null, $this->calculate_shipment_value(), $this->shop_settings->get_currency()));
        }
    }
    /**
     * Set dutiable if should.
     */
    private function set_dutiable()
    {
        if ($this->should_set_dutiable()) {
            $this->request->setCustomsDeclarable(\true);
            $this->request->setDeclaredValue($this->calculate_shipment_value(), $this->shop_settings->get_currency());
        }
    }
    /**
     * Should set dutiable.
     *
     * @return bool
     */
    private function should_set_dutiable()
    {
        $is_dutiable = $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::IS_DUTIABLE, \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::NEVER);
        $selected_countries = $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::DUTIABLE_SELECTED_COUNTRIES, []);
        $selected_countries = \is_array($selected_countries) ? $selected_countries : [];
        if (\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::ALWAYS === $is_dutiable && $this->shipment->ship_from->address->country_code !== $this->shipment->ship_to->address->country_code) {
            return \true;
        }
        if (\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::SELECTED_COUNTRIES === $is_dutiable && \in_array($this->shipment->ship_to->address->country_code, $selected_countries, \true)) {
            return \true;
        }
        if (\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::EXCEPT_SELECTED_COUNTRIES === $is_dutiable && $this->shipment->ship_from->address->country_code !== $this->shipment->ship_to->address->country_code && !\in_array($this->shipment->ship_to->address->country_code, $selected_countries, \true)) {
            return \true;
        }
        return \false;
    }
    /**
     * Build request.
     * @throws \Exception
     */
    public function build_request()
    {
        $this->set_shipper_account_number();
        $this->set_shipper_address();
        $this->set_recipient_address();
        $this->set_shipment_date();
        $this->set_items();
        $this->set_additional_data();
        $this->set_units();
        $this->set_insurance();
        $this->set_payment_account_number();
        $this->set_dutiable();
        return $this->request;
    }
}

<?php

namespace FedExVendor\WPDesk\FedexShippingService;

use FedExVendor\Psr\Log\LoggerInterface;
use FedExVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Package;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Weight;
use FedExVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRequestManipulation;
/**
 * Validate shipment for some cases.
 *
 * @package WPDesk\FedexShippingService
 */
class FedexValidateShipment
{
    /**
     * Maximum weights for FedEx shipping.
     *
     * @var array
     */
    const FEDEX_MAX_WEIGHTS = [\FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_G => 68000, \FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_KG => 68, \FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_LB => 150, \FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_OZ => 2433];
    /**
     * Shipment.
     *
     * @var \WPDesk\AbstractShipping\Shipment\Shipment
     */
    private $shipment;
    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * FedexValidateShipment constructor.
     *
     * @param \WPDesk\AbstractShipping\Shipment\Shipment $shipment Shipment.
     * @param \Psr\Log\LoggerInterface $logger Logger.
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \FedExVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->shipment = $shipment;
        $this->logger = $logger;
    }
    /**
     * @param Package $package
     *
     * @return float
     * @throws UnitConversionException
     */
    private function calculate_package_weight(\FedExVendor\WPDesk\AbstractShipping\Shipment\Package $package)
    {
        $package_weight = 0.0;
        foreach ($package->items as $item) {
            $item_unit = \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRequestManipulation::convert_weight_unit($item->weight->weight_unit);
            $package_weight += (new \FedExVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight($item->weight->weight, $item_unit))->as_unit_rounded(\FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_KG);
        }
        return $package_weight;
    }
    /**
     * @param Package $package
     *
     * @return string
     */
    private function get_package_weight_unit(\FedExVendor\WPDesk\AbstractShipping\Shipment\Package $package)
    {
        $package_weight_unit = \FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_KG;
        foreach ($package->items as $item) {
            $package_weight_unit = \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRequestManipulation::convert_weight_unit($item->weight->weight_unit);
        }
        return $package_weight_unit;
    }
    /**
     * Is package weight exceeded.
     *
     * @param Package $package
     *
     * @return bool
     * @throws UnitConversionException
     */
    private function is_package_weight_exceeded(\FedExVendor\WPDesk\AbstractShipping\Shipment\Package $package)
    {
        if ($this->calculate_package_weight($package) > self::FEDEX_MAX_WEIGHTS[\FedExVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_KG]) {
            $item_unit = $this->get_package_weight_unit($package);
            $notice = \sprintf(
                // translators: %1$s weight unit. %2$s number of max weight in kg or lb.
                \__('The maximum package weight has been exceeded. Maximum package weight is: %1$s %2$s.', 'flexible-shipping-fedex'),
                self::FEDEX_MAX_WEIGHTS[$item_unit],
                $item_unit
            );
            $this->logger->error($notice);
            return \true;
        }
        return \false;
    }
    /**
     * Is shipment weight exceeded.
     *
     * @return bool
     * @throws UnitConversionException Weight exception.
     */
    public function is_weight_exceeded()
    {
        foreach ($this->shipment->packages as $package) {
            if ($this->is_package_weight_exceeded($package)) {
                return \true;
            }
        }
        return \false;
    }
}

<?php

/**
 * Simple DTO: Item class.
 *
 * @package WPDesk\AbstractShipping\Shipment
 */
namespace FedExVendor\WPDesk\AbstractShipping\Shipment;

use FedExVendor\WPDesk\AbstractShipping\Rate\Money;
/**
 * Class that stores items for package.
 *
 * @package WPDesk\AbstractShipping\Shipment
 */
final class Item
{
    /**
     * Item name.
     *
     * @var string
     */
    public $name;
    /**
     * Item weight.
     *
     * @var Weight
     */
    public $weight;
    /**
     * Item dimensions.
     *
     * @var Dimensions
     */
    public $dimensions;
    /**
     * Declared value of item ie. for Insurance.
     *
     * @var Money|null
     */
    public $declared_value;
}

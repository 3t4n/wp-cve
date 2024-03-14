<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator\PriceModificatorConditionalLogic;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator\PriceModificator;
use RuntimeException;
use WC_Product;
/**
 * Class PriceModificatorGroup
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class PriceModificatorGroup
{
    /**
     *
     * @var PriceModificatorConditionalLogic
     */
    private $conditional_logic;
    /**
     *
     * @var PriceModificator
     */
    private $price_modificator;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator\PriceModificatorConditionalLogic $conditional_logic, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator\PriceModificator $price_modificator)
    {
        $this->price_modificator = $price_modificator;
        $this->conditional_logic = $conditional_logic;
    }
    public function is_valid() : bool
    {
        return $this->conditional_logic->is_valid();
    }
    public function get_regular_price(float $price) : float
    {
        return $this->price_modificator->get_regular_price($price);
    }
    public function get_sale_price(float $price) : float
    {
        return $this->price_modificator->get_sale_price($price);
    }
    public function is_sale_price() : bool
    {
        return $this->price_modificator->is_sale_price();
    }
}

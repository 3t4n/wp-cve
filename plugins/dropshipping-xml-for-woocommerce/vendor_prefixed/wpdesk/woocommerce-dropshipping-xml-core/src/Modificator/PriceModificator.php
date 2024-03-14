<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;
use WC_Product;
/**
 * Class PriceModificator
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class PriceModificator
{
    const VALUES_SEPARATOR = '|';
    /**
     *
     * @var ImportMapperService
     */
    private $mapper;
    /**
     *
     * @var array
     */
    private $conditions;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, array $conditions)
    {
        $this->conditions = $conditions;
        $this->mapper = $mapper;
    }
    public function get_regular_price(float $price) : float
    {
        if ($this->is_sale_price()) {
            return $price;
        }
        return $this->calculate_price($price, $this->conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD_VALUE], $this->conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD]);
    }
    public function get_sale_price(float $price) : float
    {
        if (!$this->is_sale_price()) {
            return $price;
        }
        return $this->calculate_price($price, $this->conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD_VALUE], $this->conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD]);
    }
    public function is_sale_price() : bool
    {
        return isset($this->conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_TYPE]) && \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_TYPE_OPTION_SALE === $this->conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_TYPE];
    }
    private function calculate_price(float $price, string $value = '', string $modificator = '') : float
    {
        $result = 0;
        $value = $this->format_number($value);
        if (empty($value)) {
            $result = $price;
        } elseif (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD_OPTION_PERCENT === $modificator) {
            $result = $price + $price * ($value / 100);
        } else {
            $result = $price + $value;
        }
        return (float) $result;
    }
    private function format_number(string $number) : float
    {
        if (!empty($number)) {
            $number = \filter_var(\str_replace(',', '.', $number), \FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION);
        } else {
            $number = 0;
        }
        return (float) $number;
    }
}

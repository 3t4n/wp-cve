<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\PriceModificatorFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;
use RuntimeException;
use WC_Product;
/**
 * Class PriceModificatorService
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class PriceModificatorService
{
    const VALUES_SEPARATOR = '|';
    /**
     *
     * @var ImportMapperService
     */
    private $mapper;
    /**
     *
     * @var PriceModificatorFactory
     */
    private $price_mod_factory;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\PriceModificatorFactory $price_mod_factory)
    {
        $this->mapper = $mapper;
        $this->price_mod_factory = $price_mod_factory;
    }
    public function set_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper)
    {
        $this->mapper = $mapper;
    }
    public function get_regular_price(float $price) : float
    {
        $conditions = null;
        if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_CONDITIONS)) {
            $conditions = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_CONDITIONS);
        } elseif ($this->has_old_price_modificator()) {
            $conditions = $this->get_converted_old_price_modificator();
        }
        if (\is_array($conditions)) {
            foreach ($conditions as $condition) {
                $group = $this->price_mod_factory->create_group_from_array($condition, ['mapper' => $this->mapper]);
                if (!$group->is_sale_price() && $group->is_valid()) {
                    return (float) $group->get_regular_price($price);
                }
            }
        }
        return $price;
    }
    public function get_sale_price(float $price) : float
    {
        $conditions = null;
        if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_CONDITIONS)) {
            $conditions = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_CONDITIONS);
        }
        if (\is_array($conditions)) {
            foreach ($conditions as $condition) {
                $group = $this->price_mod_factory->create_group_from_array($condition, ['mapper' => $this->mapper]);
                if ($group->is_sale_price() && $group->is_valid()) {
                    return (float) $group->get_sale_price($price);
                }
            }
        }
        return $price;
    }
    private function has_old_price_modificator() : bool
    {
        $price_mod = $this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR);
        $price_mod_value = $this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_VALUE);
        if ($price_mod && $price_mod_value) {
            $price_mod = !empty($this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR));
            $price_mod_value = !empty($this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_VALUE));
        }
        return $price_mod && $price_mod_value;
    }
    private function get_converted_old_price_modificator() : array
    {
        $result = [];
        $result[1] = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD => $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD_VALUE => $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_VALUE)];
        return $result;
    }
}

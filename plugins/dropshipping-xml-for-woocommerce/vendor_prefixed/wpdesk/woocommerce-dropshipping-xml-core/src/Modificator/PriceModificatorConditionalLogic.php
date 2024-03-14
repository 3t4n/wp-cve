<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Modificator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConditionalLogicFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;
use RuntimeException;
/**
 * Class PriceModificatorConditionalLogic
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class PriceModificatorConditionalLogic
{
    const VALUES_SEPARATOR = '|';
    /**
     *
     * @var ImportMapperService
     */
    private $mapper;
    /**
     *
     * @var ConditionalLogicFactory
     */
    private $conditional_logic_factory;
    /**
     *
     * @var array
     */
    private $conditions;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConditionalLogicFactory $conditional_logic_factory, array $conditions)
    {
        $this->conditions = $conditions;
        $this->mapper = $mapper;
        $this->conditional_logic_factory = $conditional_logic_factory;
    }
    public function is_valid() : bool
    {
        $conditions = $this->conditions;
        $count_items = isset($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_XPATH]) && \is_array($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_XPATH]) ? \count($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_XPATH]) : 0;
        try {
            for ($i = 0; $i < $count_items; $i++) {
                $result = \true;
                if (isset($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_XPATH][$i])) {
                    $xpath = \trim($this->mapper->get_mapped_content($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_XPATH][$i]));
                    $condition_type = $conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE][$i];
                    $condition = $this->conditional_logic_factory->create_by_name($condition_type);
                    $condition->set_xpath_field_value(\strval($xpath));
                    if ($condition instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue) {
                        $compare_value = $condition->get_value_field();
                        if (isset($conditions[$compare_value][$i])) {
                            $result = $this->is_condition_with_values_valid($condition, $conditions[$compare_value][$i]);
                        } else {
                            return \false;
                        }
                    } else {
                        $result = $condition->is_valid();
                    }
                    if ($result === \false) {
                        return \false;
                    }
                }
            }
        } catch (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException $e) {
            return \false;
        }
        return \true;
    }
    private function is_condition_with_values_valid(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue $condition, string $compare_value) : bool
    {
        if (\strpos($compare_value, self::VALUES_SEPARATOR) !== \false) {
            $multiple_values = \explode(self::VALUES_SEPARATOR, $compare_value);
            foreach ($multiple_values as $value) {
                $condition->set_compare_value(\strval(\trim($value)));
                if ($condition->is_valid()) {
                    return \true;
                }
            }
        } else {
            $condition->set_compare_value(\strval($compare_value));
        }
        return $condition->is_valid();
    }
}

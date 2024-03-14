<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConditionalLogicFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicNoValueException;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\Abstraction\ConditionalLogicServiceInterface;
/**
 * Class ConditionalLogicService
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class ConditionalLogicService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\Abstraction\ConditionalLogicServiceInterface
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
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConditionalLogicFactory $conditional_logic_factory)
    {
        $this->mapper = $mapper;
        $this->conditional_logic_factory = $conditional_logic_factory;
    }
    public function is_valid() : bool
    {
        $conditions = $this->mapper->get_raw_option_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::LOGICAL_CONDITIONS);
        $conditions = \is_array($conditions) ? $conditions : [];
        $count_items = \is_array(\reset($conditions)) ? \count(\reset($conditions)) : 1;
        try {
            for ($i = 0; $i < $count_items; $i++) {
                $result = \true;
                if (isset($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_XPATH][$i])) {
                    $should_import = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_IMPORT_OPTION_ALLOW === $conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_IMPORT][$i];
                    $xpath = \trim($this->mapper->get_mapped_content($conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_XPATH][$i]));
                    $condition_type = $conditions[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE][$i];
                    $condition = $this->conditional_logic_factory->create_by_name($condition_type);
                    $condition->set_xpath_field_value(\strval($xpath));
                    if ($condition instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\ConditionalLogic\Abstraction\ConditionalLogicWithCompareValue) {
                        $compare_value = $condition->get_value_field();
                        if (isset($conditions[$compare_value][$i])) {
                            $result = $this->is_condition_with_values_valid($condition, $conditions[$compare_value][$i]);
                            $result = $should_import ? $result : !$result;
                        } else {
                            return \false;
                        }
                    } else {
                        $result = $should_import ? $condition->is_valid() : !$condition->is_valid();
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

<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper;

use DropshippingXmlFreeVendor\Peast\Selector\Node\Group;
use WC_Product;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use RuntimeException;
/**
 * Class ImportMapperService
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class ImportMapperService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface
{
    /**
     * @var ImportMapperDataProvider
     */
    private $mapper_data_provider;
    /**
     * @var XmlAnalyser[]
     */
    private $analysers = [];
    /**
     *
     * @var array
     */
    private $values = [];
    /**
     *
     * @var ImportOptionsDataProvider
     */
    private $options_data_provider;
    /**
     *
     * @var array
     */
    private $product_fields_params;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider $mapper_data_provider, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider $options_data_provider, array $analysers = [])
    {
        $this->mapper_data_provider = $mapper_data_provider;
        $this->options_data_provider = $options_data_provider;
        $this->set_analysers($analysers);
    }
    public function set_mapped_value(string $key, $value, string $group = '')
    {
        if (!empty($group)) {
            $this->values[$group][$key] = $value;
        } else {
            $this->values[$key] = $value;
        }
    }
    public function set_analysers(array $analysers)
    {
        $this->analysers = [];
        if (!empty($this->analysers)) {
            foreach ($analysers as $analyser) {
                if (\is_object($analyser) && $analyser instanceof \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser) {
                    $this->analysers[] = $analyser;
                } else {
                    throw new \RuntimeException('Error, added value is not XmlAnalyser instance');
                }
            }
        }
    }
    public function add_analyser(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $analyser, bool $add_as_first = \false)
    {
        if (\true === $add_as_first) {
            $this->analysers = \array_merge([$analyser], $this->analysers);
        } else {
            $this->analysers = \array_merge($this->analysers, [$analyser]);
        }
    }
    public function get_analysers() : array
    {
        return $this->analysers;
    }
    public function get_mapped_content(string $string) : string
    {
        \preg_match_all('/{(.*?)}/', $string, $matches);
        foreach ($matches[0] as $key => $val) {
            if (isset($matches[1][$key])) {
                $search = $matches[1][$key];
                foreach ($this->get_analysers() as $analyser) {
                    try {
                        $matches[1][$key] = $analyser->count_values_by_xpath($search) === 1 ? $analyser->get_value_by_xpath($search) : '';
                        if (!empty($matches[1][$key]) || \is_numeric($matches[1][$key])) {
                            break;
                        }
                    } catch (\Exception $e) {
                        $matches[1][$key] = '';
                    }
                }
            }
        }
        return \str_replace($matches[0], $matches[1], $string);
    }
    public function get_xpath_from_content(string $string) : string
    {
        \preg_match_all('/{(.*?)}/', $string, $matches);
        if (isset($matches[1]) && \is_array($matches[1])) {
            return \reset($matches[1]);
        }
        return '';
    }
    public function map(string $key, string $group = '') : string
    {
        if (!empty($group) && isset($this->values[$group][$key])) {
            return $this->values[$group][$key];
        } elseif (isset($this->values[$key])) {
            return $this->values[$key];
        }
        if ($this->has_value_to_map($key, $group)) {
            return $this->get_mapped_content(\trim($this->get_raw_value($key, $group)));
        }
        return '';
    }
    public function has_value_to_map(string $key, string $group = '') : bool
    {
        $value = $this->get_raw_value($key, $group);
        if (\is_array($value)) {
            return \true;
        }
        $value = \trim($value);
        if (!empty($value) || \is_numeric($value)) {
            return \true;
        }
        return \false;
    }
    public function has_option_value_to_map(string $key, string $group = '') : bool
    {
        $value = $this->get_raw_option_value($key, $group);
        if (\is_array($value)) {
            return \true;
        }
        $value = \trim($value);
        if (!empty($value) || \is_numeric($value)) {
            return \true;
        }
        return \false;
    }
    /**
     * @see ImportMapperServiceInterface::get_raw_value
     */
    public function get_raw_value(string $key, string $group = '')
    {
        $value = null;
        if (!empty($group)) {
            if ($this->mapper_data_provider->has($group)) {
                $group = $this->mapper_data_provider->get($group);
                if (isset($group[$key])) {
                    $value = $group[$key];
                }
            }
        } else {
            if ($this->mapper_data_provider->has($key)) {
                $value = $this->mapper_data_provider->get($key);
            }
        }
        return $value;
    }
    public function get_raw_option_value(string $key, string $group = '')
    {
        $value = null;
        if (!empty($group)) {
            if ($this->options_data_provider->has($group)) {
                $group = $this->options_data_provider->get($group);
                if (isset($group[$key])) {
                    $value = $group[$key];
                }
            }
        } else {
            if ($this->options_data_provider->has($key)) {
                $value = $this->options_data_provider->get($key);
            }
        }
        return $value;
    }
    public function is_product_field_group_should_be_mapped(\WC_Product $product, string $group_key) : bool
    {
        if (!($product->get_id() > 0)) {
            return \true;
        }
        $product_fields = $this->get_synced_product_fields();
        return \in_array($group_key, $product_fields);
    }
    private function get_synced_product_fields() : array
    {
        if (isset($this->product_fields_params)) {
            return $this->product_fields_params;
        }
        if ($this->options_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD)) {
            $params = $this->options_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD);
        } else {
            $params = \array_keys(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::get_grouped_fields());
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SHIPPING_CLASS_SYNC_DISABLED)) {
                if (isset($params[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_CLASS])) {
                    unset($params[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_CLASS]);
                }
            }
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_SYNC_DISABLED)) {
                if (isset($params[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_CATEGORIES])) {
                    unset($params[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_CATEGORIES]);
                }
            }
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE_SYNC_DISABLED)) {
                if (isset($params[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_ATTRIBUTES])) {
                    unset($params[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_ATTRIBUTES]);
                }
            }
        }
        $this->product_fields_params = $params;
        return $this->product_fields_params;
    }
}

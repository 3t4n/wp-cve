<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use WC_Product;
use WC_Product_Variation;
use WC_Product_Variable;
use WC_Product_Attribute;
use WC_Product_Factory;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
/**
 * Class ProductCreatorService, creates woocommerce product.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Creator
 */
class ProductAttributeMapperService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface
{
    const VARIATION_META_UNIQUE_ID = 'uniqid';
    const LAST_ADDED_VARIATION_META_ID = 'last_added_variation';
    /**
     * @var ImportMapperService
     */
    protected $mapper;
    /**
     * @var ImportLoggerService
     */
    protected $logger;
    /**
     * @var bool
     */
    protected $update_variation = \false;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger)
    {
        $this->logger = $logger;
        $this->mapper = $mapper;
    }
    public function update_product(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_ATTRIBUTES)) {
            $attributes = $wc_product->get_attributes();
            $attributes = \array_filter($attributes, function ($v, $k) {
                if (!\is_object($v)) {
                    return \false;
                }
                $is_variation = $v->get_variation();
                return \false !== $is_variation;
            }, \ARRAY_FILTER_USE_BOTH);
            $attributes_new = $this->get_attributes($this->update_variation);
            $attributes = $attributes + $attributes_new;
            if (!empty($attributes)) {
                $wc_product->set_attributes($attributes);
            }
        }
        return $wc_product;
    }
    public function update_for_variation(bool $bool)
    {
        $this->update_variation = $bool;
    }
    public function create_variation(\WC_Product_Variable $wc_product) : \WC_Product_Variation
    {
        $attributes = $wc_product->get_attributes();
        $attr = $this->get_attributes();
        $variations_attr = $this->convert_attributes_for_variations($attr);
        $attributes = $this->merge_attributes_values($attributes, $attr);
        $wc_product->set_attributes([]);
        $wc_product->set_attributes($attributes);
        $wc_product->save();
        $variation = $this->create_variation_from_attributes($wc_product, $variations_attr);
        return $variation;
    }
    protected function generate_unique_variation_id(array $attributes) : string
    {
        \ksort($attributes);
        $serialized = \serialize($attributes);
        return \md5($serialized);
    }
    protected function create_variation_from_attributes(\WC_Product_Variable $product, array $variations_attr) : \WC_Product_Variation
    {
        $variation = null;
        $new_variation_id = $this->generate_unique_variation_id($variations_attr);
        $factory = new \WC_Product_Factory();
        foreach ($product->get_children() as $child_id) {
            $child = $factory->get_product($child_id);
            if (\is_object($child) && $child instanceof \WC_Product_Variation) {
                if ($child->get_meta(self::VARIATION_META_UNIQUE_ID) === $new_variation_id) {
                    $variation = $child;
                }
            }
            unset($child);
        }
        if ($variation === null) {
            $variation = new \WC_Product_Variation();
        }
        $variation->set_parent_id($product->get_id());
        $variation->set_name($product->get_name());
        $variation->set_attributes($variations_attr);
        $variation->update_meta_data(self::VARIATION_META_UNIQUE_ID, $new_variation_id);
        return $variation;
    }
    protected function merge_attributes_values(array $variable_attributes, array $variation_attributes) : array
    {
        foreach ($variable_attributes as $key => $attribute) {
            if (isset($variation_attributes[$key])) {
                $variation_options = [];
                if (\is_object($variation_attributes[$key])) {
                    $attr = $variation_attributes[$key];
                    $variation_options = $attr->get_options();
                }
                if (\is_object($attribute)) {
                    $variable_options = $attribute->get_options();
                    $options = \array_unique(\array_merge($variable_options, $variation_options));
                    $attribute->set_options($options);
                }
            }
        }
        $merged = \array_merge($variation_attributes, $variable_attributes);
        return $merged;
    }
    protected function convert_attributes_for_variations(array $attributes) : array
    {
        $result = [];
        foreach ($attributes as $id => $attribute) {
            $name = $attribute->get_name();
            $options = [];
            if ($attribute->is_taxonomy()) {
                foreach ($attribute->get_options() as $option) {
                    if (\term_exists($option, $name)) {
                        $term = \get_term_by('id', $option, $name);
                        $options[] = $term->slug;
                    } else {
                        $res = \wp_insert_term($option, $name);
                        if (\is_array($res) && isset($res['term_id'])) {
                            $term = \get_term_by('id', $res['term_id'], $name);
                            $options[] = $term->slug;
                        } else {
                            $options[] = $option;
                        }
                    }
                }
            } else {
                $options = $attribute->get_options();
            }
            $result[$id] = \reset($options);
        }
        \ksort($result);
        return $result;
    }
    protected function create_attribute(string $name, array $values, int $position, bool $is_taxonomy = \false, bool $for_variations = \true) : \WC_Product_Attribute
    {
        $attribute = new \WC_Product_Attribute();
        if ($is_taxonomy) {
            $attribute = $this->create_attribute_as_taxonomy($attribute, $name, $values, $position);
        } else {
            $attribute = $this->create_attribute_as_text($attribute, $name, $values, $position);
        }
        $attribute->set_position($position);
        $attribute->set_visible(\apply_filters('woocommerce_attribute_default_visibility', 1));
        $attribute->set_variation(\apply_filters('woocommerce_attribute_default_is_variation', \intval($for_variations)));
        return $attribute;
    }
    protected function create_attribute_as_taxonomy(\WC_Product_Attribute $attribute, string $name, array $values, int $position) : \WC_Product_Attribute
    {
        $attribute_id = $this->get_attribute_taxonomy_id($name);
        $attribute_name = $attribute_id ? \wc_attribute_taxonomy_name_by_id($attribute_id) : $name;
        $options = \array_map('wc_sanitize_term_text_based', $values);
        $options = \array_filter($options, 'strlen');
        $attribute->set_id($attribute_id);
        $attribute->set_name($attribute_name);
        $taxonomy = \get_taxonomy($attribute_name);
        $ids = [];
        foreach ($options as $option) {
            if (\term_exists($option, $taxonomy->name)) {
                $term = \get_term_by('name', $option, $taxonomy->name);
                $ids[] = $term->term_id;
            } else {
                $res = \wp_insert_term($option, $taxonomy->name);
                if (\is_array($res) && isset($res['term_id'])) {
                    $ids[] = $res['term_id'];
                } else {
                    $ids[] = $option;
                }
            }
        }
        $attribute->set_options($ids);
        return $attribute;
    }
    protected function create_attribute_as_text(\WC_Product_Attribute $attribute, string $name, array $values, int $position) : \WC_Product_Attribute
    {
        $options = \array_map('wc_clean', $values);
        $attribute->set_name(\sanitize_text_field(\wp_unslash($name)));
        $attribute->set_options($options);
        return $attribute;
    }
    protected function get_attribute_taxonomy_id(string $raw_name) : int
    {
        global $wpdb, $wc_product_attributes;
        $attribute_labels = \wp_list_pluck(\wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name');
        $attribute_name = \array_search($raw_name, $attribute_labels, \true);
        if (!$attribute_name) {
            $attribute_name = \wc_sanitize_taxonomy_name($raw_name);
        }
        $attribute_id = \wc_attribute_taxonomy_id_by_name($attribute_name);
        if ($attribute_id) {
            return (int) $attribute_id;
        }
        $attribute_id = \wc_create_attribute(['name' => $raw_name, 'slug' => $attribute_name, 'type' => 'select', 'order_by' => 'menu_order', 'has_archives' => \false]);
        if (\is_wp_error($attribute_id)) {
            throw new \Exception($attribute_id->get_error_message(), 400);
        }
        // Register as taxonomy while importing.
        $taxonomy_name = \wc_attribute_taxonomy_name($attribute_name);
        \register_taxonomy($taxonomy_name, \apply_filters('woocommerce_taxonomy_objects_' . $taxonomy_name, ['product']), \apply_filters('woocommerce_taxonomy_args_' . $taxonomy_name, ['labels' => ['name' => $raw_name], 'hierarchical' => \true, 'show_ui' => \false, 'query_var' => \true, 'rewrite' => \false]));
        // Set product attributes global.
        $wc_product_attributes = [];
        foreach (\wc_get_attribute_taxonomies() as $taxonomy) {
            $wc_product_attributes[\wc_attribute_taxonomy_name($taxonomy->attribute_name)] = $taxonomy;
        }
        return (int) $attribute_id;
    }
    protected function get_attributes(bool $for_variations = \true) : array
    {
        $result = $temp_attr = [];
        $attributes = $this->get_attributes_from_field();
        $attributes = \is_array($attributes) ? $attributes : [];
        $count_items = \is_array(\reset($attributes)) ? \count(\reset($attributes)) : 1;
        $is_taxonomy = $this->is_attribute_taxonomy();
        $attribute_name = $this->get_attribute_name();
        $attribute_value = $this->get_attribute_value();
        for ($i = 0; $i < $count_items; $i++) {
            if (isset($attributes[$attribute_name][$i]) && isset($attributes[$attribute_value][$i])) {
                $name = $this->mapper->get_mapped_content($attributes[$attribute_name][$i]);
                $value = $this->mapper->get_mapped_content($attributes[$attribute_value][$i]);
                if (!empty($name) && !empty($value)) {
                    $temp_attr[\strtolower($name)]['name'] = $name;
                    $temp_attr[\strtolower($name)]['value'][] = $value;
                }
            }
        }
        $j = 1;
        foreach ($temp_attr as $value) {
            try {
                $attribute = $this->create_attribute($value['name'], $value['value'], $j, $is_taxonomy, $for_variations);
                if (\is_object($attribute)) {
                    $result[\sanitize_title($attribute->get_name())] = $attribute;
                    $j++;
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
        return $result;
    }
    protected function get_attribute_name() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE_NAME;
    }
    protected function get_attribute_value() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE_VALUE;
    }
    protected function get_attributes_from_field() : array
    {
        return $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE);
    }
    protected function is_attribute_taxonomy() : bool
    {
        return \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE_AS_TAXONOMY);
    }
}

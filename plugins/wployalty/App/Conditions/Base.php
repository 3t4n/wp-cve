<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Conditions;
defined('ABSPATH') or die();

use DateTime;
use Exception;
use stdClass;
use Wlr\App\Helpers\Input;
use Wlr\App\Helpers\Woocommerce;

abstract class Base
{
    public static $woocommerce_helper, $filter;
    public $name = null, $rule = null, $label = null, $group = null, $input, $extra_params = array('render_saved_condition' => false);

    function __construct()
    {
        self::$woocommerce_helper = (!empty(self::$woocommerce_helper)) ? self::$woocommerce_helper : new Woocommerce();
        $this->input = new Input();
    }

    abstract function check($options, $data);

    /**
     * return the name of the condition. If condition does not have name, then the condition will not get consider.
     * @return null
     */
    function name()
    {
        return $this->name;
    }

    function getCalculateBased($data = array())
    {
        return is_array($data) && isset($data['is_calculate_based']) && !empty($data['is_calculate_based']) ? $data['is_calculate_based'] : '';
    }

    function isValidCalculateBased($is_calculate_based = '')
    {
        return is_string($is_calculate_based) && in_array($is_calculate_based, apply_filters('wlr_allowed_calculate_based', array('cart', 'order', 'product')));
    }

    function generateBase64Encode($data)
    {
        return base64_encode(serialize($data));
    }

    function getDateByString($value, $format = 'Y-m-d H:i:s')
    {
        if (!empty($value)) {
            $value = str_replace('_', ' ', $value);
            try {
                $date = new DateTime(current_time('mysql'));
                $date->modify($value);
                return $date->format($format);
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    /*function getProductFromSettings($values)
    {
        if (!is_array($values)) {
            return array();
        }
        $products = array();
        foreach ($values as $value) {
            if (isset($value->value) && $value->value > 0) {
                $products[] = $value->value;
            }
        }
        return $products;
    }*/

    function getProductFromSettings($values)
    {
        return is_array($values) ? array_column(
            array_filter(
                $values,
                function ($value) {
                    return isset($value->value) && $value->value > 0;
                }
            ),
            'value'
        ) : array();
    }


    /*function combineProductArrays($products, $additional_products)
    {
        if (!is_array($products) || !in_array($additional_products)) return array();
        $products = array_merge($products, $additional_products);
        $products = array_unique($products);
        return $products;
    }*/

    function doCompareInListOperation($operation, $key, $list)
    {
        if (!is_array($list)) {
            return false;
        }
        $key = is_array($key) || is_object($key) ? (array)$key : $key;
        switch ($operation) {
            case 'not_in_list':
                if (is_array($key)) {
                    return empty(array_intersect($key, $list));
                }
                return !in_array($key, $list);
            default:
            case 'in_list';
                if (is_array($key)) {
                    return !empty(array_intersect($key, $list));
                }

                return in_array($key, $list);
        }
    }

    function doComparisionOperation($operation, $operand1, $operand2, $operand3 = null)
    {
        switch ($operation) {
            case 'equal_to':
                return $operand1 == $operand2;
            case 'not_equal_to':
                return $operand1 != $operand2;
            case 'greater_than':
                return $operand1 > $operand2;
            case 'less_than':
                return $operand1 < $operand2;
            case 'greater_than_or_equal':
                return $operand1 >= $operand2;
            case 'less_than_or_equal':
                return $operand1 <= $operand2;
            case 'in_range':
                return !empty($operand3) && $operand1 >= $operand2 && $operand1 <= $operand3;
            default:
                return false;
        }
    }

    /*function doCompareInListOperation($operation, $key, $list)
    {
        if (!is_array($list)) return false;
        $key = (array)$key;
        return $operation === 'not_in_list' ? empty(array_intersect($key, $list)) : !empty(array_intersect($key, $list));
    }*/

    function doItemsCheck($object, $items, $options, $data, $type)
    {
        if (empty($object) || empty($items)) {
            return false;
        }
        $is_calculate_base = isset($data['is_calculate_based']) && !empty($data['is_calculate_based']) ? $data['is_calculate_based'] : '';

        $comparison_operator = isset($options->condition) ? $options->condition : 'less_than_or_equal';
        $comparison_quantity = isset($options->qty) ? $options->qty : 0;
        $comparison_method = isset($options->operator) ? $options->operator : 'in_list';
        $comparison_method = sanitize_text_field($comparison_method);
        $comparison_value = (array)isset($options->value) ? $options->value : array();
        $quantity = 0;
        foreach ($items as $item) {
            if (isset($item['loyalty_free_product']) && $item['loyalty_free_product'] == 'yes') {
                continue;
            }
            $status = $this->checkAdditionalRestriction($item, $is_calculate_base);
            if (!$status) {
                continue;
            }
            $qty = 0;
            $product = new stdClass();
            if ($is_calculate_base === 'cart') {
                $product = isset($item['data']) ? $item['data'] : array();
                $qty = (int)$item['quantity'];
            } elseif ($is_calculate_base === 'order') {
                $product = version_compare(WC_VERSION, '4.4.0', '<')
                    ? $object->get_product_from_item($item)
                    : $item->get_product();
                $qty = $item->get_quantity();
            }
            $is_in_list = $this->match($product, $type, $comparison_method, $comparison_value);

            if (($is_in_list && $comparison_method == 'in_list') || (!$is_in_list && $comparison_method == 'not_in_list')) {
                $quantity += (int)$qty;
            }
        }
        switch ($comparison_operator) {
            case 'less_than':
                return $quantity < $comparison_quantity && $quantity > 0;
            case 'greater_than_or_equal':
                return $quantity >= $comparison_quantity && $quantity > 0;
            case 'greater_than':
                return $quantity > $comparison_quantity && $quantity > 0;
            default:
            case 'less_than_or_equal':
                return $quantity <= $comparison_quantity && $quantity > 0;
        }
    }

    /*function checkAdditionalRestriction($item, $is_calculate_base)
    {
        $status = true;
        if ($is_calculate_base == 'cart') {
            if (is_array($item) && isset($item['bundled_by']) && !empty($item['bundled_by'])) $status = false;
        } elseif ($is_calculate_base == 'order' && is_object($item) && self::$woocommerce_helper->isMethodExists($item, 'get_meta_data')) {
            $item_meta_data = $item->get_meta_data();
            foreach ($item_meta_data as $single_meta) {
                $single_meta_data = is_object($single_meta) && self::$woocommerce_helper->isMethodExists($single_meta, 'get_data') ? $single_meta->get_data() : array();
                if (isset($single_meta_data['key']) && $single_meta_data['key'] == '_bundled_by') {
                    $status = false;
                    break;
                }
            }
        }
        return apply_filters('wlr_check_item_for_additional_restriction', $status, $item, $is_calculate_base);
    }*/

    function checkAdditionalRestriction($item, $is_calculate_base)
    {
        $status = true;
        if ($is_calculate_base == 'cart' && is_array($item) && isset($item['bundled_by']) && !empty($item['bundled_by'])) {
            $status = false;
        } elseif ($is_calculate_base == 'order' && is_object($item) && self::$woocommerce_helper->isMethodExists($item, 'get_meta')) {
            $status = !$item->get_meta('_bundled_by');
        }
        return apply_filters('wlr_check_item_for_additional_restriction', $status, $item, $is_calculate_base);
    }

    /*function doItemsCheck($object, $items, $options, $data, $type)
    {
        $is_calculate_base = isset($data['is_calculate_based']) && !empty($data['is_calculate_based']) ? $data['is_calculate_based'] : '';
        if (empty($object)) {
            return false;
        }
        if (empty($items)) {
            return false;
        }
        $comparision_operator = isset($options->condition) ? $options->condition : 'less_than_or_equal';
        $comparision_quantity = isset($options->qty) ? $options->qty : 0;
        $comparision_method = isset($options->operator) ? $options->operator : 'in_list';
        $comparision_method = sanitize_text_field($comparision_method);
        $comparision_value = (array)isset($options->value) ? $options->value : array();
        $quantity = $not_in_list_quantity = 0;
        foreach ($items as $item) {
            if (isset($item['loyalty_free_product']) && $item['loyalty_free_product'] == 'yes') {
                continue;
            }
            $status = $this->checkAdditionalRestriction($item, $is_calculate_base);
            if (!$status) {
                continue;
            }
            if ($is_calculate_base === 'cart') {
                $product = isset($item['data']) ? $item['data'] : array();
                $qty = (int)$item['quantity'];
            } elseif ($is_calculate_base === 'order') {
                $product = version_compare(WC_VERSION, '4.4.0', '<')
                    ? $object->get_product_from_item($item)
                    : $item->get_product();
                $qty = $item->get_quantity();
            }
            $is_in_list = $this->match($product, $type, $comparision_method, $comparision_value);
            if ($is_in_list && $comparision_method == 'in_list') {
                $quantity += (int)$qty;
            }
            if (!$is_in_list && $comparision_method == 'not_in_list') {
                $quantity += (int)$qty;
            }
        }
        $compare_list = array();
        switch ($comparision_operator) {
            case 'less_than':
                if ($quantity < $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
            case 'greater_than_or_equal':
                if ($quantity >= $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
            case 'greater_than':
                if ($quantity > $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
            default:
            case 'less_than_or_equal':
                if ($quantity <= $comparision_quantity && $quantity > 0) {
                    $compare_list[] = 'yes';
                } else {
                    $compare_list[] = 'no';
                }
                break;
        }
        if (!empty($compare_list) && in_array('no', $compare_list)) {
            return false;
        } else if ((!empty($compare_list) && in_array('yes', $compare_list))) {
            return true;
        }
        return false;
    }*/

    function match($product, $type, $method, $values, $cart_item = array())
    {
        if (is_a($product, 'WC_Product')) {
            $method = !empty($method) ? $method : 'in_list';
            if ('all_products' === $type) {
                return true;
            } elseif ('product_attributes' === $type) {
                return $this->compareWithAttributes($product, $values, $cart_item);
            } elseif ('product_category' === $type) {
                return $this->compareWithCategories($product, $values);
            } elseif ('product_sku' === $type) {
                return $this->compareWithSku($product, $values, $cart_item);
            } elseif ('product_tags' === $type) {
                $product = self::$woocommerce_helper->getParentProduct($product);
                return $this->compareWithTags($product, $values);
            } elseif ('products' === $type) {
                $values = $this->getProductValues((array)$values);
                return $this->compareWithProducts($product, $values);
            }
        }
        return false;
    }

    function compareWithAttributes($product, $operation_values, $cart_item)
    {
        $attrs = self::$woocommerce_helper->getProductAttributes($product);
        $attr_ids = array();
        if (self::$woocommerce_helper->productTypeIs($product, 'variation')) {
            if (count(array_filter($attrs)) < count($attrs)) {
                if (isset($cart_item['variation']) && !empty($cart_item['variation'])) {
                    $attrs = array();
                    foreach ($cart_item['variation'] as $attribute_name => $value) {
                        $attrs[str_replace('attribute_', '', $attribute_name)] = $value;
                    }
                }
            }
            $product_variation = self::$woocommerce_helper->getProduct(self::$woocommerce_helper->getProductParentId($product));
            foreach ($attrs as $taxonomy => $value) {
                if ($value) {
                    $taxonomy = apply_filters('wlr_rules_attribute_slug', urldecode($taxonomy), $taxonomy, $value);
                    $term_obj = get_term_by('slug', $value, $taxonomy);
                    if (!is_wp_error($term_obj) && $term_obj && $term_obj->name) {
                        $attr_ids = array_merge($attr_ids, (array)($term_obj->term_id));
                    }
                } else {
                    $attrs_variation = self::$woocommerce_helper->getProductAttributes($product_variation);
                    foreach ($attrs_variation as $attr) {
                        if ($taxonomy == self::$woocommerce_helper->getAttributeName($attr)) {
                            $attr_ids = array_merge($attr_ids, self::$woocommerce_helper->getAttributeOption($attr));
                        }
                    }
                }
                $attr_ids = apply_filters('wlr_rules_get_attribute_id_from_taxonomy_name', $attr_ids, $taxonomy, $product, $cart_item, $operation_values);
            }
            if (!empty($product_variation)) {
                $attributes_parent = self::$woocommerce_helper->getProductAttributes($product_variation);
                foreach ($attributes_parent as $attributes) {
                    if (!empty($attributes) && is_object($attributes)) {
                        $variation = self::$woocommerce_helper->getAttributeVariation($attributes);
                        if (!(int)$variation) {
                            $options = self::$woocommerce_helper->getAttributeOption($attributes);
                            if (!empty($options) && is_array($options)) {
                                $attr_ids = array_merge($attr_ids, $options);
                            }
                        }
                    } else {
                        $options = self::$woocommerce_helper->getAttributeOption($attributes);
                        if (!empty($options) && is_array($options)) {
                            $attr_ids = array_merge($attr_ids, $options);
                        }
                    }
                }
            }
        } else {
            foreach ($attrs as $attr) {
                $attr_ids = array_merge($attr_ids, self::$woocommerce_helper->getAttributeOption($attr));
            }
        }
        $attr_ids = array_unique($attr_ids);
        return count(array_intersect($attr_ids, $operation_values)) > 0;
    }

    function compareWithCategories($product, $operation_values)
    {
        $categories = self::$woocommerce_helper->getProductCategories($product);
        return count(array_intersect($categories, $operation_values)) > 0;
    }

    function compareWithSku($product, $operation_values, $cart_item, $sale_badge = false)
    {
        $product_sku = self::$woocommerce_helper->getProductSku($product);
        $product_sku = apply_filters('wlr_check_sku_filter', $product_sku, $product, $operation_values, $cart_item, $sale_badge);
        return in_array($product_sku, $operation_values);
    }

    function compareWithTags($product, $operation_values)
    {
        if (!is_object($product) || !is_array($operation_values)) {
            return false;
        }
        $tag_ids = self::$woocommerce_helper->getProductTags($product);
        if (count(array_intersect($tag_ids, $operation_values)) > 0) {
            return true;
        }
        if (self::$woocommerce_helper->isMethodExists($product, 'get_type') && $product->get_type() == 'variation') {
            $parent_product = self::$woocommerce_helper->getParentProduct($product);
            if (is_object($parent_product)) {
                $tag_ids = self::$woocommerce_helper->getProductTags($parent_product);
            }
        }
        return count(array_intersect($tag_ids, $operation_values)) > 0;
    }

    function getProductValues($products = array())
    {
        if (empty($products) || !is_array($products)) {
            return $products;
        }
        $apply_discount_to_child = apply_filters('wlr_apply_condition_to_child', false, $products);
        if ($apply_discount_to_child) {
            $product_variations = self::$woocommerce_helper->getVariantsOfProducts($products);
            if (!empty($product_variations) && is_array($product_variations)) {
                $products = $this->combineProductArrays($products, $product_variations);
            }
        }
        return $products;
    }

    function combineProductArrays($products, $additional_products)
    {
        if (!is_array($products) || !is_array($additional_products)) {
            return $products;
        }
        return array_unique(array_merge($products, $additional_products));
    }

    /*function compareWithProducts($product, $operation_values, $cart_item)
    {
        $product_id = self::$woocommerce_helper->getProductId($product);
        $apply_discount_to_child = apply_filters('wlr_apply_condition_to_variants', true);
        if ($apply_discount_to_child) {
            $parent_id = self::$woocommerce_helper->getProductParentId($product);
            if (!empty($parent_id)) $product_id = $parent_id;
        }
        return (in_array($product_id, $operation_values));
    }*/

    function compareWithProducts($product, $operation_values)
    {
        $product_id = self::$woocommerce_helper->getProductId($product);
        if (in_array($product_id, $operation_values)) {
            return true;
        }
        if (self::$woocommerce_helper->isMethodExists($product, 'get_variation_prices') && apply_filters('wlr_apply_condition_to_variants', true)) {
            $variations = $product->get_variation_prices();
            if (is_array($variations) && isset($variations['price']) && is_array($variations['price'])) {
                foreach ($operation_values as $key) {
                    if (array_key_exists($key, $variations['price'])) {
                        return true;
                    }
                }
            }
        }
        /*if (apply_filters('wlr_apply_condition_to_variants', true)) {
            $product_id = self::$woocommerce_helper->getProductParentId($product);
        }*/
        return in_array($product_id, $operation_values);
    }

    public function isProductValid($options, $data)
    {
        return (isset($data['is_message']) && $data['is_message'] && isset($data['is_calculate_based']) && $data['is_calculate_based'] === 'product');
    }

    public function changeOptionValue($option_values)
    {
        if (!is_array($option_values)) {
            return $option_values;
        }
        return $option_values ? array_column(
            array_filter(
                $option_values,
                function ($option_value) {
                    return isset($option_value->value);
                }
            ),
            'value'
        ) : array();
        /*if (!empty($option_values)) {
            $final_value = array();
            foreach ($option_values as $sinle_value) {
                if (isset($sinle_value->value)) {
                    $final_value[] = $sinle_value->value;
                }
            }
            if (!empty($final_value)) {
                $option_values = $final_value;
            }
        }
        return $option_values;*/
    }
}

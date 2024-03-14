<?php

namespace ShopWP\Render;

use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Attributes
{
    public function __construct($DB_Products)
    {
        $this->DB_Products = $DB_Products;
    }

    public function has_attr($attributes, $value)
    {
        return isset($attributes[$value]) && !empty($attributes[$value]);
    }

    public function add_boolean_to_query($key, $val)
    {
        if (is_bool($val)) {
            $bool_converted = $val ? 'true' : 'false';
        } else {
            $bool_converted = $val;
        }

        return $key . ':' . $bool_converted;
    }

    public function add_string_to_query($key, $val)
    {
        if (substr($val, -1) === '*') {
            return $key . ':' . $val;
        } else {
            return $key . ':' . '"' . $val . '"';
        }
    }

    public function query_checks($key, $val, $query)
    {
        if (is_bool($val) || $val === 'true' || $val === 'false') {
            $query .= $this->add_boolean_to_query($key, $val);
        } else {
            $query .= $this->add_string_to_query($key, $val);
        }

        return $query;
    }

    public function add_nested_query($key, $values, $all_attrs, $keep_commas)
    {
        $query = '';

        if ($keep_commas) {
            $query_keep = $key . ': "';

            foreach ($all_attrs[$key] as $v) {
                $query_keep .= $v . ', ';
            }

            $query_keep = rtrim($query_keep, ", ");
            $query_keep = $query_keep . '"';

            return $query_keep;
        }

        foreach ($values as $val) {

            $query = $this->query_checks($key, $val, $query);

            if ($val !== end($values)) {
                $query .= ' ' . strtoupper($all_attrs['connective']) . ' ';
            }
        }

        return $query;
    }

    public function build_query($filter_params, $all_attrs)
    {
        if (
            isset($all_attrs['keep_commas']) &&
            $all_attrs['keep_commas'] === true
        ) {
            $keep_commas = true;
        } else {
            $keep_commas = false;
        }


        if (!array_filter($filter_params)) {
            return '*'; // Returns the default query instead
        }

        if (isset($filter_params['available_for_sale'])) {

            if ($filter_params['available_for_sale'] === 'any') {
                unset($filter_params['available_for_sale']);

            } else {
                if ($filter_params['available_for_sale'] === 'true' || $filter_params['available_for_sale'] === 1) {
                    $filter_params['available_for_sale'] = 'true';

                } else {
                    $filter_params['available_for_sale'] = 'false';
                }
            }
        }

        $query = '';
        $valid_filter_params = array_filter($filter_params);

        foreach ($valid_filter_params as $key => $value) {
            if (\is_array($value)) {
                $query .= $this->add_nested_query(
                    $key,
                    $value,
                    $all_attrs,
                    $keep_commas
                );
            } else {
                $query = $this->query_checks($key, $value, $query);
            }

            if ($value !== end($valid_filter_params)) {
                if ($key === 'available_for_sale') {
                    $query .= ' AND ';
                } else {
                    $query .= ' ' . strtoupper($all_attrs['connective']) . ' ';
                }
            }
        }

        // Fallback
        if ($query === '') {
            $query = '*';
        }

        return $query;
    }

    public function attr($attrs, $attr_name, $default, $override = false)
    {
        if (
            !\is_array($attrs) ||
            !\array_key_exists($attr_name, $attrs) ||
            empty($attrs)
        ) {
            return $default;
        }

        return $attrs[$attr_name];
    }

    public function gather_products_attrs($shortcode_atts)
    {
        $products_only_attrs = [];

        if (!empty($shortcode_atts)) {
            foreach ($shortcode_atts as $key => $value) {
                if (Utils::str_contains($key, 'products_')) {
                    $products_only_attrs[$key] = $value;
                }
            }
        }

        return $products_only_attrs;
    }

    public function add_products_attrs_to_data($all_atts, $products_only_attrs)
    {
        if (empty($products_only_attrs)) {
            return $all_atts;
        } else {
            foreach ($products_only_attrs as $key => $value) {
                $without_prefix = str_replace('products_', '', $key);
                $all_atts['products'][$without_prefix] = $value;
            }
        }

        return $all_atts;
    }

    public function combine_products_attributes($all_atts)
    {
        return $this->add_products_attrs_to_data(
            $all_atts,
            $this->gather_products_attrs($all_atts)
        );
    }

    public function maybe_get_product_ids($attrs)
    {

        $product_ids = [];

        if (isset($attrs['post_id']) && $attrs['post_id']) {

            $post_ids = [];

            if (!is_array($attrs['post_id'])) {
                $post_ids[] = $attrs['post_id'];

            } else {
                $post_ids = array_merge($attrs['post_id'], $post_ids);
            }

            foreach ($post_ids as $post_id) {
                $p_id = get_post_meta($post_id, 'product_id', true);

                $product_ids[] = $p_id;
            }

        } else if (isset($attrs['product_id']) && $attrs['product_id']) {

            if (!is_array($attrs['product_id'])) {
                $product_ids[] = $attrs['product_id'];
            } else {
                $product_ids = array_merge($attrs['product_id'], $product_ids);
            }
            
        }

        return empty($product_ids) ? false : $product_ids;
    }

    public function maybe_create_array_of_values($data, $keep_commas = false) {

        if ($keep_commas) {
            return $data;
        }

        if (is_array($data)) {
            $exploded = $data;

        } else {
            $exploded = explode(',', $data);
        }

        if (empty($exploded)) {
            return $data;
        }

        return array_filter(array_map('trim', $exploded));
    }

    public function get_products_filter_params_from_shortcode($attrs)
    {

        $maybe_ids = $this->maybe_get_product_ids($attrs);

        if (isset($attrs['keep_commas'])) {
            $keep_commas = $attrs['keep_commas'];

        } else {
            $keep_commas = false;
        }

        $afs = isset($attrs['available_for_sale']) ? $attrs['available_for_sale'] : 'any';

        if (isset($attrs['available_for_sale'])) {
            if ($attrs['available_for_sale'] === 'any') {
               $afs = 'any';
            } else {
                if ($attrs['available_for_sale'] == true) {
                    $afs = 'true';
                } else {
                    $afs = 'false';
                }
            }
        }

        return [
            'available_for_sale' => $afs,
            'product_type' => isset($attrs['product_type'])
                ? $this->maybe_create_array_of_values($attrs['product_type'], $keep_commas)
                : false,
            'tag' => isset($attrs['tag']) ? $this->maybe_create_array_of_values($attrs['tag'], $keep_commas) : false,
            'title' => isset($attrs['title']) ? $this->maybe_create_array_of_values($attrs['title'], $keep_commas) : false,
            'variants_price' => isset($attrs['variants_price'])
                ? $attrs['variants_price']
                : false,
            'vendor' => isset($attrs['vendor']) ? $this->maybe_create_array_of_values($attrs['vendor'], $keep_commas) : false,
            'id' => $maybe_ids,
            'collection' => isset($attrs['collection'])
                ? $this->maybe_create_array_of_values($attrs['collection'], $keep_commas)
                : false,
        ];
    }

    public function get_collections_filter_params_from_shortcode($attrs)
    {
        return [
            'title' => isset($attrs['title']) ? $attrs['title'] : false,
            'collection_type' => isset($attrs['collection_type'])
                ? $attrs['collection_type']
                : false,
        ];
    }
}

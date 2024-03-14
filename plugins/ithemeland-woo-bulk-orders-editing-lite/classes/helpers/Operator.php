<?php

namespace wobel\classes\helpers;

class Operator
{
    public static function edit_text($extra = [])
    {
        $operators =  [
            'text_new' => __('New', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'text_append' => __('Append', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'text_prepend' => __('Prepend', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'text_delete' => __('Delete', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'text_replace' => __('Replace', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];

        if (!empty($extra) && is_array($extra)) {
            foreach ($extra as $key => $label) {
                $operators[sanitize_text_field($key)] = sanitize_text_field($label);
            }
        }

        return $operators;
    }

    public static function edit_taxonomy()
    {
        return [
            'taxonomy_append' => __('Append', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'taxonomy_replace' => __('Replace', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'taxonomy_delete' => __('Delete', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function edit_number()
    {
        return [
            'number_new' => __('Set New', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'number_clear' => __('Clear Value', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'number_formula' => __('Formula', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'increase_by_value' => __('Increase by value', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'decrease_by_value' => __('Decrease by value', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'increase_by_percent' => __('Increase by %', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'decrease_by_percent' => __('Decrease by %', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function edit_regular_price()
    {
        return [
            'increase_by_value_from_sale' => __('Increase by value (From sale)', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'increase_by_percent_from_sale' => __('Increase by % (From sale)', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function edit_sale_price()
    {
        return [
            'decrease_by_value_from_regular' => __('Decrease by value (From regular)', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'decrease_by_percent_from_regular' => __('Decrease by % (From regular)', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function filter_text()
    {
        return [
            'like' => __('Like', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'exact' => __('Exact', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'not' => __('Not', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'begin' => __('Begin', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'end' => __('End', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function filter_multi_select()
    {
        return [
            'or' => 'OR',
            'and' => 'And',
            'not_in' => 'Not IN',
        ];
    }

    public static function round_items()
    {
        return [
            5 => 5,
            10 => 10,
            19 => 19,
            29 => 29,
            39 => 39,
            49 => 49,
            59 => 59,
            69 => 69,
            79 => 79,
            89 => 89,
            99 => 99
        ];
    }
}

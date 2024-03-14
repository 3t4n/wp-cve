<?php

namespace ShopWP\Utils;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Data
{
    /*

	Finds the size in bytes of a given piece of data / variable

	*/
    public static function size_in_bytes($data)
    {
        $serialized_data = serialize($data);

        if (function_exists('mb_strlen')) {
            $size = mb_strlen($serialized_data, '8bit');
        } else {
            $size = strlen($serialized_data);
        }

        return $size;
    }

    /*

	Convert to readable size format

	*/
    public static function to_readable_size($size)
    {
        $base = log($size) / log(1024);
        $suffix = ["", "KB", "MB", "GB", "TB"];
        $f_base = floor($base);

        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }

    /*

	Converts an array to a comma seperated string without spaces

	*/
    public static function array_to_comma_string($maybe_array)
    {
        return Utils::remove_spaces_from_string(
            Utils::convert_to_comma_string($maybe_array)
        );
    }

    /*

	Retrieves an array value based on a provided index, minus one

	*/
    public static function current_index_value_less_one($items, $index)
    {
        $current_index_less_one = $index - 1;

        // Send current index if empty
        if (!isset($items[$current_index_less_one])) {
            return $index;
        }

        return $items[$current_index_less_one];
    }

    /*

	Chunks an array

	*/
    public static function chunk_data($data, $items_per_chunk)
    {
        return array_chunk($data, $items_per_chunk);
    }

    /*

	Used within a Reduce to count the total number of items

	*/
    public static function add($carry, $item)
    {
        $carry += $item;

        return $carry;
    }

    /*

	Adds ints from an array like:

	[1, 2, 3, 4]

	*/
    public static function add_totals($array_of_ints)
    {
        return array_reduce($array_of_ints, [__CLASS__, 'add']);
    }

    /*

	Only returns wp_errors

	*/
    public static function only_wp_errors($item)
    {
        return is_wp_error($item);
    }

    /*

	Only returns wp_errors

	*/
    public static function return_only_wp_errors($items)
    {
        return array_filter($items, [__CLASS__, 'only_wp_errors']);
    }

    /*

	Coerce into a given type

	*/
    public static function coerce($value, $type)
    {
        $new_value = $value;

        if (settype($new_value, $type)) {
            return $new_value;
        }

        return false;
    }

    public static function contains_comma($string)
    {
        return Utils::str_contains($string, ',');
    }

    public static function to_bool_int($value)
    {
        if ($value) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function sanitize_setting($setting, $serialize = false)
    {
        if (\is_string($setting)) {
            if ($setting === '1') {
                return true;
            }

            if ($setting === '0') {
                return false;
            }

            if (\is_numeric($setting)) {
                if (\is_float($setting)) {
                    return (float) $setting;
                }

                return (int) $setting;
            }

            if ($serialize) {
                return \maybe_serialize($setting);
            } else {
                return \maybe_unserialize($setting);
            }
        }

        return $setting;
    }

    public static function mask_value($settings, $key) {

        $val = $settings[$key];

        $sup = Utils::mask_value($val);
        $start = strlen($sup) - 30;
        $sup1 = substr($sup, $start);

        $settings[$key] = $sup1;

        return $settings;
    }

    public static function sanitize_settings($settings, $serialize = false)
    {

        if (!empty($settings['recharge_api_key'])) {
            $settings = self::mask_value($settings, 'recharge_api_key');
        }

        if (!empty($settings['yotpo_api_key'])) {
            $settings = self::mask_value($settings, 'yotpo_api_key');
        }

        if (!empty($settings['yotpo_api_secret_key'])) {
            $settings = self::mask_value($settings, 'yotpo_api_secret_key');
        }

        if (!empty($settings['yotpo_utoken'])) {
            $settings = self::mask_value($settings, 'yotpo_utoken');
        }

        return \array_map(function ($setting) use ($serialize) {
            return self::sanitize_setting($setting, $serialize);
        }, $settings);
    }

    public static function sanitize_text_fields($fields)
    {
        return \array_map(function ($field) {
            return sanitize_text_field($field);
        }, $fields);
    }

    public static function map_array_prop($items, $prop)
    {
        return array_map(function ($item) use ($prop) {
            return $item[$prop];
        }, $items);
    }

    public static function attr_to_boolean($attr_val)
    {
        if (
            $attr_val === 'true' ||
            $attr_val == 1 ||
            $attr_val == 'yes' ||
            $attr_val == 'on'
        ) {
            return true;
        }

        return false;
    }

    public static function attr_to_integer($attr_val)
    {
        return (int) $attr_val;
    }

    public static function to_type($value)
    {
        if (
            $value === 'true' ||
            $value === 'false' ||
            $value === 'yes' ||
            $value === 'no' ||
            $value === 'on'
        ) {
            return self::attr_to_boolean($value);
        }

        if (is_numeric($value)) {
            return self::attr_to_integer($value);
        }

        return $value;
    }

    public static function keep_commas_always($prop) {
        return in_array($prop, [
            'cart_icon_color',
            'cart_icon_background_color',
            'cart_counter_background_color',
            'cart_counter_text_color',
            'cart_title',
            'checkout_text',
            'checkout_failed_message',
            'lineitem_remove_text',
            'lineitem_sale_label_text',
            'notes_label',
            'notes_placeholder',
            'empty_cart_text',
            'subtotal_label_text',
            'no_filter_group_found_text',
            'filter_by_label_text',
            'page_size_label_text',
            'clear_filter_selections_text',
            'selections_available_for_sale_text',
            'sort_by_label_text',
            'load_more_collections_busy_text',
            'load_more_collections_text',
            'collections_heading',
            'price_heading',
            'tags_heading',
            'types_heading',
            'vendors_heading',
            'search_placeholder_text',
            'title_type_font_family',
            'description_type_font_family',
            'no_results_text',
            'add_to_cart_button_text',
            'add_to_cart_button_type_font_family',
            'variant_dropdown_button_color',
            'variant_dropdown_text_color',
            'variant_dropdown_type_font_family',
            'quantity_label_text',
            'pricing_type_font_family',
            'pricing_color',
            'carousel_prev_arrow',
            'carousel_next_arrow',
            'after_added_text',
            'clear_selections_text',
            'notice_unavailable_text',
            'pagination_load_more_text',
            'out_of_stock_notice_text',
            'sale_label_text',
            'sold_out_image_label_text'
        ]);
    }    

    public static function format_shortcode_attr(
        $arg_value,
        $arg_name,
        $keep_commas = false
    ) {
        // TODO: We might need to check more than title & query
        if (($arg_name === 'title' || $arg_name === 'query' || $arg_name === 'vendor' || $arg_name === 'tag' || $arg_name === 'product_type') || $arg_name === 'collection' && $keep_commas) {
            return $arg_value;
        }

        if (is_string($arg_value)) {

            if (self::contains_comma($arg_value)) {

                if (self::keep_commas_always($arg_name)) {
                    return trim($arg_value);
                }

                // TODO: We might need to check more than title & query
                if (
                    ($arg_name === 'title' || $arg_name === 'query') &&
                    $keep_commas
                ) {
                    return $arg_value;
                }

                return array_filter(
                    Utils::comma_list_to_array(self::to_type(trim($arg_value)))
                );
            } else {
                return self::to_type(trim($arg_value));
            }
        }

        return self::to_type($arg_value);
    }

    /*
    
    This evaluates to true mostly on the default products page. But could potentially evaluate as true by using the Render API. 

    If the firt value in the array of post ids is an integer, we can safely assume the rest will be too.
    
    Since we fetch all the post ids on the product details page, we need to add an upper bound to how many we "format" here so as to not run into performance issues.
    
    */
    public static function has_real_post_ids($name, $value)
    {

      if ($name === 'post_id' && is_array($value) && !empty($value)) {

         if (!isset($value[0])) {
            $reset_value = array_values($value);

            if (is_int($reset_value[0])) {
               return true;
            }

            return false;
            
         }

         if (is_int($value[0])) {
            return true;
         }

      } else {
         return false;
      }

    }

    public static function format_shortcode_attrs($shortcode_args)
    {
        if (
            isset($shortcode_args['keep_commas']) &&
            $shortcode_args['keep_commas'] === 'true'
        ) {
            $keep_commas = true;
        } else {
            $keep_commas = false;
        }

        if (empty($shortcode_args)) {
            return [];
        }

        foreach ($shortcode_args as $arg_name => $arg_value) {
            if (self::has_real_post_ids($arg_name, $arg_value)) {
                continue;
            }

            if (\is_array($shortcode_args[$arg_name])) {
                $shortcode_args[$arg_name] = self::format_shortcode_attrs(
                    $shortcode_args[$arg_name]
                );
            } else {
                $shortcode_args[$arg_name] = self::format_shortcode_attr(
                    $arg_value,
                    $arg_name,
                    $keep_commas
                );
            }
        }

        return $shortcode_args;
    }

    public static function standardize_layout_data($shortcode_args)
    {
        if (!isset($shortcode_args) || !$shortcode_args) {
            return [];
        }

        $after_result = self::format_shortcode_attrs($shortcode_args);

        return $after_result;
    }

    public static function mask_api_values($api_values) {

      return array_map(function ($value) use($api_values) {
         $is_domain = strpos($value, 'myshopify.com');
         $is_shop_name = false;

         if (!empty($api_values['shop_name']) && $api_values['shop_name'] === $value) {
            $is_shop_name = true;
         }

         if ($value && !$is_domain && !$is_shop_name) {
            return Utils::mask_value($value);
         }

         return $value;
         
      }, $api_values);
    }
}

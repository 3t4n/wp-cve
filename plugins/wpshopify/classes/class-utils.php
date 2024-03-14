<?php

namespace ShopWP;

use ShopWP\Messages;
use ShopWP\Utils\Data as Utils_Data;
use ShopWP\Utils\Sorting as Utils_Sorting;

if (!defined('ABSPATH')) {
    exit();
}

class Utils
{
    public static function filter_errors($item)
    {
        return is_wp_error($item);
    }

    public static function filter_error_messages($error)
    {
        if (isset($error->errors) && isset($error->errors['error'])) {
            return $error->errors['error'][0];
        }
    }

    public static function wp_error($params)
    {
        if (is_string($params)) {
            return new \WP_Error('error', $params);
        }

        return new \WP_Error('error', Messages::error($params));
    }

    public static function return_only_errors($items)
    {
        return array_filter(
            $items,
            [__CLASS__, 'filter_errors'],
            ARRAY_FILTER_USE_BOTH
        );
    }

    public static function return_only_error_messages($array_of_errors)
    {
        return array_values(
            array_map([__CLASS__, 'filter_error_messages'], $array_of_errors)
        );
    }

    public static function hash_rand($content, $serialize = false)
    {
        $bytes = \random_int(1, 200);

        if ($serialize) {
            return md5(serialize($content)) . wp_rand(5, 8) . $bytes;
        }

        return md5($content) . wp_rand(5, 8) . $bytes;
    }

    public static function hash($content, $serialize = false)
    {
        if ($serialize) {
            return md5(serialize($content));
        }

        return md5($content);
    }

    public static function base64($content, $type = 'encode')
    {
        if ($type === 'encode') {
            return base64_encode(json_encode($content));
        }

        return json_decode(base64_decode($content));
    }

    public static function hash_static_num($content)
    {
        return crc32(self::hash_rand($content));
    }

    public static function construct_admin_path_from_urls($homeURL, $adminURL)
    {
        if (self::str_contains($homeURL, 'https://')) {
            $homeProtocol = 'https';
        } else {
            $homeProtocol = 'http';
        }

        if (self::str_contains($adminURL, 'https://')) {
            $adminProtocol = 'https';
        } else {
            $adminProtocol = 'http';
        }

        $explodedHome = explode($homeProtocol, $homeURL);
        $explodedAdmin = explode($adminProtocol, $adminURL);

        $explodedHomeFiltered = array_values(array_filter($explodedHome))[0];
        $explodedAdminFiltered = array_values(array_filter($explodedAdmin))[0];

        $adminPath = explode($explodedHomeFiltered, $explodedAdminFiltered);

        return array_values(array_filter($adminPath))[0];
    }

    public static function get_abs_admin_path($path = '')
    {
        return rtrim(ABSPATH, '/') . '/wp-admin/' . $path;
    }

    public static function is_plugin_installed($plugin_basename)
    {
        $installed_plugins = \get_plugins();

        return array_key_exists($plugin_basename, $installed_plugins);
    }

    public static function convert_to_comma_string($items)
    {
        if (is_string($items)) {
            return $items;
        }

        if (is_array($items)) {
            return implode(', ', $items);
        }

        return false;
    }

    public static function is_multi_array($array)
    {
        if (empty($array)) {
            return false;
        }
        $rest = reset($array);

        return is_array($rest);
    }

    public static function get_first_image_if_exists($product)
    {
        if (self::has($product, 'images') && !empty($product->images)) {
            return $product->images[0]->src;
        }
    }

    public static function flatten_image_prop($items)
    {
        $items_copy = $items;
        $items_copy = self::convert_array_to_object($items_copy);

        if (
            self::has($items_copy, 'image') &&
            self::has($items_copy->image, 'src')
        ) {
            $items_copy->image = $items_copy->image->src;
        } else {
            $items_copy->image = self::get_first_image_if_exists($items_copy);
        }

        return $items_copy;
    }

    public static function filter_items_by_id($items, $diff, $keyToCheck = 'id')
    {
        $finalResuts = [];

        foreach ($items as $key => $value) {
            foreach ($diff as $key => $diffID) {
                if (is_object($value)) {
                    if ($diffID === $value->$keyToCheck) {
                        $finalResuts[] = $value;
                    }
                } else {
                    if ($diffID === $value[$keyToCheck]) {
                        $finalResuts[] = $value;
                    }
                }
            }
        }

        return $finalResuts;
    }

    public static function gather_item_ids(
        $current_items,
        $new_items,
        $num_dimensions,
        $key_to_check
    ) {
        return [
            'current' => self::get_item_ids(
                $current_items,
                $num_dimensions,
                $key_to_check
            ),
            'new' => self::get_item_ids(
                $new_items,
                $num_dimensions,
                $key_to_check
            ),
        ];
    }

    public static function find_items_to_delete(
        $current_items,
        $new_items,
        $num_dimensions = false,
        $key_to_check = 'id'
    ) {
        $ids_to_check = self::gather_item_ids(
            $current_items,
            $new_items,
            $num_dimensions,
            $key_to_check
        );

        // Deletes ids in 'current' that arent in 'new'
        $difference = array_values(
            array_diff($ids_to_check['current'], $ids_to_check['new'])
        );

        return self::filter_items_by_id(
            $current_items,
            $difference,
            $key_to_check
        );
    }

    public static function find_items_to_add(
        $current_items,
        $new_items,
        $num_dimensions = false,
        $key_to_check = 'id'
    ) {
        $ids_to_check = self::gather_item_ids(
            $current_items,
            $new_items,
            $num_dimensions,
            $key_to_check
        );

        // Adds ids from 'new' that arent in 'current'
        $difference = array_values(
            array_diff($ids_to_check['new'], $ids_to_check['current'])
        );

        return self::filter_items_by_id($new_items, $difference, $key_to_check);
    }

    public static function get_item_ids(
        $items,
        $one_dimension = false,
        $key_to_check = 'id'
    ) {
        $items = self::convert_to_assoc_array($items);

        $results = [];

        if ($one_dimension) {
            foreach ($items as $item) {
                if (isset($item[$key_to_check]) && $item[$key_to_check]) {
                    $results[] = $item[$key_to_check];
                }
            }
        } else {
            foreach ($items as $sub_array) {
                foreach ($sub_array as $item) {
                    if (isset($item[$key_to_check]) && $item[$key_to_check]) {
                        $results[] = $item[$key_to_check];
                    }
                }
            }
        }

        return $results;
    }

    public static function convert_object_to_array($maybe_object)
    {
        if (is_array($maybe_object)) {
            return $maybe_object;
        }

        // Unable to convert to Object from these. Return false.
        if (
            is_float($maybe_object) ||
            is_int($maybe_object) ||
            is_bool($maybe_object)
        ) {
            return self::wp_error([
                'message_lookup' => 'unable_to_convert_to_array',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        return (array) $maybe_object;
    }

    public static function convert_array_to_object($maybe_array)
    {
        if (is_object($maybe_array)) {
            return $maybe_array;
        }

        // Unable to convert to Object from these. Return false.
        if (
            is_float($maybe_array) ||
            is_int($maybe_array) ||
            is_bool($maybe_array)
        ) {
            return self::wp_error([
                'message_lookup' => 'unable_to_convert_to_object',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        if (is_array($maybe_array)) {
            return json_decode(json_encode($maybe_array), false);
        }
    }

    public static function convert_to_assoc_array($items)
    {
        return json_decode(json_encode($items), true);
    }

    public static function serialize_data_for_db($data)
    {
        $dataSerialized = [];

        foreach ($data as $key => $value) {
            /*

			IMPORTANT -- Need to check for both Array and Objects
			otherwise the following error is thrown and data not saved:

			mysqli_real_escape_string() expects parameter 2 to be string, object given

			*/
            if (is_array($value) || is_object($value)) {
                $value = maybe_serialize($value);
            }

            $dataSerialized[$key] = $value;
        }

        return $dataSerialized;
    }

    public static function data_values_size_limit_reached($data, $table_name)
    {
        global $wpdb;

        foreach ($data as $key => $value) {
            $db_col_size = $wpdb->get_col_length($table_name, $key);

            if ($db_col_size !== false && !is_wp_error($db_col_size)) {
                if (
                    Utils_Data::size_in_bytes($value) > $db_col_size['length']
                ) {
                    return [
                        'table_name' => $table_name,
                        'value_attempted' => $value,
                        'column_name' => $key,
                        'max_size' => $db_col_size['length'],
                    ];
                }
            }
        }

        return false;
    }

    public static function mask($string)
    {
        $length = strlen($string);
        $stringNew =
            str_repeat('•', $length - 4) .
            $string[$length - 4] .
            $string[$length - 3] .
            $string[$length - 2] .
            $string[$length - 1];
        return $stringNew;
    }

    public static function remove_spaces_from_string($string)
    {
        return str_replace(' ', '', $string);
    }

    public static function comma_list_to_array($string)
    {
        return array_map('trim', explode(',', $string));
    }

    public static function is_available_to_buy($variant)
    {
        if (empty($variant) && !is_array($variant) && !is_object($variant)) {
            return false;
        }

        if (is_array($variant)) {
            $variant = self::convert_array_to_object($variant);
        }

        if (!property_exists($variant, 'inventory_management')) {
            return false;
        }

        // User has set Shopify to track the product's inventory
        if ($variant->inventory_management === 'shopify') {
            // If the product's inventory is 0 or less than 0
            if ($variant->inventory_quantity <= 0) {
                // If 'Allow customers to purchase this product when it's out of stock' is unchecked
                if ($variant->inventory_policy === 'deny') {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }

            // User has set product to "do not track inventory" (always able to purchase)
        } else {
            return true;
        }
    }

    public static function only_available_variants($variants = [])
    {
        if (empty($variants)) {
            return [];
        }

        return array_values(
            array_filter($variants, [__CLASS__, 'is_available_to_buy'])
        );
    }

    public static function has_option_values_set($variant)
    {
        $variant_copy = (array) $variant;

        if (
            isset($variant_copy['option_values']) &&
            !empty($variant_copy['option_values'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    public static function clean_option_values($option_values)
    {
        $clean_option_values = [];

        foreach ($option_values as $key => $option_value) {
            $clean_option_values['option' . ($key + 1)] = $option_value->value;
        }

        return $clean_option_values;
    }

    public static function has_option_property($key, $property)
    {
        return self::str_contains($property, 'option');
    }

    public static function only_option_properties($variant)
    {
        return array_filter(
            (array) $variant,
            [__CLASS__, 'has_option_property'],
            ARRAY_FILTER_USE_BOTH
        );
    }

    public static function get_options_values($option_values)
    {
        return maybe_unserialize($option_values);
    }

    public static function build_numbered_options_from_option_values($option)
    {
        $option_values = self::get_options_values($option['option_values']);

        $clean_option_values = self::clean_option_values($option_values);

        return $clean_option_values;
    }

    public static function maybe_build_numbered_options_from_option_values(
        $option
    ) {
        if (self::has_option_values_set($option)) {
            return self::build_numbered_options_from_option_values($option);
        }

        return $option;
    }

    public static function prevent_timeouts()
    {
        if (!function_exists('ini_get') || !ini_get('safe_mode')) {
            @set_time_limit(0);
        }
    }

    public static function has($item, $property)
    {
        if (is_array($item)) {
            $item = self::convert_array_to_object($item);
        }

        return is_object($item) && property_exists($item, $property)
            ? true
            : false;
    }

    public static function array_not_empty($maybe_array)
    {
        if (is_array($maybe_array) && !empty($maybe_array)) {
            return true;
        } else {
            return false;
        }
    }

    public static function object_is_empty($object)
    {
        $object_copy = $object;
        $object_copy = (array) $object_copy;

        if (count(array_filter($object_copy)) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function maybe_wrap_in_array($something)
    {
        if (!is_array($something)) {
            $something = [$something];
        }

        return $something;
    }

    public static function convert_to_https_url($url)
    {
        if (self::str_contains_start($url, '://')) {
            return 'https://' . explode('://', $url)[1];
        }

        return $url;
    }

    public static function unset_by($object, $keys = [])
    {
        foreach ($keys as $key) {
            unset($object->{$key});
        }

        return $object;
    }

    public static function unset_all_except($item, $exception)
    {
        if (!self::has($item, $exception)) {
            return $item;
        }

        foreach ($item as $key => $value) {
            if ($key !== $exception) {
                unset($item->{$key});
            }
        }

        return $item;
    }

    public static function filter_data_by($items, $criteria = [])
    {
        if (!$criteria) {
            return $items;
        }

        if (is_object($items)) {
            $items = self::convert_object_to_array($items);
        }

        return array_map(function ($item) use ($criteria) {
            return self::unset_by($item, $criteria);
        }, $items);
    }

    public static function filter_data_except($items, $exception = false)
    {
        if (!$exception) {
            return $items;
        }

        if (is_object($items)) {
            $items = self::convert_object_to_array($items);
        }

        return array_map(function ($item) use ($exception) {
            return self::unset_all_except($item, $exception);
        }, $items);
    }

    public static function new_cols_greater_than_old(
        $columns_new,
        $columns_current
    ) {
        return count($columns_new) > count($columns_current);
    }

    public static function new_cols_less_than_old(
        $columns_new,
        $columns_current
    ) {
        return count($columns_new) < count($columns_current);
    }

    public static function flatten_array($array)
    {
        $result = [];

        if (!is_array($array)) {
            $array_new = func_get_args();
        } else {
            $array_new = $array;
        }

        foreach ($array_new as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::flatten_array($value));
            } else {
                $result = array_merge($result, [$key => $value]);
            }
        }

        return $result;
    }

    public static function convert_array_to_in_string($array)
    {
        return "('" . implode("', '", $array) . "')";
    }

    public static function first_num($num)
    {
        $num_split = str_split($num);

        return (int) $num_split[0];
    }

    public static function get_last_index($array_size)
    {
        return $array_size - 1;
    }

    public static function is_network_wide()
    {
        if (!function_exists('is_plugin_active_for_network')) {
            require_once self::get_abs_admin_path('includes/plugin.php');
        }

        return \is_plugin_active_for_network(SHOPWP_BASENAME);
    }

    public static function get_site_url($blog_id = false)
    {
        if (is_multisite()) {
            if ($blog_id) {
                $blog_details = get_blog_details($blog_id);
            } else {
                $blog_details = get_blog_details(get_current_blog_id());
            }

            if (!empty($blog_details)) {
                return $blog_details->home;
            }
        } else {
            return get_home_url();
        }
    }

    public static function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function str_contains($string, $needle)
    {
        if (!is_string($string) || !is_string($needle)) {
            return false;
        }

        if (strpos($string, $needle) !== false) {
            return true;
        }

        return false;
    }

    public static function str_contains_start($string, $needle)
    {
        if (!is_string($string) || !is_string($needle)) {
            return false;
        }

        if (strpos($string, $needle) === 0) {
            return true;
        }

        return false;
    }

    public static function convert_camel_to_underscore($string)
    {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', '_$1', $string));
    }

    public static function convert_underscore_to_camel_string($string)
    {
        $str = str_replace('_', '', ucwords($string, '_'));

        $str = lcfirst($str);

        return $str;
    }

    public static function convert_hyphen_to_underscore($string)
    {
        return str_replace('-', '_', $string);
    }

    public static function convert_space_to_underscore($string)
    {
        return str_replace(' ', '_', $string);
    }

    public static function convert_underscore_to_camel_array($array)
    {
        $arr = [];

        foreach ($array as $key => $value) {
            $key = self::convert_underscore_to_camel_string($key);

            if (is_array($value)) {
                $value = self::convert_underscore_to_camel_array($value);
            }

            $arr[$key] = $value;
        }

        return $arr;
    }

    public static function mask_value($val)
    {
        $length_str = strlen($val);

        if ($length_str <= 4) {
            return $val;
        }

        $str_length_minus_four = $length_str - 4;
        $str_last_four_chars = substr($val, -4);

        return str_repeat("*", $str_length_minus_four) . $str_last_four_chars;
    }

    public static function strip_tags_content(
        $text,
        $tags = '',
        $invert = false
    ) {
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) and count($tags) > 0) {
            if ($invert == false) {
                return preg_replace(
                    '@<(?!(?:' .
                        implode('|', $tags) .
                        ')\b)(\w+)\b.*?>.*?</\1>@si',
                    '',
                    $text
                );
            } else {
                return preg_replace(
                    '@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si',
                    '',
                    $text
                );
            }
        } elseif ($invert == false) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }

        return $text;
    }

    public static function sanitize_html_template($html)
    {
        return preg_replace('~>\\s+<~m', '><', $html);
    }

    public static function convert_storefront_id_to_numeric($storefront_id, $type = 'Product') {

        if (strpos($storefront_id, 'gid://') === false && $type === 'Product') {
            $id_to_check = base64_decode($storefront_id);
        } else {
            $id_to_check = $storefront_id;
        }

        $result = str_replace('gid://shopify/' . $type . '/', '', $id_to_check);

        return (int) $result;        
    }    
}

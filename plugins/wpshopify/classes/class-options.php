<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class Options
{
    public static function get($name, $default = false)
    {
        return get_option($name, $default);
    }

    public static function update($name, $value)
    {
        $updated_result = update_option($name, $value);

        if ($name === 'shopwp_cache_cleared') {
            return true;
        }

        if (!$updated_result) {
            if (is_array($value)) {
                $attempted_valued = 'Array';
            } elseif (is_object($value)) {
                $attempted_valued = 'Object';
            } else {
                $attempted_valued = $value;
            }

            return Utils::wp_error([
                'message_lookup' =>
                    'Failed to update option: ' .
                    $name .
                    ' with value: ' .
                    $attempted_valued,
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        return true;
    }

    public static function delete($name)
    {
        return delete_option($name);
    }
}

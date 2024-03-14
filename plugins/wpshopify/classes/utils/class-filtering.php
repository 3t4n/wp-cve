<?php

namespace ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Filtering
{
    /*

	Generic function to filter by a specific key / value

	*/
    public static function filter_by($items, $type)
    {
        $items_copy = $items;

        return array_filter($items_copy, [__CLASS__, 'filter_by_' . $type]);
    }
}

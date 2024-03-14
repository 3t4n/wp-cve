<?php

namespace ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Sorting
{
    public static function sort_by($items, $type)
    {
        usort($items, [__CLASS__, 'sort_by_' . $type]);

        return $items;
    }

    public static function reverse($items)
    {
        return array_reverse($items);
    }
}

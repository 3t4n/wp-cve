<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Query;

abstract class Search
{
    /*
    public static function init(): void
    {
    }
    */

    public static function isEncyclopediaSearch(WP_Query $query): bool
    {
        if ($query->is_search) {
            # Check post type
            if ($query->get('post_type') == PostType::post_type_name) return true;

            # Check taxonomies
            $encyclopedia_taxonomies = get_Object_Taxonomies(PostType::post_type_name);
            if (!empty($encyclopedia_taxonomies) && $query->is_Tax($encyclopedia_taxonomies)) return true;
        }

        return false;
    }
}

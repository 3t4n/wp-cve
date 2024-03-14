<?php

namespace WordPress\Plugin\GalleryManager;

use WP_Query;

abstract class Query
{
    public static function isGallerySearch(): bool
    {
        if (is_search()) {
            # Check if the search is inside a post type
            if (get_query_var('post_type') == PostType::post_type_name) return true;

            # Check if the search is inside a taxonomy
            $gallery_taxonomies = get_Object_Taxonomies(PostType::post_type_name);
            if (!empty($gallery_taxonomies) && is_Tax($gallery_taxonomies)) return true;
        }

        return false;
    }

    /*
    private static function loadQuery(&$query = null)
    {
        if (!$query instanceof WP_Query) {
            global $wp_query;
            $query = $wp_query;
        }
    }

    public static function isGallerySingle($query = null, $post)
    {
        static::loadQuery($query);
        return $query->is_Single($post);
    }

    public static function isGalleryPostTypeArchive($query = null)
    {
        static::loadQuery($query);
        return (!$query->is_search && $query->is_Post_Type_Archive(PostType::post_type_name));
    }

    public static function isGalleryTaxonomyArchive($query = null)
    {
        static::loadQuery($query);
        $gallery_taxonomies = get_Object_Taxonomies(PostType::post_type_name);
        return !empty($gallery_taxonomies) && $query->is_Tax($gallery_taxonomies);
    }

    public static function isGlobalSearch($query = null)
    {
        static::loadQuery($query);
        return ($query->is_search && $query->get('post_type') != PostType::post_type_name);
    }
    */

}

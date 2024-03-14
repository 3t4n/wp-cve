<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class TaxonomyFallbacks
{
    public static function init()
    {
        add_filter('get_the_terms', [static::class, 'Filter_Get_The_Terms'], 10, 3);
        add_filter('the_category', [static::class, 'Filter_The_Category'], 10, 2);
    }

    public static function Filter_Get_The_Terms($arr_terms, int $post_id, string $taxonomy_name)
    {
        static $term_remap = [
            'category' => 'encyclopedia-category',
            'post_tag' => 'encyclopedia-tag'
        ];

        $map_to_taxonomy = $term_remap[$taxonomy_name] ?? false;

        $post = get_Post($post_id);

        if (
            !is_Admin() &&
            $post && $post->post_type === PostType::post_type_name &&
            !is_Object_in_Taxonomy($post->post_type, $taxonomy_name) &&
            $map_to_taxonomy && taxonomy_exists($map_to_taxonomy) && is_Object_in_Taxonomy($post->post_type, $map_to_taxonomy)
        ) {
            $arr_terms = get_the_terms($post, $map_to_taxonomy);
        }

        return $arr_terms;
    }

    public static function Filter_The_Category(string $category_list, string $separator = '' /* , string $parents */): string
    {
        global $post;
        $encyclopedia_taxonomy = 'encyclopedia-category';

        if (
            empty($category_list) &&
            !is_Admin() &&
            $post && $post->post_type === PostType::post_type_name &&
            !is_Object_in_Taxonomy($post->post_type, 'category') &&
            taxonomy_exists($encyclopedia_taxonomy) && is_Object_in_Taxonomy($post->post_type, $encyclopedia_taxonomy)
        ) {
            $category_list = get_The_Term_List($post->ID, $encyclopedia_taxonomy, null, $separator, null);
            if (empty($category_list)) $category_list = I18n::__('Uncategorized');
        }

        return $category_list;
    }
}

TaxonomyFallbacks::init();

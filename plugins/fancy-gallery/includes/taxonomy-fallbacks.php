<?php

namespace WordPress\Plugin\GalleryManager;

abstract class TaxonomyFallbacks
{
    public static function init()
    {
        add_Filter('get_the_categories', [static::class, 'Filter_Get_The_Categories'], 10, 2);
        add_Filter('the_category', [static::class, 'Filter_The_Category'], 10, 3);
        add_Filter('get_the_tags', [static::class, 'Filter_Get_The_Tags']);
        add_Filter('the_tags', [static::class, 'Filter_The_Tags'], 10, 5);
    }

    public static function Filter_Get_The_Categories($arr_categories, $post_id)
    {
        $post = get_Post($post_id);

        if (!is_Admin() && $post) {
            $gallery_taxonomy = 'gallery-category';
            $taxonomy_exists = Taxonomy_Exists($gallery_taxonomy);
            $is_gallery = $post->post_type == PostType::post_type_name;
            $uses_post_categories = is_Object_in_Taxonomy($post->post_type, 'category');
            $uses_gallery_categories = is_Object_in_Taxonomy($post->post_type, $gallery_taxonomy);

            if ($taxonomy_exists && $is_gallery && !$uses_post_categories && $uses_gallery_categories) {
                $arr_categories = get_The_Terms($post->ID, $gallery_taxonomy);
                if (is_Array($arr_categories)) {
                    foreach ($arr_categories as &$category) {
                        _make_Cat_Compat($category); # Compat mode for very very very old and deprecated themes...
                    }
                } else {
                    $arr_categories = [];
                }
            }
        }

        return $arr_categories;
    }

    public static function Filter_The_Category($category_list, $separator = null, $parents = null)
    {
        global $post;

        if (!is_Admin() && $post) {
            $gallery_taxonomy = 'gallery-category';
            $taxonomy_exists = Taxonomy_Exists($gallery_taxonomy);
            $is_gallery = $post->post_type == PostType::post_type_name;
            $uses_post_categories = is_Object_in_Taxonomy($post->post_type, 'category');
            $uses_gallery_categories = is_Object_in_Taxonomy($post->post_type, $gallery_taxonomy);

            if ($taxonomy_exists && $is_gallery && !$uses_post_categories && $uses_gallery_categories) {
                $category_list = get_The_Term_List($post->ID, $gallery_taxonomy, null, $separator, null);
                if (empty($category_list)) $category_list = I18n::__('Uncategorized');
            }
        }

        return $category_list;
    }

    public static function Filter_Get_The_Tags($arr_tags)
    {
        global $post;

        if (!is_Admin() && $post) {
            $gallery_taxonomy = 'gallery-tag';
            $taxonomy_exists = Taxonomy_Exists($gallery_taxonomy);
            $is_gallery = $post->post_type == PostType::post_type_name;
            $uses_post_tags = is_Object_in_Taxonomy($post->post_type, 'post_tag');
            $uses_gallery_tags = is_Object_in_Taxonomy($post->post_type, $gallery_taxonomy);

            if ($taxonomy_exists && $is_gallery && !$uses_post_tags && $uses_gallery_tags) {
                $arr_tags = get_The_Terms($post->ID, $gallery_taxonomy);
            }
        }

        return $arr_tags;
    }

    public static function Filter_The_Tags($tag_list, $before, $separator, $after, $post_id)
    {
        $post = get_Post($post_id);

        if (!is_Admin() && $post) {
            $gallery_taxonomy = 'gallery-tag';
            $taxonomy_exists = Taxonomy_Exists($gallery_taxonomy);
            $is_gallery = $post->post_type == PostType::post_type_name;
            $uses_post_tags = is_Object_in_Taxonomy($post->post_type, 'post_tag');
            $uses_gallery_tags = is_Object_in_Taxonomy($post->post_type, $gallery_taxonomy);

            if ($taxonomy_exists && $is_gallery && !$uses_post_tags && $uses_gallery_tags) {
                $tag_list = get_The_Term_List($post_id, $gallery_taxonomy, $before, $separator, $after);
            }
        }

        return $tag_list;
    }
}

TaxonomyFallbacks::init();

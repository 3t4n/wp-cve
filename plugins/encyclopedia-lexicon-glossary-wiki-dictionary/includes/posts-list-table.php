<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class PostsListTable
{
    public static function init(): void
    {
        add_filter('disable_categories_dropdown', [static::class, 'disableCategoriesFilterDropdown'], 10, 2);
        add_action('restrict_manage_posts', [static::class, 'printFilterDropdown']);
    }

    public static function disableCategoriesFilterDropdown(bool $status, string $post_type): bool
    {
        if ($post_type === PostType::post_type_name) {
            $status = true; # true means the dropdown is disabled
        }

        return $status;
    }

    public static function printTaxonomyFilterDropdown(string $taxonomy_name): void
    {
        $taxonomy = get_taxonomy($taxonomy_name);

        if ($taxonomy) {
            $dropdown_options = [
                'show_option_none' => $taxonomy->labels->all_items,
                'option_none_value' => '',
                'taxonomy' => $taxonomy->name,
                'orderby' => 'name',
                'hide_empty' => false,
                'hierarchical' => true,
                'show_count' => false,
                'id' => sprintf('taxonomy-filter-%s', $taxonomy->name),
                'name' => $taxonomy->query_var,
                'selected' => $_GET[$taxonomy->query_var] ?? null,
                'value_field' => 'slug'
            ];

            printf('<label class="screen-reader-text" for="taxonomy-filter-%1$s">%2$s</label>', $taxonomy->name, $taxonomy->labels->filter_by_item);

            wp_dropdown_categories($dropdown_options);
        }
    }

    public static function printFilterDropdown(string $post_type): void
    {
        if ($post_type === PostType::post_type_name) {
            $arr_taxonomies = get_object_taxonomies($post_type, 'objects');

            foreach ($arr_taxonomies as $taxonomy) {
                static::printTaxonomyFilterDropdown($taxonomy->name);
            }
        }
    }
}

PostsListTable::init();

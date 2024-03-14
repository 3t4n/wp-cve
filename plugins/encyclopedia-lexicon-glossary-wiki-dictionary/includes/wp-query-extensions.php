<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Query;

abstract class WPQueryExtensions
{
    public static function init(): void
    {
        add_filter('query_vars', [static::class, 'registerQueryVars']);
        add_action('pre_get_posts', [static::class, 'filterQuery']);
        add_filter('posts_where', [static::class, 'filterPostsWhere'], 10, 2);
        add_filter('posts_fields', [static::class, 'filterPostsFields'], 10, 2);
        add_filter('posts_orderby', [static::class, 'filterPostsOrderBy'], 10, 2);
    }

    public static function registerQueryVars(array $query_vars): array
    {
        $query_vars[] = 'prefix'; # Will store the filter of the prefix search
        return $query_vars;
    }

    public static function filterQuery(WP_Query $query): void
    {
        if (!$query->get('suppress_filters') && !$query->is_Feed()) {
            $order_by_post_title = false;
            $include_entries = false;
            $set_archive_post_count = false;

            # Change post order for all kind of Encyclopedia queries
            if ($query->get('post_type') == PostType::post_type_name) {
                $order_by_post_title = true;
            }

            if (!is_Admin() && $query->is_Main_Query()) {
                # Take a look at the prefix filter - this works for all post types
                if (!$query->get('post_title_like') && $query->get('prefix'))
                    $query->set('post_title_like', RawUrlDecode($query->get('prefix')) . '%');

                # Change the number of terms per page
                if ($query->is_Post_Type_Archive(PostType::post_type_name))
                    $set_archive_post_count = true;

                if ($query->is_category || $query->is_tag || $query->is_tax) {
                    $current_term = $query->get_Queried_Object();
                    $current_taxonomy = $current_term->taxonomy ?? false;

                    # get all taxonomies associated with the Encyclopedia post type
                    $arr_encyclopedia_taxonomies = (array) get_Object_Taxonomies(PostType::post_type_name);

                    if ($current_taxonomy && in_Array($current_taxonomy, $arr_encyclopedia_taxonomies)) {
                        $set_archive_post_count = true;
                        $order_by_post_title = true;

                        if ($query->is_category || $query->is_tag) {
                            # custom taxonomies have "any" as default post type and we do not need to add encyclopedia entries manually
                            $include_entries = true;
                        }
                    }
                }
            }

            if ($order_by_post_title) {
                # Order the terms by title, ASC
                if (!$query->get('orderby')) {
                    $query->set('orderby', 'title');

                    if (!$query->get('order'))
                        $query->set('order', 'asc');
                }
            }

            if ($include_entries) {
                # Add encyclopedia entries to the query
                $post_types = (array) $query->get('post_type');
                $post_types = array_filter($post_types);

                if (empty($post_types))
                    $post_types = ['post'];

                if (!in_Array(PostType::post_type_name, $post_types))
                    $post_types[] = PostType::post_type_name;

                $query->set('post_type', $post_types);
            }

            if ($set_archive_post_count) {
                if (!$query->get('posts_per_page'))
                    $query->set('posts_per_page', Options::get('items_per_page'));
            }
        }
    }

    public static function filterPostsWhere(string $where, WP_Query $query): string
    {
        global $wpdb;

        $post_title_like = $query->get('post_title_like');
        if (!empty($post_title_like)) {
            $post_title_like_esced = esc_SQL($post_title_like);
            $where .= " AND {$wpdb->posts}.post_title LIKE \"{$post_title_like_esced}\" ";
        }

        $min_title_length = (int) $query->get('min_title_length');
        if ($min_title_length > 1) {
            $where .= " AND CHAR_LENGTH({$wpdb->posts}.post_title) >= {$min_title_length} ";
        }

        return $where;
    }

    public static function filterPostsFields(string $fields, WP_Query $query): string
    {
        global $wpdb;

        if ($query->get('orderby') == 'post_title_length')
            $fields .= ", CHAR_LENGTH({$wpdb->posts}.post_title) post_title_length";

        return $fields;
    }

    public static function filterPostsOrderBy(string $orderby, WP_Query $query): string
    {
        if ($query->get('orderby') == 'post_title_length')
            $orderby = trim(sprintf('post_title_length %s', $query->get('order')));

        return $orderby;
    }
}

WPQueryExtensions::init();

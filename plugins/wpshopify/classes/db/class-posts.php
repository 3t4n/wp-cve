<?php

namespace ShopWP\DB;

use ShopWP\Utils;
use ShopWP\Transients;
use ShopWP\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class Posts extends \ShopWP\DB
{
    public function __construct() {}

    public function delete_posts_by_type($post_type)
    {
        global $wpdb;

        $wp_posts = $wpdb->prefix . SHOPWP_TABLE_NAME_WP_POSTS;
        $wp_term = $wpdb->prefix . SHOPWP_TABLE_NAME_WP_TERM_RELATIONSHIPS;
        $meta = $wpdb->prefix . SHOPWP_TABLE_NAME_WP_POSTMETA;

        $query = $wpdb->prepare('DELETE posts, pt, pm FROM ' . $wp_posts . ' posts LEFT JOIN ' . $wp_term . ' pt ON pt.object_id = posts.ID LEFT JOIN ' . $meta . ' pm ON pm.post_id = posts.ID WHERE posts.post_type = %s', $post_type);

        return $this->query($query);
    }

    /*

	Delete all posts

	*/
    public function delete_all_posts()
    {
        $results = [];
        // $this->delete_taxonomies('wps_tags'); Not currently used
        $results[
            SHOPWP_PRODUCTS_POST_TYPE_SLUG
        ] = $this->delete_posts_by_type(SHOPWP_PRODUCTS_POST_TYPE_SLUG);
        $results[
            SHOPWP_COLLECTIONS_POST_TYPE_SLUG
        ] = $this->delete_posts_by_type(SHOPWP_COLLECTIONS_POST_TYPE_SLUG);

        return $results;
    }

    /*

	Delete posts by ids

	*/
    public function delete_posts_by_ids($post_ids)
    {
        global $wpdb;

        if (!is_array($post_ids)) {
            $post_ids = Utils::maybe_wrap_in_array($post_ids);
        }

        if (empty($post_ids)) {
            return false;
        }

        // how many entries will we select?
        $how_many = count($post_ids);

        // prepare the right amount of placeholders
        $placeholders = array_fill(0, $how_many, '%d');

        // $format = '%d, %d, %d, %d, %d, [...]'
        $format = Utils::convert_to_comma_string($placeholders);

        $wp_posts = $wpdb->prefix . SHOPWP_TABLE_NAME_WP_POSTS;
        $wp_term = $wpdb->prefix . SHOPWP_TABLE_NAME_WP_TERM_RELATIONSHIPS;
        $meta = $wpdb->prefix . SHOPWP_TABLE_NAME_WP_POSTMETA;

        $query = "DELETE posts, pt, pm FROM " . $wp_posts . " posts LEFT JOIN " . $wp_term . " pt ON pt.object_id = posts.ID LEFT JOIN " . $meta . " pm ON pm.post_id = posts.ID WHERE posts.ID IN(" . $format . ")";

        $query_prepared = $wpdb->prepare($query, $post_ids);

        return $wpdb->query($query_prepared);
    }

    /*

	Delete taxonomies

	*/
    public function delete_taxonomies($type)
    {
        if (!taxonomy_exists($type)) {
            return;
        }

        $deletions = [];

        $terms = get_terms([
            'taxonomy' => $type,
            'hide_empty' => false
        ]);

        foreach ($terms as $term) {
            $deletions[] = wp_delete_term($term->term_id, $type);
        }

        return $deletions;
    }

    /*

	Delete the synced Shopify data
	$type = 'all', $items = false

	*/
    public function delete_posts($params = [])
    {
        $results = [];

        if (empty($params)) {
            return $this->delete_all_posts();
        }

        if (!empty($params['ids'])) {
            $results['delete_posts_ids'] = $this->delete_posts_by_ids(
                $params['ids']
            );
            return $results;
        }

        if (!empty($params['post_type'])) {
            $results['delete_posts_type'] = $this->delete_posts_by_type(
                $params['post_type']
            );
            return $results;
        }
    }

    public function get_from_post_name_query_prepared($table_name, $post_name)
    {
        return 'SELECT items.* FROM ' . $table_name . ' as items WHERE items.post_name = ' . $post_name;
    }

    /*

	Finds a given item based on its post ID

	*/
    public function get_from_post_name($table_name = false, $post_name = false)
    {
        global $wpdb;

        if (!$table_name) {
            return;
        }

        if ($post_name === false) {
            return;
        }

        $query = $this->get_from_post_name_query_prepared($post_name, $table_name);

        return $wpdb->get_row($query);
    }
}

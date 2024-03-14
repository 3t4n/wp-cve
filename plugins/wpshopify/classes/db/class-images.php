<?php

namespace ShopWP\DB;

use ShopWP\Utils;
use ShopWP\Utils\URLs;

if (!defined('ABSPATH')) {
    exit();
}

class Images extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_image_id;
    public $default_product_id;
    public $default_post_id;
    public $default_variant_ids;
    public $default_src;
    public $default_alt;
    public $default_position;
    public $default_created_at;
    public $default_updated_at;
    public $default_collection_id;

    public function __construct()
    {
        $this->table_name_suffix = SHOPWP_TABLE_NAME_IMAGES;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'image_id';
        $this->cache_group = 'wps_db_images';
        $this->type = 'image';

        $this->default_image_id = 0;
        $this->default_product_id = 0;
        $this->default_collection_id = 0;
        $this->default_post_id = 0;
        $this->default_variant_ids = '';
        $this->default_src = '';
        $this->default_alt = '';
        $this->default_position = 0;
        $this->default_created_at = '';
        $this->default_updated_at = '';
    }

    public function get_columns()
    {
        return [
            'id' => '%d',
            'image_id' => '%s',
            'product_id' => '%s',
            'collection_id' => '%s',
            'post_id' => '%d',
            'variant_ids' => '%s',
            'src' => '%s',
            'alt' => '%s',
            'position' => '%d',
            'created_at' => '%s',
            'updated_at' => '%s'
        ];
    }

    public function cols_that_should_remain_ints()
    {
        return [
            'id',
            'post_id',
            'position'
        ];
    }

    public function get_column_defaults()
    {
        return [
            'image_id' => $this->default_image_id,
            'product_id' => $this->default_product_id,
            'collection_id' => $this->default_collection_id,
            'post_id' => $this->default_post_id,
            'variant_ids' => $this->default_variant_ids,
            'src' => $this->default_src,
            'alt' => $this->default_alt,
            'position' => $this->default_position,
            'created_at' => $this->default_created_at,
            'updated_at' => $this->default_updated_at
        ];
    }

    public function modify_options(
        $shopify_item,
        $item_lookup_key = SHOPWP_PRODUCTS_LOOKUP_KEY
    ) {
        return [
            'item' => $shopify_item,
            'item_lookup_key' => $item_lookup_key,
            'item_lookup_value' => $shopify_item->id,
            'prop_to_access' => 'images',
            'change_type' => 'image'
        ];
    }

    public function mod_before_change($image)
    {
        $image_copy = $this->copy($image);
        $image_copy = $this->maybe_rename_to_lookup_key($image_copy);

        return $image_copy;
    }

    public function insert_image($image)
    {
        return $this->insert($image);
    }

    public function update_image($image)
    {
        return $this->update(
            $this->lookup_key,
            $this->get_lookup_value($image),
            $image
        );
    }

    public function delete_image($image)
    {
        return $this->delete_rows(
            $this->lookup_key,
            $this->get_lookup_value($image)
        );
    }

    public function delete_images_from_product_id($product_id)
    {
        return $this->delete_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    public function get_images_from_product_id($product_id)
    {
        return $this->get_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    public function query_all_images($table_name)
    {
        return 'SELECT images.src, images.post_id, images.alt FROM ' . $table_name . ' as images WHERE images.position = 1';
    }

    public function get_all_images()
    {
        global $wpdb;

        $query = $this->query_all_images($this->table_name);

        return $wpdb->get_results($query);
    }

    public function delete_media()
    {
        $media_ids = $this->get_all_plugin_attachments();

        if ($media_ids && !empty($media_ids)) {
            $results = [];

            foreach ($media_ids as $media_id) {
                $results[] = wp_delete_attachment($media_id->ID, true);
            }

            return $results;
        } else {
            return false;
        }
    }

    public function get_all_plugin_attachments()
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'no_found_rows' => true,
            'meta_query' => [
                [
                    'key' => '_wp_attachment_metadata',
                    'value' => 'shopwp',
                    'compare' => 'LIKE'
                ]
            ]
        ];

        $images = new \WP_Query($args);

        return $images->posts;
    }

    public function query_get_all_plugin_attachments_meta($posts_table, $post_meta_table)
    {
        return "SELECT " . $post_meta_table . ".* FROM " . $posts_table . " INNER JOIN " . $post_meta_table . " ON (" . $posts_table . ".ID = " . $post_meta_table . ".post_id ) WHERE 1=1 AND (( " . $post_meta_table . ".meta_key = '_wp_attachment_metadata' AND " . $post_meta_table . ".meta_value LIKE '%shopwp%' )) AND " . $posts_table . ".post_type = 'attachment' AND ((" . $posts_table . ".post_status = 'inherit')) GROUP BY " . $posts_table . ".ID";

    }

    public function get_all_plugin_attachments_meta()
    {
        global $wpdb; 

        $posts_table = $wpdb->prefix . 'posts';
        $post_meta_table = $wpdb->prefix . 'postmeta';

        $query = $this->query_get_all_plugin_attachments_meta($posts_table, $post_meta_table);

        return $wpdb->get_results($query);
    }

    /*

	Creates a table query string

	*/
    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			image_id varchar(255) DEFAULT '{$this->default_image_id}',
			product_id varchar(255) DEFAULT '{$this->default_product_id}',
            collection_id varchar(255) DEFAULT '{$this->default_collection_id}',
            post_id bigint(100) DEFAULT '{$this->default_post_id}',
			variant_ids varchar(255) DEFAULT '{$this->default_variant_ids}',
			src varchar(255) DEFAULT '{$this->default_src}',
			alt varchar(255) DEFAULT '{$this->default_alt}',
			position int(20) DEFAULT '{$this->default_position}',
			created_at varchar(255) DEFAULT '{$this->default_created_at}',
			updated_at varchar(255) DEFAULT '{$this->default_updated_at}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}

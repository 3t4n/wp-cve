<?php

namespace ShopWP\DB;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Tags extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_tag_id;
    public $default_product_id;
    public $default_post_id;
    public $default_tag;

    public function __construct()
    {
        $this->table_name_suffix = SHOPWP_TABLE_NAME_TAGS;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'tag_id';
        $this->cache_group = 'wps_db_tags';
        $this->type = 'tag';

        $this->default_tag_id = '';
        $this->default_product_id = '';
        $this->default_post_id = 0;
        $this->default_tag = '';
    }

    public function get_columns()
    {
        return [
            'id' => '%d',
            'tag_id' => '%s',
            'product_id' => '%s',
            'post_id' => '%d',
            'tag' => '%s',
        ];
    }

    public function cols_that_should_remain_ints()
    {
        return ['id', 'post_id'];
    }

    public function get_column_defaults()
    {
        return [
            'tag_id' => $this->default_tag_id,
            'product_id' => $this->default_product_id,
            'post_id' => $this->default_post_id,
            'tag' => $this->default_tag,
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
            'prop_to_access' => 'tags',
            'change_type' => 'tag',
        ];
    }

    public function insert_tag($tag)
    {
        return $this->insert($tag);
    }

    public function delete_tag($tag)
    {
        return $this->delete_rows(
            $this->lookup_key,
            $this->get_lookup_value($tag)
        );
    }

    public function get_tags_from_product_id($product_id)
    {
        return $this->get_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    public function get_product_ids_from_tags($tags)
    {
        return $this->select_in_col('product_id', 'tag', $tags);
    }

    public function delete_tags_from_product_id($product_id)
    {
        return $this->delete_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    public function get_id_from_shopify($product)
    {
        if (Utils::has($product, 'id')) {
            return $product->id;
        } else {
            return 0;
        }
    }

    public function construct_tag_model($tag, $product = 0, $post_id = 0)
    {
        return [
            'tag_id' => 0,
            'product_id' => $this->get_id_from_shopify($product),
            'post_id' => $post_id,
            'tag' => $tag,
        ];
    }

    public function construct_tags_for_insert($product, $post_id = 0)
    {
        $results = [];
        $tags = Utils::comma_list_to_array($product->tags);

        foreach ($tags as $tag) {
            $results[] = Utils::convert_array_to_object(
                $this->add_tag_id_to_tag(
                    $this->construct_tag_model($tag, $product, $post_id)
                )
            );
        }

        return $results;
    }

    public function create_tag_id($tag)
    {
        return Utils::hash_static_num($tag['product_id'] . $tag['tag']);
    }

    public function add_tag_id_to_tag($tag)
    {
        $tag_id_hash = $this->create_tag_id($tag);
        $tag['tag_id'] = $tag_id_hash;

        return $tag;
    }

    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			tag_id longtext DEFAULT '{$this->default_tag_id}',
			product_id longtext DEFAULT '{$this->default_product_id}',
			post_id bigint(100) DEFAULT '{$this->default_post_id}',
			tag varchar(255) DEFAULT '{$this->default_tag}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}

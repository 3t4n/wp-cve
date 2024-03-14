<?php

namespace ShopWP\DB;

use ShopWP\CPT;
use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Products extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_product_id;
    public $default_post_id;
    public $default_title;
    public $default_body_html;
    public $default_handle;
    public $default_post_name;
    public $default_image;
    public $default_images;
    public $default_vendor;
    public $default_product_type;
    public $default_published_scope;
    public $default_published_at;
    public $default_updated_at;
    public $default_created_at;
    public $default_admin_graphql_api_id;
    public $default_metafields_global_title_tag;
    public $default_metafields_global_description_tag;

    public function __construct()
    {
        $this->table_name_suffix = SHOPWP_TABLE_NAME_PRODUCTS;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = SHOPWP_PRODUCTS_LOOKUP_KEY;
        $this->cache_group = 'wps_db_products';
        $this->type = 'product';

        $this->default_product_id = '';
        $this->default_post_id = 0;
        $this->default_title = '';
        $this->default_body_html = '';
        $this->default_handle = '';
        $this->default_post_name = '';
        $this->default_image = '';
        $this->default_images = '';
        $this->default_vendor = '';
        $this->default_product_type = '';
        $this->default_published_scope = '';
        $this->default_published_at = '';
        $this->default_updated_at = '';
        $this->default_created_at = '';
        $this->default_admin_graphql_api_id = '';

        $this->metafields_global_title_tag = '';
        $this->metafields_global_description_tag = '';
    }

    /*

    Table column name / formats

    Important: Used to determine when new columns are added

     */
    public function get_columns()
    {
        return [
            'id' => '%d',
            'product_id' => '%s',
            'post_id' => '%d',
            'title' => '%s',
            'body_html' => '%s',
            'handle' => '%s',
            'post_name' => '%s',
            'image' => '%s',
            'images' => '%s',
            'vendor' => '%s',
            'product_type' => '%s',
            'published_scope' => '%s',
            'published_at' => '%s',
            'updated_at' => '%s',
            'created_at' => '%s',
            'admin_graphql_api_id' => '%s',
            'metafields_global_title_tag' => '%s',
            'metafields_global_description_tag' => '%s',
        ];
    }

    /*

    Columns that should remain integers during casting.
    We need to check against this when retrieving data since MYSQL 
    converts all cols to strings upon retrieval. 

    */
    public function cols_that_should_remain_ints()
    {
        return ['id', 'post_id'];
    }

    public function get_column_defaults()
    {
        return [
            'product_id' => $this->default_product_id,
            'post_id' => $this->default_post_id,
            'title' => $this->default_title,
            'body_html' => $this->default_body_html,
            'handle' => $this->default_handle,
            'post_name' => $this->default_post_name,
            'image' => $this->default_image,
            'images' => $this->default_images,
            'vendor' => $this->default_vendor,
            'product_type' => $this->default_product_type,
            'published_scope' => $this->default_published_scope,
            'published_at' => $this->default_published_at,
            'updated_at' => $this->default_updated_at,
            'created_at' => $this->default_created_at,
            'admin_graphql_api_id' => $this->default_admin_graphql_api_id,
            'metafields_global_title_tag' =>
                $this->default_metafields_global_title_tag,
            'metafields_global_description_tag' =>
                $this->default_metafields_global_description_tag,
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
            'prop_to_access' => 'products',
            'change_type' => 'product',
        ];
    }

    public function mod_before_change($product, $post_id = false)
    {
        $product_copy = $this->copy($product);
        $product_copy = $this->maybe_rename_to_lookup_key($product_copy);
        $product_copy = Utils::flatten_image_prop($product_copy);

        if ($post_id) {
            $product_copy = CPT::set_post_id($product_copy, $post_id);
        }

        // Important. If handle doesn't match post_name, the product won't show
        $product_copy->post_name = sanitize_title($product_copy->handle);

        return $product_copy;
    }

    public function insert_product($product)
    {
        return $this->insert($product);
    }

    public function update_product($product)
    {
        return $this->update(
            $this->lookup_key,
            $this->get_lookup_value($product),
            $product
        );
    }

    public function delete_product($product)
    {
        return $this->delete_rows(
            $this->lookup_key,
            $this->get_lookup_value($product)
        );
    }

    public function delete_products_from_product_id($product_id)
    {
        return $this->delete_rows($this->lookup_key, $product_id);
    }

    public function get_product_from_product_id($product_id)
    {
        return $this->get_rows($this->lookup_key, $product_id);
    }

    public function return_vendor($product)
    {
        return $product->vendor;
    }

    public function return_product_type($product)
    {
        return $product->product_type;
    }

    public function get_unique_vendors()
    {
        $products = $this->get_all_rows();

        return array_values(
            array_unique(array_map([__CLASS__, 'return_vendor'], $products))
        );
    }

    public function get_unique_types()
    {
        $products = $this->get_all_rows();

        return array_values(
            array_unique(
                array_map([__CLASS__, 'return_product_type'], $products)
            )
        );
    }

    public function query_get_product_from_post_id($table_name, $post_id) {
        return 'SELECT products.* FROM ' . $table_name . ' as products WHERE products.post_id = ' . $post_id;
    }

    public function get_product_from_post_id($post_id = null)
    {
        global $wpdb;

        if ($post_id === null) {
            $post_id = get_the_ID();
        }

        $query_prepared = query_get_product_from_post_id($this->table_name, $post_id);

        return $wpdb->get_row($query_prepared);

    }

    public function get_product_ids_query($limit = false)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . SHOPWP_TABLE_NAME_PRODUCTS;

        if ($limit) {
            return 'SELECT product.product_id FROM ' . $table_name . ' product LIMIT ' . $limit;

        } else {
            return 'SELECT product.product_id FROM ' . $table_name . ' product';
        }

        return $main_query;
    }

    public function get_product_ids($limit = false)
    {
        global $wpdb;

        $query = $this->get_product_ids_query($limit);

        $results = $wpdb->get_results($query);

        return array_column($results, 'product_id');
    }

    /*

    Add Post ID To Product

     */
    public function add_post_id_to_product($product, $cpt_id)
    {
        $product->post_id = $cpt_id;

        return $product;
    }

    /*

    Assigns a post id to the product data

     */
    public function assign_post_id_to_product($post_id, $product_id)
    {
        global $wpdb;

        return $wpdb->update(
            $this->table_name,
            ['post_id' => $post_id],
            [$this->lookup_key => $product_id],
            ['%d'],
            ['%d']
        );
    }

    public function product_exists($product_id)
    {
        if (empty($product_id)) {
            return false;
        }

        $product_found = $this->get_row_by('product_id', $product_id);

        if (empty($product_found)) {
            return false;
        } else {
            return true;
        }
    }

    /*

    Find a post ID from a product ID

     */
    public function get_post_id_from_product_id($product_id)
    {
        $product = $this->get_product_from_product_id($product_id);

        if (empty($product)) {
            return [];
        }

        return $product[0]->post_id;
    }

    public function get_post_id_by_product_id($product_id)
    {
        return \get_posts([
            'post_type'         => 'wps_products',
            'posts_per_page'    => 1,
            'meta_key'          => 'product_id',
            'meta_value'        => $product_id,
            'fields'            => 'ids',
        ]);
    }

    public function update_products($products)
    {
        $result = [];

        foreach ($products as $key => $product) {
            $result[] = $this->update(
                $this->lookup_key,
                $product['id'],
                $product
            );
        }

        return $result;
    }

    public function get_product_ids_from_titles($titles)
    {
        return $this->select_in_col('product_id', 'title', $titles);
    }

    public function get_product_ids_from_vendors($vendors)
    {
        return $this->select_in_col('product_id', 'vendor', $vendors);
    }

    public function get_product_ids_from_handles($handles)
    {
        return $this->select_in_col('product_id', 'handle', $handles);
    }

    public function get_product_ids_from_types($types)
    {
        return $this->select_in_col('product_id', 'product_type', $types);
    }

    public function get_product_ids_from_post_ids($post_ids)
    {   
        $result = [];

        foreach ($post_ids as $post_id) {
            $result[] = get_post_meta($post_id, 'product_id', true);
        }

        $result = array_filter($result);

        if (empty($result)) {
            return false;
        }

        return $result;

    }

    public function get_product_ids_from_description($description)
    {
        return $this->select_like_col(
            'product_id',
            'body_html',
            $description,
            $this->table_name
        );
    }

    public function get_products()
    {
        return $this->get_all_rows();
    }

    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			product_id longtext NOT NULL DEFAULT '{$this->default_product_id}',
			post_id bigint(100) unsigned DEFAULT '{$this->default_post_id}',
			title varchar(255) DEFAULT '{$this->default_title}',
			body_html longtext DEFAULT '{$this->default_body_html}',
			handle varchar(255) DEFAULT '{$this->default_handle}',
			post_name varchar(255) DEFAULT '{$this->default_post_name}',
			image longtext DEFAULT '{$this->default_image}',
			images longtext DEFAULT '{$this->default_images}',
			vendor varchar(255) DEFAULT '{$this->default_vendor}',
			product_type varchar(255) DEFAULT '{$this->default_product_type}',
			published_scope varchar(100) DEFAULT '{$this->default_published_scope}',
			published_at varchar(255) DEFAULT '{$this->default_published_at}',
			updated_at varchar(255) DEFAULT '{$this->default_updated_at}',
			created_at varchar(255) DEFAULT '{$this->default_created_at}',
			admin_graphql_api_id longtext DEFAULT '{$this->default_admin_graphql_api_id}',
            metafields_global_title_tag longtext DEFAULT '{$this->default_metafields_global_title_tag}',
            metafields_global_description_tag longtext DEFAULT '{$this->default_metafields_global_description_tag}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}

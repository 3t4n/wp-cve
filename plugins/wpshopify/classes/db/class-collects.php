<?php

namespace ShopWP\DB;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Collects extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_collect_id;
    public $default_product_id;
    public $default_collection_id;
    public $default_featured; // deprecated in 2020-1 Admin REST API version
    public $default_position;
    public $default_sort_value;
    public $default_created_at;
    public $default_updated_at;

    public function __construct()
    {
        // Table info
        $this->table_name_suffix = SHOPWP_TABLE_NAME_COLLECTS;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'collect_id';
        $this->cache_group = 'wps_db_collects';
        $this->type = 'collect';

        // Defaults
        $this->default_collect_id = '';
        $this->default_product_id = '';
        $this->default_collection_id = '';
        $this->default_featured = 0;
        $this->default_position = 0;
        $this->default_sort_value = 0;
        $this->default_created_at = '';
        $this->default_updated_at = '';
    }

    /*

	Table column name / formats

	Important: Used to determine when new columns are added

	*/
    public function get_columns()
    {
        return [
            'id' => '%d',
            'collect_id' => '%s',
            'product_id' => '%s',
            'collection_id' => '%s',
            'featured' => '%d',
            'position' => '%d',
            'sort_value' => '%d',
            'created_at' => '%s',
            'updated_at' => '%s'
        ];
    }

    /*

  Columns that should remain integers during casting.
  We need to check against this when retrieving data since MYSQL 
  converts all cols to strings upon retrieval. 

  */
    public function cols_that_should_remain_ints()
    {
        return [
            'id',
            'position',
            'sort_value'
        ];
    }

    /*

	Table default values

	*/
    public function get_column_defaults()
    {
        return [
            'collect_id' => $this->default_collect_id,
            'product_id' => $this->default_product_id,
            'collection_id' => $this->default_collection_id,
            'featured' => $this->default_featured,
            'position' => $this->default_position,
            'sort_value' => $this->default_sort_value,
            'created_at' => $this->default_created_at,
            'updated_at' => $this->default_updated_at
        ];
    }

    /*

	The modify options used for inserting / updating / deleting

	*/
    public function modify_options(
        $shopify_item,
        $item_lookup_key = SHOPWP_PRODUCTS_LOOKUP_KEY
    ) {
        return [
            'item' => $shopify_item,
            'item_lookup_key' => $item_lookup_key,
            'item_lookup_value' => $shopify_item->id,
            'prop_to_access' => 'collects',
            'change_type' => 'collect'
        ];
    }

    /*

	Mod before change

	*/
    public function mod_before_change($collect)
    {
        $collect_copy = $this->copy($collect);
        $collect_copy = $this->maybe_rename_to_lookup_key($collect_copy);

        return $collect_copy;
    }

    /*

	Insert single collect

	*/
    public function insert_collect($collect)
    {
        return $this->insert($collect);
    }

    /*

	Updates a single collect

	*/
    public function update_collect($collect)
    {
        return $this->update(
            $this->lookup_key,
            $this->get_lookup_value($collect),
            $collect
        );
    }

    /*

	Deletes a single image

	The two params to delete_rows must match

	*/
    public function delete_collect($collect)
    {
        return $this->delete_rows(
            $this->lookup_key,
            $this->get_lookup_value($collect)
        );
    }

    /*

	Delete collects by collection ID

	*/
    public function delete_collects_from_collection_id($collection_id)
    {
        return $this->delete_rows(
            SHOPWP_COLLECTIONS_LOOKUP_KEY,
            $collection_id
        );
    }

    public function get_collects_from_collection_id($collection_id)
    {
        return $this->get_rows(
            SHOPWP_COLLECTIONS_LOOKUP_KEY,
            $collection_id
        );
    }

    public function get_collects_from_product_id($product_id) {
		return $this->get_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
	}

    public function get_published_collects($collects, $published_product_ids)
    {
        return array_filter($collects, function ($collect) use (
            $published_product_ids
        ) {
            return $this->is_collect_published(
                $collect,
                $published_product_ids
            );
        });
    }

    public function is_collect_published($collect, $published_product_ids)
    {
        if (!isset($collect->product_id)) {
            return false;
        }

        if (in_array($collect->product_id, $published_product_ids)) {
            return true;
        }

        return false;
    }

    // Need to do a comparison as some collects could have been found within
    // the batch of 250. So we just need to find the difference and then add that number.
    public function find_published_difference_to_add($items, $published_items)
    {
        $original_total = count($items);
        $after_filter_total = count($published_items);

        return $original_total - $after_filter_total;
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
			collect_id varchar(255) DEFAULT '{$this->default_collect_id}',
			product_id varchar(255) DEFAULT '{$this->default_product_id}',
			collection_id varchar(255) DEFAULT '{$this->default_collection_id}',
			featured tinyint(1) DEFAULT '{$this->default_featured}',
			position int(20) DEFAULT '{$this->default_position}',
			sort_value int(20) DEFAULT '{$this->default_sort_value}',
			created_at varchar(255) DEFAULT '{$this->default_created_at}',
			updated_at varchar(255) DEFAULT '{$this->default_updated_at}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}

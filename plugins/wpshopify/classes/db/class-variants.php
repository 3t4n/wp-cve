<?php

namespace ShopWP\DB;

use ShopWP\Utils;
use ShopWP\Utils\Sorting as Utils_Sorting;
use ShopWP\Utils\Filtering as Utils_Filtering;
use ShopWP\Utils\Pricing;

if (!defined('ABSPATH')) {
    exit();
}

class Variants extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_variant_id;
    public $default_product_id;
    public $default_image_id;
    public $default_title;
    public $default_price;
    public $default_compare_at_price;
    public $default_position;
    public $default_option1;
    public $default_option2;
    public $default_option3;
    public $default_option_values;
    public $default_taxable;
    public $default_weight;
    public $default_weight_unit;
    public $default_sku;
    public $default_inventory_policy;
    public $default_inventory_quantity;
    public $default_old_inventory_quantity;
    public $default_inventory_management;
    public $default_requires_shipping;
    public $default_fulfillment_service;
    public $default_barcode;
    public $default_created_at;
    public $default_updated_at;
    public $default_admin_graphql_api_id;

    public function __construct()
    {
        $this->table_name_suffix = SHOPWP_TABLE_NAME_VARIANTS;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'variant_id';
        $this->cache_group = 'wps_db_variants';
        $this->type = 'variant';

        $this->default_variant_id = '';
        $this->default_product_id = '';
        $this->default_image_id = '';
        $this->default_title = '';
        $this->default_price = 0;
        $this->default_compare_at_price = 0;
        $this->default_position = 0;
        $this->default_option1 = '';
        $this->default_option2 = '';
        $this->default_option3 = '';
        $this->default_option_values = '';
        $this->default_taxable = 0;
        $this->default_weight = 0.0;
        $this->default_weight_unit = '';
        $this->default_sku = '';
        $this->default_inventory_policy = '';
        $this->default_inventory_quantity = 0;
        $this->default_old_inventory_quantity = 0;
        $this->default_inventory_management = '';
        $this->default_requires_shipping = 0;
        $this->default_fulfillment_service = '';
        $this->default_barcode = '';
        $this->default_created_at = '';
        $this->default_updated_at = '';
        $this->default_admin_graphql_api_id = '';
    }

    public function get_columns()
    {
        return [
            'id' => '%d',
            'variant_id' => '%s',
            'product_id' => '%s',
            'image_id' => '%s',
            'title' => '%s',
            'price' => '%f',
            'compare_at_price' => '%f',
            'position' => '%d',
            'option1' => '%s',
            'option2' => '%s',
            'option3' => '%s',
            'option_values' => '%s',
            'taxable' => '%d',
            'weight' => '%f',
            'weight_unit' => '%s',
            'sku' => '%s',
            'inventory_policy' => '%s',
            'inventory_quantity' => '%d',
            'old_inventory_quantity' => '%d',
            'inventory_management' => '%s',
            'requires_shipping' => '%d',
            'fulfillment_service' => '%s',
            'barcode' => '%s',
            'created_at' => '%s',
            'updated_at' => '%s',
            'admin_graphql_api_id' => '%s',
        ];
    }

    public function cols_that_should_remain_ints()
    {
        return [
            'id',
            'price',
            'compare_at_price',
            'position',
            'taxable',
            'inventory_quantity',
            'old_inventory_quantity',
            'weight',
            'requires_shipping',
        ];
    }

    public function get_column_defaults()
    {
        return [
            'variant_id' => $this->default_variant_id,
            'product_id' => $this->default_product_id,
            'image_id' => $this->default_image_id,
            'title' => $this->default_title,
            'price' => $this->default_price,
            'compare_at_price' => $this->default_compare_at_price,
            'position' => $this->default_position,
            'option1' => $this->default_option1,
            'option2' => $this->default_option2,
            'option3' => $this->default_option3,
            'option_values' => $this->default_option_values,
            'taxable' => $this->default_taxable,
            'weight' => $this->default_weight,
            'weight_unit' => $this->default_weight_unit,
            'sku' => $this->default_sku,
            'inventory_policy' => $this->default_inventory_policy,
            'inventory_quantity' => $this->default_inventory_quantity,
            'old_inventory_quantity' => $this->default_old_inventory_quantity,
            'inventory_management' => $this->default_inventory_management,
            'requires_shipping' => $this->default_requires_shipping,
            'fulfillment_service' => $this->default_fulfillment_service,
            'barcode' => $this->default_barcode,
            'created_at' => $this->default_created_at,
            'updated_at' => $this->default_updated_at,
            'admin_graphql_api_id' => $this->default_admin_graphql_api_id,
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
            'prop_to_access' => 'variants',
            'change_type' => 'variant',
        ];
    }

    public function mod_before_change($variant)
    {
        $variant_copy = $this->copy($variant);
        $variant_copy = $this->maybe_rename_to_lookup_key($variant_copy);

        return $variant_copy;
    }

    public function insert_variant($variant)
    {
        return $this->insert($variant);
    }

    public function update_variant($variant)
    {
        return $this->update(
            $this->lookup_key,
            $this->get_lookup_value($variant),
            $variant
        );
    }

    public function delete_variant($variant)
    {
        return $this->delete_rows(
            $this->lookup_key,
            $this->get_lookup_value($variant)
        );
    }

    public function delete_variants_from_product_id($product_id)
    {
        return $this->delete_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    public function get_variants_from_product_id($product_id)
    {
        return $this->get_rows(SHOPWP_PRODUCTS_LOOKUP_KEY, $product_id);
    }


    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			variant_id longtext NOT NULL DEFAULT '{$this->default_variant_id}',
			product_id longtext DEFAULT '{$this->default_product_id}',
			image_id longtext DEFAULT '{$this->default_image_id}',
			title longtext DEFAULT '{$this->default_title}',
			price decimal(12,2) DEFAULT '{$this->default_price}',
			compare_at_price decimal(12,2) DEFAULT '{$this->default_compare_at_price}',
			position int(20) DEFAULT '{$this->default_position}',
			option1 longtext DEFAULT '{$this->default_option1}',
			option2 longtext DEFAULT '{$this->default_option2}',
			option3 longtext DEFAULT '{$this->default_option3}',
			option_values longtext DEFAULT '{$this->default_option_values}',
			taxable tinyint(1) DEFAULT '{$this->default_taxable}',
			sku longtext DEFAULT '{$this->default_sku}',
			inventory_policy varchar(255) DEFAULT '{$this->default_inventory_policy}',
			inventory_quantity bigint(20) DEFAULT '{$this->default_inventory_quantity}',
			old_inventory_quantity bigint(20) DEFAULT '{$this->default_old_inventory_quantity}',
			inventory_management varchar(255) DEFAULT '{$this->default_inventory_management}',
			fulfillment_service varchar(255) DEFAULT '{$this->default_fulfillment_service}',
			barcode varchar(255) DEFAULT '{$this->default_barcode}',
			weight decimal(20,4) DEFAULT '{$this->default_weight}',
			weight_unit varchar(100) DEFAULT '{$this->default_weight_unit}',
			requires_shipping tinyint(1) DEFAULT '{$this->default_requires_shipping}',
			created_at varchar(255) DEFAULT '{$this->default_created_at}',
			updated_at varchar(255) DEFAULT '{$this->default_updated_at}',
			admin_graphql_api_id longtext DEFAULT '{$this->default_admin_graphql_api_id}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}

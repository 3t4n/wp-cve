<?php

namespace ShopWP\DB;

use ShopWP\Utils;
use ShopWP\CPT;
use ShopWP\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class Collections extends \ShopWP\DB
{
    private $DB_Collects;
    private $CPT_Model;

    private $DB_Collections_Smart;
    private $DB_Collections_Custom;

    public $lookup_key;
    public $type;

    public function __construct(
        $DB_Collects,
        $CPT_Model,
        $DB_Collections_Smart,
        $DB_Collections_Custom
    ) {
        $this->DB_Collects = $DB_Collects;
        $this->CPT_Model = $CPT_Model;

        $this->DB_Collections_Smart = $DB_Collections_Smart;
        $this->DB_Collections_Custom = $DB_Collections_Custom;

        $this->lookup_key = SHOPWP_COLLECTIONS_LOOKUP_KEY;
        $this->type = 'collection';
    }

    public function mod_before_change($collection, $post_id = false)
    {
        $collection_copy = $this->copy($collection);

        $collection_copy = $this->maybe_rename_to_lookup_key($collection_copy);
        $collection_copy = Utils::flatten_image_prop($collection_copy);

        if ($post_id) {
            $collection_copy = CPT::set_post_id($collection_copy, $post_id);
        }

        // Important. If handle doesn't match post_name, the product won't show
        $collection_copy->post_name = sanitize_title($collection_copy->handle);

        return $collection_copy;
    }

    public function insert_collection($collection)
    {
        if ($this->is_smart_collection($collection)) {
            return $this->DB_Collections_Smart->insert($collection);
        } else {
            return $this->DB_Collections_Custom->insert($collection);
        }
    }

    public function update_collection($collection)
    {
        if ($this->is_smart_collection($collection)) {
            return $this->DB_Collections_Smart->update(
                $this->DB_Collections_Smart->lookup_key,
                $this->DB_Collections_Smart->get_lookup_value($collection),
                $collection
            );
        } else {
            return $this->DB_Collections_Custom->update(
                $this->DB_Collections_Custom->lookup_key,
                $this->DB_Collections_Custom->get_lookup_value($collection),
                $collection
            );
        }
    }

    public function delete_collection($collection)
    {
        if ($this->is_smart_collection($collection)) {
            return $this->DB_Collections_Smart->delete_rows(
                $this->DB_Collections_Smart->lookup_key,
                $this->DB_Collections_Smart->get_lookup_value($collection)
            );
        } else {
            return $this->DB_Collections_Custom->delete_rows(
                $this->DB_Collections_Custom->lookup_key,
                $this->DB_Collections_Custom->get_lookup_value($collection)
            );
        }
    }

    public function has_collection($maybe_collection)
    {
        if (
            is_object($maybe_collection[0]) &&
            property_exists($maybe_collection[0], 'collection_id')
        ) {
            return true;
        }

        return false;
    }

    public function get_post_id_by_collection_id($collection_id)
    {
        return \get_posts([
            'post_type' => 'wps_collections',
            'posts_per_page' => 1,
            'meta_key' => 'collection_id',
            'meta_value' => $collection_id,
            'fields' => 'ids'
        ]);
    }

    public function is_smart_collection($collection)
    {
        return Utils::has($collection, 'rules') ? true : false;
    }

    public function get_post_id_from_collection($collection)
    {
        $args = array(
            'post_type' => 'wps_products',
            'meta_query' => array(
                array(
                    'key' => 'collection_id',
                    'value' => $collection->id,
                    'compare' => '=',
                )
            )
        );
        
        $collection_found = \get_posts($args);

        if (empty($collection_found) || \is_wp_error($collection_found)) {
            return false;
        }

        return $collection_found[0]->ID;
    }

}

<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class CPT_Model
{
 
    public function set_collection_model_defaults($collection)
    {
        $collection = Utils::convert_array_to_object($collection);

        return [
            'post_title' => property_exists($collection, 'title')
                ? $collection->title
                : '',
            'post_status' => 'publish',
            'post_date' => property_exists($collection, 'published_at') ? $collection->published_at : '',
            'post_author' => CPT::return_author_id(),
            'post_type' => SHOPWP_COLLECTIONS_POST_TYPE_SLUG,
            'post_name' => property_exists($collection, 'handle')
                ? sanitize_title($collection->handle)
                : false,
            'meta_input' => [
                'collection_id' => property_exists($collection, 'id')
                    ? $collection->id
                    : '',
            ],
        ];
    }

    public function return_post_name($product) {
        return property_exists($product, 'handle') ? $product->handle : sanitize_title($product->title);
    }

    public function return_post_title($product) {
        return property_exists($product, 'title') ? $product->title : '';
    }

    public function return_post_description($product) {
        return property_exists($product, 'descriptionHtml') ? $product->descriptionHtml : '';
    }

    public function return_product_seo_title($product) {
        return property_exists($product, 'seo') ? $product->seo->title : '';
    }

    public function return_product_seo_description($product) {
        return property_exists($product, 'seo') ? $product->seo->description : '';
    }

    public function set_product_model_defaults($product, $product_id, $post_id)
    {

        $product = Utils::convert_array_to_object($product);

        $info = [
            'post_title' => $this->return_post_title($product),
            'post_content' => $this->return_post_description($product),
            'post_name' => $this->return_post_name($product),
            'post_status' => 'publish',
            'post_type' => SHOPWP_PRODUCTS_POST_TYPE_SLUG,
        ];

        if ($product_id) {
            $info['meta_input'] = [ 
                'product_id' => $product_id,
                'product_seo_title' => $this->return_product_seo_title($product),
                'product_seo_description' => $this->return_product_seo_description($product)
            ];
        }

        return $info;
    }

    public function build_collections_model_for_update($collection, $product_id, $post_id)
    {

        $collection_model = $this->set_collection_model_defaults($collection);
        
        $collection_model = CPT::set_post_id_if_exists(
            $collection_model,
            $post_id
        );

        return $collection_model;
    }

    public function set_existing_product_categories($post_id, $product_model)
    {
        $categories = get_the_category($post_id);
        $cat_ids = [];

        if (empty($categories)) {
            return $product_model;
        }

        foreach ($categories as $category) {
            array_push($cat_ids, $category->cat_ID);
        }

        $product_model['post_category'] = $cat_ids;

        return $product_model;
    }

    public function set_existing_product_excerpt($post_id, $product_model)
    {
        if (!has_excerpt($post_id)) {
            return $product_model;
        }

        $product_model['post_excerpt'] = get_the_excerpt($post_id);

        return $product_model;
    }

    public function build_products_model_for_update($product, $product_id, $post_id = false)
    {
        $product_model = $this->set_product_model_defaults($product, $product_id, $post_id);

        $product_model = CPT::set_post_id_if_exists($product_model, $post_id);

        $product_model = $this->set_existing_product_categories(
            $post_id,
            $product_model
        );

        $product_model = $this->set_existing_product_excerpt(
            $post_id,
            $product_model
        );

        return $product_model;
    }

    public function insert_or_update_product_post($product, $product_id, $post_id = false)
    { 

        $model = $this->build_products_model_for_update($product, $product_id, $post_id);

        return wp_insert_post($model, true);

    }

    public function insert_or_update_collection_post(
        $collection,
        $product_id,
        $post_id = false
    ) {
        $model = $this->build_collections_model_for_update(
            $collection,
            $product_id,
            $post_id
        );
        return wp_insert_post($model, true);
    }
}

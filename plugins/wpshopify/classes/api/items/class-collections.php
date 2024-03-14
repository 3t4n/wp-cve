<?php

namespace ShopWP\API\Items;

use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Utils\Sorting;
use ShopWP\Transients;
use ShopWP\Options;
use ShopWP\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class Collections extends \ShopWP\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $DB_Settings_Connection,
        $DB_Collects,
        $Shopify_API,
        $Processing_Collections_Custom,
        $Processing_Collections_Smart,
        $Processing_Images,
        $Processing_Collections_Smart_Collects,
        $Admin_API_Metafields,
        $Storefront_Collections
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Settings_Connection = $DB_Settings_Connection;
        $this->DB_Collects = $DB_Collects;
        $this->Shopify_API = $Shopify_API;

        $this->Processing_Collections_Smart = $Processing_Collections_Smart;
        $this->Processing_Collections_Custom = $Processing_Collections_Custom;

        $this->Processing_Images = $Processing_Images;
        $this->Processing_Collections_Smart_Collects = $Processing_Collections_Smart_Collects;
        $this->Admin_API_Metafields = $Admin_API_Metafields;
        $this->Storefront_Collections = $Storefront_Collections;
    }

    /*

	Get Smart Collections Count

	Nonce checks are handled automatically by WordPress

	*/
    public function handle_get_smart_collections_count($request)
    {
        $response = $this->Shopify_API->api_get_smart_collections_count();

        return $this->handle_response([
            'response' => $this->Shopify_API->pre_response_check($response),
            'access_prop' => 'count',
            'return_key' => 'smart_collections',
            'warning_message' => 'smart_collections_count_not_found',
        ]);
    }

    public function custom_collections_meta_info()
    {
        return [
            'post_type' => SHOPWP_COLLECTIONS_POST_TYPE_SLUG,
            'increment_name' => 'custom_collections',
        ];
    }


    public function process_custom_collections($custom_collections) {
        return $this->handle_response([
            'response' => $custom_collections,
            'access_prop' => 'custom_collections',
            'return_key' => 'custom_collections',
            'warning_message' => 'custom_collections_count_not_found',
            'meta' => $this->custom_collections_meta_info(),
            'process_fns' => [
                $this->Processing_Collections_Custom,
            ],
        ]);
    }

    public function process_smart_collections($smart_collections) {
        return $this->handle_response([
            'response' => $smart_collections,
            'access_prop' => 'smart_collections',
            'return_key' => 'smart_collections',
            'warning_message' => 'smart_collections_count_not_found',
            'meta' => $this->Shopify_API->smart_collections_meta_info(),
            'process_fns' => [
                $this->Processing_Collections_Smart,
            ],
        ]);
    }
    

    /*

	Get Custom Collections

	Nonce checks are handled automatically by WordPress

	*/
    public function handle_get_custom_collections($request)
    {

        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return [];
        }

        $limit = $this->DB_Settings_General->get_items_per_request();

        // Grab smart collections from Shopify
        $custom_collections = $this->get_custom_collections_paginated(false, $limit, []);

        if (is_wp_error($custom_collections)) {
            return $this->handle_response(['response' => $custom_collections]);
        }

        $s_coll = new \stdClass();
        $s_coll->custom_collections = $custom_collections;
        
        return $this->process_custom_collections($s_coll);

    }


   public function get_custom_collections_paginated($page_link = false, $limit = 250, $combined_custom_collections = []) {

        $response = $this->Shopify_API->get_custom_collections_per_page(
            $page_link,
            $limit
        );

        if (is_wp_error($response)) {
            return $response;
        }

        // No additional pages left
        if (!$response) {
            return $combined_custom_collections;
        }

        $response_body = $this->Shopify_API->sanitize_response(
            $response['body']
        );

        $new_custom_collections = $response_body->custom_collections;
        $new_custom_collections_count = count($new_custom_collections);

        $new_custom_collections = CPT::add_props_to_items(
            $new_custom_collections,
            $this->custom_collections_meta_info()
        );

        // Save the result in memory
        $combined_custom_collections = array_merge(
            $combined_custom_collections,
            $new_custom_collections
        );

        if (!$this->Shopify_API->has_pagination($response)) {
            return $combined_custom_collections;
        }

        $page_link = $this->Shopify_API->get_pagination_link($response);

        if (empty($page_link)) {
            return $combined_custom_collections;
        }        

        return $this->get_custom_collections_paginated(
            $page_link,
            $limit,
            $combined_custom_collections
        );
    }




    


    /*

	Get smart collections

	Nonce checks are handled automatically by WordPress

	*/
    public function handle_get_smart_collections($request)
    {

        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return [];
        }

        $limit = $this->DB_Settings_General->get_items_per_request();

        // Grab smart collections from Shopify
        $smart_collections = $this->Shopify_API->get_smart_collections_paginated(false, $limit, []);

        if (is_wp_error($smart_collections)) {
            return $this->handle_response(['response' => $smart_collections]);
        }

        $collections_to_processes = new \stdClass();
        $collections_to_processes->smart_collections = $smart_collections;

        return $this->process_smart_collections($collections_to_processes);

    }

    /*

	Get Custom Collections Count

	Nonce checks are handled automatically by WordPress

	*/
    public function handle_get_custom_collections_count($request)
    {
        // Get custom collections count
        $response = $this->Shopify_API->api_get_custom_collections_count();

        return $this->handle_response([
            'response' => $this->Shopify_API->pre_response_check($response),
            'access_prop' => 'count',
            'return_key' => 'custom_collections',
            'warning_message' => 'custom_collections_count_not_found',
        ]);
    }

    public function cache_metafields($new_metafield_data) {
        
        $active_metafields = Options::get('shopwp_active_metafields');

        if (!empty($active_metafields)) {
            $active_metafields = maybe_unserialize($active_metafields);

        } else {
            $active_metafields = [];
        }

        $active_metafields[] = $new_metafield_data;

        return Options::update('shopwp_active_metafields', maybe_serialize($active_metafields));
    }

    public function metafields_compare($a, $b) {

        if ($a['id'] === $b['id']) {
            return 0;

        } else {
            return -1;
        }
         
    }

    public function uncache_metafields($metafield_id) {
        
        $active_metafields = Options::get('shopwp_active_metafields');

        if (!empty($active_metafields)) {
            $active_metafields = maybe_unserialize($active_metafields);

        } else {
            return false;
        }

        $new_metafields = array_filter($active_metafields, function($metafield) use($metafield_id) {
            return $metafield['id'] === $metafield_id;
        });

        $new_stuff = array_udiff($active_metafields, $new_metafields, [$this, 'metafields_compare']);

        return Options::update('shopwp_active_metafields', maybe_serialize($new_stuff));
    }

    public function handle_show_metafield($request) {

        $key = $request->get_param('key');
        $namespace = $request->get_param('namespace');

        $response = $this->Admin_API_Metafields->api_show_metafield($key, $namespace);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        $metafield_id = $response->id;

        $new_metafield_data = [
            'id' => $metafield_id,
            'key' => $key,
            'namespace' => $namespace
        ];

        $saved_metafields_result = $this->cache_metafields($new_metafield_data);
            
        return wp_send_json_success($metafield_id);
    }

    public function handle_hide_metafield($request) {

        $metafield_id = $request->get_param('id');
        
        $hide_result = $this->Admin_API_Metafields->api_hide_metafield($metafield_id);
        
        $this->uncache_metafields($metafield_id);
            
        return $hide_result;
    }

     public function handle_get_collections($request) {

        $query_params = $request->get_param('queryParams');
        $with_products = $request->get_param('withProducts');
        $query_params_products = $request->get_param('queryParamsProducts');

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $hash = Utils::hash($query_params, true);
            
            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $hash));

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }

        $result = $this->Storefront_Collections->api_get_collections($query_params, false, $with_products, $query_params_products);

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }

        return \wp_send_json_success($result);

    }

    /*

	Gets all collections

	*/
    public function handle_get_all_collections($request)
    {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $all_collections = Transients::get('shopwp_all_collections');

            if (!empty($all_collections)) {
                return $this->handle_response([
                    'response' => $all_collections,
                ]);
            }
        }

        $limit = $this->DB_Settings_General->get_items_per_request();
        $has_connection = $this->DB_Settings_Connection->has_connection();

        if (!$has_connection) {
            return wp_send_json_error(Messages::get('connection_not_found') . ' (handle_get_all_collections)');
        }

        $smart_collections_response = $this->Shopify_API->get_smart_collections_paginated(false, $limit, []);

        if (is_wp_error($smart_collections_response)) {
            return $this->handle_response([
                'response' => $smart_collections_response,
            ]);
        }

        $custom_collections_response = $this->get_custom_collections_paginated(false, $limit, []);

        if (is_wp_error($custom_collections_response)) {
            return $this->handle_response([
                'response' => $custom_collections_response,
            ]);
        }

        $smart_collections = $smart_collections_response;
        $custom_collections = $custom_collections_response;

        if (Utils::has($smart_collections, 'errors')) {
            return $this->handle_response([
                'response' => $smart_collections,
            ]);
        }

        if (Utils::has($custom_collections, 'errors')) {
            return $this->handle_response([
                'response' => $custom_collections,
            ]);
        }

        $collections_merged = array_merge(
            $smart_collections,
            $custom_collections
        );

        if (!empty($collections_merged)) {

            $collections_merged_final_reduced = array_map(function (
                $collection
            ) {
                $new_collection_obj = new \stdClass();
                $new_collection_obj->id = $collection->id;
                $new_collection_obj->title = $collection->title;
                $new_collection_obj->handle = $collection->handle;

               if (!empty($collection->rules)) {
                  $new_collection_obj->rules = true;
               }

                return $new_collection_obj;
            },
            $collections_merged);
            
            usort($collections_merged_final_reduced, function ($a, $b) {
               return strcmp($a->title, $b->title);
            });

            $serialized_collections = maybe_serialize($collections_merged_final_reduced);

            if ($cached_enabled) {
                Transients::set('shopwp_all_collections', $serialized_collections, $data_cache_length);
            }

            return $collections_merged_final_reduced;

        }
    }

    public function register_routes() {
        $this->api_route('/metafields/hide', 'POST', [$this, 'handle_hide_metafield']);
        $this->api_route('/metafields/show', 'POST', [$this, 'handle_show_metafield']);
        $this->api_route('/query/collections', 'POST', [$this, 'handle_get_collections']);
        $this->api_route('/collections', 'POST', [$this, 'handle_get_all_collections']);
        $this->api_route('/custom_collections', 'POST', [$this, 'handle_get_custom_collections']);
        $this->api_route('/smart_collections', 'POST', [$this, 'handle_get_smart_collections']);
        $this->api_route('/custom_collections/count', 'POST', [$this, 'handle_get_custom_collections_count']);
        $this->api_route('/smart_collections/count', 'POST', [$this, 'handle_get_smart_collections_count']);
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
}

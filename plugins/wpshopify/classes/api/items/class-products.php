<?php

namespace ShopWP\API\Items;

use ShopWP\Transients;
use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;
use ShopWP\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class Products extends \ShopWP\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $DB_Tags,
        $DB_Products,
        $Shopify_API,
        $Processing_Products,
        $Processing_Variants,
        $Processing_Tags,
        $Processing_Options,
        $Processing_Images,
        $Admin_API_Variants,
        $API_Counts,
        $plugin_settings,
        $Processing_Database,
        $API_Syncing_Status,
        $Admin_API_Shop,
        $Storefront_Products,
        $API_Items_Collections
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Tags = $DB_Tags;
        $this->DB_Products = $DB_Products;

        $this->Shopify_API = $Shopify_API;

        $this->Processing_Products = $Processing_Products;
        $this->Processing_Variants = $Processing_Variants;
        $this->Processing_Tags = $Processing_Tags;
        $this->Processing_Options = $Processing_Options;
        $this->Processing_Images = $Processing_Images;

        $this->Admin_API_Variants = $Admin_API_Variants;
        $this->API_Counts = $API_Counts;
        $this->plugin_settings = $plugin_settings;
        $this->Processing_Database = $Processing_Database;
        $this->API_Syncing_Status = $API_Syncing_Status;

        $this->Admin_API_Shop = $Admin_API_Shop;
        
        $this->Storefront_Products = $Storefront_Products;

        $this->API_Items_Collections = $API_Items_Collections;
    }


    public function construct_streaming_options($count) {

        $count = (int) $count;
        $items_per_request = (int) $this->plugin_settings['general']['items_per_request'];
        $total_pages = ceil($count / $items_per_request);

        return [
            'current_page' => 1,
            'pages' => $total_pages
        ];

      }

    public function stream_collections($counts) {

        $final = [];

        if (!empty($counts['custom_collections'])) {
            $final['custom_collections'] = $this->API_Items_Collections->get_custom_collections_paginated();
        }

        if (!empty($counts['smart_collections'])) {
            $final['smart_collections'] = $this->Shopify_API->get_smart_collections_paginated();
        }

        return $final;
        
    }

    public function stream_products($counts, $sync_by_collections, $is_syncing_collections) {

        $custom_schema = '
            id
            title
            handle
            descriptionHtml
            seo {
                title
                description
            }
        ';
                
        // Syncing by collections
        if (!empty($sync_by_collections)) {

            $collection_ids = $this->format_collection_ids($sync_by_collections);

            $query_params = [
                'first' => $this->DB_Settings_General->get_items_per_request(),
                'language' => strtoupper($this->DB_Settings_General->get_col_val('language_code', 'string')),
                'country' => strtoupper($this->DB_Settings_General->get_col_val('country_code', 'string'))
            ];

            $products_to_process = $this->get_all_products_from_collection_ids($collection_ids, $query_params, $custom_schema);

        // Syncing all products
        } else {

            $query_params = [
                'query' => '*',
                'first' => $this->DB_Settings_General->get_items_per_request(),
                'language' => strtoupper($this->DB_Settings_General->get_col_val('language_code', 'string')),
                'country' => strtoupper($this->DB_Settings_General->get_col_val('country_code', 'string'))
            ];

            $products_to_process = $this->get_all_products($query_params, $custom_schema);

        }

        // Stop syncing if no products are found and NOT syncing collections
        if (empty($products_to_process) && !$is_syncing_collections) {
            $this->DB_Settings_Syncing->set_current_syncing_step_text('Finalizing ...');
            $this->DB_Settings_Syncing->toggle_syncing(0);
            $this->DB_Settings_Syncing->expire_sync();
            $this->DB_Settings_Syncing->server_error('No products found to sync!', __METHOD__, __LINE__);
            return false;
        }
        
        return $this->handle_response([
            'response' => $products_to_process,
            'warning_message' => 'missing_products_for_page',
            'process_fns' => [
                $this->Processing_Products
            ]
        ]);
        
    }

    public function handle_remove_synced_data($request) {

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Removing previously synced data ...');
        
        // Kick off the async data deletion 
        $this->Processing_Database->delete_only_synced_data();

        return true;

    }

    public function handle_expire_sync($request) {

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Finalizing ...');
        $this->DB_Settings_Syncing->toggle_syncing(0);
        
        return $this->DB_Settings_Syncing->expire_sync();
    }

    public function get_total_collections_count() {

        $is_syncing_collections = $this->DB_Settings_General->get_selective_sync_collections_status();

        if (empty($is_syncing_collections)) {
            return false;
        }
        
        $custom_count = $this->Shopify_API->api_get_custom_collections_count();

        if (is_wp_error($custom_count)) {
            $this->DB_Settings_Syncing->server_error($custom_count->get_error_message(), __METHOD__, __LINE__);
            die();
        }

        $smart_count = $this->Shopify_API->api_get_smart_collections_count();

        if (is_wp_error($smart_count)) {
            $this->DB_Settings_Syncing->server_error($smart_count->get_error_message(), __METHOD__, __LINE__);
            die();
        }

        return [
            'smart_collections' => $smart_count['body']->count,
            'custom_collections' => $custom_count['body']->count
        ];

    }

    /*

	Sync detail pages

	*/
    public function handle_sync_product_detail_pages($request)
    {

        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->DB_Settings_Syncing->expire_sync();
            return;
        }
        
        $selective_sync = $this->DB_Settings_General->selective_sync_status();
        $is_syncing_by_collections = $this->DB_Settings_General->sync_by_collections();

        $sync_by_collections = maybe_unserialize($is_syncing_by_collections);

        $is_syncing_collections = $selective_sync['smart_collections'] && $selective_sync['custom_collections'];

        $total_products_count = [];
        $total_collections_count = [];

        // If syncing collections ...
        if ($is_syncing_collections) {

            // If syncing by collections
            if (!empty($sync_by_collections)) {

                $smart_collections = [];
                $custom_collections = [];

                foreach ($sync_by_collections as $collection) {
                    if (empty($collection['smart'])) {
                        $custom_collections['custom_collections'][] = $collection;
                    } else {
                        $smart_collections['smart_collections'][] = $collection;
                    }
                }

                if (!empty($custom_collections['custom_collections'])) {
                    $total_collections_count['custom_collections'] = count($custom_collections['custom_collections']);
                }

                if (!empty($smart_collections['smart_collections'])) {
                    $total_collections_count['smart_collections'] = count($smart_collections['smart_collections']);
                }

                $this->API_Counts->set_syncing_counts($total_collections_count, ['webhooks', 'media']);

                if (!empty($custom_collections)) {
                    $this->API_Items_Collections->process_custom_collections($custom_collections);
                }

                if (!empty($smart_collections)) {
                    $this->API_Items_Collections->process_smart_collections($smart_collections);
                }

            } else {

                $this->DB_Settings_Syncing->set_current_syncing_step_text('Getting collections count ...');

                $total_collections_count = $this->get_total_collections_count();
                
                if (!empty($total_collections_count)) {
                    
                    $save_collections_count_result = $this->API_Counts->set_syncing_counts($total_collections_count, ['webhooks', 'media']);

                    if (is_wp_error($save_collections_count_result)) {
                        die();
                    }
                }
            }            
        }


        if ($selective_sync['products']) {
            $this->DB_Settings_Syncing->set_current_syncing_step_text('Getting products count ...');
            $total_products_count = $this->get_products_count();
        }

        if (empty($total_collections_count) && empty($total_products_count)) {
            $this->DB_Settings_Syncing->expire_sync();
            return wp_send_json_success();
        }


        if ($selective_sync['products']) {

            if (!empty($total_products_count)) {

                if (is_wp_error($total_products_count)) {
                    $this->DB_Settings_Syncing->server_error($total_products_count->get_error_message(), __METHOD__, __LINE__);
                    die();
                }

                if (!empty($total_collections_count)) {
                    $final_count = array_merge($total_products_count, $total_collections_count);

                } else {
                    $final_count = $total_products_count;
                }

                $save_result = $this->API_Counts->set_syncing_counts($final_count, ['webhooks', 'media']);

                if (is_wp_error($save_result)) {
                    die();
                }

                $this->DB_Settings_Syncing->set_current_syncing_step_text('Syncing detail pages ...');

                // Fetch products and start the processor
                $stream_products_result = $this->stream_products($total_products_count['products'], $sync_by_collections, $is_syncing_collections);

            }

        }

        // Lands here if we need to sync all collections
        if (!empty($total_collections_count) && empty($sync_by_collections)) {

            $this->DB_Settings_Syncing->set_current_syncing_step_text('Syncing detail pages ...');
           
            // Fetch all collections and start the processor
            $stream_collections_result = $this->stream_collections($total_collections_count);

            if (!empty($stream_collections_result['custom_collections'])) {
                $this->API_Items_Collections->process_custom_collections($stream_collections_result);
            }

            if (!empty($stream_collections_result['smart_collections'])) {
                $this->API_Items_Collections->process_smart_collections($stream_collections_result);
            }
        }

        return wp_send_json_success();

    }


    public function get_product_listings_count_by_collection_ids()
    {
        $products_count = [];
        $collections = $this->DB_Settings_General->get_sync_by_collections_ids();
        $errors = false;

        foreach ($collections as $collection) {

            $response = $this->Shopify_API->get_product_listings_count_by_collection_id(
                $collection['id']
            );

            if (is_wp_error($response)) {
                $errors = $response;
                break;
            }

            $response_body = $this->Shopify_API->sanitize_response(
                $response['body']
            );

            if (Utils::has($response_body, 'count')) {
                $products_count[] = $response_body->count;
            }
        }

        if ($errors) {
            return $errors;
        }

        return [
            'count' => array_sum($products_count),
        ];
    }

    
    public function get_products_count()
    {
        if ($this->DB_Settings_General->is_syncing_by_collection()) {
            $resp = $this->get_product_ids_by_collection_ids();

            if (is_wp_error($resp)) {
                return $resp;
            }

            $count = count($resp);

        } else {
            $resp = $this->Shopify_API->get_product_listings_count();

            if (is_wp_error($resp)) {
                return $resp;
            }
                        
            $count = (int) $resp['body']->count;
        }

        return [
            'products' => $count
        ];
    }

    
    public function get_product_ids_by_collection_id(
        $collection_id,
        $page_link = false,
        $limit = false,
        $combined_product_ids = []
    ) {

        $response = $this->Shopify_API->get_products_listing_product_ids_by_collection_id_per_page(
            $collection_id,
            $limit,
            $page_link
        );

        if (is_wp_error($response)) {
            return $response;
        }

        // No additional pages left
        if (!$response) {
            return $combined_product_ids;
        }

        $response_body = $this->Shopify_API->sanitize_response(
            $response['body']
        );

        $new_product_ids = $response_body->product_listings;

        $new_array = array_map(function($product_id) {
            return $product_id->product_id;
        }, $new_product_ids);

        // Save the result in memory
        $combined_product_ids = array_merge(
            $combined_product_ids,
            $new_array
        );

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Found ' . count($combined_product_ids) . ' products to sync ...');

        if (!$this->Shopify_API->has_pagination($response)) {
            return $combined_product_ids;
        }

        $page_link = $this->Shopify_API->get_pagination_link($response);

        return $this->get_product_ids_by_collection_id(
            $collection_id,
            $page_link,
            $limit,
            $combined_product_ids
        );
    }

    public function get_product_ids_by_collection_ids()
    {
        $collections = maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );
     
        $all_product_ids = [];

        $limit = $this->DB_Settings_General->get_items_per_request();

        if (empty($collections)) {
            return [];
        }

        foreach ($collections as $collection) {

            $this->DB_Settings_Syncing->set_current_syncing_step_text('Getting products from collection: ' . $collection['title'] . ' ...');

            $collection_product_ids = $this->get_product_ids_by_collection_id(
                $collection['id'],
                false,
                $limit
            );

            if (is_wp_error($collection_product_ids)) {
                return $collection_product_ids;
            }

            $all_product_ids = array_merge(
                $all_product_ids,
                $collection_product_ids
            );
        }

        return array_values(array_unique($all_product_ids));

    }

    public function update_total_count_with_duplicates($new_total_count_to_set)
    {
        return $this->DB_Settings_Syncing->update_col('syncing_totals_products', $new_total_count_to_set);
    }

    public function has_duplicates_product_ids(
        $all_product_ids = [],
        $current_totals = 0
    ) {
        if (empty($all_product_ids) || !is_int($current_totals)) {
            return false;
        }

        $num_of_unique_ids = count(array_count_values($all_product_ids));
        $num_of_all_ids = count($all_product_ids);

        if ($num_of_all_ids > $num_of_unique_ids) {
            $difference = $num_of_all_ids - $num_of_unique_ids;

            $new_total_count_to_set = $current_totals - $difference;

            // New totals should never be negative
            if ($new_total_count_to_set < 0) {
                return false;
            }

            return $new_total_count_to_set;
        }

        return false;
    }


    /*

	Gets published product ids as a URL param string

	*/
    public function get_published_product_ids_as_param($current_page)
    {
        $product_ids = $this->DB_Settings_Syncing->get_published_product_ids();

        if (empty($product_ids)) {
            return false;
        }

        $limit = $this->DB_Settings_General->get_items_per_request();

        return $this->Shopify_API->create_param_ids(
            $product_ids,
            $limit,
            $current_page
        );
    }

    /*

	Gets products by page

	*/
    public function get_products_per_page($current_page)
    {

        $product_ids_comma_string = $this->get_published_product_ids_as_param($current_page);
        $limit = $this->DB_Settings_General->get_items_per_request();

        $response = $this->Shopify_API->get_products_per_page(
            $product_ids_comma_string,
            $limit
        );

        return $this->Shopify_API->pre_response_check($response);
    }

    public function get_all_products_vendors()
    {
        return [
            'vendors' => $this->DB_Products->get_unique_vendors(),
        ];
    }

    public function get_all_products_types()
    {
        return [
            'types' => $this->DB_Products->get_unique_types(),
        ];
    }

    public function normalize_filter_data($data) {
        return array_map(function($item) {
            return $item->node;
        }, $data->edges);
    }


    public function handle_get_all_product_tags($request) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {
            $cached_tags = Transients::get('shopwp_all_tags');

            if (!empty($cached_tags)) {
                return wp_send_json_success($cached_tags);
            }
        }

        $tags = $this->Admin_API_Shop->get_tags();

        if (is_wp_error($tags)) {
            return $this->handle_response($tags);
        }

        $tags = $this->normalize_filter_data($tags);

        if ($cached_enabled) {
            Transients::set('shopwp_all_tags', $tags, $data_cache_length);
        }

        return wp_send_json_success($tags);
    }

    public function handle_get_all_product_vendors($request) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {
            $cached_vendors = Transients::get('shopwp_all_vendors');

            if (!empty($cached_vendors)) {
                return wp_send_json_success($cached_vendors);
            }
        }

        $vendors = $this->Admin_API_Shop->get_vendors();

        if (is_wp_error($vendors)) {
            return $this->handle_response($vendors);
        }

        $vendors = $this->normalize_filter_data($vendors);

        if ($cached_enabled) {
            Transients::set('shopwp_all_vendors', $vendors, $data_cache_length);
        }

        return wp_send_json_success($vendors);

    }

    public function handle_get_all_product_types($request) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_types = Transients::get('shopwp_all_types');

            if (!empty($cached_types)) {
                return wp_send_json_success($cached_types);
            }

        }

        $types = $this->Admin_API_Shop->get_product_types();

        if (is_wp_error($types)) {
            return $this->handle_response($types);
        }

        $types = $this->normalize_filter_data($types);

        if ($cached_enabled) {
            Transients::set('shopwp_all_types', $types, $data_cache_length);
        }

        return wp_send_json_success($types);
    }

    public function handle_get_product_by_id($request) {

        $storefront_id = $request->get_param('id');
        $language = $request->get_param('language');
        $country = $request->get_param('country');

        $query_params = [
            'storefront_id'  => !empty($storefront_id) ? $storefront_id : false,
            'language'       => !empty($language) ? strtoupper($language) : strtoupper($this->DB_Settings_General->get_col_val('language_code', 'string')),
            'country'        => !empty($country) ? strtoupper($country) : strtoupper($this->DB_Settings_General->get_col_val('country_code', 'string')),
        ];

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        $cache_key = 'shopwp_query_' . $query_params['storefront_id'] . $query_params['language'] . $query_params['country'];

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get($cache_key));

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }

        $result = $this->Storefront_Products->api_get_product_by_id($query_params);

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set($cache_key, \maybe_serialize($result), $data_cache_length);
        }

        return \wp_send_json_success($result);

    }


    /*
    
    Public API Method
    
    */
    public function get_products($params) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $hash = Utils::hash($params, true);

            $cached_query = Transients::get('shopwp_query_' . $hash);

            $cached_query = \maybe_unserialize($cached_query);

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }

        $final_params = $this->public_api_default_values($params);

        $result = $this->Storefront_Products->api_get_products($final_params, $final_params['schema']);

        if (is_wp_error($result)) {
            return $result;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }

        return $result;

    }

    /*
    
    Public API Method
    
    */
    public function get_product($params) {
        
        $query_params = [
            'storefront_id'  => $this->create_product_storefront_id($params),
            'language'       => !empty($params['language']) ? strtoupper($params['language']) : strtoupper($this->DB_Settings_General->get_col_val('language_code', 'string')),
            'country'        => !empty($params['country']) ? strtoupper($params['country']) : strtoupper($this->DB_Settings_General->get_col_val('country_code', 'string')),
        ];

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');
        $schema = !empty($params['schema']) ? $params['schema'] : false;

        $cache_key = md5($query_params['storefront_id'] . $query_params['language'] . $query_params['country'] . $schema);

        if (empty($query_params['storefront_id'])) {
            return Utils::wp_error('No storefront id provided');
        }

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $cache_key));

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }

        $result = $this->Storefront_Products->api_get_product_by_id($query_params, $schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $cache_key, \maybe_serialize($result), $data_cache_length);
        }

        return $result;

    }

    public function format_collection_ids($collection_ids) {
        return array_map(function($collection_id) {

            if (is_array($collection_id)) {
                return 'gid://shopify/Collection/' . $collection_id['id'];
            } else {
                return 'gid://shopify/Collection/' . $collection_id;
            }
            
        }, $collection_ids);
    }

    public function get_all_products_from_collection_id($query_params, $custom_schema = false, $has_next_page = true, $total_products = []) {

        // called during processing
        $result = $this->Storefront_Products->api_get_products_from_collection_id($query_params, $custom_schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if (empty($result) || empty($result[0])) {
            return $total_products;
        }

        $products = $result[0]->products->edges;

        $only_products = [];

        foreach ($products as $product) {
            $only_products[] = $product->node;
        }

        $total_products = array_merge($total_products, $only_products);

        $last_cursor_id = $this->Shopify_API->get_last_cursor($products);

        $has_next_page = $this->Shopify_API->has_next_page($result[0]->products);

        if ($has_next_page) {
            $query_params['cursor'] = $last_cursor_id;

            return $this->get_all_products_from_collection_id($query_params, $custom_schema, $has_next_page, $total_products);

        } else {
            return $total_products;
        }
    }

    public function get_all_products($query_params, $custom_schema, $has_next_page = false, $total_products = []) {

        $result = $this->Storefront_Products->api_get_products($query_params, $custom_schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if (empty($result)) {
            return $total_products;
        }

        $products = $result->edges;

        if (empty($products)) {
            return [];
        }

        $total_products = array_merge($total_products, $products);

        $last_cursor_id = $this->Shopify_API->get_last_cursor($products);

        $has_next_page = $this->Shopify_API->has_next_page($result);

        if ($has_next_page) {
            $query_params['cursor'] = $last_cursor_id;

            return $this->get_all_products($query_params, $custom_schema, $has_next_page, $total_products);

        } else {

            return array_values(array_map(function($product) {
                return $product->node;
            }, $total_products));
        }

    }

    public function get_all_products_from_collection_ids($collection_ids, $query_params, $custom_schema = false) {
    
        $final_all_products = [];

        foreach ($collection_ids as $collections_id) {

            $query_params['ids'] = [$collections_id];

            $result = $this->get_all_products_from_collection_id($query_params, $custom_schema);

            if (is_wp_error($result)) {
                $final_all_products = $result;
                break;
            }

            $final_all_products = array_merge($final_all_products, $result);
            
        }

        if (is_array($final_all_products)) {
            return array_values(array_unique($final_all_products, SORT_REGULAR));
        }

        return $final_all_products;
    }

    /*
    
    Public API Method
    
    */
    public function get_products_by_collection_ids($params)
    {
        if (empty($params) || empty($params['collection_ids']) || !is_array($params['collection_ids'])) {
            return [];
        }

        $cache_id = md5(implode("-", $params['collection_ids']));

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $cache_id));

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }
        

        $collection_ids = $this->format_collection_ids($params['collection_ids']);

        $query_params = $this->public_api_default_values($params);

        $all_products = $this->get_all_products_from_collection_ids($collection_ids, $query_params, $query_params['schema']);

        if (is_wp_error($all_products)) {
            return $all_products;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $cache_id, \maybe_serialize($all_products), $data_cache_length);
        }

        return $all_products;

    }

    public function create_product_storefront_id($params) {

        $prefix = 'gid://shopify/Product/';

        if (!is_array($params) | empty($params)) {
            return false;
        }

        if (isset($params['post_id'])) {
            $product_id = get_post_meta($params['post_id'], 'product_id', true);

            if (empty($product_id)) {
                return false;
            }

            $storefront_id = $prefix . $product_id;

        } else if (isset($params['product_id'])) {
            $storefront_id = $prefix . $params['product_id'];

        } else if (isset($params['storefront_id'])) {
            $storefront_id = $params['storefront_id'];

        } else {
            $storefront_id = false;
        }

        return $storefront_id;
        
    }

    public function handle_get_products($request) {

        $query_params = $request->get_param('queryParams');
        $shop_state = $request->get_param('shopState');

        return \wp_send_json_error(__('Sorry, the free version of ShopWP is no longer supported. Please upgrade to ShopWP Pro to continue using this plugin.', 'shopwp'));
        
        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        $query_params['language'] = empty($shop_state['language']) ? strtoupper($this->DB_Settings_General->get_col_val('language_code', 'string')) : strtoupper($shop_state['language']);
        $query_params['country'] = empty($shop_state['country']) ? strtoupper($this->DB_Settings_General->get_col_val('country_code', 'string')) : strtoupper($shop_state['country']);

        if ($cached_enabled) {
            $hash = Utils::hash($query_params, true);

            $cached_query = Transients::get('shopwp_query_' . $hash);

            $cached_query = \maybe_unserialize($cached_query);

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }

        $result = $this->Storefront_Products->api_get_products($query_params);

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }

        return \wp_send_json_success($result);

    }

    public function build_collection_id_obj($collection_title, $cached_collections_list, $found_key) {
        return [
            'label' => $collection_title,
            'id' => base64_encode('gid://shopify/Collection/' . $cached_collections_list[$found_key]->id)
        ];
    }

    public function adjust_query_params_for_products_by_collection_titles($query_params) {
        
        $supplied_titles = $query_params['collection_titles'];

        if (is_string($supplied_titles)) {
            $supplied_titles = [strtolower($supplied_titles)];
        } else {
            $supplied_titles = array_map('strtolower', $supplied_titles);
        }

        $cached_collections_list = maybe_unserialize(Transients::get('shopwp_all_collections'));

        if (empty($cached_collections_list)) {
            $cached_collections_list = $this->API_Items_Collections->handle_get_all_collections(false);
        }

        $new_stuff = array_filter(array_map(function($collection_title) use($cached_collections_list) {

            $cached_collection_titles_to_search = array_map('strtolower', array_column($cached_collections_list, 'title'));

            $found_key = array_search($collection_title, $cached_collection_titles_to_search);

            if ($found_key === false) {
                return false;
            }

            return $this->build_collection_id_obj($collection_title, $cached_collections_list, $found_key);

        }, $supplied_titles));

        return $new_stuff;

    }
    
    public function handle_get_products_from_collections($request) {
    
        $query_params = $request->get_param('queryParamsCollectionProducts');

        /*
        
        If the user is searching for products based on collection title, then
        we need to transform the data into ids. To do this, we need to first look 
        to see if they have a cached collections response. If they do, we can use that 
        to find the collection id. 

        If they don't, we need to fetch all the collections, cache them, and find the 
        id that way.
        
        */
        if (!empty($query_params['collection_titles'])) {

            $query_params['ids'] = $this->adjust_query_params_for_products_by_collection_titles($query_params);

            if (empty($query_params['ids'])) {
                return \wp_send_json_success([]);
            }

            $hash = Utils::hash($query_params, true);

        } else {
            $hash = Utils::hash($query_params, true);
        }

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_query = Transients::get('shopwp_query_' . $hash);

            $cached_query = \maybe_unserialize($cached_query);

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }
        
        if (!empty($query_params['query'])) {

            $query_params['first'] = (int)$query_params['first'];

            // Called from either shortcode or Render API
            $result = $this->Storefront_Products->api_get_products_from_collection_id($query_params);

        } else {

            // Called from Storefront collections filter
            $result = $this->get_all_products_from_collection_ids($query_params['ids'], $query_params);

            $result = [
                'nodes' => [[
                    'products' => [
                        'cursor' => '',
                        'edges' => $result,
                        'pageInfo' => [
                                'hasNextPage' => false,
                                'hasPreviousPage' => false
                            ]
                    ]
                ]]
            ];
        }

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }


        return \wp_send_json_success($result);
        
    }

    public function register_routes() {
        $this->api_route('/query/products/collections', 'POST', [$this, 'handle_get_products_from_collections']);
        $this->api_route('/query/product/id', 'POST', [$this, 'handle_get_product_by_id']);
        $this->api_route('/query/products', 'POST', [$this, 'handle_get_products']);
        $this->api_route('/syncing/expire', 'POST', [$this, 'handle_expire_sync']);
        $this->api_route('/syncing/remove', 'POST', [$this, 'handle_remove_synced_data']);
        $this->api_route('/syncing/product_detail_pages', 'POST', [$this, 'handle_sync_product_detail_pages']);
        $this->api_route('/products/types', 'POST', [$this, 'handle_get_all_product_types']);
        $this->api_route('/products/vendors', 'POST', [$this, 'handle_get_all_product_vendors']);
        $this->api_route('/products/tags', 'POST', [$this, 'handle_get_all_product_tags']);
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
}

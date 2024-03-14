<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Products {

   public function __construct($GraphQL, $Storefront_Queries) {
      $this->GraphQL = $GraphQL;
		$this->Storefront_Queries = $Storefront_Queries;
   }

   public function sanitize_collection_ids($query_params) {

      if (empty($query_params['ids'])) {
         return [];
      }

      return array_map(function($collection) {

         if (!is_array($collection)) {
            return $collection;
         }

         if (array_key_exists('id', $collection)) {
            return $collection['id'];
         }

         return false;

         
      }, $query_params['ids']);
   }
      
   public function api_get_products($query_params, $custom_schema = false) {

      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->query_get_products($query_params, $custom_schema),
         'storefront',
         [
            'mutation_key' => false,
            'data_key' => 'products', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_get_product_by_id($query_params, $custom_schema = false) {

      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->query_get_product_by_id($query_params, $custom_schema),
         'storefront',
         [
            'mutation_key' => false,
            'data_key' => 'product', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_get_products_from_collection_id($query_params, $custom_schema = false) {
   
      $query_params['ids'] = $this->sanitize_collection_ids($query_params);
      
      if (empty($query_params['ids'])) {
         return new \WP_Error('error', 'No collection ids found');
      }

      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->query_get_products_from_collection_id($query_params, $custom_schema),
         'storefront',
         [
            'mutation_key' => false,
            'data_key' => 'nodes', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }   

}
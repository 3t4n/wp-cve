<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Collections {

   public function __construct($GraphQL, $Storefront_Queries) {
      $this->GraphQL = $GraphQL;
		$this->Storefront_Queries = $Storefront_Queries;
   }
   
   public function api_get_collections($query_params, $custom_schema = false, $with_products = false, $query_params_products = false) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->query_get_collections($query_params, $custom_schema, $with_products, $query_params_products),
         'storefront',
         [
            'mutation_key' => false,
            'data_key' => 'collections', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }  

}
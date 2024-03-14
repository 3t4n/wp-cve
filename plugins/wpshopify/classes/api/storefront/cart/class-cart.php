<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Cart {

   public function __construct($GraphQL, $Storefront_Queries) {
      $this->GraphQL = $GraphQL;
		$this->Storefront_Queries = $Storefront_Queries;
   }
   
   public function api_get_cart($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_get_cart($cart_data),
         'storefront',
         [
            'mutation_key' => false,
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_create_cart($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_create_cart($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartCreate',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_add_lineitems($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_add_lineitems($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartLinesAdd',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_remove_lineitems($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_remove_lineitems($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartLinesRemove',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_update_lineitems($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_update_lineitems($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartLinesUpdate',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_apply_discount($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_apply_discount($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartDiscountCodesUpdate',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_update_note($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_update_note($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartNoteUpdate',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }   

   public function api_update_cart_attributes($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_update_cart_attributes($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartAttributesUpdate',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   public function api_update_buyer_identity($cart_data) {
      return $this->GraphQL->graphql_api_request(
         $this->Storefront_Queries->graph_query_update_buyer_identity($cart_data),
         'storefront',
         [
            'mutation_key' => 'cartBuyerIdentityUpdate',
            'data_key' => 'cart', 
            'user_errors_key' => 'userErrors'
         ]
      );
   }

   
   
}

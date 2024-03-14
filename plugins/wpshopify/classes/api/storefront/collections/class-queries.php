<?php

namespace ShopWP\API\Storefront\Collections;
use ShopWP\API\Storefront\Products\Queries as Product_Queries;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function collection_schema($with_products = false, $query_params_products = false) {

      if (!empty($query_params_products['first'])) {
         $first = $query_params_products['first'];
      } else {
         $first = 250;
      }

      if (!empty($query_params_products['sortKey'])) {
         $sortKey = strtoupper($query_params_products['sortKey']);
      } else {
         $sortKey = 'TITLE';
      }

      $products = $with_products ? 'products(first: ' . $first . ' sortKey: ' . $sortKey . ') { pageInfo { hasNextPage hasPreviousPage } edges { cursor node { ' . Product_Queries::default_product_schema() .' } } }' : '';

      return '
         title
         handle
         id
         description
         descriptionHtml
         onlineStoreUrl
         image {
            width
            height
            altText
            id
            originalSrc
            transformedSrc
         }
         ' . $products . '';
   }

   public function query_get_collections($query_params, $custom_schema = false, $with_products = false, $query_params_products = false) {

      $schema = $custom_schema ? $custom_schema : $this->collection_schema($with_products, $query_params_products);
      
      if (empty($query_params['cursor'])) {
         unset($query_params['cursor']);
      }

      return [
         "query" => 'query($query: String!, $first: Int!, $cursor: String, $sortKey: CollectionSortKeys, $reverse: Boolean, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            collections(first: $first, query: $query, after: $cursor, reverse: $reverse, sortKey: $sortKey) {
               pageInfo {
                  hasNextPage
                  hasPreviousPage
               }
               edges {
                  cursor
                  node {
                     ' . $schema . '
                  }
               }
            }
         }',
         "variables" => $query_params
      ];
   }

}
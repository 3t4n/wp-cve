<?php

namespace ShopWP\API\Storefront\Products;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {
   

   public static function default_product_schema() {
      return '
         availableForSale
         compareAtPriceRange {
            maxVariantPrice {
               amount
               currencyCode
            }
            minVariantPrice {
               amount
               currencyCode
            }
         }
         createdAt
         description
         descriptionHtml
         handle
         id
         onlineStoreUrl
         options {
            id
            name
            values
         }
         priceRange {
            maxVariantPrice {
               amount
               currencyCode
            }
            minVariantPrice {
               amount
               currencyCode
            }
         }
         productType
         publishedAt
         requiresSellingPlan
         title
         totalInventory
         updatedAt
         vendor,
         images(first: 250) {
            edges {
               node {
                  width
                  height
                  altText
                  id
                  originalSrc
                  transformedSrc
               }
            }
         },
         media(first: 250) {
            edges {
               node {
                  alt
                  mediaContentType
                  previewImage {
                     width
                     height
                     altText
                     id
                     url
                  }
                  ...on ExternalVideo {
                     id
                     embeddedUrl
                  }
                  ...on MediaImage {
                     image {
                        width
                        height
                        altText
                        id
                        originalSrc
                        transformedSrc                        
                     }
                  }
                  ...on Video {
                     sources {
                        url
                        mimeType
                        format
                        height
                        width
                     }
                  }
               }
            }
         },
         variants(first: 100) {
            edges {
               node {
                  product {
                     title
                  }
                  availableForSale
                  compareAtPriceV2 {
                     amount
                     currencyCode
                  }
                  currentlyNotInStock
                  id
                  image {
                     width
                     height
                     altText
                     id
                     originalSrc
                     transformedSrc
                  }
                  priceV2 {
                     amount
                     currencyCode
                  }
                  quantityAvailable
                  requiresShipping
                  selectedOptions {
                     name 
                     value
                  }
                  sku
                  title
                  weight
                  weightUnit
               }
            }
         }
         sellingPlanGroups(first: 50) {
            edges {
               node {
                  appName
                  name
                  options {
                     name
                     values
                  }
                  sellingPlans(first: 50) {
                     edges {
                        node {
                           description
                           id
                           name
                           recurringDeliveries
                           options {
                              name
                              value
                           }
                           priceAdjustments {
                              orderCount
                              adjustmentValue
                           }
                        }
                     }
                  }
               }
            }
         }
      ';
   }

   public function query_get_product_by_id($query_params, $custom_schema = false) {

      $schema = $custom_schema ? $custom_schema : self::default_product_schema();

      return [
         "query" => 'query($id: ID!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            product(id: $id) {
               ' . $schema . '
            }
         }',
         "variables" => [
            'id' => $query_params['storefront_id'],
            'language' => $query_params['language'],
            'country' => $query_params['country']
         ]
      ];
   }

   public function query_get_products($query_params, $custom_schema = false) {

      if (empty($query_params['cursor'])) {
         unset($query_params['cursor']);
      }

      $schema = $custom_schema ? $custom_schema : self::default_product_schema();

      $final_vars = [
            'query' => $query_params['query'],
            'first' => $query_params['first'],
            'reverse' => isset($query_params['reverse']) ? $query_params['reverse'] : false,
            'sortKey' => isset($query_params['sortKey']) ? $query_params['sortKey'] : 'TITLE',
            'language' => $query_params['language'],
            'country' => $query_params['country']
      ];

      if (isset($query_params['cursor'])) {
         $final_vars['cursor'] = $query_params['cursor'];
      }

      // Docs: https://shopify.dev/api/storefront/reference/common-objects/queryroot#products-2021-10

      return [
         "query" => 'query($query: String!, $first: Int!, $cursor: String, $sortKey: ProductSortKeys, $reverse: Boolean, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            products(first: $first, query: $query, after: $cursor, reverse: $reverse, sortKey: $sortKey) {
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
         "variables" => $final_vars
      ];
   }

   public function query_get_products_from_collection_id($query_params, $custom_schema = false) {

      if (empty($query_params['cursor'])) {
         unset($query_params['cursor']);
      }

      $schema = $custom_schema ? $custom_schema : self::default_product_schema();

      return [
         "query" => 'query nodes($ids: [ID!]!, $first: Int!, $cursor: String, $sortKey: ProductCollectionSortKeys, $reverse: Boolean, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            nodes(ids: $ids) {
               ...on Collection {
                  id
                  products(first: $first sortKey: $sortKey reverse: $reverse after: $cursor) {
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
               }
            }
         }',
         "variables" => $query_params 
      ];
      
   }

}
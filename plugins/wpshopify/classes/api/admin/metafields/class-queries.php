<?php

namespace ShopWP\API\Admin\Metafields;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function graph_query_show_metafield($key, $namespace) {

      return [
        "query" => 'mutation($input: MetafieldStorefrontVisibilityInput!) {
            metafieldStorefrontVisibilityCreate(
              input: $input
            ) {
              metafieldStorefrontVisibility {
                id
              }
              userErrors {
                field
                message
              }
            }
          }',
         "variables" => [
            'input' => [
                "namespace" => $namespace,
                "key" => $key,
                "ownerType" => "PRODUCT"
            ]
         ]
      ];
   }

   public function graph_query_hide_metafield($metafield_id) {

    return [
      "query" => 'mutation metafieldStorefrontVisibilityDelete($id: ID!) {
        metafieldStorefrontVisibilityDelete(id: $id) {
          deletedMetafieldStorefrontVisibilityId
          userErrors {
            field
            message
          }
        }
      }',
       "variables" => [
          'id' => $metafield_id
       ]
    ];
  }

  public function graph_query_get_metafields($namespace) {

    return [
      "query" => 'query($namespace: String!) {
        metafieldStorefrontVisibilities(first:5, namespace: $namespace) {
          edges {
            node {
              id
              namespace
              key
              ownerType
            }
          }
        }
      }',
      "variables" => [
         'namespace' => $namespace
      ]
   ];
  }

}



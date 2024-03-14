<?php

namespace ShopWP\API\Admin\Shop;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

  public function graph_query_get_tags() {

    return [
      "query" => '{
        shop {
          productTags(first: 250) {
            edges {
              node
            }
          }
        }
      }'
    ];

  }

  public function graph_query_get_vendors() {

    return [
      "query" => '{
        shop {
          productVendors(first: 250) {
            edges {
              node
            }
          }
        }
      }'
    ];

  }

  public function graph_query_get_product_types() {

    return [
      "query" => '{
        shop {
          productTypes(first: 250) {
            edges {
              node
            }
          }
        }
      }'
    ];

  }  

}
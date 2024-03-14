<?php

namespace ShopWP\API\Admin;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
	exit;
}

class Shop {

    public function __construct($GraphQL, $Admin_Queries) {
        $this->GraphQL = $GraphQL;
        $this->Admin_Queries = $Admin_Queries;
    }

    public function get_tags() {
        return $this->GraphQL->graphql_api_request(
            $this->Admin_Queries->graph_query_get_tags(),
            'admin',
            [
                'mutation_key' => 'shop',
                'data_key' => 'productTags', 
                'user_errors_key' => 'userErrors'
            ]
        );
    }

    public function get_vendors() {
        return $this->GraphQL->graphql_api_request(
            $this->Admin_Queries->graph_query_get_vendors(),
            'admin',
            [
                'mutation_key' => 'shop',
                'data_key' => 'productVendors', 
                'user_errors_key' => 'userErrors'
            ]
        );
    }

    public function get_product_types() {
        return $this->GraphQL->graphql_api_request(
            $this->Admin_Queries->graph_query_get_product_types(),
            'admin',
            [
                'mutation_key' => 'shop',
                'data_key' => 'productTypes', 
                'user_errors_key' => 'userErrors'
            ]
        );
    }    
}

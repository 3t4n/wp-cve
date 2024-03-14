<?php

namespace ShopWP\API\Admin;

if (!defined('ABSPATH')) {
    exit();
}

class Orders
{
    public function __construct($GraphQL, $Admin_Order_Queries)
    {
        $this->GraphQL = $GraphQL;
        $this->Admin_Order_Queries = $Admin_Order_Queries;
    }

    function api_get_orders($params, $custom_scheme = false) {
        return $this->GraphQL->graphql_api_request(
            $this->Admin_Order_Queries->graph_query_get_orders($params, $custom_scheme),
            'admin',
            [
                'data_key' => 'orders', 
                'user_errors_key' => 'userErrors'
            ]
        );
    }

}
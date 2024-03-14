<?php

namespace ShopWP\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

class Shop {

    public function __construct($GraphQL, $Storefront_Queries) {
        $this->GraphQL = $GraphQL;
        $this->Storefront_Queries = $Storefront_Queries;
    }

    public function api_get_available_localizations() {
        return $this->GraphQL->graphql_api_request(
            $this->Storefront_Queries->query_get_available_localizations(),
            'storefront',
            [
                'mutation_key' => false,
                'data_key' => 'localization', 
                'user_errors_key' => 'userErrors'
            ]
        );
    }

}
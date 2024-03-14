<?php

namespace ShopWP\API;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
	exit;
}

class GraphQL {

    public function __construct($DB_Settings_Connection) {
        $this->DB_Settings_Connection = $DB_Settings_Connection;
    }

    public function graphql_storefront_api_endpoint() {
        return 'https://' . $this->DB_Settings_Connection->get_domain() . '/api/' . SHOPWP_STOREFRONT_GRAPHQL_API_VERSION . '/graphql.json';
    }

    public function graphql_admin_api_endpoint() {
        return 'https://' . $this->DB_Settings_Connection->get_domain() . '/admin/api/' . SHOPWP_ADMIN_GRAPHQL_API_VERSION . '/graphql.json';
    }

    public function graphql_storefront_api_headers() {

        return [
            'X-Shopify-Storefront-Access-Token' => $this->DB_Settings_Connection->get_storefront_access_token(),
            'Content-type' => 'application/json',
            'Accept' => "application/json"
        ];
    }

    public function graphql_admin_api_headers() {
        return [
            'X-Shopify-Access-Token' => $this->DB_Settings_Connection->get_admin_api_password(),
            'Content-type' => 'application/json',
        ];
    }

    public function post($endpoint, $options) {
        return wp_remote_post($endpoint, $options);      
    }

    public function graph_post_options($query, $type) {
        return [
            'headers' => $type === 'storefront' ? $this->graphql_storefront_api_headers() : $this->graphql_admin_api_headers(),
            'body' => json_encode($query)
        ];
    }

    public function graphql_api_request($query, $type = 'storefront', $access_keys = false) {

        if ($type === 'storefront') {
            $endpoint = $this->graphql_storefront_api_endpoint();

        } else if ($type === 'admin') {
            $endpoint = $this->graphql_admin_api_endpoint();
        }
    
        $options = $this->graph_post_options($query, $type);

        return $this->return_response(
            $this->post(
                $endpoint, 
                $options
            ),
            $access_keys
        );
    }

    public function return_response($response, $access_keys = false) {

        $response_headers    = wp_remote_retrieve_headers($response);
        $response_code       = wp_remote_retrieve_response_code($response);
        $response_message    = wp_remote_retrieve_response_message($response);
        $body                = json_decode(wp_remote_retrieve_body($response));

        if (is_wp_error($response)) {
            return $response;
        }

        if ( 200 != $response_code && !empty( $response_message ) ) {
            return Utils::wp_error($response_message);

        } elseif ( 200 != $response_code ) {
            return Utils::wp_error('Unknown error occurred while calling Shopify');

        } elseif (property_exists($body, 'errors')) {
            return Utils::wp_error($body->errors[0]->message);

        } else {

            if ($access_keys) {

                if (empty($access_keys['mutation_key'])) {
                    return $body->data->{$access_keys['data_key']};
                }

                $raw_data = $body->data->{$access_keys['mutation_key']};

                if (!empty($raw_data->{$access_keys['user_errors_key']})) {
                    return Utils::wp_error($raw_data->{$access_keys['user_errors_key']}[0]->message);
                }

                return $raw_data->{$access_keys['data_key']};

            }

            return $body->data;

        }

    }

}

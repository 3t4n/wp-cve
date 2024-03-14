<?php

namespace ShopWP\API\Settings;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Utils\Data;

class Connection extends \ShopWP\API
{
    public $DB_Settings_Connection;
    public $DB_Settings_General;
    public $DB_Settings_Syncing;
    public $Shopify_API;

    public function __construct(
        $DB_Settings_Connection,
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $Shopify_API
    ) {
        $this->DB_Settings_Connection = $DB_Settings_Connection;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Shopify_API = $Shopify_API;
    }

    public function only_valid_storefront_access_tokens(
        $access_token,
        $user_entered_token
    ) {
        return $access_token->access_token === $user_entered_token;
    }

    public function valid_storefront_access_tokens(
        $storefront_access_tokens,
        $user_entered_token
    ) {
        return array_filter($storefront_access_tokens, function (
            $access_token
        ) use ($user_entered_token) {
            return $this->only_valid_storefront_access_tokens(
                $access_token,
                $user_entered_token
            );
        });
    }

    public function mask_connection($request)
    {
        return $this->handle_response([
            'response' => $this->DB_Settings_Connection->mask_connection(),
        ]);
    }

    public function get_storefront_access_token_from_api($token_to_check_for) {
      
      $get_tokens = $this->Shopify_API->get_storefront_access_tokens();

      if (empty($get_tokens)) {
         return new \WP_Error('error', 'No valid Shopify storefront access tokens found. Please double check that you\'ve entered the correct API keys and domain.');
      }

      $access_tokens_resp = $this->Shopify_API->pre_response_check($get_tokens);

      if (is_wp_error($access_tokens_resp)) {
         return $access_tokens_resp;
      }

      $valid_tokens = $this->valid_storefront_access_tokens($access_tokens_resp->storefront_access_tokens, $token_to_check_for);

      if (empty($valid_tokens)) {
         return new \WP_Error('error', 'Oops, it looks like the Storefront Access Token you provided is wrong. Try to copy / paste the key again.');
      }

      $valid_tokens = array_values($valid_tokens);

      return $valid_tokens[0];

    }

    public function log_error_and_reset_connection($error_message) {
        $this->DB_Settings_Connection->truncate();
        $this->DB_Settings_General->reset_sync_by_collections();

        // Logging for debugging
        error_log('ShopWP Error: ' . $error_message);
    }

    public function delete_connection($request)
    {

        $existing_storefront_access_token = $this->DB_Settings_Connection->get_storefront_access_token();

        if (empty($existing_storefront_access_token)) {

            $this->log_error_and_reset_connection('No existing Storefront Access Token found for: ' . $_SERVER['HTTP_HOST']);
            $result_for_delete = true;

        } else {

            $storefront_access_token = $this->get_storefront_access_token_from_api($existing_storefront_access_token);

            // Could land here if token is stale.
            // Also the app was probably already deleted. We can just clear our local database out and return
            if (empty($storefront_access_token) || is_wp_error($storefront_access_token)) {
                
                $result_for_get = $storefront_access_token->get_error_message();

                $this->log_error_and_reset_connection($result_for_get);
                
                $result_for_delete = true;
                // return new \WP_Error('error', 'ShopWP Warning: ' . $result_for_get);

            } else {

                $delete_storefront_access_token_rep = $this->Shopify_API->delete_storefront_access_token($storefront_access_token->id);

                if (is_wp_error($delete_storefront_access_token_rep)) {
                    
                    $error_message = wp_remote_retrieve_response_message($delete_storefront_access_token_rep);

                    $this->log_error_and_reset_connection($error_message);

                    return new \WP_Error('error', $error_message);

                } else {
                    $result_for_delete = true;
                }
            }

        }


        $removing_wordpress_domain_result = wp_remote_post("https://auth.wpshop.io/wp-json/wpshopify-auth/connections/delete", [
            'body' => [
                'wordpress_domain' => $_SERVER['HTTP_HOST']
            ]
        ]);

        $this->DB_Settings_Syncing->set_finished_removing_connection(1);

        $final_resp = $this->handle_response([
            'response_multi' => [
                $this->DB_Settings_Connection->truncate(),
                $this->DB_Settings_General->reset_sync_by_collections(),
                $removing_wordpress_domain_result,
                $result_for_delete
            ],
        ]);

        return $final_resp;
    }

    public function set_connection($request)
    {

        $connection = $request->get_param('connection');

        $nonce_verified = wp_verify_nonce(
            $request->get_param('nonce'),
            'wp_rest'
        );

        if (!$nonce_verified) {
            return $this->handle_response([
                'response' => Utils::wp_error(
                    'Whoops, looks like you don\'t have permission to update this.'
                ),
            ]);
        }

        $clean_connection = $this->Shopify_API->sanitize_response($connection);

        // Remove any existing connection first
        $this->DB_Settings_Connection->truncate();

        return $this->DB_Settings_Connection->maybe_insert_connection($clean_connection);
        
    }

    public function register_route_connection()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/connection',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'set_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_connection_delete()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/connection/delete',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_connection_mask()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/connection/mask',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'mask_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_connection_mask']);
        add_action('rest_api_init', [$this, 'register_route_connection']);

        add_action('rest_api_init', [
            $this,
            'register_route_connection_delete',
        ]);
    }
}

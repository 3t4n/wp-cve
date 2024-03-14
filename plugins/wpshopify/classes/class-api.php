<?php

namespace ShopWP;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class API
{
    public function __construct($DB_Settings_Syncing, $plugin_settings)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->plugin_settings = $plugin_settings;
    }

    public function public_api_default_values($user_data, $type = 'products') {

        if (isset($user_data['page_size'])) {
            $first = $user_data['page_size'];
            
        } else if (isset($user_data['first'])) {
            $first = $user_data['first'];

        } else {
            $first = 10;
        }

        $default_sort_key = $type === 'orders' ? 'CREATED_AT' : 'TITLE';

        $defaults = [
            'first'     => $first,
            'reverse'   => isset($user_data['reverse']) ? $user_data['reverse'] : false,
            'sortKey'   => isset($user_data['sort_by']) ? strtoupper($user_data['sort_by']) : $default_sort_key,
            'schema'    => isset($user_data['schema']) ? $user_data['schema'] : false,
            'language'  => isset($user_data['language']) ? strtoupper($user_data['language']) : strtoupper($this->plugin_settings['general']['language_code']),
            'country'   => isset($user_data['country']) ? strtoupper($user_data['country']) : strtoupper($this->plugin_settings['general']['country_code']),
            'cursor'    => false
        ];

        if (isset($user_data['query'])) {
            $defaults['query'] = $user_data['query'];
        }

        return $defaults;
    }

    public function return_error($message, $method, $line)
    {
        return $this->handle_response([
            'response' => Utils::wp_error([
                'message_lookup' => $message,
                'call_method' => $method,
                'call_line' => $line,
            ]),
        ]);
    }

    public function return_success($params)
    {
        return $this->handle_response($params);
    }

    public function send_error($message)
    {
        if (is_wp_error($message)) {
            $message = $message->get_error_message();
        }

        return [
            'type' => 'error',
            'message' => $message,
        ];
    }

    public function send_warning($message)
    {
        return [
            'type' => 'warning',
            'message' => $message,
        ];
    }

    public function handle_warnings($message)
    {

        if ($this->DB_Settings_Syncing->is_syncing()) {
            $this->DB_Settings_Syncing->save_warning(Messages::get($message));
        }

        return $this->send_warning(Messages::get($message));
    }

    /*

	Will throw a warning if the response prop does not exist or is empty

	*/
    public function has_warning($params)
    {
        return !Utils::has($params['response'], $params['access_prop']) ||
            empty($params['response']->{$params['access_prop']});
    }

    public function handle_errors($WP_Error)
    {
        if ($this->DB_Settings_Syncing) {
            $this->DB_Settings_Syncing->save_notice_and_expire_sync($WP_Error);
        }

        return $this->send_error($WP_Error->get_error_message());
    }

    public function run_processes($params)
    {
        foreach ($params['process_fns'] as $class) {
            if ($params['access_prop']) {
                $params['response'] = Utils::convert_array_to_object(
                    $params['response']
                );

                \call_user_func_array(
                    [$class, 'process'],
                    [$params['response']->{$params['access_prop']}, $params]
                );
            } else {
                \call_user_func_array(
                    [$class, 'process'],
                    [$params['response'], $params]
                );
            }
        }
    }

    public function handle_response_defaults()
    {
        return [
            'response' => false,
            'response_multi' => false,
            'access_prop' => false,
            'return_key' => false,
            'warning_message' => false,
            'process_fns' => false,
        ];
    }

    public function has_multi_response($params)
    {
        return !empty($params['response_multi']);
    }

    public function has_response($params)
    {
        return !empty($params['response']);
    }

    public function needs_processing($params)
    {
        return !empty($params['process_fns']);
    }

    public function has_return_key($params)
    {
        return !empty($params['return_key']);
    }

    public function has_access_prop($params)
    {
        return !empty($params['access_prop']);
    }

    public function has_warning_message($params)
    {
        return !empty($params['warning_message']);
    }

    public function handle_response_params($params)
    {
        return wp_parse_args($params, $this->handle_response_defaults());
    }

    public function return_keyed_response($params)
    {
        $params['response'] = Utils::convert_array_to_object(
            $params['response']
        );

        if (!empty($params['access_prop'])) {
            $key = $params['response']->{$params['access_prop']};
        } else {
            $key = false;
        }

        return [
            $params['return_key'] => $key,
        ];
    }

    public function return_from_prop($params)
    {
        $params['response'] = Utils::convert_array_to_object(
            $params['response']
        );

        return $params['response']->{$params['access_prop']};
    }

    public function handle_response_logic($params)
    {
        if (!$this->has_response($params)) {
            if ($this->needs_processing($params)) {
                $this->run_processes($params);
            } else {
                return $params;
            }
        }

        if (is_wp_error($params['response'])) {
            return $this->handle_errors($params['response']);
        }

        if ($params['response'] === false) {
            return $params['response'];
        }

        if (
            $this->has_access_prop($params) &&
            $this->has_warning($params) &&
            $this->has_warning_message($params)
        ) {
            // no need to return if just a warning
            $this->handle_warnings($params['warning_message']);
        }

        if ($this->needs_processing($params)) {
            $this->run_processes($params);
        }

        if ($this->has_return_key($params)) {
            return $this->return_keyed_response($params);
        }

        if ($this->has_access_prop($params)) {
            return $this->return_from_prop($params);
        }

        return $params['response'];
    }

    public function handle_multi_response($responses)
    {
        return array_map([$this, 'handle_response_logic'], $responses);
    }

    public function is_public_route($route)
    {
        if (
            strpos($route, 'products/types') !== false ||
            strpos($route, 'products/vendors') !== false ||
            strpos($route, 'products/tags') !== false ||
            strpos($route, 'cart/create') !== false ||
            strpos($route, 'cart/get') !== false ||
            strpos($route, 'cart/discount') !== false ||
            strpos($route, 'cart/lineitems/add') !== false ||
            strpos($route, 'cart/lineitems/remove') !== false ||
            strpos($route, 'cart/lineitems/update') !== false ||
            strpos($route, 'cart/note/update') !== false ||
            strpos($route, 'cart/attributes/update') !== false ||
            strpos($route, 'cart/buyer/update') !== false ||
            strpos($route, 'query/products') !== false ||
            strpos($route, 'query/products/collections') !== false ||
            strpos($route, 'query/product/id') !== false ||
            strpos($route, 'query/collections') !== false ||
            strpos($route, 'query/translator/available') !== false ||
            strpos($route, 'components/template') !== false ||
            strpos($route, 'inventory_management') !== false ||
            strpos($route, 'license/downloads') !== false ||
            strpos($route, 'cache') !== false
        ) {
            return true;
        }

        return false;
    }

    public function pre_process($rest_obj)
    {
        
        $route = $rest_obj->get_route();

        if ($this->is_public_route($rest_obj->get_route())) {
            return true;
        }

        if (!current_user_can('edit_pages')) {
            return new \WP_Error(
                'rest_forbidden',
                __('You do not have the correct capabilities', 'shopwp'),
                ['status' => 401]
            );
        }

        // Allows the callback to be processed
        return true;
    }

    /*

	Params:

	'response' 				=> $response,
	'access_prop' 		=> 'count',
	'return_key' 			=> 'smart_collections',
	'warning_message'	=> 'this_is_a_message'

	*/
    public function handle_response($params)
    {
        if (is_wp_error($params)) {
            return wp_send_json_error($params);
        }

        if (!is_array($params)) {
            return $params;
        }

        $params = $this->handle_response_params($params);

        if ($this->has_multi_response($params)) {
            return $this->handle_multi_response($params);
        }

        return $this->handle_response_logic($params);
    }

    public function api_route($route, $method, $callback)
    {
        register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            $route,
            [
                [
                    'methods' => $method,
                    'callback' => $callback,
                    'permission_callback' => [$this, 'pre_process'],
                ]
            ]
        );
    }
}

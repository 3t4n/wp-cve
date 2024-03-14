<?php
namespace UiCoreAnimate;

/**
 * REST_API Handler
 */
class REST_API {

    public function __construct() {
        add_action('rest_api_init', [$this, 'add_route']);
        
    }

    public function add_route() {
        register_rest_route('uianim/v1', 'settings', [
            'methods' => 'POST',
            'permission_callback' => [$this, 'check_for_permission'],
            'show_in_index' => false,
            'callback' => [$this, 'settings_update'],
        ]);
    }

    
    public function check_for_permission()
    {
        return current_user_can('manage_options');
    }

    public function settings_update(\WP_REST_Request $request) {

        $name = $request->get_param( 'name' );
        $value = $request->get_param( 'value' );
    
        Settings::update_option( $name, $value );
    
        return new \WP_REST_Response( [
            'success' => true,
            'message' => 'Settings updated successfully!',
        ]);
    }
}
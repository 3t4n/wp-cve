<?php

/**
 * Description of RestApi
 *
 * @author Ali2Woo Team
 * 
 * @autoload: a2wl_init
 */

namespace AliNext_Lite;;

class RestApi {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    public function register_routes() {
        register_rest_route('a2wl-api/v1', '/info', array(
            'methods' => \WP_REST_Server::READABLE,
            'callback' => array($this, 'info'),
            'permission_callback' => '__return_true'
        ));
    }

    public function info($request) {
        $result = array();

        $result['server_ping'] = SystemInfo::server_ping();
        $result['plugin_version'] = A2WL()->version;
        
        return rest_ensure_response($result);
    }       
}


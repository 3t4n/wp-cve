<?php

if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

class WShop_Payment_Controller extends WP_REST_Controller{

    public function __construct()
    {
        $this->namespace = 'wshop';
        $this->rest_base = 'payment';
    }

    /**
     * Registers the routes for the objects of the controller.
     *
     * @since 4.7.0
     * @access public
     *
     * @see register_rest_route()
     */
    public function register_routes(){
        foreach (apply_filters('wshop_wp_reset_payments', array()) as $route=>$callback){
            register_rest_route($this->namespace, "/{$this->rest_base}/{$route}", array(
                array(
                    'methods' => WP_REST_Server::ALLMETHODS,
                    'callback' => $callback
                ),
                'schema' => array( $this,'get_public_item_schema')
            ));
        }
    }
}
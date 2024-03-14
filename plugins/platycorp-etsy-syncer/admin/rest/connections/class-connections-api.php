<?php
namespace platy\etsy\rest\connections;
use platy\etsy\EtsyDataService;

class ConnectionsRestController extends \WP_REST_Controller {
    /**
     *
     * @var int
     */
    private $current_shop;

    /**
     * @var EtsyDataService
     */
    private $service;
    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'connections';
        $this->service = EtsyDataService::get_instance();
        $this->current_shop = $this->service->get_current_shop();
    }

    public function register_routes() {

        \register_rest_route( $this->namespace, '/' . $this->resource_name . "/product-cat/(?P<id>[0-9]+)", array(
            array(
                'methods'   => 'PUT',
                'callback'  => function($request){
                    return $this->update_connection($request, "product_cat");
                },
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name . "/product-shipping-class/(?P<id>[0-9]+)", array(
            array(
                'methods'   => 'PUT',
                'callback'  => function($request){
                    return $this->update_connection($request, "product_shipping_class");
                },
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name  . "/(?P<id>[0-9]+)", array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'change_shop' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );   
        
    }
    
    public function update_connection($request, $type){
        $this->service->update_connection($request->get_json_params(),$type);
        return [];
    }

    public function get_connections($request, $type){
        return [];
    }

    public function permissions_check( $request ) {
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_manager' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have sufficient privileges' ), array( 'status' => 403 ) );
        }
        return true;
    }

   
  }
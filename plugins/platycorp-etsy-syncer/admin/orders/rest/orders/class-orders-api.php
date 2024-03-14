<?php

namespace platy\etsy\rest\orders;
use platy\etsy\orders\EtsyOrdersSyncer;
use platy\etsy\EtsySyncerException;

class OrdersRestController extends \WP_REST_Controller {
    /**
     * @var EtsyOrdersSyncer
     */
    private $syncer;
    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'etsy-orders';
        $this->syncer = new EtsyOrdersSyncer();
    }

    public function register_routes() {
        \register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_orders' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'PUT',
                'callback'  => array( $this, 'import_order' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

      }

    public function permissions_check( $request ) {
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_manager' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have sufficient privileges' ), array( 'status' => 403 ) );
        }
        return true;
    }

    public function get_orders($request){
        $params = $request->get_query_params();
        try{
            $receipts = $this->syncer->get_etsy_orders_by_date($params['min_created'], $params['_limit'], $params['_start']);
            return array_map(function($receipt){ return ['id' => $receipt['receipt_id']]; }, $receipts);
        }catch(EtsySyncerException $e){
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 404 ) );
        }
    }


    public function import_order($data){
        try{
            $this->syncer->sync_order($data['id']);
            return ['id' => $data['id']];
        }catch(EtsySyncerException $e){
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 404 ) );
        }
    }

  }
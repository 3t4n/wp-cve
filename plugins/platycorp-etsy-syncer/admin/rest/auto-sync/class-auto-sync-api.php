<?php

namespace platy\etsy\rest\autosync;
use platy\etsy\orders\EtsyOrdersSyncer;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsyDataService;

class AutoSyncRestController extends \WP_REST_Controller {
    /**
	 *
	 * @var EtsyDataService
	 */
	protected $data_service;

    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'auto-sync';
        $this->data_service = EtsyDataService::get_instance();
        
    }

    public function register_routes() {


        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/orders/(?P<rate>[a-zA-Z0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'PUT',
                'callback'  => array( $this, 'update_orders_sync' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/stock/(?P<rate>[a-zA-Z0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'PUT',
                'callback'  => array( $this, 'update_stock_sync' ),
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


    public function update_orders_sync($data){
        $syncer = new EtsyOrdersSyncer();
        try{
            $this->data_service->save_option("auto_sync_orders",$data['rate'],1, "order-settings");
            $syncer->update_cron_shcedule($data['rate']);
        }catch(EtsySyncerException $e){
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 404 ) );
        }
    }

    public function update_stock_sync($data){
        
        try{
            $schedule = $data['rate'];
            $shop_id = $this->data_service->get_current_shop_id();
            $timestamp = wp_next_scheduled( 'platy_etsy_stock_cron_hook', [$shop_id] );
            wp_unschedule_event( $timestamp, 'platy_etsy_stock_cron_hook', [$shop_id] );
            if($schedule != "none"){
                wp_schedule_event( time(), $schedule, "platy_etsy_stock_cron_hook", [$shop_id]);
            }
            $this->data_service->save_option("2w_stock_sync",$data['rate'],1, "stock_management");
        }catch(EtsySyncerException $e){
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 404 ) );
        }
    }


  }
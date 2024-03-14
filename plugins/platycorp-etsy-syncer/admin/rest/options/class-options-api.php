<?php
namespace platy\etsy\rest\options;
use platy\etsy\EtsySyncer;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsyDataService;

class OptionsRestController extends \WP_REST_Controller {
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
        $this->resource_name = 'etsy-options';
        $this->service = EtsyDataService::get_instance();
    }

    public function register_routes() {

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[a-zA-Z0-9_-]+)", array(
            array(
                'methods'   => 'PUT',
                'callback'  => function($request){
                    try{
                        $ret = $this->save_option($request);
                        return $ret;
                    }catch(EtsySyncerException $e){
                        return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 403 ) );
                    }
                    
                },
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

       
        
    }

    public function save_option($request){
        $params = $request->get_json_params();
        if(isset($params['group'])){
            $this->service->save_option_group($params['value'], $params['group'],  $params['shop_id']);
        }else{
            $this->service->save_option($params['id'], $params['value'], $params['shop_id']);

        }

        return $params;
    }


    public function permissions_check( $request ) {
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_manager' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have sufficient privileges' ), array( 'status' => 403 ) );
        }
        return true;
    }

   
  }
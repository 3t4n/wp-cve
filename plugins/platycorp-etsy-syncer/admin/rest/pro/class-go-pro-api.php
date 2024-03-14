<?php
namespace platy\etsy\rest\pro;
use platy\etsy\EtsySyncer;
use platy\etsy\PlatysService;
class ProRestController extends \WP_REST_Controller {

    
    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'go-pro';
    }

    
    public function register_routes() {

        \register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            array(
                'methods'   => 'POST',
                'callback'  => function($request){
                    $params = $request->get_json_params();
                    $platy =$params['platy'];
                    $lic = $params['license_key'];
                    $platy_service = PlatysService::get_instance();
                    $success = $platy_service->activate($lic, $platy);
                    return $success ? ['success' => true] : 
                        new \WP_Error( 'bad_license', esc_html__( 'License key invalid' ), array( 'status' => 403 ) );

                },
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
}

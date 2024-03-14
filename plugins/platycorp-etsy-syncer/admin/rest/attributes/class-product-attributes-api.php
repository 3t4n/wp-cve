<?php

namespace platy\etsy\rest\attributes;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsyDataService;
use platy\etsy\EtsyAttributesSyncer;

class EtsyProductAttributesController extends \WP_REST_Controller {
    /**
	 *
	 * @var EtsyDataService
	 */
	protected $data_service;

    private $syncer;
    
    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'product-attributes';
        $this->data_service = EtsyDataService::get_instance();
        $this->syncer = new EtsyAttributesSyncer();
    }

    public function register_routes() {


        

      }

    public function permissions_check( $request ) {
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_manager' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have sufficient privileges' ), array( 'status' => 403 ) );
        }
        return true;
    }


}
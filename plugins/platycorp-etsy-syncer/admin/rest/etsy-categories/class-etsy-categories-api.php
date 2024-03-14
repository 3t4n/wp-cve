<?php
namespace platy\etsy\rest\etsy\categories;
use platy\etsy\EtsySyncer;
use platy\etsy\EtsySyncerException;
use platy\etsy\NoAttributesException;
use platy\etsy\EtsyAttributesSyncer;
use platy\etsy\EtsyDataService;

class EtsyCategoriesApi extends \WP_REST_Controller {
    /**
     *
     * @var int
     */
    private $current_shop;

    /**
     * @var EtsySyncer
     */

    protected $data_service;
    private $syncer;
    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'etsy-categories';
        $this->data_service = EtsyDataService::get_instance();
        $this->syncer = new EtsyAttributesSyncer();
    }

    public function register_routes() {

        \register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            array(
                'methods'   => 'GET',
                'callback'  => function($request){ 
                    try{
                        $cats = $this->syncer->get_etsy_taxonomy_tree();
                    }catch(EtsySyncerException $e){
                        return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 400 ) );
                    }
                    $ret = [];
                    $ret = $this->clean_results($cats, $ret);
                    return $ret;
                },
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_product_attributes' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'PUT',
                'callback'  => function($data) {
                    $this->data_service->update_attribute($data['id'], $data['attributes']);
                },
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );


        
    }

    protected function clean_results($cats, &$ret){
        foreach($cats as $cat){
            $cleaned = ['id' => $cat['id'], "name" => EtsySyncer::clean_name($cat['name'])];
            if(!empty($cat['children'])){
                $children = [];
                $cleaned['children'] = $this->clean_results($cat['children'], $children);
            }
            $ret[] = $cleaned;
        }
        return $ret;
    }


    public function permissions_check( $request ) {
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_manager' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have sufficient privileges' ), array( 'status' => 403 ) );
        }
        return true;
    }

    public function get_product_attributes($data) {
        try {
            $attrs_from_db = $this->data_service->load_attributes($data['id']);
        }catch(NoAttributesException $e) {
            $attrs_from_db = [];
        }

        try {
            $attrs_from_api = $this->syncer->get_taxonomy_attributes($data['id']);
            $attrs = [];
            foreach($attrs_from_api as $api_attr) {
                $prop_id = $api_attr['property_id'];
                $db_attr = @$attrs_from_db[$prop_id];
                if(empty($db_attr)) {
                    $db_attr = [];
                }
                $attrs[] = array_merge($api_attr, $db_attr);
            }

            return ['id' => $data['id'], 'attributes' => $attrs];
        }catch(EtsySyncerException $e) {
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 403 ) );
        }
    }

   
  }
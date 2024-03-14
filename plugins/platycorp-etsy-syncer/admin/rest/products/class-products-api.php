<?php

namespace platy\etsy\rest\products;
use platy\etsy\EtsySyncer;
use platy\etsy\EtsySyncerException;
use platy\etsy\LinkingException;
use platy\etsy\NoSuchPostException;
use platy\etsy\logs\PlatySyncerLogger;
use platy\etsy\logs\PlatyLogger;

class ProductRestController extends \WP_REST_Controller {
    /**
     * @var EtsySyncer
     */
    private $syncer;

    /**
     * @var PlatySyncerLogger
     */
    private $logger;

    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'etsy-products';
        $this->syncer = new EtsySyncer();
        $this->logger = PlatySyncerLogger::get_instance();
    }

    public function register_routes() {
        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'PUT',
                'callback'  => array( $this, 'sync_product' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'link_products' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'DELETE',
                'callback'  => array( $this, 'delete_product' ),
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

    public function delete_product($request){
        try{

            $this->syncer->delete_item($request['id']);
            $this->logger->delete_log($request['id'], $this->syncer->get_shop_id());
            $this->logger->delete_child_logs($request['id'], $this->syncer->get_shop_id());
            $this->logger->delete_meta_logs($request['id'], $this->syncer->get_shop_id());
        }catch(EtsySyncerException $e){
            
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 403 ) );
        }
        return [];
    }

    public function link_products($data) {
        try {
            $offset = (int) $data['offset'];
            $limit = (int) $data['limit'];
            $state = $data['state'];
            $etsy_listings = $this->syncer->get_etsy_listings($offset, $limit, $state);
            $ret = [];
            $logger = PlatyLogger::get_instance();
            foreach($etsy_listings as $etsy_listing) {
                try {
                    $listing_id = $etsy_listing['listing_id'];
                    $post_id = $this->link_product($etsy_listing);
                    $logger->log_success("Linked listing $listing_id to $post_id", "etsy_linking", 
                        $this->syncer->get_shop_id());
                    $ret[] = ["id" => $listing_id, "success" => true];

                }catch(LinkingException $e){
                    $error = $e->getMessage();
                    $logger->log_error("Failed linking $listing_id: $error", "etsy_linking", 
                        $this->syncer->get_shop_id());
                    $ret[] = ["id" => $listing_id, "success" => false];
                }
            }
        }catch(EtsySyncerException $e){
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 403 ) );
        }
        return $ret;
    }

    private function link_product($etsy_listing) {
        $skus = $etsy_listing['skus'];
        $etsy_id = $etsy_listing['listing_id'];
        return $this->syncer->link_etsy_item($skus, $etsy_id);
    }

    public function sync_product($data){
        try{
            $listing_id = $this->syncer->sync_product($data['id'], $data->get_json_params()['template_id'], $data['mask']);
            return [];
        }catch(EtsySyncerException $e){
            $post_id = $data['id'];
            if(!empty($post_id)){
                $this->logger->log_error($data['id'],$e->getMessage(), $e->get_listing_id(), $this->syncer->get_shop_id(), 'product');
            }
            return new \WP_Error( 'platy_syncer_error', $e->getMessage(), array( 'status' => 403 ) );
        }catch(\Exception | \Error $e) {
            return new \WP_Error( 'platy_syncer_error', $e->getMessage() . "\n" . $e->getTraceAsString(), array( 'status' => 403 ) );
        }
    }
  }
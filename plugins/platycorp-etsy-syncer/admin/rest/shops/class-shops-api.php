<?php
namespace platy\etsy\rest\shops;
use platy\etsy\EtsySyncer;
use platy\etsy\NoSuchShopException;
use platy\etsy\EtsySyncerException;
use platy\etsy\AuthenticationException;
use platy\etsy\EtsyDataService;

class ShopsRestController extends \WP_REST_Controller {

    /**
     * @var EtsyDataService
     */
    private $service;

    /**
     * @var EtsySyncer
     */
    private $syncer;
    public function __construct() {
        $this->namespace     = 'platy-syncer/v1';
        $this->resource_name = 'etsy-shops';
        $this->syncer = new EtsySyncer();
        $this->service = EtsyDataService::get_instance();

    }

    public function register_routes() {
        \register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'save_shop' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );


        \register_rest_route( $this->namespace, '/' . $this->resource_name  . "/(?P<id>[0-9]+)", array(
            array(
                'methods'   => 'PUT',
                'callback'  => array( $this, 'change_shop' ),
                'permission_callback' => array( $this, 'permissions_check' ),
            )
        ) );


        \register_rest_route( $this->namespace, '/' . $this->resource_name . "/(?P<id>.+)", array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_shop' ),
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

    public function change_shop($data){
        $this->service->save_default_shop($data['id']);
        return [];
    }

    public function save_shop($data){
        $json = $data->get_json_params();
        try{
            $verification_code = $json['verificationCode'];
            $user_id = $json['user_id'];
            $tempCredentials = $json['tempCredentials'];
            $token = $tempCredentials['oauth_token'];
            $secret = $tempCredentials['oauth_secret'];
            $credentials = $this->syncer->authenticate_user($verification_code,$token,$secret);
            $shop= [
                'id' => $json['id'], 
                "name" => $json['name'], 
                'user_id' => $user_id, 
                'identifier'=> $credentials->getIdentifier(), 
                'secret' => $credentials->getSecret()];
            $this->service->save_shop($shop);
            $this->service->save_default_shop($shop['id']);
            return $shop;
        }catch(AuthenticationException $e){
            return new \WP_Error( 'auth_error', esc_html__( 'Couldnt authenticate' ), array( 'status' => 403 ) );
        }
    }

    public function get_shop($data){
        try{
            $shop = $this->syncer->get_etsy_shop_by_name($data['id']);
            $shop_id = $shop['shop_id'];
            $user_id = $shop['user_id'];
            $shop_label = $shop['shop_name'];
            $details = ["id" => $shop_id, "name" => EtsySyncer::clean_name($shop_label), 'user_id' => $user_id];
            $details['oauth_url'] = $this->syncer->get_oauth_url($details['id'], $details['name'], $details['user_id']);
            return $details;
        }catch(EtsySyncerException $e){
            return new \WP_Error( 'no_such_etsy_shop', esc_html__( $e->getMessage() ), array( 'status' => 404 ) );
        }
    }
  }
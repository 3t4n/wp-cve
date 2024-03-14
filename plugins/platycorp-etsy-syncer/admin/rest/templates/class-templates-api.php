<?php
namespace platy\etsy\rest\templates;
use platy\etsy\EtsyDataService;
use platy\etsy\DuplicateTemplateNameException;

class TemplatesRestController extends \WP_REST_Controller {
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
        $this->resource_name = 'etsy-templates';
        $this->service = EtsyDataService::get_instance();
    }

    public function register_routes() {

        \register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            array(
                'methods'   => 'POST',
                'callback'  => function($request){
                    $params = $request->get_json_params();
                    $template = ['shop_id' => $this->service->get_current_shop_id() , 'name' => $params['name']];
                    try{
                        $params['id'] = $this->service->add_template($template);
                    }catch(DuplicateTemplateNameException $e){
                        return new \WP_Error( 'template_error', esc_html__( 'Duplicate template name' ), array( 'status' => 403 ) );
                    }catch(Exception $e){
                        return new \WP_Error( 'template_error', esc_html__( $e->getMessage() ), array( 'status' => 403 ) );
                    }

                    if(empty($params['id']))  return new \WP_Error( 'template_error', esc_html__( 'Couldnt add template' ), array( 'status' => 403 ) );
                    
                    $this->update_template_metas($params);

                    return $params;
                   
                },
                'permission_callback' => array( $this, 'permissions_check' )
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            array(
                'methods'   => 'PUT',
                'callback'  => function($request){
                    $params = $request->get_json_params();
                    $template = ['shop_id' => $this->service->get_current_shop_id() , 'name' => $params['name']];
                    
                    try{
                        $this->service->update_template($request['id'], $template);
                    }catch(DuplicateTemplateNameException $e){
                        return new \WP_Error( 'template_error', esc_html__( 'Duplicate template name' ), array( 'status' => 403 ) );
                    }

                    $this->update_template_metas($params);
                    return $params;
                },
                'permission_callback' => array( $this, 'permissions_check' )
            )
        ) );

        \register_rest_route( $this->namespace, '/' . $this->resource_name ."/(?P<id>[0-9]+)", array(
            array(
                'methods'   => 'DELETE',
                'callback'  => function($request){

                    $this->service->delete_template($request['id']);
                    return [];
                },
                'permission_callback' => array( $this, 'permissions_check' )
            )
        ) );

       
        
    }


    public function update_template_metas($params){
        $meta = $params;
        $id = $meta['id'];
        unset($meta['name']);
        unset($meta['id']);
        foreach($meta as $m => $v){
            $this->service->update_template_meta($id, $m, $v);
        }
    }

    public function permissions_check( $request ) {
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'shop_manager' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have sufficient privileges' ), array( 'status' => 403 ) );
        }
        return true;
    }

   
  }
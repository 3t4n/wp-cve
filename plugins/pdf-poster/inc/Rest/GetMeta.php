<?php
namespace PDFPro\Rest;

use PDFPro\Model\Block;
use PDFPro\Model\AdvanceSystem;

class GetMeta{
    public $route = '';
    function __construct(){
        $this->route = '/single(?:/(?P<id>\d+))?';
        add_action('rest_api_init', [$this, 'single_doc']);
    }

    public function single_doc(){
        register_rest_route( 'pdfposter/v1',
        $this->route,
            [
                'methods' => 'GET',
                'callback' => [$this, 'single_doc_callback'],
                'permission_callback' => '__return_true',
            ] 
        );
    }

    function single_doc_callback(\WP_REST_Request $request){
        $response = [];
        $params = $request->get_params();
        $id = $params['id'];

        $data = $this->get_data($id);
        // $video = ['data' => 'no data available'];
       

        return new \WP_REST_Response($data);
    }


    public function get_data( $id = 2038){

        $blocks =  Block::getBlock($id);
        if(isset($blocks[0])){
            $data = AdvanceSystem::getData($blocks[0]);
            if(isset($data['template'])){
                return $data['template'];
            }
        }
        return false;

    }

    public static function meta($id, $key, $default = null){
        if (metadata_exists('post', $id, $key)) {
            $value = get_post_meta($id, $key, true);
            if ($value != '') {
                return $value;
            } else {
                return $default;
            }
        } else {
            return $default;
        }
    }

 
}

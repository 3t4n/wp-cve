<?php 
namespace YTP\Model;

use YTP\Helper\Import;

class Ajax{


    protected static $_instance = null;
    private $params = [];
    private $requestType; 
    private $requestMethod;
    private $requestModel;
    private $namespace = 'YTP\Model\\';
    private $model;

    public function register(){
        add_action('wp_ajax_nopriv_ytp_import_data', [$this, 'ytp_import_data']);
        add_action('wp_ajax_ytp_import_data', [$this, 'ytp_import_data']);

        add_action('wp_ajax_ytp_ajax', [$this, 'prepareAjax']);
    }

    public static function instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }   

    public function isset($array, $key, $default = false){
        if(isset($array[$key])){
            return $array[$key];
        }
        return $default;
    }

    public function prepareAjax(){
        if(isset($_GET['nonce'])){
            $this->params = $_GET;
            $this->requestType = 'POST';
        } else {
            $this->params = $_POST;
            $this->requestType = 'GET';
        }
        echo wp_kses_post(wp_json_encode($this->proceedRequest()));
        die();
    }

    public function proceedRequest(){
        $data = $this->params;
        $nonce = $this->isset($data,'nonce');

        if(!wp_verify_nonce( $nonce, 'wp_ajax' )){
            return new \WP_Error('invalid', 'invalid nonce');
        }

        $this->requestModel = $this->isset($data, 'model', 'Model');
        $this->requestMethod = $this->isset($data, 'method', 'invalid');
        $this->model = $this->namespace.$this->requestModel;
        
        unset($this->params['method']);
        unset($this->params['action']);
        unset($this->params['nonce']);
        unset($this->params['model']);
        
        if(method_exists($this, $this->requestMethod)){
            return new \WP_REST_Response($this->{$this->requestMethod}($this->params), 200);
        }
        
        if(!class_exists($this->namespace.$this->requestModel)){
            return new \WP_Error('invalid', $this->namespace.$this->requestModel);
            return $this->invalid();
        }

        $model = new $this->model();

        if(method_exists($model, $this->requestMethod)){
            return new \WP_REST_Response($model->{$this->requestMethod}($this->params), 200);
        }else {
            return new \WP_Error('invalid', 'Invalid Request');
            return false;
        }
    }

    public function invalid(){
       return new \WP_REST_Response('invalid request', 400);
    }

    public function savePreset(){
        global $wpdb;
        return $this->params['preset'];

    }

    public function ytp_import_data(){
        Import::meta();
        Import::option();
        echo wp_json_encode(["success" => true]);
        die();
    }
}
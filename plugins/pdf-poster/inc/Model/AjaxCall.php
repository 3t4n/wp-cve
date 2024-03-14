<?php
namespace PDFPro\Model;

class AjaxCall{

    protected static $_instance = null;
    private $params = [];
    private $requestType; 
    private $requestMethod;
    private $requestModel;
    private $namespace = 'PDFPro\Model\\';
    private $model;

    public function register(){
        add_action('wp_ajax_pdf_poster_ajax', [$this, 'prepareAjax']);
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
        echo wp_json_encode($this->proceedRequest());
        die();
    }

    public function proceedRequest(){
        $data = $this->params;
        $nonce = $this->isset($data,'nonce');

        $this->requestModel = $this->isset($data, 'model', 'Model');
        $this->requestMethod = $this->isset($data, 'method', 'invalid');
        if(!class_exists($this->namespace.$this->requestModel)){
            return $this->invalid();
        }
        $this->model = $this->namespace.$this->requestModel;
        $model = new $this->model();

        if(wp_verify_nonce( $nonce, 'wp_ajax' ) && method_exists($model, $this->requestMethod)){
            unset($this->params['method']);
            unset($this->params['action']);
            unset($this->params['nonce']);
            unset($this->params['model']);
            return $model->{$this->requestMethod}($this->params);
        }else {
            return $this->invalid();
        }
    }

    public function invalid(){
       return new \WP_REST_Response('invalid request', 400);
    }

}

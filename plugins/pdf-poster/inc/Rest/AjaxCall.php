<?php
namespace PDFPro\Rest;
use PDFPro\Model\Import;

class AjaxCall{
    protected static $_instance = null;

    public function __construct(){
        add_action('wp_ajax_fpdf_active_licence_key', [$this, 'fpdf_active_licence_key']);
        add_action('wp_ajax_fpdf_import_data', [$this, 'fpdf_import_data']);
    }

    /**
     * Create Instance
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //add licencek key to database / ajax call
    function fpdf_active_licence_key(){
        $licence_key = sanitize_text_field( $_GET['license_key']);
        $activated =  sanitize_text_field( $_GET['activated']);
        $result = update_option('flcbplsc', array(
            'key' => $licence_key,
            'active' => $activated
        ));
        echo $result;
        die();
    }


    //add licencek key to database / ajax call
    function fpdf_import_data(){
        $meta = Import::meta();
        $settings = Import::settings();
        echo wp_json_encode(['meta' => $meta, 'settings' => $settings, 'success' => true]);
        die();
    }
}

AjaxCall::instance();
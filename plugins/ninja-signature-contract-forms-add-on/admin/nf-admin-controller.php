<?php

if (!defined('ABSPATH'))
    exit;

class esig_NF_Ajax_Controller extends NF_Abstracts_Controller {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {
        add_filter("ninja_forms_save_form", array($this, "publish_ninja_form"), 999, 1);
    }

    public function publish_ninja_form($formId) {
        
         $this->errors[] = __( 'Form Not Found', 'ninja-forms' );
       
    }

}

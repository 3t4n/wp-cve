<?php
namespace IfSo\Addons\Base;
require_once (__DIR__ . '/extension-initializer-base.class.php');
require_once (__DIR__ . '/extension-main-base.class.php');

class Extension{
    protected static $instance;
    protected $default_initializer;

    public function __construct($plugin_main_class=null,$custom_init=null){
        $this->set_default_initializer();
        if($plugin_main_class!==null && class_exists($plugin_main_class)){
            $main = $plugin_main_class::get_instance();
            $upd_settings = $main->get_update_settings();
            if($custom_init!==null)
                $init = new $custom_init($main,$upd_settings);
            else
                $init = new $this->default_initializer($main,$upd_settings);
        }
    }

    protected function set_default_initializer(){
        $this->default_initializer = ExtensionInitializer::class;
    }
}

class UpdateSettings{
    public $url;
    public $slug;
    public $main_file;
    public function __construct($slug,$url,$main_file){
        $this->url=$url;
        $this->slug=$slug;
        $this->main_file = $main_file;
    }
}
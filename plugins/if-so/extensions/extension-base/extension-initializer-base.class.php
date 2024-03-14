<?php
namespace IfSo\Addons\Base;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class ExtensionInitializer{
    protected $extension_instance;

    public function __construct($plugin_main,$update_settings){
        $this->check_for_updates($update_settings);
    }

    private function check_for_updates($update_settings){
        if(!empty($update_settings)){
            require_once(__DIR__ . '/lib/plugin-update-checker/plugin-update-checker.php');
            try{
                $myUpdateChecker = PucFactory::buildUpdateChecker(      //Check for updates
                    $update_settings->url,
                    $update_settings->main_file,
                    $update_settings->slug
                );
            }
            catch(Exception $e){
            }
        }
    }

}
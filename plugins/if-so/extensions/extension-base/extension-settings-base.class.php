<?php
namespace IfSo\Addons\Base;

use IfSo\Services\PluginSettingsService;

require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

class Settings{
    public function __construct(){
        if(method_exists($this,'print_extra_settings_ui'))
            add_action('ifso_extra_settings_display_ui',[$this,'print_extra_settings_ui']);
        if(method_exists($this,'print_extra_settings_ui_geolocation'))
            add_action('ifso_extra_settings_display_ui_geolocation',[$this,'print_extra_settings_ui_geolocation']);
        add_filter('ifso_extra_settings_options',[$this,'register_extra_settings'],10,1);
    }

    public function register_extra_settings($extra_settings){
        return $extra_settings;
    }

}
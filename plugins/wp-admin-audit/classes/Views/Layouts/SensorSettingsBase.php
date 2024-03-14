<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_SensorSettingsBase implements WADA_Layout_SettingLayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-sensor-settings';
    public $sensor;
    
    public function __construct($sensor = null){
        if($sensor){
            $this->sensor = $sensor;
        }
    }

    public function display($returnAsString = false){ // overwritten in the subclasses to show a less generic layout
        if($returnAsString){
            ob_start();
        }
        echo wp_kses_post($this->renderDefaultSettingsAsRows());
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }

    protected function renderDefaultSettingsAsRows(){
        return ''; // nothing as default
    }

    public function getSettingIds(){
        return false; // nothing as default
    }

    /**
     * @param stdClass $sensor
     * @return WADA_Layout_SensorSettingsBase
     */
    public static function getSensorSettingsLayout($sensor){
        switch ($sensor->id) {
            case WADA_Sensor_Base::EVT_OPTION_UPDATE_CORE:
            case WADA_Sensor_Base::EVT_OPTION_UPDATE_OTHER:
                $layout = new WADA_Layout_OptionSensorSettings($sensor);
                break;
            default:
                $layout = new self($sensor);
        }
        return apply_filters('wp_admin_audit_html_sensor_settings_layout', $layout, $sensor);
    }
}
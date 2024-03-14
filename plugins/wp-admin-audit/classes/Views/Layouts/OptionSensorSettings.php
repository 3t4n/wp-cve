<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_OptionSensorSettings extends WADA_Layout_SensorSettingsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-option-sensor-settings';

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }

    ?>

    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }

    public function getSettingIds(){
    }
}
<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_FileEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-file-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        if($this->event->object_id > 0) {
            $title = sprintf(__('%s ID %d', 'wp-admin-audit'), __('File', 'wp-admin-audit'), $this->event->object_id);
        }else{
            $title = sprintf(__('%s event', 'wp-admin-audit'),  __('File', 'wp-admin-audit'));
        }
        $subtitle = '';
        $fileName = '';
        $fileNameInfo = $this->extractEventInfoFromArray($this->event->infos, 'file');
        if($fileNameInfo){
            $fileName = $fileNameInfo->info_value;
        }

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_FILE_THEME_FILE_EDIT:
                $title = sprintf(__('Theme file %s edited', 'wp-admin-audit'), $fileName);
                break;
            case WADA_Sensor_Base::EVT_FILE_PLUGIN_FILE_EDIT:
                $title = sprintf(__('Plugin file %s edited', 'wp-admin-audit'), $fileName);
                break;
        }
        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){
        return array(
            array('info_key' => 'content', 'callback' => array($this, 'base64ContentRenderFileContentDiff'))
        );
    }

    public function base64ContentRenderFileContentDiff($field, $infoValue, $priorValue){
        $infoValue = (base64_decode($infoValue));
        $priorValue = (base64_decode($priorValue));
        $showPriorValue = $this->isInDifferingRenderingPriorValueNeeded();
        return $this->renderFileContentDiff($field, $infoValue, $priorValue, $showPriorValue);
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            list($title, $subtitle) = $this->getEventTitleAndSubtitle();
            $specialInfoKeys = $this->getSpecialInfoKeys();
            $showPriorValue = $this->isInDifferingRenderingPriorValueNeeded();

            $additionalParams = array();
            switch($this->event->sensor_id){
                case WADA_Sensor_Base::EVT_FILE_THEME_FILE_EDIT:
                case WADA_Sensor_Base::EVT_FILE_PLUGIN_FILE_EDIT:
                    if(!$showPriorValue){
                        $additionalParams[] = 'ONLY_SINGLE_VALUE';
                    }
            }

            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys, $additionalParams);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}
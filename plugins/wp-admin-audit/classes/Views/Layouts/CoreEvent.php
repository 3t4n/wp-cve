<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_CoreEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-core-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        $title = __('WordPress core', 'wp-admin-audit');
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_CORE_UPDATE:
                $versionAfter = $this->extractValueFromInfoArray($this->event->infos, 'CORE_VERSION');
                $versionPrior = $this->extractPriorValueFromInfoArray($this->event->infos, 'CORE_VERSION');
                if($versionAfter && $versionPrior){
                    $title = sprintf(__('WordPress core updated from version %s to %s', 'wp-admin-audit'), $versionPrior, $versionAfter);
                }else if($versionAfter){
                    $title = sprintf(__('WordPress core updated to version %s', 'wp-admin-audit'), $versionAfter);
                }else if($versionPrior){
                    $title = sprintf(__('WordPress core updated from version %s', 'wp-admin-audit'), $versionPrior);
                }else{
                    $title = __('WordPress core updated', 'wp-admin-audit');
                }
                $updateSuccess = $this->extractValueFromInfoArray($this->event->infos, 'OP_SUCCESS');
                $subtitle = (!is_null($updateSuccess) ? ((intval($updateSuccess) > 0) ? __('Update successful', 'wp-admin-audit') : __('Update failed', 'wp-admin-audit')) : '');
                break;
        }
        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){
        return array(
            array('info_key' => 'OP_SUCCESS', 'callback' => array($this, 'skipLine'))
        );
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
            $this->renderTitleAndDefaultEventInfos($title, $subtitle, $specialInfoKeys);
            ?>
        </div>
    <?php
        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}
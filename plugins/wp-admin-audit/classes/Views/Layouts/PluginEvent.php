<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_PluginEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-plugin-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        $pluginName = $this->extractValueFromInfoArray($this->event->infos, 'Name', '');
        $title = sprintf(__('Plugin %s', 'wp-admin-audit'), $pluginName);
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_PLUGIN_INSTALL:
                $pluginVersion = $this->extractValueFromInfoArray($this->event->infos, 'PLUGIN_VERSION');
                $installSuccess = $this->extractValueFromInfoArray($this->event->infos, 'OP_SUCCESS');
                if(!is_null($installSuccess) && (intval($installSuccess) > 0)){
                    $title = sprintf(__('Plugin %s (version %s) was installed', 'wp-admin-audit'), $pluginName, $pluginVersion);
                    $subtitle =  __('Installation successful', 'wp-admin-audit');
                }elseif(!is_null($installSuccess) && (intval($installSuccess) == 0)){
                    $title = sprintf(__('Plugin %s (version %s) installation failed', 'wp-admin-audit'), $pluginName, $pluginVersion);
                    $subtitle = __('Installation failed', 'wp-admin-audit');
                    $errorMsg = $this->extractValueFromInfoArray($this->event->infos, 'ERROR_MESSAGES');
                    if($errorMsg){
                        $subtitle .= ': '.$errorMsg;
                    }
                }else{
                    $title = sprintf(__('Plugin %s (version %s) installation', 'wp-admin-audit'), $pluginName, $pluginVersion);
                    $subtitle = '';
                }
                break;
            case WADA_Sensor_Base::EVT_PLUGIN_DELETE:
                $title = sprintf(__('Plugin %s was deleted', 'wp-admin-audit'), $pluginName);
                $deletedSuccess = $this->extractValueFromInfoArray($this->event->infos, 'DELETION_RESULT');
                if(!is_null($deletedSuccess)){
                    if($deletedSuccess == 1){
                        $subtitle = __('Deletion successful', 'wp-admin-audit');
                    }else{
                        $subtitle = __('Deletion failed', 'wp-admin-audit');
                    }
                }
                break;
            case WADA_Sensor_Base::EVT_PLUGIN_ACTIVATE:
                $title = sprintf(__('Plugin %s was activated', 'wp-admin-audit'), $pluginName);
                break;
            case WADA_Sensor_Base::EVT_PLUGIN_DEACTIVATE:
                $title = sprintf(__('Plugin %s was deactivated', 'wp-admin-audit'), $pluginName);
                break;
            case WADA_Sensor_Base::EVT_PLUGIN_UPDATE:
                $pluginVersionAfter = $this->extractValueFromInfoArray($this->event->infos, 'PLUGIN_VERSION');
                $pluginVersionPrior = $this->extractPriorValueFromInfoArray($this->event->infos, 'PLUGIN_VERSION');
                if($pluginVersionAfter && $pluginVersionPrior){
                    if(version_compare($pluginVersionAfter, $pluginVersionPrior, '>')){
                        $title = sprintf(__('Plugin %s updated from version %s to %s', 'wp-admin-audit'), $pluginName, $pluginVersionPrior, $pluginVersionAfter);
                    }else{
                        if(version_compare($pluginVersionAfter, $pluginVersionPrior, 'eq')){
                            $title = sprintf(__('Plugin %s re-installed with version %s', 'wp-admin-audit'), $pluginName, $pluginVersionAfter);
                        }else {
                            $title = sprintf(__('Plugin %s downgraded from version %s to %s', 'wp-admin-audit'), $pluginName, $pluginVersionPrior, $pluginVersionAfter);
                        }
                    }
                }else if($pluginVersionAfter){
                    $title = sprintf(__('Plugin %s updated to version %s', 'wp-admin-audit'), $pluginName, $pluginVersionAfter);
                }else if($pluginVersionPrior){
                    $title = sprintf(__('Plugin %s updated from version %s', 'wp-admin-audit'), $pluginName, $pluginVersionPrior);
                }else{
                    $title = sprintf(__('Plugin %s updated', 'wp-admin-audit'), $pluginName);
                }
                $updateSuccess = $this->extractValueFromInfoArray($this->event->infos, 'OP_SUCCESS');
                $subtitle = (!is_null($updateSuccess) ? ((intval($updateSuccess) > 0) ? __('Update successful', 'wp-admin-audit') : __('Update failed', 'wp-admin-audit')) : '');
                break;
        }
        return array($title, $subtitle);
    }

    public function getSpecialInfoKeys(){
        return array(
            array('info_key' => 'DELETION_RESULT', 'callback' => array($this, 'skipLine')),
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
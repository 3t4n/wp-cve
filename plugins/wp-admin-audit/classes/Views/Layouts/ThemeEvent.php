<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_ThemeEvent extends WADA_Layout_EventDetailsBase
{
    const LAYOUT_IDENTIFIER = 'wada-layout-theme-event';

    public function getEventTitleAndSubtitle(){ // overwrites parent
        $themeSlug = $this->extractValueFromInfoArray($this->event->infos, 'THEME_SLUG', '');
        $themeName = $this->extractValueFromInfoArray($this->event->infos, 'Name', $themeSlug); // take slug as backup if no name found
        $priorThemeName = $this->extractPriorValueFromInfoArray($this->event->infos, 'Name', '');
        $title = sprintf(__('Theme %s', 'wp-admin-audit'), $themeName);
        $subtitle = '';

        switch($this->event->sensor_id){
            case WADA_Sensor_Base::EVT_THEME_INSTALL:
                $themeVersion = $this->extractValueFromInfoArray($this->event->infos, 'THEME_VERSION');
                $installSuccess = $this->extractValueFromInfoArray($this->event->infos, 'OP_SUCCESS');
                if(!is_null($installSuccess) && (intval($installSuccess) > 0)){
                    $title =  sprintf(__('Theme %s (version %s) was installed', 'wp-admin-audit'), $themeName, $themeVersion);
                    $subtitle =  __('Installation successful', 'wp-admin-audit');
                }elseif(!is_null($installSuccess) && (intval($installSuccess) == 0)){
                    $title =  sprintf(__('Theme %s (version %s) installation failed', 'wp-admin-audit'), $themeName, $themeVersion);
                    $subtitle = __('Installation failed', 'wp-admin-audit');
                    $errorMsg = $this->extractValueFromInfoArray($this->event->infos, 'ERROR_MESSAGES');
                    if($errorMsg){
                        $subtitle .= ': '.$errorMsg;
                    }
                }else{
                    $title = sprintf(__('Theme %s (version %s) installation', 'wp-admin-audit'), $themeName, $themeVersion);
                    $subtitle = '';
                }
                break;
            case WADA_Sensor_Base::EVT_THEME_DELETE:
                $title = sprintf(__('Theme %s was deleted', 'wp-admin-audit'), $themeName);
                $deletedSuccess = $this->extractValueFromInfoArray($this->event->infos, 'DELETION_RESULT');
                if(!is_null($deletedSuccess)){
                    if(intval($deletedSuccess) > 0){
                        $subtitle = __('Deletion successful', 'wp-admin-audit');
                    }else{
                        $subtitle = __('Deletion failed', 'wp-admin-audit');
                    }
                }
                break;
            case WADA_Sensor_Base::EVT_THEME_SWITCH:
                $title = sprintf(__('Theme switch from %s to %s', 'wp-admin-audit'), $priorThemeName, $themeName);
                break;
            case WADA_Sensor_Base::EVT_THEME_UPDATE:
                $themeVersionAfter = $this->extractValueFromInfoArray($this->event->infos, 'THEME_VERSION');
                $themeVersionPrior = $this->extractPriorValueFromInfoArray($this->event->infos, 'THEME_VERSION');
                if($themeVersionAfter && $themeVersionPrior){
                    if(version_compare($themeVersionAfter, $themeVersionPrior, '>')){
                        $title = sprintf(__('Theme %s updated from version %s to %s', 'wp-admin-audit'), $themeName, $themeVersionPrior, $themeVersionAfter);
                    }else{
                        if(version_compare($themeVersionAfter, $themeVersionPrior, 'eq')){
                            $title = sprintf(__('Theme %s re-installed with version %s', 'wp-admin-audit'), $themeName, $themeVersionAfter);
                        }else{
                            $title = sprintf(__('Theme %s downgraded from version %s to %s', 'wp-admin-audit'), $themeName, $themeVersionPrior, $themeVersionAfter);
                        }
                    }
                }else if($themeVersionAfter){
                    $title = sprintf(__('Theme %s updated to version %s', 'wp-admin-audit'), $themeName, $themeVersionAfter);
                }else if($themeVersionPrior){
                    $title = sprintf(__('Theme %s updated from version %s', 'wp-admin-audit'), $themeName, $themeVersionPrior);
                }else{
                    $title = sprintf(__('Theme %s updated', 'wp-admin-audit'), $themeName);
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
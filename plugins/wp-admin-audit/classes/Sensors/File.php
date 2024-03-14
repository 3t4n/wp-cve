<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_File extends WADA_Sensor_Post
{
    public $firstContext = null;

    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_FILE);
    }

    public function registerSensor(){
        add_action('wp_ajax_edit-theme-plugin-file', array($this, 'onEditThemePluginFile'), 0); // need to use priority <= 1, because the default callback for wp_ajax_edit-theme-plugin-file has priority 1 and calls wp_send_json_success() or wp_send_json_error() (stopping execution)

        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    public function onEditThemePluginFile(){
        $file = array_key_exists('file', $_REQUEST) ? sanitize_text_field($_REQUEST['file']) : null;
        $plugin = array_key_exists('plugin', $_REQUEST) ? sanitize_text_field($_REQUEST['plugin']) : null;
        $theme = array_key_exists('theme', $_REQUEST) ? sanitize_text_field($_REQUEST['theme']) : null;
        $nonce = array_key_exists('nonce', $_REQUEST) ? sanitize_text_field($_REQUEST['nonce']) : null;
        $referer = array_key_exists('_wp_http_referer', $_REQUEST) ? sanitize_text_field($_REQUEST['_wp_http_referer']) : null;
        $editorType = $referer ? parse_url($referer, PHP_URL_PATH) : null;
        $editorType = $editorType ? basename($editorType, '.php') : null;

        if(!$editorType)
            return false;

        $res = false;
        $nonceOkay = (
            (($editorType === 'plugin-editor') && (wp_verify_nonce($nonce, 'edit-plugin_'.$file)))
            || (($editorType === 'theme-editor') && (wp_verify_nonce($nonce, 'edit-theme_'.$theme.'_'.$file)))
        );

        $fileEditPermissionOkay = (
            (($editorType === 'plugin-editor') && current_user_can('edit_plugins'))
            || (($editorType === 'theme-editor') && current_user_can('edit_themes'))
        );

        if($nonceOkay && $fileEditPermissionOkay){
            WADA_Log::debug('onEditThemePluginFile file: '.$file);
            WADA_Log::debug('onEditThemePluginFile referer: '.$referer);
            WADA_Log::debug('onEditThemePluginFile editorType: '.$editorType);

            $newContent = array_key_exists('newcontent', $_REQUEST) ? wp_unslash($_REQUEST['newcontent']) : null;

            $sensorId = null;
            $realFile = null;
            $infos = array();
            $infos[] = self::getEventInfoElement('editor', $editorType);
            if($editorType === 'plugin-editor'){
                $realFile = WP_PLUGIN_DIR . '/' . $file;
                WADA_Log::debug('onEditThemePluginFile PLUGIN edit: '.$plugin.' / file: '.$file);
                $infos[] = self::getEventInfoElement('plugin', $plugin);
                $sensorId = WADA_Sensor_Base::EVT_FILE_PLUGIN_FILE_EDIT;
            }elseif($editorType === 'theme-editor'){
                $themeObj = wp_get_theme($theme);
                $realFile = $themeObj ? ($themeObj->get_stylesheet_directory() . '/' . $file) : null;
                WADA_Log::debug('onEditThemePluginFile THEME edit: '.$theme.' / file: '.$file);
                $infos[] = self::getEventInfoElement('theme', $theme);
                $sensorId = WADA_Sensor_Base::EVT_FILE_THEME_FILE_EDIT;
            }

            if($sensorId){
                if(!$this->isActiveSensor($sensorId)) return $this->skipEvent($sensorId);

                $previousContent = null;
                if($realFile && is_file($realFile)){
                    $previousContent = file_get_contents($realFile);
                }

                WADA_Log::debug('onEditThemePluginFile newContent: '.$newContent);
                WADA_Log::debug('onEditThemePluginFile previousContent: '.$previousContent);

                $newContent = base64_encode($newContent);
                $previousContent = base64_encode($previousContent);

                $infos[] = self::getEventInfoElement('file', $file);
                $infos[] = self::getEventInfoElement('file_ext', ($file ? ltrim(substr($file, strrpos($file, '.', -1), strlen($file)), '.') : null));
                $infos[] = self::getEventInfoElement('real_file', $realFile);
                $infos[] = self::getEventInfoElement('content', $newContent, $previousContent);
                $res = $this->storeFileEvent($sensorId, array('infos' => $infos));
            }
        }

        return $res;
    }

    /**
     * @param int $userId
     * @param int $targetObjectId
     * @param string|null $targetObjectType
     * @return array
     */
    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = self::OBJ_TYPE_FILE)
    {
        // change to parent function is that we default to passing in the object type of file
        return parent::getEventDefaults($userId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @return bool
     */
    protected function storeFileEvent($sensorId, $eventData = array()){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}
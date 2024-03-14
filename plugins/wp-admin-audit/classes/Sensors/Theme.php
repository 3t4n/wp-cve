<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Theme extends WADA_Sensor_Base
{
    public $themesBefore;

    public function __construct(){
        $this->themesBefore = array();
        parent::__construct(WADA_Sensor_Base::GRP_THEME);
    }

    public function registerSensor(){
        add_action('admin_init', array($this, 'onAdminInit'));
        add_action('upgrader_process_complete', array($this, 'onThemeInstall'), 10, 2);
        add_action('switch_theme', array($this, 'onThemeSwitch'), 10, 3);
        add_action('upgrader_process_complete', array($this, 'onThemeUpdate'), 10, 2);
        add_action('delete_theme', array($this, 'onThemeDeleteAttempt'));
        add_action('deleted_theme', array($this, 'onThemeDelete'), 10, 2);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    public function onAdminInit(){
        $isInstall = self::matchEvent(array('action' => 'install'), $_REQUEST);
        $isUpdate = self::matchEvent(array('action' => 'update'), $_REQUEST);
        $isUploadTheme = self::matchEvent(array('action' => 'upload-theme'), $_REQUEST);
        $isUpdateTheme = self::matchEvent(array('action' => 'update-theme'), $_REQUEST);
        $isDelete = self::matchEvent(array('action' => 'delete'), $_REQUEST) || self::matchEvent(array('action' => 'delete-selected'), $_REQUEST);
        if(!$isInstall && !$isUpdate && !$isUploadTheme && !$isUpdateTheme && !$isDelete) return false; // skip, this was not a theme install etc

        $allThemes = wp_get_themes();
        foreach($allThemes AS $key => $theme){
            $themeBefore = new stdClass();
            $themeBefore->stylesheet = $theme->get_stylesheet();
            $themeBefore->Name = $theme->display('Name', false);
            $themeBefore->Version = $theme->display('Version', false);
            $this->themesBefore[$themeBefore->stylesheet] = $themeBefore;
            $this->themesBefore[$themeBefore->Name] = $themeBefore; // twice is a charm
        }
    }


    protected function getWP_ThemeAttributesToRecord(){
        return array(
            'Name', 'ThemeURI', 'Description', 'Author', 'AuthorURI', 'Version', 'Template',
            'Status', 'Tags', 'TextDomain', 'DomainPath');
    }

    /**
     * @param WP_Upgrader $upgrader
     * @param array $hookExtract
     */
    public function onThemeInstall($upgrader, $hookExtract){
        if(!self::matchEvent(array('action' => 'install', 'type' => 'theme'), $hookExtract)) return false; // skip, this was not a theme install
        if(!$this->isActiveSensor(self::EVT_THEME_INSTALL)) return $this->skipEvent(self::EVT_THEME_INSTALL);
        $eventType = self::EVT_THEME_INSTALL;
        WADA_Log::debug('onThemeInstall upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
        /** @var Theme_Upgrader $upgrader */
        $res = WADA_UpgraderUtils::getThemeInstallDigest($upgrader);
        if(is_null($res->updateSuccessful)){
            WADA_Log::warning('onThemeInstall Unclear if install was successful (consider it failed)! upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
            $res->updateSuccessful = false;
        }

        $pastVersion = null;
        if($res->overwrite === 'update-theme' || $res->overwrite === 'downgrade-theme'){ // we went into it as an installation, but it turns out the theme already exists -> go for theme update event instead
            $eventType = self::EVT_THEME_UPDATE;
            WADA_Log::warning('onThemeInstall Override! (theme: '.$res->destinationName.')');
            WADA_Log::debug('onThemeInstall themesBefore: '.print_r($this->themesBefore, true));
            if($res->destinationName){
                $pastVersion = array_key_exists($res->destinationName, $this->themesBefore) ? (property_exists($this->themesBefore[$res->destinationName], 'Version') ? $this->themesBefore[$res->destinationName]->Version : null) : null;
                WADA_Log::debug('onThemeInstall pastVersion: '.$pastVersion);
            }
        }
        $res->infos[] = self::getEventInfoElement('OP_SUCCESS', $res->updateSuccessful ? 1 : 0);
        $res->infos[] = self::getEventInfoElement('THEME_VERSION', $res->futureVersion, $pastVersion);
        return $this->storeThemeEvent($eventType, array('infos' => $res->infos));
    }

    /**
     * @param string $newName
     * @param WP_Theme $newTheme
     * @param WP_Theme $oldTheme
     * @return bool
     */
    public function onThemeSwitch($newName, WP_Theme $newTheme, WP_Theme $oldTheme){
        WADA_Log::debug('onThemeSwitch');
        if(!$this->isActiveSensor(self::EVT_THEME_SWITCH)) return $this->skipEvent(self::EVT_THEME_SWITCH);
        WADA_Log::debug('onThemeSwitch new: '.$newName.', old: '.$oldTheme->get('Name'));
        $infos = $this->getThemeEventDetails($newTheme, $oldTheme);
        return $this->storeThemeEvent(self::EVT_THEME_SWITCH, array('infos' => $infos));
    }

    
    /**
     * @param WP_Upgrader $upgrader
     * @param array $hookExtract
     */
    public function onThemeUpdate($upgrader, $hookExtract){
        if(!self::matchEvent(array('action' => 'update', 'type' => 'theme'), $hookExtract)) return false; // skip, this was not a theme update
        if(!$this->isActiveSensor(self::EVT_THEME_UPDATE)) return $this->skipEvent(self::EVT_THEME_UPDATE);
        WADA_Log::debug('onThemeUpdate upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));

        $currentVersion = null;
        /** @var Theme_Upgrader $upgrader */
        $res = WADA_UpgraderUtils::getThemeUpgradeDigest($upgrader);
        if(is_null($res->updateSuccessful)){
            WADA_Log::warning('onThemeUpdate Unclear if update was successful (consider it failed)! upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
            $res->updateSuccessful = false;
        }
        $res->infos[] = self::getEventInfoElement('OP_SUCCESS', $res->updateSuccessful ? 1 : 0);
        if(array_key_exists('themes', $hookExtract)) {
            foreach ($hookExtract['themes'] AS $theme){
                $res->infos[] = self::getEventInfoElement('THEME_SLUG', $theme);
                $themeInfo = wp_get_theme($theme);
                $currentVersion = $themeInfo ? $themeInfo->display('Version', false) : null;
                $res->infos[] = self::getEventInfoElement('THEME_VERSION', $currentVersion, $res->priorVersion);
            }
        }
        return $this->storeThemeEvent(self::EVT_THEME_UPDATE, array('infos' => $res->infos));
    }

    /**
     * @param string stylesheet
     */
    public function onThemeDeleteAttempt($stylesheet){
        WADA_Log::debug('onThemeDeleteAttempt');
        if(!$this->isActiveSensor(self::EVT_THEME_DELETE)) return $this->skipEvent(self::EVT_THEME_DELETE);
        WADA_Log::debug('onThemeDeleteAttempt for theme '.$stylesheet);
        $theme = wp_get_theme($stylesheet);
        $infos = $this->getThemeEventDetails($theme);
        $themeBefore = new stdClass();
        $themeBefore->stylesheet = $stylesheet;
        $themeBefore->theme = $theme;
        $themeBefore->infos = $infos;
        $this->themesBefore[$stylesheet] = $themeBefore;
        return true;
    }

    /**
     * @param string $stylesheet
     * @param bool $deleted
     * @return bool
     */
    public function onThemeDelete($stylesheet, $deleted){
        WADA_Log::debug('onThemeDelete');
        if(!$this->isActiveSensor(self::EVT_THEME_DELETE)) return $this->skipEvent(self::EVT_THEME_DELETE);
        WADA_Log::debug('onThemeDelete for theme '.$stylesheet.', deleted: '.($deleted ? 'y':'n'));
        $infos = array_key_exists($stylesheet, $this->themesBefore) ? $this->themesBefore[$stylesheet]->infos : array();
        $infos[] = self::getEventInfoElement('DELETION_RESULT', $deleted ? 1 : 0);
        WADA_Log::debug('onThemeDelete infos (incl. from earlier caching): '.print_r($infos, true));
        return $this->storeThemeEvent(self::EVT_THEME_DELETE, array('infos' => $infos));
    }


    /**
     * @param WP_Theme $theme
     * @param WP_Theme $oldTheme
     * @return array
     */
    protected function getThemeEventDetails($theme, $oldTheme = null){
        $infos = array();
        $attributesToRecord = $this->getWP_ThemeAttributesToRecord();
        if($theme instanceof WP_Theme){
            foreach($attributesToRecord as $attribute){
                $themeAttribute = $theme->get($attribute);
                $priorThemeAttr = ($oldTheme instanceof WP_Theme) ? $oldTheme->get($attribute) : null;
                $themeAttribute = is_array($themeAttribute) ? implode(', ', $themeAttribute) : $themeAttribute;
                $priorThemeAttr = is_array($priorThemeAttr) ? implode(', ', $priorThemeAttr) : $priorThemeAttr;
                $infos[] = self::getEventInfoElement($attribute, $themeAttribute, $priorThemeAttr);
            }
        }
        WADA_Log::debug('getThemeEventDetails: '.print_r($infos, true));
        return $infos;
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @return bool
     */
    protected function storeThemeEvent($sensorId, $eventData = array()){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}
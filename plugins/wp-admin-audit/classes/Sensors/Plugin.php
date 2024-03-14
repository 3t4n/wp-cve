<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Plugin extends WADA_Sensor_Base
{
    public $pluginsBefore;

    public function __construct(){
        $this->pluginsBefore = array();
        parent::__construct(WADA_Sensor_Base::GRP_PLUGIN);
    }

    public function registerSensor(){
        add_action('admin_init', array($this, 'onAdminInit'));
        add_action('upgrader_process_complete', array($this, 'onPluginInstall'), 10, 2);
        add_action('activated_plugin', array($this, 'onPluginActivate'), 10, 2);
        add_action('upgrader_process_complete', array($this, 'onPluginUpdate'), 10, 2);
        add_action('deactivated_plugin', array($this, 'onPluginDeactivate'), 10, 2);
        add_action('delete_plugin', array($this, 'onPluginDeleteAttempt'));
        add_action('deleted_plugin', array($this, 'onPluginDelete'), 10, 2);
        add_action('pre_auto_update', array($this, 'onBeforeAutomaticUpdate'), 10, 3);
        add_action('automatic_updates_complete', array($this, 'onAutomaticUpdatesComplete'));
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    public function onAdminInit(){
        $isInstall = self::matchEvent(array('action' => 'install'), $_REQUEST);
        $isUpdate = self::matchEvent(array('action' => 'update'), $_REQUEST);
        $isUploadPlugin = self::matchEvent(array('action' => 'upload-plugin'), $_REQUEST);
        $isUpdatePlugin = self::matchEvent(array('action' => 'update-plugin'), $_REQUEST);
        $isDelete = self::matchEvent(array('action' => 'delete'), $_REQUEST) || self::matchEvent(array('action' => 'delete-selected'), $_REQUEST);
        if(!$isInstall && !$isUpdate && !$isUploadPlugin && !$isUpdatePlugin && !$isDelete) return false; // skip, this was not a plugin install etc

        $allPlugins = get_plugins();
        foreach($allPlugins AS $pluginFile => $plugin){
            $pluginBefore = new stdClass();
            $pluginBefore->basename = $pluginFile;
            $pluginBefore->Name = array_key_exists('Name', $plugin) ? $plugin['Name'] : null;
            $pluginBefore->Version = array_key_exists('Version', $plugin) ? $plugin['Version'] : null;
            $pluginDirPath = wp_normalize_path(plugin_dir_path(WP_PLUGIN_DIR . '/' .$pluginFile));
            $this->pluginsBefore[$pluginFile] = $pluginBefore;
            $this->pluginsBefore[$pluginDirPath] = $pluginBefore; // twice is a charm
        }
        return true;
    }

    /**
     * @param WP_Upgrader $upgrader
     * @param array $hookExtract
     */
    public function onPluginInstall($upgrader, $hookExtract){
        if(!self::matchEvent(array('action' => 'install', 'type' => 'plugin'), $hookExtract)) return false; // skip, this was not a plugin install
        if(!$this->isActiveSensor(self::EVT_PLUGIN_INSTALL)) return $this->skipEvent(self::EVT_PLUGIN_INSTALL);
        $eventType = self::EVT_PLUGIN_INSTALL;
        WADA_Log::debug('onPluginInstall upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
        /** @var Plugin_Upgrader $upgrader */
        $res = WADA_UpgraderUtils::getPluginInstallDigest($upgrader);
        if(is_null($res->updateSuccessful)){
            WADA_Log::warning('onPluginUpdate Unclear if install was successful (consider it failed)! upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
            $res->updateSuccessful = false;
        }
        $res->infos[] = self::getEventInfoElement('PLUGIN_SLUG', array_key_exists('slug', $_POST) ? sanitize_text_field($_POST['slug']) : null);

        $pastVersion = null;
        if($res->overwrite === 'update-plugin' || $res->overwrite === 'downgrade-plugin'){ // we went into it as an installation, but it turns out the plugin already exists -> go for plugin update event instead
            $eventType = self::EVT_PLUGIN_UPDATE;
            $pluginPathForSearch = wp_normalize_path($res->upgradeDestination);
            WADA_Log::debug('onPluginUpdate overwrite active: '.$res->overwrite);
            WADA_Log::debug('onPluginUpdate pluginPathForSearch: '.$pluginPathForSearch);
            WADA_Log::debug('onPluginUpdate pluginsBefore: '.print_r($this->pluginsBefore, true));
            $pastVersion = array_key_exists($pluginPathForSearch, $this->pluginsBefore) ? $this->pluginsBefore[$pluginPathForSearch]->Version : null;
        }
        $res->infos[] = self::getEventInfoElement('OP_SUCCESS', $res->updateSuccessful ? 1 : 0);
        $res->infos[] = self::getEventInfoElement('PLUGIN_VERSION', $res->futureVersion, $pastVersion);
        return $this->storePluginEvent($eventType, array('infos' => $res->infos));
    }

    /**
     * @param string $plugin
     * @param bool $networkDeactivating
     */
    public function onPluginActivate($plugin, $networkDeactivating){
        WADA_Log::debug('onPluginActivate');
        if(!$this->isActiveSensor(self::EVT_PLUGIN_ACTIVATE)) return $this->skipEvent(self::EVT_PLUGIN_ACTIVATE);
        WADA_Log::debug('onPluginActivate for plugin '.$plugin.', network deactivating: '.($networkDeactivating ? 'y':'n'));
        $infos = $this->getPluginEventDetails($plugin);
        return $this->storePluginEvent(self::EVT_PLUGIN_ACTIVATE, array('infos' => $infos));
    }

    /**
     * @param WP_Upgrader $upgrader
     * @param array $hookExtract
     */
    public function onPluginUpdate($upgrader, $hookExtract){
        if(!self::matchEvent(array('action' => 'update', 'type' => 'plugin'), $hookExtract)) return false; // skip, this was not a plugin update
        if(!$this->isActiveSensor(self::EVT_PLUGIN_UPDATE)) return $this->skipEvent(self::EVT_PLUGIN_UPDATE);
        WADA_Log::debug('onPluginUpdate upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));

        $currentVersion = null;
        /** @var Plugin_Upgrader $upgrader */
        $res = WADA_UpgraderUtils::getPluginUpgradeDigest($upgrader);
        if(is_null($res->updateSuccessful)){
            WADA_Log::warning('onPluginUpdate Unclear if update was successful (consider it failed)! upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
            $res->updateSuccessful = false;
        }
        $res->infos[] = self::getEventInfoElement('OP_SUCCESS', $res->updateSuccessful ? 1 : 0);
        if(array_key_exists('plugins', $hookExtract)) {
            foreach ($hookExtract['plugins'] AS $plg){
                $res->infos[] = self::getEventInfoElement('PLUGIN_SLUG', $plg);
                $plgData = $this->getPluginData(WP_PLUGIN_DIR . '/' . $plg);
                if($plgData) {
                    $currentVersion = array_key_exists('Version', $plgData) ? $plgData['Version'] : null;
                }
                $res->infos[] = self::getEventInfoElement('PLUGIN_VERSION', $currentVersion, $res->priorVersion);
            }
        }
        return $this->storePluginEvent(self::EVT_PLUGIN_UPDATE, array('infos' => $res->infos));
    }

    /**
     * @param string $pluginFilePath
     * @param bool $markup
     * @param bool $translate
     * @return array
     */
    protected function getPluginData($pluginFilePath, $markup = false, $translate = true){
        WADA_Log::debug('getPluginData for '.$pluginFilePath);
        if(!function_exists( 'get_plugin_data')){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        return get_plugin_data($pluginFilePath, $markup, $translate);
    }

    /**
     * @param string $plugin
     * @param bool $networkDeactivating
     */
    public function onPluginDeactivate($plugin, $networkDeactivating){
        WADA_Log::debug('onPluginDeactivate');
        if(!$this->isActiveSensor(self::EVT_PLUGIN_DEACTIVATE)) return $this->skipEvent(self::EVT_PLUGIN_DEACTIVATE);
        WADA_Log::debug('onPluginDeactivate for plugin '.$plugin.', network deactivating: '.($networkDeactivating ? 'y':'n'));
        $infos = $this->getPluginEventDetails($plugin);
        return $this->storePluginEvent(self::EVT_PLUGIN_DEACTIVATE, array('infos' => $infos));
    }

    /**
     * @param string $plugin
     */
    public function onPluginDeleteAttempt($plugin){
        WADA_Log::debug('onPluginDeleteAttempt');
        if(!$this->isActiveSensor(self::EVT_PLUGIN_DELETE)) return $this->skipEvent(self::EVT_PLUGIN_DELETE);
        $file = plugin_basename( $plugin );
        WADA_Log::debug('onPluginDeleteAttempt for plugin '.$plugin);
        $infos = $this->getPluginEventDetails($plugin);
        $pluginBefore = new stdClass();
        $pluginBefore->plugin = $plugin;
        $pluginBefore->basename = $file;
        $pluginBefore->infos = $infos;
        $this->pluginsBefore[$file] = $pluginBefore;
        return true;
    }

    /**
     * @param string $plugin
     * @param bool $deleted
     */
    public function onPluginDelete($plugin, $deleted){
        WADA_Log::debug('onPluginDelete');
        if(!$this->isActiveSensor(self::EVT_PLUGIN_DELETE)) return $this->skipEvent(self::EVT_PLUGIN_DELETE);
        $file = plugin_basename( $plugin );
        WADA_Log::debug('onPluginDelete for plugin '.$plugin.', deleted: '.($deleted ? 'y':'n'));
        $infos = array_key_exists($file, $this->pluginsBefore) ? $this->pluginsBefore[$file]->infos : array();
        $infos[] = self::getEventInfoElement('DELETION_RESULT', $deleted ? 1 : 0);
        WADA_Log::debug('onPluginDelete infos (incl. from earlier caching): '.print_r($infos, true));
        return $this->storePluginEvent(self::EVT_PLUGIN_DELETE, array('infos' => $infos));
    }

    /**
     * @param string $type The type of update: 'core', 'theme', 'plugin', or 'translation'
     * @param object $item The update offer
     * @param string $context The filesystem context (path) against which filesystem access and status should be checked
     */
    public function onBeforeAutomaticUpdate($type, $item, $context){
        WADA_Log::debug('onBeforeAutomaticUpdate type: '.$type.', item: '.print_r($item, true).', context: '.$context);
    }

    /**
     * @param array $updateResults
     */
    public function onAutomaticUpdatesComplete($updateResults){
        WADA_Log::debug('onAutomaticUpdatesComplete updateResults: '.print_r($updateResults, true));
    }

    /**
     * @param string $pluginFile
     * @return array
     */
    protected function getPluginEventDetails($pluginFile){
        $infos = array();
        if($pluginFile){
            $pluginDataArr = $this->getPluginData(WP_PLUGIN_DIR . '/' .$pluginFile);
            WADA_Log::debug('getPluginEventDetails pluginDataArr: '.print_r($pluginDataArr, true));
            if($pluginDataArr && is_array($pluginDataArr) && count($pluginDataArr)){
                foreach($pluginDataArr AS $key => $plgData){
                    if(!is_null($plgData)) {
                        $infoValue = $plgData;
                        if (!is_string($infoValue)) {
                            $infoValue = print_r($plgData, true);
                        }
                        $infos[] = self::getEventInfoElement($key, $infoValue);
                    }
                }
            }
            $infos[] = self::getEventInfoElement('plugin_file', $pluginFile);
        }
        WADA_Log::debug('getPluginEventDetails: '.print_r($infos, true));
        return $infos;
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @return bool
     */
    protected function storePluginEvent($sensorId, $eventData = array()){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}
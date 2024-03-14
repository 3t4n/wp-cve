<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Core extends WADA_Sensor_Base
{

    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_CORE);
    }

    public function registerSensor(){
        add_action('upgrader_process_complete', array($this, 'onCoreUpdate'), 10, 2);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param WP_Upgrader $upgrader
     * @param array $hookExtract
     */
    public function onCoreUpdate($upgrader, $hookExtract){
        global $wp_version;
        if(!$this->isActiveSensor(self::EVT_CORE_UPDATE)) return $this->skipEvent(self::EVT_CORE_UPDATE);
        if(!$hookExtract) return $this->skipEvent(self::EVT_CORE_UPDATE, false, 'No hook extract provided');
        $action = array_key_exists('action', $hookExtract) ? $hookExtract['action'] : null;
        $type = array_key_exists('type', $hookExtract) ? $hookExtract['type'] : null;
        WADA_Log::debug('onCoreUpdate (type: '.$type.', action: '.$action.')');
        if($action === 'update' && $type === 'core'){
            WADA_Log::debug('onCoreUpdate upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));

            $priorVersion = $wp_version; // is still old version number at this stage
            $currentVersion = array_key_exists('version', $_POST) ? sanitize_text_field($_POST['version']) : null;
            WADA_Log::debug('onCoreUpdate update from '.$priorVersion.' to '.$currentVersion);

            /** @var Core_Upgrader $upgrader */
            $res = WADA_UpgraderUtils::getCoreUpgradeDigest($upgrader);
            if(is_null($res->updateSuccessful)){
                WADA_Log::warning('onCoreUpdate Unclear if update was successful (consider it failed)! upgrader: '.print_r($upgrader, true).', hookExtract: '.print_r($hookExtract, true));
                $res->updateSuccessful = false;
            }
            $res->infos[] = self::getEventInfoElement('OP_SUCCESS', $res->updateSuccessful ? 1 : 0);
            $res->infos[] = self::getEventInfoElement('CORE_VERSION', $currentVersion, $priorVersion);
            return $this->storeCoreEvent(self::EVT_CORE_UPDATE, array('infos' => $res->infos));
        }
        return false; // skip, this was not a core update
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @return bool
     */
    protected function storeCoreEvent($sensorId, $eventData = array()){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}
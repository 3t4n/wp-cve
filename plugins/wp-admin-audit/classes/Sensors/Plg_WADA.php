<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Plg_WADA extends WADA_Sensor_Base
{
    public function __construct(){
        parent::__construct(WADA_Sensor_Base::GRP_PLG_WADA);
    }

    public function registerSensor(){
        add_action('wp_admin_audit_sensor_status_change', array($this, 'onSensorStatusChange'), 10, 3);
        add_action('wp_admin_audit_sensor_update', array($this, 'onSensorUpdate'), 10, 3);
        add_action('wp_admin_audit_settings_update', array($this, 'onSettingsUpdate'), 10, 2);
        add_action('wp_admin_audit_notification_status_change', array($this, 'onNotificationStatusChange'), 10, 3);
        add_action('wp_admin_audit_notification_create', array($this, 'onNotificationCreate'), 10, 2);
        add_action('wp_admin_audit_notification_update', array($this, 'onNotificationUpdate'), 10, 3);
        add_action('wp_admin_audit_notification_delete', array($this, 'onNotificationDelete'), 10, 2);

        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param int $sensorId
     * @param int $newStatus
     * @param int $previousStatus
     */
    public function onSensorStatusChange($sensorId, $newStatus, $previousStatus){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_SENSOR_UPDATE)) return $this->skipEvent(self::EVT_PLG_WADA_SENSOR_UPDATE);
        WADA_Log::debug('onSensorStatusChange for sensor '.$sensorId.', new status: '.$newStatus.', previous status: '.$previousStatus);
        $infos = array();
        $infos[] = self::getEventInfoElement('active', $newStatus, $previousStatus);
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $sensorId;
        $targetObjectType = self::OBJ_TYPE_PLG_WADA_SENSOR;
        return $this->storeWADAEvent(self::EVT_PLG_WADA_SENSOR_UPDATE, $eventData, $executingUserId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $sensorAfterUpdate
     * @param array $sensorBeforeUpdate
     */
    public function onSensorUpdate($sensorId, $sensorAfterUpdate, $sensorBeforeUpdate){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_SENSOR_UPDATE)) return $this->skipEvent(self::EVT_PLG_WADA_SENSOR_UPDATE);
        WADA_Log::debug('onSensorUpdate for sensor '.$sensorId.', sensor now: '.print_r($sensorAfterUpdate, true).', sensor before: '.print_r($sensorBeforeUpdate, true));
        $infos = WADA_CompUtils::getChangedAttributes((object)$sensorBeforeUpdate, (object)$sensorAfterUpdate, array('active', 'severity', 'name', 'description'));
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $sensorId;
        $targetObjectType = self::OBJ_TYPE_PLG_WADA_SENSOR;
        return $this->storeWADAEvent(self::EVT_PLG_WADA_SENSOR_UPDATE, $eventData, $executingUserId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param array $settingsAfterSaving
     * @param array $settingsPriorSaving
     */
    public function onSettingsUpdate($settingsAfterSaving, $settingsPriorSaving){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_SETTINGS_UPDATE)) return $this->skipEvent(self::EVT_PLG_WADA_SETTINGS_UPDATE);

        WADA_Log::debug('onSettingsUpdate settings now: '.print_r($settingsAfterSaving, true).', settings before: '.print_r($settingsPriorSaving, true));
        $infos = array();
        foreach($settingsPriorSaving AS $settingId => $priorSettingObj){
            if(array_key_exists($settingId, $settingsAfterSaving)){
                $infoValue = $settingsAfterSaving[$settingId]->value;
                $priorValue = $priorSettingObj->value;
                if(is_array($infoValue)){
                    sort($infoValue);
                    $infoValue = implode(',', $infoValue);
                }
                if(is_array($priorValue)){
                    sort($priorValue);
                    $priorValue = implode(',', $priorValue);
                }
                if($infoValue !== $priorValue) {
                    $infos[] = array(
                        'info_key' => $priorSettingObj->name,
                        'info_value' => $infoValue,
                        'prior_value' => $priorValue
                    );
                }
            }
        }
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        return $this->storeWADAEvent(self::EVT_PLG_WADA_SETTINGS_UPDATE, $eventData, $executingUserId);
    }


    /**
     * @param int $notificationId
     * @param int $newStatus
     * @param int $previousStatus
     */
    public function onNotificationStatusChange($notificationId, $newStatus, $previousStatus){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_NOTIFICATION_UPDATE)) return $this->skipEvent(self::EVT_PLG_WADA_NOTIFICATION_UPDATE);
        WADA_Log::debug('onNotificationStatusChange for notification '.$notificationId.', new status: '.$newStatus.', previous status: '.$previousStatus);
        $infos = array();
        $infos[] = self::getEventInfoElement('active', $newStatus, $previousStatus);
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $notificationId;
        $targetObjectType = self::OBJ_TYPE_PLG_WADA_NOTIFICATION;
        return $this->storeWADAEvent(self::EVT_PLG_WADA_NOTIFICATION_UPDATE, $eventData, $executingUserId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $notificationId
     * @param object $newNotification
     */
    public function onNotificationCreate($notificationId, $newNotification){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_NOTIFICATION_CREATE)) return $this->skipEvent(self::EVT_PLG_WADA_NOTIFICATION_CREATE);
        WADA_Log::debug('onNotificationCreate for notification '.$notificationId.', notification: '.print_r($newNotification, true));
        $pseudoPreviousNotification = new stdClass();
        $pseudoPreviousNotification->triggers = array();
        $pseudoPreviousNotification->targets = array();

        $infos = $this->getNotificationEventInfos($newNotification, $pseudoPreviousNotification);
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $notificationId;
        $targetObjectType = self::OBJ_TYPE_PLG_WADA_NOTIFICATION;
        return $this->storeWADAEvent(self::EVT_PLG_WADA_NOTIFICATION_CREATE, $eventData, $executingUserId, $targetObjectId, $targetObjectType);
    }

    protected function getNotificationEventInfos($notificationAfterUpdate, $notificationBeforeUpdate){
        $infos = WADA_CompUtils::getChangedAttributes((object)$notificationBeforeUpdate, (object)$notificationAfterUpdate, array('active', 'name'));

        list($beforeSeverityTriggers, $beforeSensorTriggers) = WADA_Model_Notification::separateNotificationTriggers($notificationBeforeUpdate->triggers);
        list($afterSeverityTriggers, $afterSensorTriggers) = WADA_Model_Notification::separateNotificationTriggers($notificationAfterUpdate->triggers);
        list($beforeUserTargets, $beforeRoleTargets, $beforeEmailTargets, $beforeIntegrationTargets) = WADA_Model_Notification::separateNotificationTargets($notificationBeforeUpdate->targets);
        list($afterUserTargets, $afterRoleTargets, $afterEmailTargets, $afterIntegrationTargets) =  WADA_Model_Notification::separateNotificationTargets($notificationAfterUpdate->targets);

        $sensorTriggers = WADA_CompUtils::getChangedObjectIdsInArrays($beforeSensorTriggers, $afterSensorTriggers, 'trigger_id', 'Triggers/Sensor');
        $severityTriggers = WADA_CompUtils::getChangedObjectIdsInArrays($beforeSeverityTriggers, $afterSeverityTriggers, 'trigger_id', 'Triggers/Severity');
        $userTargets = WADA_CompUtils::getChangedObjectIdsInArrays($beforeUserTargets, $afterUserTargets, 'target_id', 'Targets/Users');
        $roleTargets = WADA_CompUtils::getChangedObjectIdsInArrays($beforeRoleTargets, $afterRoleTargets, 'target_str_id', 'Targets/Roles');
        $emailTargets = WADA_CompUtils::getChangedObjectIdsInArrays($beforeEmailTargets, $afterEmailTargets, 'target_str_id', 'Targets/Emails');

        return array_merge($infos, $sensorTriggers, $severityTriggers, $userTargets, $roleTargets, $emailTargets);
    }

    /**
     * @param int $notificationId
     * @param object $notificationAfterUpdate
     * @param object $notificationBeforeUpdate
     */
    public function onNotificationUpdate($notificationId, $notificationAfterUpdate, $notificationBeforeUpdate){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_NOTIFICATION_UPDATE)) return $this->skipEvent(self::EVT_PLG_WADA_NOTIFICATION_UPDATE);
        WADA_Log::debug('onNotificationUpdate for notification '.$notificationId.', notification now: '.print_r($notificationAfterUpdate, true).', notification before: '.print_r($notificationBeforeUpdate, true));

        $infos = $this->getNotificationEventInfos($notificationAfterUpdate, $notificationBeforeUpdate);
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $notificationId;
        $targetObjectType = self::OBJ_TYPE_PLG_WADA_NOTIFICATION;
        return $this->storeWADAEvent(self::EVT_PLG_WADA_NOTIFICATION_UPDATE, $eventData, $executingUserId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $notificationId
     * @param object $notificationBeforeDelete
     */
    public function onNotificationDelete($notificationId, $notificationBeforeDelete){
        if(!$this->isActiveSensor(self::EVT_PLG_WADA_NOTIFICATION_DELETE)) return $this->skipEvent(self::EVT_PLG_WADA_NOTIFICATION_DELETE);
        WADA_Log::debug('onNotificationDelete for notification '.$notificationId.', notification before deleting: '.print_r($notificationBeforeDelete, true));
        list($severityTriggers, $sensorTriggers) = WADA_Model_Notification::separateNotificationTriggers($notificationBeforeDelete->triggers);
        list($userTargets, $roleTargets, $emailTargets, $integrationTargets) = WADA_Model_Notification::separateNotificationTargets($notificationBeforeDelete->targets);
        $infos = array();
        $infos[] = self::getEventInfoElement('name', $notificationBeforeDelete->name);
        $infos[] = self::getEventInfoElement('active', $notificationBeforeDelete->active);
        if(property_exists($notificationBeforeDelete, 'nr_queue_entries')){
            $infos[] = self::getEventInfoElement('nr_queue_entries', $notificationBeforeDelete->nr_queue_entries);
        }
        $infos[] = self::getEventInfoElement(__('Triggers', 'wp-admin-audit').'/'.__('Severity', 'wp-admin-audit'), implode(', ', array_map(function($o) { return $o->trigger_id; }, $severityTriggers)));
        $infos[] = self::getEventInfoElement(__('Triggers', 'wp-admin-audit').'/'.__('Sensor', 'wp-admin-audit'), implode(', ', array_map(function($o) { return $o->trigger_id; }, $sensorTriggers)));
        $infos[] = self::getEventInfoElement(__('Targets', 'wp-admin-audit').'/'.__('Users', 'wp-admin-audit'), implode(', ', array_map(function($o) { return $o->target_id; }, $userTargets)));
        $infos[] = self::getEventInfoElement(__('Targets', 'wp-admin-audit').'/'.__('Roles', 'wp-admin-audit'), implode(', ', array_map(function($o) { return $o->target_str_id; }, $roleTargets)));
        $infos[] = self::getEventInfoElement(__('Targets', 'wp-admin-audit').'/'.__('Emails', 'wp-admin-audit'), implode(', ', array_map(function($o) { return $o->target_str_id; }, $emailTargets)));
        $eventData = array('infos' => $infos);
        $executingUserId = get_current_user_id();
        $targetObjectId = $notificationId;
        $targetObjectType = self::OBJ_TYPE_PLG_WADA_NOTIFICATION;
        return $this->storeWADAEvent(self::EVT_PLG_WADA_NOTIFICATION_DELETE, $eventData, $executingUserId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $eventData
     * @param int $executingUserId
     * @param int $targetObjectId
     * @param string $targetObjectType
     * @return bool
     */
    protected function storeWADAEvent($sensorId, $eventData = array(), $executingUserId = 0, $targetObjectId = 0, $targetObjectType = null){
        $event = (object)(array_merge($this->getEventDefaults($executingUserId, $targetObjectId, $targetObjectType), $eventData));
        return $this->storeEvent($sensorId, $event);
    }

}
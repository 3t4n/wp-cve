<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_Sensor extends WADA_Model_Base
{
    const SEVERITY_DEBUG = 0;
    const SEVERITY_LOW = 1;
    const SEVERITY_MEDIUM = 2;
    const SEVERITY_HIGH = 3;
    const SEVERITY_SEVERE = 4;
    const SEVERITY_EMERGENCY = 5;

    public function __construct($id = null){
        parent::__construct($id);
    }

    public static function getTable(){
        return WADA_Database::tbl_sensors();
    }

    public function getAttributes(){
        return array(
            'id', 'extension_id', 'severity', 'active', 'name',
            'description', 'event_group', 'event_category'
        );
    }

    public static function getSeverityLevels($mostSevereFirst=false){
        $levels = array(
            static::SEVERITY_DEBUG => __('Debug', 'wp-admin-audit'),
            static::SEVERITY_LOW => __('Low', 'wp-admin-audit'),
            static::SEVERITY_MEDIUM => __('Medium', 'wp-admin-audit'),
            static::SEVERITY_HIGH => __('High', 'wp-admin-audit'),
            static::SEVERITY_SEVERE => __('Severe', 'wp-admin-audit'),
            static::SEVERITY_EMERGENCY => __('Emergency', 'wp-admin-audit')
        );
        if($mostSevereFirst){
            return array_reverse($levels, true);
        }
        return $levels;
    }

    public static function getSeverityNameForLevel($severityLevel, $default = null){
        $severityLevel = intval($severityLevel);
        $severityLevels = self::getSeverityLevels();
        if(array_key_exists($severityLevel, $severityLevels)){
            return $severityLevels[$severityLevel];
        }else{
            if(is_null($default)) {
                return sprintf(__('Unknown value: %s', 'wp-admin-audit'), strval($severityLevel));
            }
        }
        return $default;
    }

    public static function getEventCategories(){
        return array(
            WADA_Sensor_Base::CAT_CORE => __('WP Core', 'wp-admin-audit'),
            WADA_Sensor_Base::CAT_PLUGIN => __('Plugin', 'wp-admin-audit')
        );
    }

    public static function getEventCategoryName($eventCategory, $default = null){
        $eventCategories = self::getEventCategories();
        if(array_key_exists($eventCategory, $eventCategories)){
            return $eventCategories[$eventCategory];
        }else{
            if(is_null($default)) {
                return sprintf(__('Unknown value: %s', 'wp-admin-audit'), strval($eventCategory));
            }
        }
        return $default;
    }

    protected function check(){
        if(!$this->_data){
            $this->_last_error = __('No data object provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->name){
            $this->_last_error = __('No sensor name provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->event_group){
            $this->_last_error = __('No sensor group provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->event_category){
            $this->_last_error = __('No sensor category provided', 'wp-admin-audit');
            return false;
        }
        if(!isset($this->_data->severity) || !array_key_exists($this->_data->severity, self::getSeverityLevels())){
            $this->_last_error = __('No sensor severity provided', 'wp-admin-audit');
            return false;
        }
        return true;
    }

    protected function save(){
        $sensorBeforeUpdate = $this->loadData(true);
        WADA_Log::debug('Sensor->save sensorBeforeUpdate: '.print_r($sensorBeforeUpdate, true));
        global $wpdb;
        $query = 'INSERT INTO '.WADA_Model_Sensor::getTable();
        $query .= ' (';
        $query .= 'id,';
        $query .= 'extension_id,';
        $query .= 'severity,';
        $query .= 'active,';
        $query .= 'name,';
        $query .= 'description,';
        $query .= 'event_group,';
        $query .= 'event_category';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%d,'; // extension_id
        $query .= '%d,'; // severity
        $query .= '%d,'; // active
        $query .= '%s,'; // name
        $query .= '%s,'; // description
        $query .= '%s,'; // event_group
        $query .= '%s'; // event_category
        $query .= ') ON DUPLICATE KEY UPDATE severity = %d, active = %d';
        $preparedQuery = $wpdb->prepare($query,
            ($this->_data->id && $this->_data->id > 0) ? $this->_data->id : null,
            isset($this->_data->extension_id) ? $this->_data->extension_id : 0,
            $this->_data->severity,
            $this->_data->active,
            $this->_data->name,
            $this->_data->description,
            $this->_data->event_group,
            $this->_data->event_category,
            $this->_data->severity, // for ON DUPLICATE KEY UPDATE part
            $this->_data->active // for ON DUPLICATE KEY UPDATE part
        );
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('Sensor->save: '.$wpdb->last_error);
            WADA_Log::error('Sensor->save query was: '.$preparedQuery);
            return false;
        }else{
            if($wpdb->insert_id > 0) {
                $this->_id = $wpdb->insert_id;
            }
            $sensorAfterUpdate = $this->loadData(true);
            WADA_Log::debug('Sensor->save sensorAfterUpdate: '.print_r($sensorAfterUpdate, true));
            WADA_Log::debug('Sensor->save ok, inserted id: '.$this->_id);

            do_action('wp_admin_audit_sensor_update', $this->_id, $sensorAfterUpdate, $sensorBeforeUpdate);
        }
        return $this->_id;
    }

    public function normalizeSensorGroup($sensorGroup){
        return str_replace(" ", "_", ucwords(str_replace("_", " ", $sensorGroup)));
    }

    public function loadSensorsOfGroup($sensorGroup){
        $sensorGroup = $this->normalizeSensorGroup($sensorGroup);
        global $wpdb;
        $query = 'SELECT * FROM '.WADA_Model_Sensor::getTable().' WHERE event_group = %s';
        $this->_data = $wpdb->get_results($wpdb->prepare($query, $sensorGroup));
    }

    public static function setActiveStatus($id, $status){
        if($status === true || intval($status) === 1){
            $status = 1;
        }else{
            $status = 0;
        }
        $id = absint($id);
        global $wpdb;
        $query = 'SELECT active FROM '.WADA_Model_Sensor::getTable().' WHERE id=%d';
        $previousStatus = $wpdb->get_var($wpdb->prepare($query, $id));
        WADA_Log::debug('Sensor->setActiveStatus id '.$id.', new status: '.$status.', current/previous status: '.$previousStatus);
        $res = $wpdb->update(WADA_Model_Sensor::getTable(), array('active' => $status), array('id' => $id));
        if($res === false){
            WADA_Log::error('Sensor->setActiveStatus: '.$wpdb->last_error);
            WADA_Log::error('Sensor->setActiveStatus query was: '.$wpdb->last_query);
            return false;
        }else{
            WADA_Log::debug('Sensor->setActiveStatus update okay, result: '.$res);
            do_action('wp_admin_audit_sensor_status_change', $id, $status, $previousStatus);
        }
        return true;
    }

    public static function getEventGroupNames(){
        $eventGroupNames = array();
        $eventGroupNames[WADA_Sensor_Base::GRP_CORE] = __('Core', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_FILE] = __('File', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_MEDIA] = __('Media', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_POST] = __('Post', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_TAXONOMY] = __('Taxonomy', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_SETTING] = __('Setting', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_OPTION] = __('Option', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_THEME] = __('Theme', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_USER] = __('User', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_PLUGIN] = __('Plugin', 'wp-admin-audit');
        $eventGroupNames[WADA_Sensor_Base::GRP_PLG_WADA] = __('WP Admin Audit', 'wp-admin-audit');

        $eventGroupNames = apply_filters('wp_admin_audit_extension_event_group_names', $eventGroupNames);
        return $eventGroupNames;
    }

    public static function getEventGroupName($eventGroup){
        $eventGroupNames = self::getEventGroupNames();

        if(array_key_exists($eventGroup, $eventGroupNames)){
            return $eventGroupNames[$eventGroup];
        }
        return $eventGroup; // fallback
    }

    public static function getEventGroupIcons(){
        $eventGroupIcons = array();
        $eventGroupIcons[WADA_Sensor_Base::GRP_CORE] = '🛠‍';
        $eventGroupIcons[WADA_Sensor_Base::GRP_FILE] = '📄';
        $eventGroupIcons[WADA_Sensor_Base::GRP_MEDIA] = '🖼';
        $eventGroupIcons[WADA_Sensor_Base::GRP_POST] = '📝';
        $eventGroupIcons[WADA_Sensor_Base::GRP_TAXONOMY] = '✳';
        $eventGroupIcons[WADA_Sensor_Base::GRP_SETTING] = '⚙';
        $eventGroupIcons[WADA_Sensor_Base::GRP_OPTION] = '☑';
        $eventGroupIcons[WADA_Sensor_Base::GRP_THEME] = '🌄';
        $eventGroupIcons[WADA_Sensor_Base::GRP_USER] = '👤';
        $eventGroupIcons[WADA_Sensor_Base::GRP_PLUGIN] = '🤖';
        $eventGroupIcons[WADA_Sensor_Base::GRP_PLG_WADA] = '🛂';

        $eventGroupIcons = apply_filters('wp_admin_audit_extension_event_group_icons', $eventGroupIcons);
        return $eventGroupIcons;
    }

    public static function getEventGroupIcon($eventGroup){
        $eventGroupIcons = self::getEventGroupIcons();

        if(array_key_exists($eventGroup, $eventGroupIcons)){
            return $eventGroupIcons[$eventGroup];
        }
        return '🔔'; // fallback
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){
            global $wpdb;
            $query = "SELECT sen.*, ext.name as extension_name, ext.active as extension_active, "
                ."ext.plugin_folder as extension_plugin_folder, ext.ext_api_version as extension_api_version "
                ."FROM ".WADA_Model_Sensor::getTable()." sen "
                ."LEFT JOIN ".WADA_Database::tbl_extensions() ." ext ON (sen.extension_id = ext.id) "
                ."WHERE sen.id = %d ";
            $sensorObj = $wpdb->get_row($wpdb->prepare($query, $this->_id));

            if($sensorObj && property_exists($sensorObj, 'event_group')){
                $sensorObj->event_group_name = self::getEventGroupName($sensorObj->event_group);
            }

            if($onlyReturnNoInternalUpdate){
                return $sensorObj;
            }
            $this->_data = $sensorObj;
            return true;

        }
        return false;
    }

    public static function getAllSensors($onlyActive = true, $filterForSeverity = array(), $orderByCond = array('name')){
        global $wpdb;
        $sql = "SELECT sen.id, sen.name, sen.description, "
            ."sen.severity, sen.active, sen.event_group, sen.event_category, "
            ."sen.extension_id, ext.active as extension_active, ext.name as extension_name, "
            ."ext.plugin_folder as extension_plugin_folder, ext.ext_api_version as extension_api_version "
            ."FROM ".WADA_Model_Sensor::getTable()." sen "
            ."LEFT JOIN ".WADA_Database::tbl_extensions() . " ext ON (sen.extension_id = ext.id) ";
        $where = array();
        if($onlyActive){
            $where[] = "(sen.active = '1')";
        }
        if(is_array($filterForSeverity) && count($filterForSeverity)){
            $sevLevels = self::getSeverityLevels();
            $sevLevelCodes = array_keys($sevLevels);
            $sevFilterValues = array();
            foreach($filterForSeverity AS $sevFilter){
                if(in_array(intval($sevFilter), $sevLevelCodes)){
                    $sevFilterValues[] = intval($sevFilter);
                }
            }
            if(count($sevFilterValues)){
                $where[] = "(sen.severity IN (".implode(',', $sevFilterValues)."))";
            }
        }
        $allowedOrderCond = array('id', 'name', 'severity', 'active', 'event_group', 'event_category');
        $orderConditions = array();
        if(is_array($orderByCond) && count($orderByCond)){
            foreach($orderByCond AS $orderBy){
                $addToCond = false;
                $orderField = 'name';
                $orderDirection = 'ASC';
                if(is_array($orderBy) && count($orderBy) == 2){
                    if(in_array($orderBy[0], $allowedOrderCond)){
                        $addToCond = true;
                        $orderField = $orderBy[0];
                    }
                    if(strtoupper($orderBy[1]) === 'DESC'){
                        $orderDirection = 'DESC';
                    }
                }elseif(is_string($orderBy)){
                    if(in_array($orderBy, $allowedOrderCond)){
                        $addToCond = true;
                        $orderField = $orderBy;
                    }
                }
                if($addToCond){
                    $orderConditions[] = 'sen.'.$orderField.' '.$orderDirection;
                }
            }
        }

        if(count($where)) {
            $sql .= (' WHERE ' . implode(' AND ', $where));
        }
        if(count($orderConditions)) {
            $sql .= (' ORDER BY ' . implode(', ', $orderConditions));
        }

        WADA_Log::debug('getAllSensors sql: '.$sql);
        $res = $wpdb->get_results( $sql, 'ARRAY_A' );

        if($res && count($res)){
            for($i=0;$i<count($res);$i++){
                $res[$i]['event_group_name'] = self::getEventGroupName($res[$i]['event_group']);
            }
        }
        //WADA_Log::debug('getAllSensors '.print_r($res, true));

        return $res;
    }

}
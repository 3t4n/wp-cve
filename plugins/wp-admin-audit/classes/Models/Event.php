<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_Event extends WADA_Model_Base
{
    public function __construct($id = null){
        parent::__construct($id);
    }

    public static function getTable(){
        return WADA_Database::tbl_events();
    }

    public function getAttributes(){
        return array(
            'id', 'occurred_on', 'sensor_id', 'site_id',
            'user_id', 'user_name', 'user_email',
            'object_type', 'object_id', 'source_ip', 'source_client',
            'check_value_head', 'check_value_full', 'replication_done',
            'infos' // this is from/for the event_infos table
        );
    }

    protected function check(){
        if(!$this->_data){
            $this->_last_error = __('No data object provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->occurred_on){
            $this->_last_error = __('No event time (event occurred on) provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->sensor_id){
            $this->_last_error = __('No event type (sensor identifier) provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->site_id){
            $this->_last_error = __('No site identifier provided', 'wp-admin-audit');
            return false;
        }
        return true;
    }

    protected function save(){
        global $wpdb;
        $query = 'INSERT INTO '.WADA_Model_Event::getTable();
        $query .= ' (';
        $query .= 'id,';
        $query .= 'occurred_on,';
        $query .= 'sensor_id,';
        $query .= 'site_id,';
        $query .= 'user_id,';
        $query .= 'user_name,';
        $query .= 'user_email,';
        $query .= 'object_type,';
        $query .= 'object_id,';
        $query .= 'source_ip,';
        $query .= 'source_client,';
        $query .= 'check_value_head,';
        $query .= 'check_value_full,';
        $query .= 'replication_done';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%s,'; // occurred_on
        $query .= '%d,'; // sensor_id
        $query .= '%d,'; // site_id
        $query .= '%d,'; // user_id
        $query .= '%s,'; // user_name
        $query .= '%s,'; // user_email
        $query .= '%s,'; // object_type
        $query .= '%d,'; // object_id
        $query .= '%s,'; // source_ip
        $query .= '%s,'; // source_client
        $query .= '%s,'; // check_value_head
        $query .= '%s,'; // check_value_full
        $query .= '%d'; // replication_done
        $query .= ')';
        $preparedQuery = $wpdb->prepare($query,
            null,
            $this->_data->occurred_on,
            $this->_data->sensor_id,
            $this->_data->site_id,
            $this->_data->user_id,
            $this->_data->user_name,
            $this->_data->user_email,
            $this->_data->object_type,
            $this->_data->object_id,
            $this->_data->source_ip,
            $this->_data->source_client,
            $this->_data->check_value_head,
            $this->_data->check_value_full,
            $this->_data->replication_done
        );
        $preparedQuery = str_replace("''", "NULL", $preparedQuery);
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('Event->save: '.$wpdb->last_error);
            WADA_Log::error('Event->save query was: '.$preparedQuery);
            return false;
        }else{
            $this->_id = $wpdb->insert_id;
            WADA_Log::debug('Event->save ok (sensor: '.$this->_data->sensor_id.') inserted id: '.$this->_id);
            if($this->_id > 0 && $this->_data->infos){
                $this->storeEventInfos($this->_id, $this->_data->infos);
                $this->loadData();
                $checkValueFull = self::getFullCheckValue($this->_data);
                $this->updateEventWithFullCheckValue($this->_id, $checkValueFull);
            }
            if($this->_id > 0
                && defined('WADA_Version::FT_ID_REPLICATE') // check first, because this may be run during update of WADA where it has not yet existed
                && WADA_Version::getFtSetting(WADA_Version::FT_ID_REPLICATE)){
                $this->queueReplications($this->_id);
            }
        }
        return $this->_id;
    }

    protected function queueReplications($eventId){
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
    }

    protected function updateEventWithFullCheckValue($eventId, $checkValueFull){
        global $wpdb;
        if($eventId > 0 && $checkValueFull) {
            $query = 'UPDATE ' . WADA_Model_Event::getTable() . ' SET ';
            $query .= 'check_value_full = %s ';
            $query .= 'WHERE id = %d';
            return $wpdb->query($wpdb->prepare($query, $checkValueFull, $eventId));
        }
        return false;
    }

    protected function storeEventInfos($eventId, $eventInfos){
        if($eventId > 0 && is_array($eventInfos) && count($eventInfos)>0){
            WADA_Log::debug('Event->storeEventInfos for event '.$eventId.', infos: '.print_r($eventInfos, true));
            foreach($eventInfos AS $eventInfoArr) {
                $infoObj = (object)$eventInfoArr;

                if(is_object($infoObj->info_value)){
                    $infoObj->info_value = json_encode($infoObj->info_value);
                }
                if(is_object($infoObj->prior_value)){
                    $infoObj->prior_value = json_encode($infoObj->prior_value);
                }

                if(strlen($infoObj->info_key) > 45){
                    WADA_Log::warning('storeEventInfos eventId '.$eventId.' limit info_key to 45 chars, before: '.$infoObj->info_key);
                    $infoObj->info_key = substr($infoObj->info_key, 0, 45);
                    WADA_Log::warning('storeEventInfos eventId '.$eventId.' limit info_key to 45 chars, after: '.$infoObj->info_key);
                }

                global $wpdb;
                $query = 'INSERT INTO ' . WADA_Database::tbl_event_infos();
                $query .= ' (';
                $query .= 'id,';
                $query .= 'event_id,';
                $query .= 'info_key,';
                $query .= 'info_value,';
                $query .= 'prior_value';
                $query .= ') VALUES (';
                $query .= '%d,'; // id
                $query .= '%d,'; // event_id
                $query .= '%s,'; // info_key
                $query .= '%s,'; // info_value
                $query .= '%s'; // prior_value
                $query .= ')';
                $preparedQuery = $wpdb->prepare($query,
                    null, // id
                    $eventId,
                    $infoObj->info_key,
                    $infoObj->info_value,
                    $infoObj->prior_value
                );
                $preparedQuery = str_replace("''", "NULL", $preparedQuery);
                $res = $wpdb->query($preparedQuery);
                if ($res === false) {
                    WADA_Log::error('Event->storeEventInfos: ' . $wpdb->last_error);
                    WADA_Log::error('Event->storeEventInfos query was: ' . $preparedQuery);
                } else {
                    WADA_Log::debug('Event->storeEventInfos ok, inserted id: ' . $wpdb->insert_id);
                }
            }
        }else{
            WADA_Log::debug('Event->storeEventInfos no infos to store for event '.$eventId);
        }
    }

    protected static function na($event, $attribute){
        $default = 'n/a';
        if(property_exists($event, $attribute)){
            return is_null($event->$attribute) ? $default : $event->$attribute;
        }
        return $default;
    }

    protected static function getEventCheckValue($event, $headerOnly=true){
        $header = array();
        if(!$headerOnly){
            $header[] = self::na($event, 'id');
        }
        $header[] = self::na($event, 'occurred_on');
        $header[] = self::na($event, 'sensor_id');
        $header[] = self::na($event, 'site_id');
        $header[] = self::na($event, 'user_id');
        $header[] = self::na($event, 'user_name');
        $header[] = self::na($event, 'user_email');
        $header[] = self::na($event, 'object_type');
        $header[] = self::na($event, 'object_id');
        $header[] = self::na($event, 'source_ip');
        $header[] = self::na($event, 'source_client');
        if(!$headerOnly){
            $header[] = self::na($event, 'check_value_head');
            if(property_exists( $event, 'infos') && is_array($event->infos) && count($event->infos) > 0){
                foreach($event->infos AS $info){
                    $header[] = (self::na($info, 'id') . '-' . self::na($info, 'info_key') . '-' . self::na($info, 'info_value') . '-' . self::na($info, 'prior_value'));
                }
            }
        }
        $str = implode('-/-', $header);
        return hash('sha256', $str);
    }

    public static function getHeaderCheckValue($event){
       return self::getEventCheckValue($event);
    }

    public static function getFullCheckValue($event){
        return self::getEventCheckValue($event, false);
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){
            global $wpdb;
            $query = "SELECT evt.*, sen.name as sensor_name, sen.description as sensor_description, sen.severity, sen.event_group, sen.event_category FROM ".$this->getTable()." evt "
            ."LEFT JOIN ".WADA_Database::tbl_sensors() ." sen ON (evt.sensor_id = sen.id) "
            ."WHERE evt.id = %d ";
            $eventObj = $wpdb->get_row($wpdb->prepare($query, $this->_id));

            if($eventObj){
                $eventObj->user = null;
                if($eventObj->user_id > 0){
                    $eventObj->user = get_userdata(absint($eventObj->user_id));
                }
                $occurredOn = WADA_DateUtils::formatUTCasDatetimeForWP($eventObj->occurred_on);
                $sensorName = $eventObj->sensor_name ? $eventObj->sensor_name : sprintf(__('Sensor ID %d', 'wp-admin-audit'), $eventObj->sensor_id);
                $eventObj->summary_full = trim('#'.$eventObj->id.' '.$sensorName.' / '.$occurredOn);
                $eventObj->summary_short = trim('#'.$eventObj->id.' '.$sensorName);

                if(method_exists('WADA_Model_Sensor', 'getSeverityNameForLevel')) {
                    $eventObj->severity_text = WADA_Model_Sensor::getSeverityNameForLevel($eventObj->severity, strval($eventObj->severity));
                }else{
                    $eventObj->severity_text = $eventObj->severity;
                }

                $query = 'SELECT * FROM ' . WADA_Database::tbl_event_infos() . ' WHERE event_id = %d ORDER BY id';
                $eventObj->infos = $wpdb->get_results($wpdb->prepare($query, $this->_id));

                $eventObj->audit_head = null;
                $eventObj->audit_full = null;

                /* @@REMOVE_START_WADA_business@@ */
                /* @@REMOVE_START_WADA_startup@@ */
                /*  */
                /* @@REMOVE_END_WADA_startup@@ */
                /* @@REMOVE_END_WADA_business@@ */

            }

            if($onlyReturnNoInternalUpdate){
                return $eventObj;
            }
            $this->_data = $eventObj;
            return true;

        }
        return false;
    }

}
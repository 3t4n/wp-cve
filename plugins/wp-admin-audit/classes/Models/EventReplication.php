<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_EventReplication extends WADA_Model_Base
{
    public function __construct($id = null){
        parent::__construct($id);
    }

    public static function getTable(){
        return WADA_Database::tbl_event_replications();
    }

    public function getAttributes(){
        return array(
            'id', 'event_id', 'replicator_id',
            'queued_at', 'replication_attempts', 'replication_completed'
        );
    }

    protected function check(){
        if(!$this->_data){
            $this->_last_error = __('No data object provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->event_id){
            $this->_last_error = __('No event ID provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->replicator_id){
            $this->_last_error = __('No replicator ID provided', 'wp-admin-audit');
            return false;
        }
        return true;
    }

    protected function save(){
        if(!property_exists($this->_data, 'queued_at') || empty($this->_data->queued_at)){
            $this->_data->queued_at = WADA_DateUtils::getUTCforMySQLDate();
        }

        global $wpdb;
        $query = 'INSERT INTO '.WADA_Model_EventReplication::getTable();
        $query .= ' (';
        $query .= 'id,';
        $query .= 'event_id,';
        $query .= 'replicator_id,';
        $query .= 'queued_at,';
        $query .= 'replication_attempts,';
        $query .= 'replication_completed';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%d,'; // event_id
        $query .= '%d,'; // replicator_id
        $query .= '%s,'; // queued_at
        $query .= '%d,'; // replication_attempts
        $query .= '%d'; // replication_completed
        $query .= ')';
        $preparedQuery = $wpdb->prepare($query,
            null,
            $this->_data->event_id,
            $this->_data->replicator_id,
            $this->_data->queued_at,
            $this->_data->replication_attempts,
            $this->_data->replication_completed
        );
        $preparedQuery = str_replace("''", "NULL", $preparedQuery);
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('EventReplication->save: '.$wpdb->last_error);
            WADA_Log::error('EventReplication->save query was: '.$preparedQuery);
            return false;
        }else{
            $this->_id = $wpdb->insert_id;
            $this->_data->id = $this->_id;
            WADA_Log::debug('EventReplication->save ok inserted id: '.$this->_id);
        }
        return $this->_id;
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){
            global $wpdb;
            $query = "SELECT id, event_id, replicator_id,"
            ." queued_at, replication_attempts, replication_completed"
            ." FROM ".WADA_Model_EventReplication::getTable()." er"
            ." WHERE er.id = %d ";
            $eventReplObj = $wpdb->get_row($wpdb->prepare($query, $this->_id));

            if($eventReplObj){
                $eventReplObj->queued_at_localized = WADA_DateUtils::formatUTCasDatetimeForWP($eventReplObj->queued_at);
            }

            if($onlyReturnNoInternalUpdate){
                return $eventReplObj;
            }
            $this->_data = $eventReplObj;
            return true;

        }
        return false;
    }

}
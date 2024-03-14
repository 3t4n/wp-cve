<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Maintenance
{
    public static function scheduledRun(){
        WADA_Log::info('Maintenance->scheduledRun');
        self::doUserAccountAutoAdjustments();
        self::sendUserPasswordChangeReminders();
        self::cleanupEventLog();
    }
    
    public static function doUserAccountAutoAdjustments(){
        if(WADA_Version::getFtSetting(WADA_Version::FT_ID_UAC_AUTO_A)){
            WADA_UserUtils::doUserAccountAutoAdjustments();
        }
    }

    public static function sendUserPasswordChangeReminders(){
        if(WADA_Version::getFtSetting(WADA_Version::FT_ID_UAC_ENF_PWC)){
            WADA_UserUtils::sendUserPasswordChangeReminders();
        }
    }

    public static function cleanupEventLog(){
        WADA_Log::debug('cleanupEventLog');
        $retentionPeriodInDays = WADA_Settings::getRetentionPeriodInDays();
        if($retentionPeriodInDays > 0){
            $currentUtc = WADA_DateUtils::getUTCforMySQLTimestamp();
            WADA_Log::debug('Maintenance->cleanupEventLog Keep event log for '.$retentionPeriodInDays.' days');

            global $wpdb;
            $sql= "SELECT evt.id, occurred_on "
                ."FROM ".WADA_Database::tbl_events() . " evt "
                ."WHERE evt.occurred_on < DATE_SUB('".$currentUtc."', INTERVAL ".$retentionPeriodInDays." DAY)";
            WADA_Log::debug('SQL: '.$sql);
            $eventsToBeDeleted = $wpdb->get_results($sql);
            WADA_Log::debug('Events to delete: '.(count($eventsToBeDeleted) ? count($eventsToBeDeleted).', '.print_r($eventsToBeDeleted, true) : 'zero'));
            if(count($eventsToBeDeleted)){
                $eventIds = array_column($eventsToBeDeleted, 'id');
                $eventIdsList = implode( ',', $eventIds );

                $deleteQueries = array();

                $deleteQueries[] = (object)array('table'=>'event_notifications', 'query'=>'DELETE FROM '.WADA_Database::tbl_event_notifications().' WHERE event_id IN ('. $eventIdsList .')');
                $deleteQueries[] = (object)array('table'=>'event_infos', 'query'=>'DELETE FROM '.WADA_Database::tbl_event_infos().' WHERE event_id IN ('. $eventIdsList .')');
                $deleteQueries[] = (object)array('table'=>'events', 'query'=>'DELETE FROM '.WADA_Database::tbl_events().' WHERE id IN ('. $eventIdsList .')');

                foreach($deleteQueries AS $delQuery){
                    $res = $wpdb->query($delQuery->query);
                    if( $res === false ) {
                        WADA_Log::error('cleanupEventLog: '.$wpdb->last_error);
                        WADA_Log::error('cleanupEventLog query was: '.$delQuery->query);
                        return false;
                    }else{
                        WADA_Log::debug('cleanupEventLog for '.$delQuery->table.': '.$res.' items deleted');
                    }
                }
                WADA_Log::debug('Maintenance->cleanupEventLog deletions finished');
            }

        }else{
            WADA_Log::debug('Maintenance->cleanupEventLog Keep event log indefinitely');
        }
        return true;
    }


}
<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_Stats extends WADA_Model_BaseReadOnly
{
    public function __construct($options = array()){
        parent::__construct($options);
    }

    protected function loadData($options = array()){
        $res = array();
        //WADA_Log::debug('WADA_Model_Stats->loadData with options: '.print_r($options, true));
        if(array_search('general_events', $options) !== false){
            $res['general_events'] = $this->getEventCounts();
        }
        if(array_search('general_sensors', $options) !== false){
            $res['general_sensors'] = $this->getSensorCounts();
        }
        if(array_search('first_event', $options) !== false){
            $res['first_event'] = $this->getFirstEvent();
        }
        if(array_search('general_notifications', $options) !== false){
            $res['general_notifications'] = $this->getNotificationCounts();
        }
        if(array_search('inactive_admins', $options) !== false){
            $res['inactive_admins'] = $this->getInactiveAdminCounts();
        }
        if(array_search('login_attempts', $options) !== false){
            $res['login_attempts_7d'] = $this->getLoginAttempts('7d');
            $res['login_attempts_30d'] = $this->getLoginAttempts('30d');
            $res['login_attempts_90d'] = $this->getLoginAttempts('90d');
        }
        $this->_data = $res;
    }

    protected function getFirstEvent(){
        global $wpdb;
        $res = null;
        $sql = 'SELECT * FROM '.WADA_Database::tbl_events().' WHERE occurred_on IN (SELECT MIN(occurred_on) FROM '.WADA_Database::tbl_events().') LIMIT  1';
        $firstEvent = $wpdb->get_row($sql);
        if($firstEvent){
            $res = new stdClass();
            $res->id = $firstEvent->id;
            $res->date_utc = $firstEvent->occurred_on;
            $res->date_wp = WADA_DateUtils::formatUTCasDatetimeForWP($res->date_utc);
        }
        return $res;
    }

    public function getEventCounts(){
        global $wpdb;

        $sql = 'SELECT severity, sum(event_cr) as sev_cr '
            .'FROM ( '
            .'SELECT ev.sensor_id, ev.event_cr, sen.severity '
            .'FROM (SELECT sensor_id, count(*) as event_cr FROM '.WADA_Database::tbl_events().' GROUP BY sensor_id) ev '
            .'LEFT JOIN '.WADA_Database::tbl_sensors().' sen ON (ev.sensor_id = sen.id) '
            .') sev_stats GROUP BY severity';
        $eventCounts = $wpdb->get_results($sql);
        $severitiesOnFile = array_column($eventCounts, 'severity');
        $severityLevels = WADA_Model_Sensor::getSeverityLevels(true);

        $totalEventCount = 0;
        $severityResult = array();
        foreach($severityLevels AS $severityLevel => $severityName){
            $resultObj = new stdClass();
            $resultObj->severity = $severityLevel;
            $resultObj->name = $severityName;
            $found = array_search($severityLevel, $severitiesOnFile);
            if($found === false){
                $resultObj->count = 0;
            }else{
                $resultObj->count = $eventCounts[$found]->sev_cr;
            }
            $totalEventCount += $resultObj->count;
            $severityResult[] = $resultObj;
        }

        $result = new stdClass();
        $result->totalEvents = $totalEventCount;
        $result->bySeverityLevel = $severityResult;
        //WADA_Log::debug('WADA_Model_Stats->getEventCounts result: '.print_r($result, true));

        return $result;
    }

    public function getTopEventTypes($returnFirstX = 5){
        global $wpdb;
        $returnFirstX = intval($returnFirstX);

        $sql = 'SELECT evts.*, sen.name AS sensor_name '
            .'FROM ( '
                .'SELECT sensor_id, count(*) AS nr_events  '
                .'FROM '.WADA_Database::tbl_events().' '
                .'GROUP BY sensor_id '
            .') evts '
            .'LEFT JOIN '.WADA_Database::tbl_sensors().' sen ON (evts.sensor_id = sen.id) '
            .'ORDER BY nr_events DESC '
            .'LIMIT '.$returnFirstX;
        return $wpdb->get_results($sql);
    }


    public function getNrEventsOfLastXDays($returnOfXDays = 7){
        global $wpdb;
        $returnOfXDays = intval($returnOfXDays);
        $sql = 'SELECT COUNT(*) AS nr_events  '
            .'FROM '.WADA_Database::tbl_events().' evt  '
            .'WHERE (evt.occurred_on >= DATE(NOW() - INTERVAL '.intval($returnOfXDays).' DAY))';
        return $wpdb->get_var($sql);
    }

    public function getLoginAttempts($timeFrame = '7d'){
        $loginsView = new WADA_View_Logins();
        $_REQUEST['timef'] = $timeFrame; // for filtering of login attempts
        $nrIpAddresses = $loginsView->getNrOfItems();
        WADA_Log::debug('getLoginAttempts: '.print_r($nrIpAddresses, true));
        return $nrIpAddresses;
    }

    protected function getInactiveUsersCountFor($inactiveSinceDays, $userIdsInScope, $currUtc){
        $inActiveUsersQuery = WADA_UserUtils::getInactiveUsersQuery($inactiveSinceDays, $userIdsInScope, $currUtc);
        global $wpdb;
        $sql= "SELECT count(*) as inactive_cr FROM (".$inActiveUsersQuery." ) inact";
        return $wpdb->get_var($sql);
    }

    public function getInactiveAdminCounts(){
        $currUtc = WADA_DateUtils::getUTCforMySQLTimestamp();
        $allAdminIds = get_users(
            array(
                'fields' => 'ID',
                'role__in' => array('administrator')
            )
        );

        $result = array();
        $checkInActiveDays = array(7, 14, 30, 90);
        foreach($checkInActiveDays AS $day){
            $inActiveCr = $this->getInactiveUsersCountFor($day, $allAdminIds, $currUtc);
            if($inActiveCr > 0){
                $result[] = (object)array('days' => $day, 'nr_inactive' => $inActiveCr);
            }
        }

        return $result;
    }

    public function getSensorCounts(){
        global $wpdb;

        $sql = 'SELECT active, count(*) as sensor_cr FROM '.WADA_Database::tbl_sensors().' GROUP BY active';
        $sensorCounts = $wpdb->get_results($sql);

        $totalSensors = 0;
        $sensorStatusCount = new stdClass();
        $sensorStatusCount->active = 0;
        $sensorStatusCount->inactive = 0;
        foreach($sensorCounts AS $sensorStatus){
            $totalSensors += $sensorStatus->sensor_cr;
            if($sensorStatus->active == 1){
                $sensorStatusCount->active += $sensorStatus->sensor_cr;
            }else{
                $sensorStatusCount->inactive += $sensorStatus->sensor_cr;
            }
        }
        $result = new stdClass();
        $result->totalSensors = $totalSensors;
        $result->bySensorStatus = $sensorStatusCount;
        WADA_Log::debug('WADA_Model_Stats->getSensorCounts result: '.print_r($result, true));

        return $result;
    }




    public function getNotificationCounts(){
        global $wpdb;

        $sql = 'SELECT active, count(*) as notification_cr FROM '.WADA_Database::tbl_notifications().' GROUP BY active';
        $notificationCounts = $wpdb->get_results($sql);

        $totalNotifications = 0;
        $notificationStatusCount = new stdClass();
        $notificationStatusCount->active = 0;
        $notificationStatusCount->inactive = 0;
        foreach($notificationCounts AS $notificationStatus){
            $totalNotifications += $notificationStatus->notification_cr;
            if($notificationStatus->active == 1){
                $notificationStatusCount->active += $notificationStatus->notification_cr;
            }else{
                $notificationStatusCount->inactive += $notificationStatus->notification_cr;
            }
        }
        $result = new stdClass();
        $result->totalNotifications = $totalNotifications;
        $result->byNotificationStatus = $notificationStatusCount;

        $sql = 'SELECT count(*) as event_notification_cr FROM '.WADA_Database::tbl_event_notifications().' ';
        $eventNotificationCr = $wpdb->get_var($sql);
        $result->eventNotificationCr = $eventNotificationCr;

        $sql = 'SELECT count(*) as queue_cr FROM '.WADA_Database::tbl_notification_queue().' ';
        $queueCr = $wpdb->get_var($sql);
        $result->queueCr = $queueCr;

        return $result;
    }
}
<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Model_Notification extends WADA_Model_Base
{
    public function __construct($id = null){
        parent::__construct($id);
    }

    public static function getTable(){
        return WADA_Database::tbl_notifications();
    }

    public function getAttributes(){
        return array(
            'id', 'name', 'active',
            'triggers', // this is from/for the notification_triggers table
            'targets' // this is from/for the notification_targets table
        );
    }

    protected function check(){
        if(!$this->_data){
            $this->_last_error = __('No data object provided', 'wp-admin-audit');
            return false;
        }
        if(!$this->_data->name){
            $this->_last_error = __('No name provided', 'wp-admin-audit');
            return false;
        }
        return true;
    }

    public function delete(){
        if($this->_id > 0){
            $notificationBeforeDeletion = $this->loadData(true);
            $eventNotifications = WADA_Notification_Queue::getEventNotificationsForNotificationId($this->_id);
            WADA_Log::debug('Notification->delete ID ' . $this->_id . ', connected to #event notifications: ' . count($eventNotifications));
            if(count($eventNotifications)) {
                $eventNotificationIds = array_map(function ($o) { return $o->id;}, $eventNotifications);
                $this->deleteLogOfNotification($eventNotificationIds);
                $this->deleteQueueOfNotification($eventNotificationIds);
                $this->deleteEventNotifications($eventNotificationIds);
            }
            $this->deleteTriggersOfNotification($this->_id);
            $this->deleteTargetsOfNotification($this->_id);
            $this->deleteRowById();
            do_action('wp_admin_audit_notification_delete', $this->_id, $notificationBeforeDeletion);
        }
    }

    protected function save(){
        $notificationBeforeUpdate = $this->loadData(true);
        $isUpdateOfExistingNotification = ($this->_data->id && $this->_data->id > 0);
        global $wpdb;
        $query = 'INSERT INTO '.WADA_Model_Notification::getTable();
        $query .= ' (';
        $query .= 'id,';
        $query .= 'name,';
        $query .= 'active';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%s,'; // name
        $query .= '%d'; // active
        $query .= ') ON DUPLICATE KEY UPDATE name = %s, active = %d';
        $preparedQuery = $wpdb->prepare($query,
            $isUpdateOfExistingNotification ? $this->_data->id : null,
            $this->_data->name,
            $this->_data->active,
            $this->_data->name, // for ON DUPLICATE KEY UPDATE part
            $this->_data->active // for ON DUPLICATE KEY UPDATE part
        );
        $preparedQuery = str_replace("''", "NULL", $preparedQuery);
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('Notification->save: '.$wpdb->last_error);
            WADA_Log::error('Notification->save query was: '.$preparedQuery);
            return false;
        }else{
            if($isUpdateOfExistingNotification) {
                $this->_id = $this->_data->id;
                WADA_Log::debug('Notification->save ok updated id: '.$this->_id);
            }else{
                if($wpdb->insert_id > 0) {
                    $this->_id = $wpdb->insert_id;
                }
                WADA_Log::debug('Notification->save ok inserted id: '.$this->_id);
            }
            if($this->_id > 0){
                $this->storeNotificationTriggers($this->_id, $this->_data->triggers);
                $this->storeNotificationTargets($this->_id, $this->_data->targets);
            }
            $notificationAfterUpdate = $this->loadData(true);

            if($isUpdateOfExistingNotification){
                do_action('wp_admin_audit_notification_update', $this->_id, $notificationAfterUpdate, $notificationBeforeUpdate);
            }else{
                do_action('wp_admin_audit_notification_create', $this->_id, $notificationAfterUpdate);
            }

        }
        return $this->_id;
    }

    /**
     * @param $sensorId
     * @param bool $idsOnly
     * @return array[]
     */
    public static function findActiveNotificationsForSensorId($sensorId, $idsOnly=true){
        global $wpdb;
        $sensorId = intval($sensorId);
        $notifications = array();
        if($sensorId) {
            $query =
                  "SELECT notification_id"
                . " FROM ("
                . " SELECT DISTINCT notification_id FROM " . WADA_Database::tbl_notification_triggers()
                . " WHERE (trigger_type = 'severity' and trigger_id IN (SELECT severity FROM " . WADA_Database::tbl_sensors() . " where id='" . $sensorId . "'))"
                . " OR (trigger_type = 'sensor' and trigger_id='" . $sensorId . "')"
                . " ) trignoti"
                . " LEFT JOIN " .WADA_Model_Notification::getTable() . " noti ON (trignoti.notification_id = noti.id)"
                . " WHERE noti.active = '1'";
            $notificationIds = $wpdb->get_col($query);

            if($idsOnly){
                return $notificationIds;
            }

            foreach($notificationIds AS $notificationId){
                $model = new static($notificationId);
                $notifications[] = $model->_data;
            }
        }
        return $notifications;
    }

    /**
     * @param array $targets
     * @return array[]
     */
    public static function separateNotificationTargets($targets){
        $userTargets = array();
        $roleTargets = array();
        $emailTargets = array();
        $integrationTargets = array();
        foreach($targets AS $target){
            if($target->target_type === 'wp_user'){
                $userTargets[] = $target;
            }elseif($target->target_type === 'wp_role'){
                $roleTargets[] = $target;
            }elseif($target->target_type === 'email'){
                $emailTargets[] = $target;
            }elseif($target->target_type === 'logsnag'){
                $integrationTargets[] = $target;
            }
        }
        return array($userTargets, $roleTargets, $emailTargets, $integrationTargets);
    }

    /**
     * @param array $triggers
     * @return array[]
     */
    public static function separateNotificationTriggers($triggers){
        $severityTriggers = array();
        $sensorTriggers = array();
        foreach($triggers AS $trigger){
            if($trigger->trigger_type === 'sensor'){
                $sensorTriggers[] = $trigger;
            }elseif($trigger->trigger_type === 'severity'){
                $severityTriggers[] = $trigger;
            }
        }
        return array($severityTriggers, $sensorTriggers);
    }

    /**
     * @param array $triggers
     * @param array $options
     * @return string
     */
    public static function printOverviewOfTriggers($triggers, $options=array('subheader'=>'h4','linebreaks'=>false)){
        list($severityTriggers, $sensorTriggers) = self::separateNotificationTriggers($triggers);
        $html = '<'.$options['subheader'].'>'.__('Severity levels', 'wp-admin-audit').':</'.$options['subheader'].'> ';
        if(count($severityTriggers)) {
            $sevLevelNames = array();
            foreach ($severityTriggers as $severityTrigger) {
                $sevLevelNames[] = WADA_Model_Sensor::getSeverityNameForLevel($severityTrigger->trigger_id, '');
            }
            $html .= implode(', ', $sevLevelNames);
        }else{
            $html .= __('None', 'wp-admin-audit');
        }
        if($options['linebreaks']){
            $html .= '<br/>';
        }
        $html .= '<'.$options['subheader'].'>'.__('Sensor / event types', 'wp-admin-audit').':</'.$options['subheader'].'> ';
        if(count($sensorTriggers)){
            $sensorNames = array();
            foreach($sensorTriggers as $sensorTrigger){
                $sensorModel = new WADA_Model_Sensor($sensorTrigger->trigger_id);
                $sensorName = $sensorTrigger->id > 0 ? $sensorModel->_data->name : '';
                $sensorNames[] = $sensorName;
            }
            $html .= implode(', ', $sensorNames);
        }else{
            $html .= __('None', 'wp-admin-audit');
        }
        return $html;
    }

    /**
     * @param array $targets
     * @param array $options
     * @return string
     */
    public static function printOverviewOfTargets($targets, $options=array('subheader'=>'h4','linebreaks'=>false)){
        list($userTargets, $roleTargets, $emailTargets, $integrationTargets) = self::separateNotificationTargets($targets);

        $html = '<'.$options['subheader'].'>'.__('WP roles', 'wp-admin-audit').':</'.$options['subheader'].'> ';
        if(count($roleTargets)){
            $roleTargets = array_map(function($o) { return $o->target_str_id; }, $roleTargets);
            global $wp_roles;
            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
            $roleNames = array();
            $roles = $wp_roles->get_names();
            foreach($roles AS $role => $roleName){
                if(in_array($role, $roleTargets)){
                    $roleNames[] = $roleName;
                }
            }
            $html .= implode(', ', $roleNames);
        }else{
            $html .= __('None', 'wp-admin-audit');
        }
        if($options['linebreaks']){
            $html .= '<br/>';
        }

        $html .= '<'.$options['subheader'].'>'.__('WP users', 'wp-admin-audit').':</'.$options['subheader'].'> ';
        if(count($userTargets)) {
            $userTargets = array_map(function ($o) { return '#' . $o->target_id. ' ' . get_user_option('display_name', $o->target_id); }, $userTargets);
            $html .= implode(', ', $userTargets);
        }else{
            $html .= __('None', 'wp-admin-audit');
        }
        if($options['linebreaks']){
            $html .= '<br/>';
        }

        $html .= '<'.$options['subheader'].'>'.__('Email addresses', 'wp-admin-audit').':</'.$options['subheader'].'> ';
        if(count($emailTargets)) {
            $emailTargets = array_map(function ($o) { return $o->target_str_id; }, $emailTargets);
            $html .= implode(', ', $emailTargets);
        }else{
            $html .= __('None', 'wp-admin-audit');
        }
        if($options['linebreaks']){
            $html .= '<br/>';
        }

        $html .= '<'.$options['subheader'].'>'.__('Integrations', 'wp-admin-audit').':</'.$options['subheader'].'> ';
        if(count($integrationTargets)) {
            $integrationTargets = array_map(function ($o) { return WADA_Notification_Sender::getChannelName($o->channel_type); }, $integrationTargets);
            $html .= implode(', ', $integrationTargets);
        }else{
            $html .= __('None', 'wp-admin-audit');
        }
        return $html;
    }

    /**
     * @param int $notificationId
     * @return array|null
     */
    public static function getLogsnagTargetOfNotification($notificationId){
        $logsnagTargets = array();
        $notificationId = intval($notificationId);
        if($notificationId) {
            global $wpdb;
            $query = " SELECT notification_id, target_type"
                . " FROM " . WADA_Database::tbl_notification_targets()
                . " WHERE notification_id='" . $notificationId . "'"
                . " AND target_type = 'logsnag'"
                . " AND channel_type = 'logsnag'"
                . " GROUP BY notification_id, target_type";
            $logsnagTargets = $wpdb->get_row($query);
        }
        return $logsnagTargets;
    }

    /**
     * @param int $notificationId
     * @return array|null
     */
    public static function getEmailRecipientsOfNotification($notificationId){
        $recipients = array();
        $notificationId = intval($notificationId);
        if($notificationId) {
            global $wpdb;
            $query = "SELECT notification_id, email_address, GROUP_CONCAT(DISTINCT recip_src ORDER BY recip_src SEPARATOR ', ') AS recip_src"
                . " FROM ("
                . " SELECT notification_id, CONCAT('wp_role ',wp_role,' user ',user_id) AS recip_src, CONVERT(wpusr.user_email USING utf8mb4) as email_address"
                . " FROM ("
                . " SELECT tgts.notification_id, tgts.target_str_id as wp_role, wpusrm.user_id"
                . " FROM " . WADA_Database::tbl_notification_targets() . " tgts, " . $wpdb->prefix . "usermeta wpusrm"
                . " WHERE tgts.notification_id='" . $notificationId . "'"
                . " AND tgts.target_type = 'wp_role'"
                . " AND tgts.channel_type = 'email'"
                . " AND CONVERT(wpusrm.meta_value USING utf8mb4) LIKE CONVERT(CONCAT('%',tgts.target_str_id,'%') USING utf8mb4)"
                . " ) role_users"
                . " LEFT JOIN " . $wpdb->prefix . "users wpusr ON (role_users.user_id = wpusr.ID)"
                . " UNION ALL"
                . " SELECT tgts.notification_id, 'email' AS recip_src, CONVERT(tgts.target_str_id USING utf8mb4) as email_address"
                . " FROM " . WADA_Database::tbl_notification_targets() . " tgts"
                . " WHERE tgts.notification_id='" . $notificationId . "'"
                . " AND tgts.target_type = 'email'"
                . " AND tgts.channel_type = 'email'"
                . " UNION ALL"
                . " SELECT notification_id, CONCAT('wp_user ',user_id) AS recip_src, CONVERT(wpusr.user_email USING utf8mb4) as email_address"
                . " FROM ("
                . " SELECT tgts.notification_id, tgts.target_id as user_id"
                . " FROM " . WADA_Database::tbl_notification_targets() . " tgts"
                . " WHERE tgts.notification_id='" . $notificationId . "'"
                . " AND tgts.target_type = 'wp_user'"
                . " AND tgts.channel_type = 'email'"
                . " ) tgtusr"
                . " LEFT JOIN " . $wpdb->prefix . "users wpusr ON (tgtusr.user_id = wpusr.ID)"
                . " ) res"
                . " GROUP BY notification_id, email_address";
            $recipients = $wpdb->get_results($query);
        }
        return $recipients;
    }

    /**
     * @param array<int> $eventNotificationIds
     */
    protected function deleteLogOfNotification($eventNotificationIds){
        global $wpdb;
        $eventNotificationIds = implode(', ', array_map('intval', $eventNotificationIds));
        $deleteQuery = 'DELETE FROM ' . WADA_Database::tbl_event_notification_log() . ' WHERE event_notification_id IN (' . $eventNotificationIds . ')';
        $result = $wpdb->query($deleteQuery);
        WADA_Log::debug('Notification->deleteLogOfNotification Deleted for event notification IDs '.$eventNotificationIds.' existing #log entries: '.$result);
        return $result;
    }

    /**
     * @param array<int> $eventNotificationIds
     */
    protected function deleteQueueOfNotification($eventNotificationIds){
        global $wpdb;
        $eventNotificationIds = implode(', ', array_map('intval', $eventNotificationIds));
        $deleteQuery = 'DELETE FROM ' . WADA_Database::tbl_notification_queue_map() . ' WHERE event_notification_id IN (' . $eventNotificationIds . ')';
        $result = $wpdb->query($deleteQuery);
        WADA_Log::debug('Notification->deleteQueueOfNotification Deleted for event notification IDs '.$eventNotificationIds.' existing #queue map entries: '.$result);

        // We need to clean up the notification_queue table as well,
        // but only for entries that are not referenced by another queue_map entry (of another still existing notification)
        $sql = 'SELECT id'
                .' FROM ('
                    .' SELECT qu.id, IFNULL(qm.nr_ref,0) as nr_ref'
                    .' FROM '.WADA_Database::tbl_notification_queue().' qu'
                    .' LEFT JOIN ('
                        .' SELECT queue_id, COUNT(*) AS nr_ref'
                        .' FROM '.WADA_Database::tbl_notification_queue_map().' GROUP BY queue_id'
                    .' ) qm ON (qu.id = qm.queue_id)'
                .' ) res'
                .' WHERE nr_ref = 0';
        $queueIds = $wpdb->get_col($sql);
        WADA_Log::debug('Notification->deleteQueueOfNotification #queue entries now without reference in queue_map: '.count($queueIds));
        if(count($queueIds)){
            $queueIds = implode(', ', array_map('intval', $queueIds));
            $deleteQuery = 'DELETE FROM ' . WADA_Database::tbl_notification_queue() . ' WHERE id IN (' . $queueIds . ')';
            $result = $wpdb->query($deleteQuery);
            WADA_Log::debug('Notification->deleteQueueOfNotification Deleted for queue entries '.$queueIds.' existing #queue entries: '.$result);
        }

        return $result;
    }

    /**
     * @param array<int> $eventNotificationIds
     */
    protected function deleteEventNotifications($eventNotificationIds){
        global $wpdb;
        $eventNotificationIds = implode(', ', array_map('intval', $eventNotificationIds));
        $deleteQuery = 'DELETE FROM ' . WADA_Database::tbl_event_notifications() . ' WHERE id IN (' . $eventNotificationIds . ')';
        $result = $wpdb->query($deleteQuery);
        WADA_Log::debug('Notification->deleteEventNotifications Deleted for event notification IDs '.$eventNotificationIds.' existing #event notification entries: '.$result);
        return $result;
    }

    protected function deleteTriggersOfNotification($notificationId){
        global $wpdb;
        $deleteExistingQuery = 'DELETE FROM ' . WADA_Database::tbl_notification_triggers() . ' WHERE notification_id =\'' . $notificationId . '\'';
        $result = $wpdb->query($deleteExistingQuery);
        WADA_Log::debug('Notification->deleteTriggersOfNotification Deleted for notification ID '.$notificationId.' existing #triggers: '.$result);
        return $result;
    }

    protected function deleteTargetsOfNotification($notificationId){
        global $wpdb;
        $deleteExistingQuery = 'DELETE FROM ' . WADA_Database::tbl_notification_targets() . ' WHERE notification_id =\'' . $notificationId . '\'';
        $result = $wpdb->query($deleteExistingQuery);
        WADA_Log::debug('Notification->deleteTargetsOfNotification Deleted for notification ID '.$notificationId.' existing #targets: '.$result);
        return $result;
    }

    protected function storeNotificationTriggers($notificationId, $triggers){
        global $wpdb;
        $notificationId = intval($notificationId);
        if($notificationId > 0) {
            WADA_Log::debug('Notification->storeNotificationTriggers for notification ' . $notificationId . ', triggers: ' . print_r($triggers, true));
            $this->deleteTriggersOfNotification($notificationId);

            if (is_array($triggers) && count($triggers) > 0) {
                foreach ($triggers as $trigger) {
                    $triggerObj = (object)$trigger;
                    $query = 'INSERT INTO ' . WADA_Database::tbl_notification_triggers();
                    $query .= ' (';
                    $query .= 'id,';
                    $query .= 'notification_id,';
                    $query .= 'trigger_type,';
                    $query .= 'trigger_id,';
                    $query .= 'trigger_str_id';
                    $query .= ') VALUES (';
                    $query .= '%d,'; // id
                    $query .= '%d,'; // notification_id
                    $query .= '%s,'; // trigger_type
                    $query .= '%d,'; // trigger_id
                    $query .= '%s'; // trigger_str_id
                    $query .= ')';
                    $preparedQuery = $wpdb->prepare($query,
                        null, // id
                        $notificationId,
                        $triggerObj->trigger_type,
                        $triggerObj->trigger_id,
                        $triggerObj->trigger_str_id
                    );
                    $preparedQuery = str_replace("''", "NULL", $preparedQuery);
                    $res = $wpdb->query($preparedQuery);
                    if ($res === false) {
                        WADA_Log::error('Notification->storeNotificationTriggers: ' . $wpdb->last_error);
                        WADA_Log::error('Notification->storeNotificationTriggers query was: ' . $preparedQuery);
                    } else {
                        WADA_Log::debug('Notification->storeNotificationTriggers ok, inserted id: ' . $wpdb->insert_id);
                    }
                }
            } else {
                WADA_Log::warning('Notification->storeNotificationTriggers no triggers to store for notification ID: ' . $notificationId);
            }
        } else {
            WADA_Log::warning('Notification->storeNotificationTriggers cannot work with notification ID: ' . $notificationId);
        }
    }

    protected function storeNotificationTargets($notificationId, $targets){
        global $wpdb;
        $notificationId = intval($notificationId);
        if($notificationId > 0) {
            WADA_Log::debug('Notification->storeNotificationTargets for notification '.$notificationId.', targets: '.print_r($targets, true));
            $this->deleteTargetsOfNotification($notificationId);

            if(is_array($targets) && count($targets)>0){
                foreach($targets AS $target) {
                    $targetObj = (object)$target;
                    $query = 'INSERT INTO ' . WADA_Database::tbl_notification_targets();
                    $query .= ' (';
                    $query .= 'id,';
                    $query .= 'notification_id,';
                    $query .= 'channel_type,';
                    $query .= 'target_type,';
                    $query .= 'target_id,';
                    $query .= 'target_str_id';
                    $query .= ') VALUES (';
                    $query .= '%d,'; // id
                    $query .= '%d,'; // notification_id
                    $query .= '%s,'; // channel_type
                    $query .= '%s,'; // target_type
                    $query .= '%d,'; // target_id
                    $query .= '%s'; // target_str_id
                    $query .= ')';
                    $preparedQuery = $wpdb->prepare($query,
                        null, // id
                        $notificationId,
                        $targetObj->channel_type,
                        $targetObj->target_type,
                        $targetObj->target_id,
                        $targetObj->target_str_id
                    );
                    $preparedQuery = str_replace("''", "NULL", $preparedQuery);
                    $res = $wpdb->query($preparedQuery);
                    if ($res === false) {
                        WADA_Log::error('Notification->storeNotificationTargets: ' . $wpdb->last_error);
                        WADA_Log::error('Notification->storeNotificationTargets query was: ' . $preparedQuery);
                    } else {
                        WADA_Log::debug('Notification->storeNotificationTargets ok, inserted id: ' . $wpdb->insert_id);
                    }
                }
            }else{
                WADA_Log::warning('Notification->storeNotificationTargets no targets to store for notification '.$notificationId);
            }
        } else {
            WADA_Log::warning('Notification->storeNotificationTargets cannot work with notification ID: ' . $notificationId);
        }
    }

    protected function loadData($onlyReturnNoInternalUpdate = false){
        if($this->_id){
            global $wpdb;
            $query = "SELECT * FROM ".$this->getTable()." notifi "
            ."WHERE notifi.id = %d ";
            $notificationObj = $wpdb->get_row($wpdb->prepare($query, $this->_id));

            if($notificationObj){
                $query = 'SELECT * FROM ' . WADA_Database::tbl_notification_triggers() . ' WHERE notification_id = %d ORDER BY id';
                $notificationObj->triggers = $wpdb->get_results($wpdb->prepare($query, $this->_id));

                $query = 'SELECT * FROM ' . WADA_Database::tbl_notification_targets() . ' WHERE notification_id = %d ORDER BY id';
                $notificationObj->targets = $wpdb->get_results($wpdb->prepare($query, $this->_id));

                $notificationObj->nr_queue_entries = WADA_Notification_Queue::getNrOfQueueEntries(0, 0, $this->_id);
            }

            if($onlyReturnNoInternalUpdate){
                return $notificationObj;
            }
            $this->_data = $notificationObj;
            return true;

        }
        return false;
    }

    public static function setActiveStatus($id, $status){
        if($status === true || intval($status) === 1){
            $status = 1;
        }else{
            $status = 0;
        }
        $id = absint($id);
        global $wpdb;
        $query = 'SELECT active FROM '.WADA_Model_Notification::getTable().' WHERE id=%d';
        $previousStatus = $wpdb->get_var($wpdb->prepare($query, $id));
        WADA_Log::debug('Notification->setActiveStatus id '.$id.', new status: '.$status.', current/previous status: '.$previousStatus);
        $res = $wpdb->update(WADA_Model_Notification::getTable(), array('active' => $status), array('id' => $id));
        if($res === false){
            WADA_Log::error('Notification->setActiveStatus: '.$wpdb->last_error);
            WADA_Log::error('Notification->setActiveStatus query was: '.$wpdb->last_query);
            return false;
        }else{
            WADA_Log::debug('Notification->setActiveStatus update okay, result: '.$res);
            do_action('wp_admin_audit_notification_status_change', $id, $status, $previousStatus);
        }
        return true;
    }

}
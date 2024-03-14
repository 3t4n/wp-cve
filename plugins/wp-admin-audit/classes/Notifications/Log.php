<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Notification_Log
{
    const EVENT_TYPE_NOTIFICATION_QUEUED = 1;
    const EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_OK = 2;
    const EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_ERROR = 3;
    const EVENT_TYPE_NOTIFICATION_SENDING_COMPLETE = 4;
    const EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_EXCEEDED_MAX_ATTEMPTS = 5;
    const EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_DELETED = 6;

    public static function getAllNotificationEventTypeNames(){
        $allTypes = array();
        $allTypes[self::EVENT_TYPE_NOTIFICATION_QUEUED] = self::getNotificationEventTypeName(self::EVENT_TYPE_NOTIFICATION_QUEUED);
        $allTypes[self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_OK] = self::getNotificationEventTypeName(self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_OK);
        $allTypes[self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_ERROR] = self::getNotificationEventTypeName(self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_ERROR);
        $allTypes[self::EVENT_TYPE_NOTIFICATION_SENDING_COMPLETE] = self::getNotificationEventTypeName(self::EVENT_TYPE_NOTIFICATION_SENDING_COMPLETE);
        $allTypes[self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_EXCEEDED_MAX_ATTEMPTS] = self::getNotificationEventTypeName(self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_EXCEEDED_MAX_ATTEMPTS);
        $allTypes[self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_DELETED] = self::getNotificationEventTypeName(self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_DELETED);
        return $allTypes;
    }

    public static function getNotificationEventTypeName($eventTypeCode, $default=null){
        if(is_null($default)){
            $default = sprintf(__('Unknown value: %s', 'wp-admin-audit'), $eventTypeCode);
        }
        $eventTypeCode = intval($eventTypeCode);
        switch($eventTypeCode){
            case self::EVENT_TYPE_NOTIFICATION_QUEUED:
                $eventTypeName = __('Notifications queued', 'wp-admin-audit');
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_OK:
                $eventTypeName = __('Notification sent successfully', 'wp-admin-audit');
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_ERROR:
                $eventTypeName = __('Notification send error', 'wp-admin-audit');
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_DELETED:
                $eventTypeName = __('Notification deleted from queue', 'wp-admin-audit');
                break;
            case self::EVENT_TYPE_NOTIFICATION_SENDING_COMPLETE:
                $eventTypeName = __('Notifications sending completed', 'wp-admin-audit');
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_EXCEEDED_MAX_ATTEMPTS:
                $eventTypeName = __('Notification send attempts exceeded max. allowed attempts', 'wp-admin-audit');
                break;
            default:
                $eventTypeName = $default;

        }
        return $eventTypeName;
    }
    
    public static function getNotificationEventDescription($eventLogEntry){
        $description = null;
        switch($eventLogEntry->event_type){
            case self::EVENT_TYPE_NOTIFICATION_QUEUED:
                $nrRecipients = intval($eventLogEntry->int_val1);
                $channelType = WADA_Notification_Sender::getChannelName($eventLogEntry->channel_type);
                if($nrRecipients > 0) {
                    $description = sprintf(__('%d %s notifications queued', 'wp-admin-audit'), $nrRecipients, $channelType);
                }else{
                    $description = sprintf(__('%s notification queued', 'wp-admin-audit'), $channelType);
                }
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_OK:
                switch($eventLogEntry->channel_type){ // TODO IMPLEMENT WHEN WE HAVE SMS ETC
                    case 'email':
                        $description = __('Email sent successfully', 'wp-admin-audit');
                        break;
                    case 'logsnag':
                        $description = __('Notification sent successfully', 'wp-admin-audit');
                        break;
                    default:
                        $description = sprintf(__('Unknown type: %s', 'wp-admin-audit'), $eventLogEntry->channel_type);
                        break;
                }
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_ERROR:
                switch($eventLogEntry->channel_type){ // TODO IMPLEMENT WHEN WE HAVE SMS ETC
                    case 'email':
                        $description = sprintf(__('Error while sending email: %s', 'wp-admin-audit'), $eventLogEntry->msg);
                        break;
                    case 'logsnag':
                        $description = __('Error while sending notification', 'wp-admin-audit');
                        break;
                    default:
                        $description = sprintf(__('Unknown type: %s', 'wp-admin-audit'), $eventLogEntry->channel_type);
                        break;
                }
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_DELETED:
                switch($eventLogEntry->channel_type){ // TODO IMPLEMENT WHEN WE HAVE SMS ETC
                    case 'email':
                    case 'logsnag':
                        $description = sprintf(__('Queue entry deleted by %s', 'wp-admin-audit'), $eventLogEntry->msg);
                        break;
                    default:
                        $description = sprintf(__('Unknown type: %s', 'wp-admin-audit'), $eventLogEntry->channel_type);
                        break;
                }
                break;
            case self::EVENT_TYPE_NOTIFICATION_SENDING_COMPLETE:
                $description = __('All notifications were sent', 'wp-admin-audit');
                break;
            case self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_EXCEEDED_MAX_ATTEMPTS:
                $sendAttemptNr = intval($eventLogEntry->int_val1);
                $maxSendAttemptNr = intval($eventLogEntry->int_val2);
                switch($eventLogEntry->channel_type){ // TODO IMPLEMENT WHEN WE HAVE SMS ETC
                    case 'email':
                    case 'logsnag':
                        $description = sprintf(__('%d of %d (max.) send attempts', 'wp-admin-audit'), $sendAttemptNr, $maxSendAttemptNr);
                        break;
                    default:
                        $description = sprintf(__('Unknown type: %s', 'wp-admin-audit'), $eventLogEntry->channel_type);
                        break;
                }
                $description = __('Notification send attempts exceeded max. allowed attempts', 'wp-admin-audit');
                break;
            default:
                $description = sprintf(__('Unknown value: %s', 'wp-admin-audit'), $eventLogEntry->event_type);
        }
        return $description;
    }

    public static function logNotificationQueued($eventNotificationId){
        WADA_Log::debug('logNotificationQueued eventNotificationId: '.$eventNotificationId);
        $emailQueue = WADA_Notification_Queue::getQueueForEventNotificationId($eventNotificationId, 'email');
        if(count($emailQueue)){
            $emailAddresses = array_map(function($o) { return $o->email_address; }, $emailQueue);
            self::storeLogEntry($eventNotificationId,
                self::EVENT_TYPE_NOTIFICATION_QUEUED,
                'email', $emailAddresses, count($emailAddresses)
            );
        }
        $logsnagQueue = WADA_Notification_Queue::getQueueForEventNotificationId($eventNotificationId, 'logsnag');
        if(count($logsnagQueue)){
            self::storeLogEntry($eventNotificationId,
                self::EVENT_TYPE_NOTIFICATION_QUEUED,
                'logsnag'
            );
        }
    }

    protected static function logNotificationSendOk($eventNotificationId, $channelType, $recipients){
        WADA_Log::debug('logNotificationSendOk eventNotificationId: '.$eventNotificationId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true));
        self::storeLogEntry($eventNotificationId,
            self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_OK,
            $channelType, $recipients, count($recipients)
        );
    }

    public static function logNotificationSendOkByQueueId($queueId, $channelType, $recipients){
        WADA_Log::debug('logNotificationSendOkByQueueId queueId: '.$queueId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true));
        $eventNotifications = WADA_Notification_Queue::getEventNotificationsForQueueId($queueId);
        foreach($eventNotifications AS $eventNotification){
            self::logNotificationSendOk($eventNotification->id, $channelType, $recipients);
        }
        return true;
    }

    protected static function logNotificationSendError($eventNotificationId, $channelType, $recipients, $errorMsg, $sendAttemptNr){
        WADA_Log::debug('logNotificationSendError eventNotificationId: '.$eventNotificationId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true).', errorMsg: '.$errorMsg.', sendAttemptNr: '.$sendAttemptNr);
        self::storeLogEntry($eventNotificationId,
            self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_SENT_ERROR,
            $channelType, $recipients, $sendAttemptNr, count($recipients), -1, -1, $errorMsg
        );
    }

    public static function logNotificationSendErrorByQueueId($queueId, $channelType, $recipients, $errorMsg, $sendAttemptNr=null){
        WADA_Log::debug('logNotificationSendErrorByQueueId queueId: '.$queueId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true).', errorMsg: '.$errorMsg.', sendAttemptNr: '.$sendAttemptNr);
        if(is_null($sendAttemptNr)){
            $queueEntry = WADA_Notification_Queue::getQueueEntry($queueId);
            $sendAttemptNr = $queueEntry->attempt_count;
        }
        $eventNotifications = WADA_Notification_Queue::getEventNotificationsForQueueId($queueId);
        foreach($eventNotifications AS $eventNotification){
            self::logNotificationSendError($eventNotification->id, $channelType, $recipients, $errorMsg, $sendAttemptNr);
        }
        return true;
    }

    protected static function logNotificationQueueEntryDeleted($eventNotificationId, $channelType, $recipients, $msgDeletedBy, $sendAttemptNr){
        WADA_Log::debug('logNotificationSendError eventNotificationId: '.$eventNotificationId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true).', msgDeletedBy: '.$msgDeletedBy.', sendAttemptNr: '.$sendAttemptNr);
        self::storeLogEntry($eventNotificationId,
            self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_DELETED,
            $channelType, $recipients, $sendAttemptNr, count($recipients), -1, -1, $msgDeletedBy
        );
    }

    public static function logNotificationQueueEntryDeletedByQueueId($queueId, $channelType, $recipients, $msgDeletedBy, $sendAttemptNr=null){
        WADA_Log::debug('logNotificationQueueEntryDeletedByQueueId queueId: '.$queueId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true).', msgDeletedBy: '.$msgDeletedBy.', sendAttemptNr: '.$sendAttemptNr);
        if(is_null($sendAttemptNr)){
            $queueEntry = WADA_Notification_Queue::getQueueEntry($queueId);
            $sendAttemptNr = $queueEntry->attempt_count;
        }
        $eventNotifications = WADA_Notification_Queue::getEventNotificationsForQueueId($queueId);
        foreach($eventNotifications AS $eventNotification){
            self::logNotificationQueueEntryDeleted($eventNotification->id, $channelType, $recipients, $msgDeletedBy, $sendAttemptNr);
        }
        return true;
    }

    public static function logNotificationSendingComplete($eventId){
        WADA_Log::debug('logNotificationSendingComplete eventId: '.$eventId);
        $eventNotifications = WADA_Notification_Queue::getEventNotificationsForEventId($eventId);
        foreach($eventNotifications AS $eventNotification){
            self::storeLogEntry($eventNotification->id,
                self::EVENT_TYPE_NOTIFICATION_SENDING_COMPLETE,
                'pseudo_channel');
        }
        return true;
    }

    protected static function logNotificationMaxSendAttemptsExceeded($eventNotificationId, $channelType, $recipients, $maxSendAttemptNr, $sendAttemptNr){
        WADA_Log::debug('logNotificationSendError eventNotificationId: '.$eventNotificationId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true).', maxSendAttemptNr: '.$maxSendAttemptNr.', sendAttemptNr: '.$sendAttemptNr);
        self::storeLogEntry($eventNotificationId,
            self::EVENT_TYPE_NOTIFICATION_QUEUE_ENTRY_EXCEEDED_MAX_ATTEMPTS,
            $channelType, $recipients, $sendAttemptNr, $maxSendAttemptNr
        );
    }

    public static function logNotificationMaxSendAttemptsExceededByQueueId($queueId, $channelType, $recipients, $maxSendAttemptNr, $sendAttemptNr=null){
        WADA_Log::debug('logNotificationMaxSendAttemptsExceeded queueId: '.$queueId.', channelType: '.$channelType.', recipients: '.print_r($recipients, true).', maxSendAttemptNr: '.$maxSendAttemptNr);
        if(is_null($sendAttemptNr)) {
            $queueEntry = WADA_Notification_Queue::getQueueEntry($queueId);
            $sendAttemptNr = $queueEntry->attempt_count;
        }
        $eventNotifications = WADA_Notification_Queue::getEventNotificationsForQueueId($queueId);
        foreach($eventNotifications AS $eventNotification){
            self::logNotificationMaxSendAttemptsExceeded($eventNotification->id, $channelType, $recipients, $maxSendAttemptNr, $sendAttemptNr);
        }
        return true;
    }

    /**
     * @param int $eventNotificationId
     * @param int $eventType
     * @param string $channelType
     * @param null|array|string $recips
     * @param int $int1
     * @param int $int2
     * @param int $int3
     * @param int $int4
     * @param null|string $msg
     * @return false|int
     */
    protected static function storeLogEntry($eventNotificationId, $eventType, $channelType,
                                            $recips=null, $int1=-1, $int2=-1, $int3=-1, $int4=-1, $msg=null){
        global $wpdb;
        if(is_array($recips)){
            $recips = json_encode($recips);
        }
        $eventTime = WADA_DateUtils::getUTCforMySQLTimestamp();

        $query = 'INSERT INTO '.WADA_Database::tbl_event_notification_log();
        $query .= ' (';
        $query .= 'id,';
        $query .= 'event_notification_id,';
        $query .= 'event_time,';
        $query .= 'event_type,';
        $query .= 'channel_type,';
        $query .= 'recips,';
        $query .= 'int_val1,';
        $query .= 'int_val2,';
        $query .= 'int_val3,';
        $query .= 'int_val4,';
        $query .= 'msg';
        $query .= ') VALUES (';
        $query .= '%d,'; // id
        $query .= '%d,'; // event_notification_id
        $query .= '%s,'; // event_time
        $query .= '%d,'; // event_type
        $query .= '%s,'; // channel_type
        $query .= '%s,'; // recips
        $query .= '%d,'; // int_val1
        $query .= '%d,'; // int_val2
        $query .= '%d,'; // int_val3
        $query .= '%d,'; // int_val4
        $query .= '%s'; // msg
        $query .= ')';
        $preparedQuery = $wpdb->prepare($query,
            null,
            $eventNotificationId,
            $eventTime,
            $eventType,
            $channelType,
            $recips,
            $int1,
            $int2,
            $int3,
            $int4,
            $msg
        );
        $preparedQuery = str_replace("''", "NULL", $preparedQuery);
        $res = $wpdb->query($preparedQuery);
        if( $res === false ) {
            WADA_Log::error('storeLogEntry: '.$wpdb->last_error);
            WADA_Log::error('storeLogEntry query was: '.$preparedQuery);
            return false;
        }else{
            $notificationLogId = $wpdb->insert_id;
            WADA_Log::debug('storeLogEntry (eventType: '.$eventType.') inserted #id: '.$notificationLogId);
        }
        return $notificationLogId;
    }



}
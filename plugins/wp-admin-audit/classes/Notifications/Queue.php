<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Notification_Queue
{
    /**
     * After a new event was recorded in the event log, this method is called.
     * It checks if there are any notification items setup that fit the given event.
     * For all matches, the notifications are enqueued to be sent out.
     */
    public static function matchAndQueueEvent($eventId, $eventObj){
        if(!WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI)) return;

        /*  */
    }

    public static function workOnQueue($maxProcessingTime = 10){
        if(!WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI)) return;
        /*  */
    }

    public static function processNotificationsOfNextEvent(){
        if(!WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI)) return;
        /*  */
    }

    public static function sendNotificationForEvent($event, $sleepSecondsBetweenMessages=0, $stopBefore=0){
        if(!WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI)) return;
        /*  */
    }

    protected static function getUniqueEmailsWithNotificationRelation($notificationQueues){
        $uniqueEmails = array();
        /*  */
        return $uniqueEmails;
    }

    /**
     * @param $eventId int
     * @param $notificationIds array
     */
    protected static function insertIntoEventNotifications($eventId, $notificationIds){
        $eventNotifications = array();
        /*  */

        return $eventNotifications;
    }

    /**
     * @param string $channelType
     * @param null|string $emailAddress
     * @param null|string $telNr
     * @return false|int
     */
    protected static function insertIntoQueue($channelType, $emailAddress = null, $telNr = null){
        $queueId = 0;
        /*  */
        return $queueId;
    }

    protected static function insertIntoQueueMap($eventNotificationId, $queueId){
        $queueMapId = 0;
        /*  */

        return $queueMapId;
    }

    public static function getQueueForEventNotificationId($eventNotificationId, $channelTypeFilter = null){
        $queue = array();
        /*  */
        return $queue;
    }

    public static function getQueueEntry($queueId){
        $queueEntry = null;
        /*  */
        return $queueEntry;
    }

    /**
     * @return string|null
     */
    public static function getNextUnprocessedEvent(){
        $eventId = 0;
        /*  */
        return intval($eventId);
    }

    public static function getQueueForEventId($eventId){
        $queueEntries = array();
        /*  */
        return $queueEntries;
    }


    public static function getEventNotificationsForQueueId($queueId){
        $eventNotifications = array();
        /*  */
        return $eventNotifications;
    }

    public static function getEventNotificationsForEventId($eventId){
        $eventNotifications = array();
        /*  */
        return $eventNotifications;
    }

    public static function getEventNotificationsForNotificationId($notificationId){
        $eventNotifications = array();
        /*  */
        return $eventNotifications;
    }

    public static function getEventNotification($eventNotificationId){
        $eventNotification = null;
        /*  */
        return $eventNotification;
    }

    public static function getNrOfQueueEntries($eventNotificationId=0, $eventId = 0, $notificationId = 0){
        $nrEntries = 0;
        /*  */
        return $nrEntries;
    }

    public static function getNrAttemptsOfQueueEntry($queueId){
        $nrAttempts = 0;
        /*  */
        return $nrAttempts;
    }

    public static function markNotificationsOfEventAsDone($eventId){
        $affRows = 0;
        /*  */
        return $affRows;
    }

    public static function removeFromQueue($queueId){
        $res1 = true;
        $res2 = true;
        /*  */
        return ($res1 && $res2);
    }

    public static function incrementError($queueId, $maxSendAttempts=5){
        /*  */
    }


}
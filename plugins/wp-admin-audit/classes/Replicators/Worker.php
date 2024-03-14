<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Replicator_Worker
{
    public static function workOnPendingReplications($maxProcessingTime = 10){
        if(!WADA_Version::getFtSetting(WADA_Version::FT_ID_REPLICATE)) return;

        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
    }

    /**
     * @return string|null
     */
    public static function getNextEventWithPendingReplications(){
        $eventId = 0;
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
        return $eventId;
    }

    public static function doReplicationForEvent($event, $sleepMicroSecondsBetweenMessages=0, $stopBefore=0){
        if(!WADA_Version::getFtSetting(WADA_Version::FT_ID_REPLICATE)) return;
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
    }

    public static function getReplicationQueueForEventId($eventId){
        $eventReplQueue = array();
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
        return $eventReplQueue;
    }

    public static function getNrAttemptsOfReplicationQueueEntry($eventReplicationId){
        $nrAttempts = 0;
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
        return $nrAttempts;
    }

    public static function markReplicationsOfEventAsDone($eventId){
        $affRows = 0;
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
        return $affRows;
    }

    public static function markReplicationsOfEventAsFailed($eventId){
        $affRows = 0;
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
        return $affRows;
    }

    public static function markAsDone($eventReplicationId){
        $result = true;
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
        return $result;
    }

    public static function incrementAttempts($eventReplicationId, $eventId, $maxSendAttempts=100){
        /* @@REMOVE_START_WADA_startup@@ */
        /*  */
        /* @@REMOVE_END_WADA_startup@@ */
    }

}
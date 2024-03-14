<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

abstract class WADA_Replicator_Base
{
    const REPLICATOR_ID_LOGGLY = 1;
    const REPLICATOR_ID_LOGTAIL = 2;

    public function __construct(){
        $this->setup();
    }

    /**
     * @return bool
     */
    abstract protected function setup();

    /**
     * @param string $severity
     * @param mixed $message
     * @return bool
     */
    abstract protected function send($severity, $message);

    /**
     * @param object $event
     * @return string
     */
    protected function prepareMessage($event){
        return $this->generateEventJson($event);
    }

    /**
     * @param int $severityLevel
     * @return string
     */
    protected function convertSeverityLevel($severityLevel){
        return WADA_Model_Sensor::getSeverityNameForLevel($severityLevel, strval($severityLevel));
    }

    /**
     * @param object $event
     * @return bool
     */
    public function sendEvent($event){
        //WADA_Log::debug('sendEvent event: '.print_r($event, true));
        $severity = $this->convertSeverityLevel($event->severity);
        $message = $this->prepareMessage($event);
        return $this->send($severity, $message);
    }

    protected static function getTitleAndDescription($event){
        $title = $description = null;
        $eventDetailsLayout = WADA_Layout_EventDetailsBase::getEventDetailsLayout($event);
        if($eventDetailsLayout){
            list($title, $description) = $eventDetailsLayout->getEventTitleAndSubtitle();
        }
        if(!$title || !$description){
            $title = '#' . $event->id . ' ' . $event->sensor_name;
            $description = property_exists($event, 'summary_short') ? $event->summary_short : $title;
        }
        $title = wp_strip_all_tags($title); // no html needed
        $description = wp_strip_all_tags($description); // no html needed
        return array($title, $description);
    }

    /**
     * @param object $event
     * @return string
     */
    protected function generateEventJson($event){
        list($title, $description) = self::getTitleAndDescription($event);
        $res = new stdClass();
        $res->log_client = 'WP Admin Audit';
        $res->log_client_source = get_site_url();

        $res->event_id = $event->id;
        $res->event_time = $event->occurred_on;
        $res->event_title = $title;
        $res->event_description = $description;
        $res->severity = WADA_Model_Sensor::getSeverityNameForLevel($event->severity, strval($event->severity));
        $res->sensor = $event->sensor_name;

        $attributes2Copy = array('sensor_id', 'site_id', 'user_id', 'user_name', 'user_email',
            'object_type', 'object_id', 'source_ip', 'source_client',
            'check_value_head', 'check_value_full',
            'summary_short', 'summary_full');

        foreach($attributes2Copy AS $attribute){
            $res->$attribute = $event->$attribute;
        }

        if($event->infos && count($event->infos)){
            $res->infos = array();
            foreach($event->infos AS $info){
                $infoObj = new stdClass();
                $infoObj->key = $info->info_key;
                $infoObj->value = $info->info_value;
                $infoObj->prior = $info->prior_value;
                $res->infos[] = $infoObj;
            }
        }

        return json_encode($res);
    }

    /**
     * @param $replicatorId
     * @return WADA_Replicator_Base|null
     */
    public static function getReplicatorInstance($replicatorId){
        $replicator = null;
        switch($replicatorId){
            case self::REPLICATOR_ID_LOGGLY:
                $replicator = new WADA_Replicator_Loggly();
                break;
            case self::REPLICATOR_ID_LOGTAIL:
                $replicator = new WADA_Replicator_Logtail();
                break;
            default:
                WADA_Log::error('getReplicatorInstance not replicator found for ID: '.$replicatorId);
        }
        return $replicator;
    }

    /**
     * @return array
     */
    public static function getIdsOfActiveReplicators(){
        $replicatorIds = array();
        if(WADA_Settings::isReplicationToLogglyEnabled()){
            $replicatorIds[] = WADA_Replicator_Base::REPLICATOR_ID_LOGGLY;
        }
        if(WADA_Settings::isReplicationToLogtailEnabled()){
            $replicatorIds[] = WADA_Replicator_Base::REPLICATOR_ID_LOGTAIL;
        }
        return $replicatorIds;
    }

    /**
     * @return bool
     */
    public static function isReplicationActive(){
        return (count(self::getIdsOfActiveReplicators()) > 0);
    }
}
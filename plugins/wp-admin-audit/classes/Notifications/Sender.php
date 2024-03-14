<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

trait WADA_Notification_Sender
{
    public $event = null;
    public $error = null;

    public $PLACEHOLDER_EVENT_TITLE = '{event_title}';
    public $PLACEHOLDER_EVENT_DETAIL_TABLE = '{event_detail_table}';
    public $PLACEHOLDER_EVENT_TIMESTAMP = '{event_timestamp}';
    public $PLACEHOLDER_EVENT_SEVERITY = '{event_severity}';
    public $PLACEHOLDER_EVENT_USER = '{event_user}';
    public $PLACEHOLDER_EVENT_USER_EMAIL = '{event_user_email}';
    public $PLACEHOLDER_EVENT_OBJECT_TITLE = '{event_object_title}';
    public $PLACEHOLDER_EVENT_OBJECT_DESCRIPTION = '{event_object_description}';
    public $PLACEHOLDER_EVENT_SENSOR_NAME = '{event_sensor_name}';
    public $PLACEHOLDER_EVENT_SENSOR_DESCRIPTION = '{event_sensor_description}';
    public $PLACEHOLDER_EVENT_SENSOR_GROUP = '{event_sensor_group}';
    public $PLACEHOLDER_EVENT_SENSOR_CATEGORY = '{event_sensor_category}';

    public $PLACEHOLDER_SITE_NAME = '{site_name}';
    public $PLACEHOLDER_SITE_URL = '{site_url}';
    public $PLACEHOLDER_POWERED_BY = '{powered_by}';

    abstract function prepareForRecipient($recipientData);
    abstract function sendNotification();

    /**
     * @return WP_Error|null
     */
    public function getLastError(){
        if(is_null($this->error)){
            return $this->error;
        }
        if($this->error instanceof WP_Error){
            return $this->error;
        }
        if(is_array($this->error)){
            $errorStr = implode(' | ', $this->error);
        }else{
            $errorStr = strval($this->error);
        }
        return new WP_Error('error', $errorStr);
    }

    /**
     * @param object $event
     * @param string $channelType
     * @return WADA_Notification_Sender|null
     */
    public static function getNotificationSender($event, $channelType){
        $sender = null;
        if($channelType){
            switch($channelType){
                case 'email':
                    $sender = new WADA_Notification_SenderEmail($event);
                    break;
                case 'logsnag':
                    $sender = new WADA_Notification_SenderLogsnag($event);
                    break;

                // TODO IMPLEMENT WHEN WE HAVE SMS ETC

                default:
                    WADA_Log::error('getNotificationSender no sender defined for channelType '.$channelType);
                    WADA_Log::error('getNotificationSender event: '.print_r($event, true));
                    break;
            }
        }
        return $sender;
    }

    public static function getAllChannelTypes(){
        return array('email', 'logsnag');
    }

    public static function getChannelName($channelType){
        $channelName = null;
        if($channelType){
            switch($channelType){
                case 'email':
                    $channelName = __('Email', 'wp-admin-audit');
                    break;
                case 'logsnag':
                    $channelName = 'Logsnag'; // no translation since brand
                    break;

                // TODO IMPLEMENT WHEN WE HAVE SMS ETC

                default:
                    WADA_Log::error('getChannelName no sender defined for channelType '.$channelType);
                    break;
            }
        }
        return $channelName;
    }

    protected function replacePlaceholders($template, $useHtml=true){
        $eventDetailsLayout = WADA_Layout_EventDetailsBase::getEventDetailsLayout($this->event);
        list($title, $subtitle) = $eventDetailsLayout->getEventTitleAndSubtitle();
        $objectTitle = WADA_Layout_EventDetailsBase::getObjectTypeDescription($this->event, '');
        if($objectTitle != '' && intval($this->event->object_id) > 0) {
            $objectTitle = $objectTitle . ' ID #' . $this->event->object_id;
        }

        $method = $eventDetailsLayout->getEventInfoTableRenderMethod();
        if(is_callable(array($eventDetailsLayout, $method))){
            $eventInfosTable = call_user_func(array($eventDetailsLayout, $method));
        }else{
            $eventInfosTable = call_user_func(array($eventDetailsLayout, 'renderDefaultEventInfosTable'));
        }
        WADA_Log::debug('replacePlaceholders method: '.$method.', eventInfosTable: '.$eventInfosTable);
        WADA_Log::debug('replacePlaceholders eventDetailsLayout: '.print_r($eventDetailsLayout, true));

        $txtVars = array();
        $repVals = array();

        $txtVars[] = $this->PLACEHOLDER_EVENT_TITLE;
        $repVals[] = $title . ($subtitle && strlen($subtitle) ? ' ('.$subtitle.')' : '');

        $txtVars[] = $this->PLACEHOLDER_EVENT_DETAIL_TABLE;
        $repVals[] = $eventInfosTable;

        $txtVars[] = $this->PLACEHOLDER_EVENT_TIMESTAMP;
        $repVals[] = WADA_DateUtils::formatUTCasDatetimeForWP($this->event->occurred_on);

        $txtVars[] = $this->PLACEHOLDER_EVENT_SEVERITY;
        $repVals[] = WADA_Model_Sensor::getSeverityNameForLevel($this->event->severity);

        $txtVars[] = $this->PLACEHOLDER_EVENT_USER;
        $repVals[] = $this->event->user_name;

        $txtVars[] = $this->PLACEHOLDER_EVENT_USER_EMAIL;
        $repVals[] = $this->event->user_email;

        $txtVars[] = $this->PLACEHOLDER_EVENT_OBJECT_TITLE;
        $repVals[] = $objectTitle;

        $txtVars[] = $this->PLACEHOLDER_EVENT_OBJECT_DESCRIPTION;
        $repVals[] = WADA_Layout_EventDetailsBase::getEventObjectDetailsTable($this->event, $useHtml);

        $txtVars[] = $this->PLACEHOLDER_EVENT_SENSOR_NAME;
        $repVals[] = $this->event->sensor_name;

        $txtVars[] = $this->PLACEHOLDER_EVENT_SENSOR_DESCRIPTION;
        $repVals[] = $this->event->sensor_description;

        $txtVars[] = $this->PLACEHOLDER_EVENT_SENSOR_GROUP;
        $repVals[] = WADA_Model_Sensor::getEventGroupName($this->event->event_group);

        $txtVars[] = $this->PLACEHOLDER_EVENT_SENSOR_CATEGORY;
        $repVals[] = WADA_Model_Sensor::getEventCategoryName($this->event->event_category, '');

        $txtVars[] = $this->PLACEHOLDER_SITE_NAME;
        $repVals[] = get_bloginfo('name');

        $txtVars[] = $this->PLACEHOLDER_SITE_URL;
        $repVals[] = get_bloginfo('url');

        $txtVars[] = $this->PLACEHOLDER_POWERED_BY;
        $repVals[] = __('powered by WP Admin Audit', 'wp-admin-audit');

        WADA_Log::debug('replacePlaceholders txtVars: '.print_r($txtVars, true));
        WADA_Log::debug('replacePlaceholders repVals: '.print_r($repVals, true));

        return str_replace($txtVars, $repVals, $template);
    }

    protected static function getTitleAndDescription($event){
        $title = $description = null;
        $eventDetailsLayout = WADA_Layout_EventDetailsBase::getEventDetailsLayout($event);
        if($eventDetailsLayout){
            list($title, $description) = $eventDetailsLayout->getEventTitleAndSubtitle();
        }
        if(!$title){
            $title = $event->sensor_name;
        }
        if(!$description){
            $description = property_exists($event, 'summary_short') ? $event->summary_short : ('#' . $event->id . ' ' . $title);
        }
        $title = wp_strip_all_tags($title); // no html needed
        $description = wp_strip_all_tags($description); // no html needed
        return array($title, $description);
    }

}
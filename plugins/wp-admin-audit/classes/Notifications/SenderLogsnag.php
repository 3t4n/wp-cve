<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Notification_SenderLogsnag
{
    use WADA_Notification_Sender;
    protected $apiUrl = 'https://api.logsnag.com/v1/';
    protected $token = null;
    protected $project = null;

    public function __construct($event){
        $this->event = $event;
        $this->setup();
    }

    protected function setup(){

        /*  */

    }

    /**
     * We override this specific for Logsnag due to the format constraints
     * @param object $event
     * @return string
     */
    protected function generateEventJson($event){
        $res = '';
        /*  */
        return json_encode($res);
    }

    protected function send($severity, $message){

        /*  */

        return false;
    }

    protected function doLogsnagSendRequest($jsonData){
        $result = $httpCode = $curlError = null;

        /*  */

        return array($result, $httpCode, $curlError);
    }

    public function prepareForRecipient($recipientData){
        // we don't really have something regarding recipient for Logsnag
    }

    public function sendNotification(){
        $this->error = null; // reset error
        $severity =  WADA_Model_Sensor::getSeverityNameForLevel($this->event->severity, strval($this->event->severity));
        $message = $this->generateEventJson($this->event);
        WADA_Log::debug('WADA_Notification_SenderLogsnag->sendNotification event '.$this->event->id);
        return $this->send($severity, $message);
    }

    // override for Logsnag format (where title is simplified and without ID)
    protected static function getTitleAndDescription($event){
        $title = $description = null;
        $eventDetailsLayout = WADA_Layout_EventDetailsBase::getEventDetailsLayout($event);
        if($eventDetailsLayout){
            list($title, $description) = $eventDetailsLayout->getEventTitleAndSubtitle();
            // For logsnag we use title as description ...
            $description = $title;
            // and sensor name for title (without event ID)
            $title = $event->sensor_name;
        }else{
            $title = $event->sensor_name;
            $description = '#' . $event->id . ' ' . $event->sensor_name;
        }
        $title = wp_strip_all_tags($title); // no html needed
        $description = wp_strip_all_tags($description); // no html needed
        return array($title, $description);
    }

}
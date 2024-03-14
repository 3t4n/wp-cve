<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Notification_SenderEmail
{
    use WADA_Notification_Sender;
    public $subject = null;
    public $body = null;
    public $toEmailAddress = null;

    public function __construct($event){
        $this->event = $event;
        /*  */
    }

    public function prepareForRecipient($recipientData){
        $this->toEmailAddress = $recipientData;
    }

    public function sendNotification(){
        $this->error = null; // reset error
        WADA_Log::debug('WADA_Notification_SenderEmail->sendNotification event '.$this->event->id.', to email: '.$this->toEmailAddress);
        return $this->sendHtmlMail($this->toEmailAddress, $this->subject, $this->body);
    }

    protected function sendHtmlMail($to, $subject, $message, $headers = array(), $attachments = array()){
        $sendOk = true;
        /*  */
        return $sendOk;
    }

    public function getHtmlContentType($content_type){
        WADA_Log::debug('getHtmlContentType change from '.$content_type.' to text/html');
        return 'text/html';
    }

    /**
     * @param WP_Error $error
     */
    public function onSendError($error){
        $this->error = $error;
        WADA_Log::error('WADA_Notification_SenderEmail onSendError');
        WADA_Log::error('error: '.print_r($error->get_error_messages(), true));
    }

    protected function getStandardMailTemplate(){
        $body = $subject = '';

        /*  */
        $subject = apply_filters('wp_admin_audit_notification_email_subject_template', $subject, $this->event);
        $body = apply_filters('wp_admin_audit_notification_email_html_body_template', $body, $this->event);
        return array($subject, $body);
    }

}
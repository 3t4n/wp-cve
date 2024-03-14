<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSwpjobnotification {

    function __construct( ) {

    }

    public function addSessionNotificationDataToTable($message, $msgtype, $sessiondatafor = 'notification',$msgkey = 'captcha'){
        /*$message belows to repsonse message
        $msgtyp belongs to reponse type eg error or success
        $sessiondatafor belong to any random thing or reponse notification after saving some data
        $msgkey belong to module
        */
        if($message == ''){
            if(!is_numeric($message))
                return false;
        }
        global $wpdb;
        $data = array();
        $update = false;
        if(isset($_COOKIE['_wpjsjob_session_']) && isset(jsjobs::$_jsjobsession->sessionid)){
            if($sessiondatafor == 'notification'){
                $data = $this->getNotificationDatabySessionId($sessiondatafor);
                if(empty($data)){
                    $data['msg'][0] = $message;
                    $data['type'][0] = $msgtype;
                }else{
                    $update = true;
                    $count = count($data['msg']);
                    $data['msg'][$count] = $message;
                    $data['type'][$count] = $msgtype;
                }
            }

            if($sessiondatafor == 'jsjobs_spamcheckid'){
                $msgkey = 'captcha';
                $data = $this->getNotificationDatabySessionId($sessiondatafor,$msgkey);
                if($data != ""){
                    $update = true;
                    $data = $message;
                }else{
                    $data = $message;
                }
            }
            if($sessiondatafor == 'jsjobs_rot13'){
                $msgkey = 'captcha';
                $data = $this->getNotificationDatabySessionId($sessiondatafor,$msgkey);
                if($data != ""){
                    $update = true;
                    $data = $message;
                }else{
                    $data = $message;
                }
            }
            if($sessiondatafor == 'jsjobs_spamcheckresult'){
                $msgkey = 'captcha';
                $data = $this->getNotificationDatabySessionId($sessiondatafor,$msgkey);
                if($data != ""){
                    $update = true;
                    $data = $message;
                }else{
                    $data = $message;
                }
            }


            $data = json_encode($data , true);
            $sessionmsg = jsjobslib::jsjobs_safe_encoding($data);
            if(!$update){
                $wpdb->insert( "{$wpdb->prefix}js_job_jsjobsessiondata", array("usersessionid" => jsjobs::$_jsjobsession->sessionid, "sessionmsg" => $sessionmsg, "sessionexpire" => jsjobs::$_jsjobsession->sessionexpire, "sessionfor" => $sessiondatafor , "msgkey" => $msgkey) );
            }else{
                $wpdb->update( "{$wpdb->prefix}js_job_jsjobsessiondata", array("sessionmsg" => $sessionmsg), array("usersessionid" => jsjobs::$_jsjobsession->sessionid , 'sessionfor' => $sessiondatafor) );
            }
        }
        return false;
    }

    public function getNotificationDatabySessionId($sessionfor , $msgkey = null, $deldata = false){
        if(jsjobs::$_jsjobsession->sessionid == '')
            return false;
        global $wpdb;
        $data = $wpdb->get_var( "SELECT sessionmsg FROM {$wpdb->prefix}js_job_jsjobsessiondata WHERE usersessionid = '" . jsjobs::$_jsjobsession->sessionid . "' AND sessionfor = '" . $sessionfor . "' AND sessionexpire > '" . time() . "'");
        if(!empty($data)){
            $data = jsjobslib::jsjobs_safe_decoding($data);
            $data = json_decode( $data , true);
        }
        if($deldata && !empty($data)){
            $wpdb->delete( "{$wpdb->prefix}js_job_jsjobsessiondata", array( 'usersessionid' => jsjobs::$_jsjobsession->sessionid , 'sessionfor' => $sessionfor , 'msgkey' => $msgkey) );
        }
        return $data;
    }

}

?>

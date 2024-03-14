<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_wphdnotification {

    function __construct( ) {

    }

    public function MJTC_addSessionNotificationDataToTable($message, $msgtype, $sessiondatafor = 'notification',$ticketid = null){
        if($message == ''){
            if(!is_numeric($message))
                return false;
        }
        global $wpdb;
        $data = array();
        $update = false;
        if(isset($_COOKIE['_wpms_session_']) && isset(majesticsupport::$_mjtcsession->sessionid)){
            if($sessiondatafor == 'notification'){
                $data = $this->MJTC_getNotificationDatabySessionId($sessiondatafor);
                if(empty($data)){
                    $data['msg'][0] = $message;
                    $data['type'][0] = $msgtype;
                }else{
                    $update = true;
                    $count = count($data['msg']);
                    $data['msg'][$count] = $message;
                    $data['type'][$count] = $msgtype;
                }
            }elseif($sessiondatafor == 'submitform'){
                $data = $this->MJTC_getNotificationDatabySessionId($sessiondatafor,true);
                $data = $message;
            }elseif($sessiondatafor == 'ticket_time_start_'){
                $data = $this->MJTC_getNotificationDatabySessionId($sessiondatafor.$ticketid);
                $sessiondatafor = $sessiondatafor.$ticketid;
                if($data != ""){
                    $update = true;
                }
                $data = $message;
            }
            if($sessiondatafor == 'majesticsupport_spamcheckid'){
                $data = $this->MJTC_getNotificationDatabySessionId($sessiondatafor);
                if($data != ""){
                    $update = true;
                    $data = $message;
                }else{
                    $data = $message;
                }
            }
            if($sessiondatafor == 'majesticsupport_rot13'){
                $data = $this->MJTC_getNotificationDatabySessionId($sessiondatafor);
                if($data != ""){
                    $update = true;
                    $data = $message;
                }else{
                    $data = $message;
                }
            }
            if($sessiondatafor == 'majesticsupport_spamcheckresult'){
                $data = $this->MJTC_getNotificationDatabySessionId($sessiondatafor);
                if($data != ""){
                    $update = true;
                    $data = $message;
                }else{
                    $data = $message;
                }
            }
            $data = json_encode($data , true);
            $sessionmsg = MJTC_majesticsupportphplib::MJTC_safe_encoding($data);
            if(!$update){
                $wpdb->insert( "{$wpdb->prefix}mjtc_support_mjtcsessiondata", array("usersessionid" => majesticsupport::$_mjtcsession->sessionid, "sessionmsg" => $sessionmsg, "sessionexpire" => majesticsupport::$_mjtcsession->sessionexpire, "sessionfor" => $sessiondatafor) );
            }else{
                $wpdb->update( "{$wpdb->prefix}mjtc_support_mjtcsessiondata", array("sessionmsg" => $sessionmsg), array("usersessionid" => majesticsupport::$_mjtcsession->sessionid , 'sessionfor' => $sessiondatafor) );
            }
        }
        return false;
    }

    public function MJTC_getNotificationDatabySessionId($sessionfor , $deldata = false){
        if(majesticsupport::$_mjtcsession->sessionid == '')
            return false;
        $query = "SELECT sessionmsg FROM `" . majesticsupport::$_db->prefix . "mjtc_support_mjtcsessiondata` WHERE usersessionid = '" . majesticsupport::$_mjtcsession->sessionid . "' AND sessionfor = '" . esc_sql($sessionfor) . "' AND sessionexpire > '" . time() . "'";
        $data = majesticsupport::$_db->get_var($query);
        if(!empty($data)){
            $data = MJTC_majesticsupportphplib::MJTC_safe_decoding($data);
            $data = json_decode( $data , true);
        }
        if($deldata){
            majesticsupport::$_db->delete(majesticsupport::$_db->prefix . "mjtc_support_mjtcsessiondata", array( 'usersessionid' => majesticsupport::$_mjtcsession->sessionid , 'sessionfor' => $sessionfor) );
        }
        return $data;
    }

}

?>

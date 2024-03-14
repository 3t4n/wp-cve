<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_emailtemplateModel {

    function getTemplate($tempfor) {
        switch ($tempfor) {
            case 'tk-nw' : $tempatefor = 'ticket-new';
                break;
            case 'sntk-tk' : $tempatefor = 'ticket-staff';
                break;
            case 'ew-md' : $tempatefor = 'department-new';
                break;
            case 'ew-gr' : $tempatefor = 'group-new';
                break;
            case 'ew-sm' : $tempatefor = 'staff-new';
                break;
            case 'ew-ht' : $tempatefor = 'helptopic-new';
                break;
            case 'rs-tk' : $tempatefor = 'reassign-tk';
                break;
            case 'cl-tk' : $tempatefor = 'close-tk';
                break;
            case 'dl-tk' : $tempatefor = 'delete-tk';
                break;
            case 'mo-tk' : $tempatefor = 'moverdue-tk';
                break;
            case 'be-tk' : $tempatefor = 'banemail-tk';
                break;
            case 'be-trtk' : $tempatefor = 'banemail-trtk';
                break;
            case 'dt-tk' : $tempatefor = 'deptrans-tk';
                break;
            case 'ebct-tk' : $tempatefor = 'banemailcloseticket-tk';
                break;
            case 'ube-tk' : $tempatefor = 'unbanemail-tk';
                break;
            case 'rsp-tk' : $tempatefor = 'responce-tk';
                break;
            case 'rpy-tk' : $tempatefor = 'reply-tk';
                break;
            case 'tk-ew-ad' : $tempatefor = 'ticket-new-admin';
                break;
            case 'lk-tk' : $tempatefor = 'lock-tk';
                break;
            case 'ulk-tk' : $tempatefor = 'unlock-tk';
                break;
            case 'minp-tk' : $tempatefor = 'minprogress-tk';
                break;
            case 'pc-tk' : $tempatefor = 'prtrans-tk';
                break;
            case 'ml-ew' : $tempatefor = 'mail-new';
                break;
            case 'ml-rp' : $tempatefor = 'mail-rpy';
                break;
            case 'fd-bk' : $tempatefor = 'mail-feedback';
                break;
            case 'no-rp' : $tempatefor = 'mail-rpy-closed';
                break;
            case 'del-data' : $tempatefor = 'delete-user-data';
                break;
            default: $tempatefor = 'ticket-new';
                break;
        }
        $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_emailtemplates` WHERE templatefor = '" . $tempatefor . "'";
        majesticsupport::$_data[0] = majesticsupport::$_db->get_row(($query));
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        majesticsupport::$_data[2] = MJTC_includer::MJTC_getModel('fieldordering')->getUserfieldsfor(1);
        return ;
    }

    //For the Email template
    function storeEmailTemplate($data) {
        $data['title'] = isset($data['title']) ? $data['title'] : '';
        $data['status'] = isset($data['status']) ? $data['status'] : 1;
        $data['body'] = wpautop(wptexturize(MJTC_majesticsupportphplib::MJTC_stripslashes($_POST['body'])));

        $row = MJTC_includer::MJTC_getTable('emailtemplates');

        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }
        if ($error == 0) {
            MJTC_message::MJTC_setMessage(esc_html(__('Email template has been stored', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Email template has not been stored', 'majestic-support')), 'error');
        }
        return;
    }

    function getDefaultEmailTemplate() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'list-email-template') ) {
            die( 'Security check Failed' );
        }
        $templatefor = MJTC_request::MJTC_getVar('templatefor');
        $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_emailtemplates` WHERE templatefor = '" . $templatefor . "'";
        $result = majesticsupport::$_db->get_row($query);
        $data =  array('defaultsubject'=>MJTC_majesticsupportphplib::MJTC_htmlentities($result->subject),'defaultbody'=>MJTC_majesticsupportphplib::MJTC_htmlentities($result->body) , 'defaultid'=>MJTC_majesticsupportphplib::MJTC_htmlentities($result->id));
        return json_encode($data);

    }
}

?>

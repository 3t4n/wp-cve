<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_replyController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $task = MJTC_request::MJTC_getLayout('task', null, 'replies_replies');
        if (self::canaddfile()) {
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'reply');
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'majesticsupport')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'mstask')
            return false;
        else
            return true;
    }

    static function savereply() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-reply') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('reply')->storeReplies($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . MJTC_request::MJTC_getVar('ticketid'));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>MJTC_request::MJTC_getVar('ticketid')));
        }
        wp_redirect($url);
        exit;
    }

    static function saveeditedreply() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-edited-reply') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('reply')->editReply($data);
        if (current_user_can('manage_options') || current_user_can('ms_support_ticket_tickets')) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . $data['reply-tikcetid']);
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>$data['reply-tikcetid'],'mspageid'=>majesticsupport::getPageid()));
        }
        wp_redirect($url);
        exit;
    }

    static function saveeditedtime() {
        if(!in_array('timetracking', majesticsupport::$_active_addons)){
            return;
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('timetracking')->editTime($data);
        if (current_user_can('manage_options') || current_user_can('ms_support_ticket_tickets')) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . $data['reply-tikcetid']);
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketdetail','majesticsupportid'=>$data['reply-tikcetid'],'mspageid'=>majesticsupport::getPageid()));
        }
        wp_redirect($url);
        exit;
    }

}

$replyController = new MJTC_replyController();
?>

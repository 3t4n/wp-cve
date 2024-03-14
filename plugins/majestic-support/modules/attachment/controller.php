<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_attachmentController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'getattachments');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'getattachments':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid', 'get', null);
                    MJTC_includer::MJTC_getModel('replies')->getrepliesForForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'attachment');
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

    static function saveattachments() {
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('attachment')->storeAttachments($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=ticketdetail&majesticsupportid=" . MJTC_request::MJTC_getVar('ticketid'));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'replies', 'mjslay'=>'replies'));
        }
        wp_redirect($url);
        exit;
    }

    static function deleteattachment() {
        $id = MJTC_request::MJTC_getVar('id');
        $call_from = MJTC_request::MJTC_getVar('call_from','',1);
        MJTC_includer::MJTC_getModel('attachment')->removeAttachment($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_ticket&mjslay=addticket&majesticsupportid=" . MJTC_request::MJTC_getVar('ticketid'));
        } else {
            if($call_from == 2){
                $url = majesticsupport::makeUrl(array('mjsmod'=>'agent', 'mjslay'=>'staffaddticket','majesticsupportid'=>MJTC_request::MJTC_getVar('ticketid')));
            }else{
                $url = majesticsupport::makeUrl(array('mjsmod'=>'replies', 'mjslay'=>'replies'));
            }
        }
        wp_redirect($url);
        exit;
    }

}

$attachmentController = new MJTC_attachmentController();
?>

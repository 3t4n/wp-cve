<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_smartreplyController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'smartreplies');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_smartreplies':
                case 'smartreplies':
                    majesticsupport::$_data['permission_granted'] = true;
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Smart Reply');
                    }
                    if (majesticsupport::$_data['permission_granted']) {
                        MJTC_includer::MJTC_getModel('smartreply')->getSmartreplyies();
                    }
                    break;
                case 'admin_addsmartreply':
                case 'addsmartreply':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid');
                    majesticsupport::$_data['permission_granted'] = true;
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        $per_task = ($id == null) ? 'Add Smart Reply' : 'Edit Smart Reply';
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask($per_task);
                    }
                    if (majesticsupport::$_data['permission_granted']) {
                        MJTC_includer::MJTC_getModel('smartreply')->getSmartReplyForForm($id);
                    }
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'smartreply');
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

    static function savesmartreply() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-smart-reply') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('smartreply')->storeSmartReply($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_smartreply&mjslay=smartreplies");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'smartreply','mjslay'=>'smartreplies'));
        }
        wp_redirect($url);
        exit;
    }

    static function deletesmartreply() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-smartreply') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('smartreplyid');
        MJTC_includer::MJTC_getModel('smartreply')->removeSmartreply($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_smartreply&mjslay=smartreplies");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'smartreply','mjslay'=>'smartreplies'));
        }
        wp_redirect($url);
        exit;
    }

}

$smartreplyController = new MJTC_smartreplyController();
?>

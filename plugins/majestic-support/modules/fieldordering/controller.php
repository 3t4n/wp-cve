<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_fieldorderingController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'fieldordering');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_fieldordering':
                    $fieldfor = MJTC_request::MJTC_getVar('fieldfor',null,1);
                    $formid = MJTC_request::MJTC_getVar('formid');
                    majesticsupport::$_data['fieldfor'] = $fieldfor;
                    if ($fieldfor != 1) {
                        majesticsupport::$_data['formid'] = 1;
                    }
                    else{
                        majesticsupport::$_data['formid'] = $formid;
                        do_action('ms_multiform_name_for_list' , $formid);
                    }
                    MJTC_includer::MJTC_getModel('fieldordering')->getFieldOrderingForList($fieldfor);
                    break;
                case 'admin_adduserfeild':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid');
                    $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
                    if($fieldfor == ''){
                        $fieldfor = majesticsupport::$_data['fieldfor'];
                    }else{
                        majesticsupport::$_data['fieldfor'] = $fieldfor;
                    }
                    // formid
                    if ($fieldfor != 1) {
                        majesticsupport::$_data['formid'] = 1;
                    }
                    else{
                        $formid = MJTC_request::MJTC_getVar('formid');
                        majesticsupport::$_data['formid'] = $formid;
                        do_action('ms_multiform_name_for_list' , $formid);
                    }
                    MJTC_includer::MJTC_getModel('fieldordering')->getUserFieldbyId($id,1);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'fieldordering');
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

    static function changeorder() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-order') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('fieldorderingid');
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        $action = MJTC_request::MJTC_getVar('order');
        MJTC_includer::MJTC_getModel('fieldordering')->changeOrder($id, $action);
        $url = admin_url("admin.php?page=majesticsupport_fieldordering&mjslay=fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        wp_redirect($url);
        exit;
    }

    static function changepublishstatus() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-publish-status') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('fieldorderingid');
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        $status = MJTC_request::MJTC_getVar('status');
        MJTC_includer::MJTC_getModel('fieldordering')->changePublishStatus($id, $status);
        $url = admin_url("admin.php?page=majesticsupport_fieldordering&mjslay=fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        wp_redirect($url);
        exit;
    }

    static function changevisitorpublishstatus() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-visitor-publish-status') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('fieldorderingid');
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        $status = MJTC_request::MJTC_getVar('status');
        MJTC_includer::MJTC_getModel('fieldordering')->changeVisitorPublishStatus($id, $status);
        $url = admin_url("admin.php?page=majesticsupport_fieldordering&mjslay=fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        wp_redirect($url);
        exit;
    }

    static function changerequiredstatus() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-required-status') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('fieldorderingid');
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        $status = MJTC_request::MJTC_getVar('status');
        MJTC_includer::MJTC_getModel('fieldordering')->changeRequiredStatus($id, $status);
        $url = admin_url("admin.php?page=majesticsupport_fieldordering&mjslay=fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        wp_redirect($url);
        exit;
    }

    static function saveuserfeild() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-userfeild') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');

        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        MJTC_includer::MJTC_getModel('fieldordering')->storeUserField($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'fieldordering', 'mjslay'=>'userfeilds'));
        }
        wp_redirect($url);
        exit;
    }

    static function savefeild() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-feild') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        MJTC_includer::MJTC_getModel('fieldordering')->updateField($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'fieldordering', 'mjslay'=>'userfeilds'));
        }
        wp_redirect($url);
        exit;
    }

    static function removeuserfeild() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'remove-userfeild') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('majesticsupportid');
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = majesticsupport::$_data['fieldfor'];
        }
        $formid = MJTC_request::MJTC_getVar('formid');
        MJTC_includer::MJTC_getModel('fieldordering')->deleteUserField($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_fieldordering&fieldfor=".esc_attr($fieldfor)."&formid=".esc_attr($formid));
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'fieldordering', 'mjslay'=>'userfeilds'));
        }
        wp_redirect($url);
        exit;
    }

}

$fieldorderingController = new MJTC_fieldorderingController();
?>

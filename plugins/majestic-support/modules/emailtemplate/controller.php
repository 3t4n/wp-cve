<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_emailtemplateController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'emailtemplates');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_emailtemplates':
                    $tempfor = MJTC_request::MJTC_getVar('for', null, 'tk-nw');
                    majesticsupport::$_data[1] = $tempfor;
                    MJTC_includer::MJTC_getModel('emailtemplate')->getTemplate($tempfor);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'emailtemplate');
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

    static function saveemailtemplate() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-email-template') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        if($data['callfor'] == 'Mulitlanguage'){
            if($data['lang_id'] == '' || $data['subject'] == '' || $data['body'] == ''){
                MJTC_message::MJTC_setMessage(esc_html(__('Required Fields are not filled', 'majestic-support')), 'error');
            }else{
                MJTC_includer::MJTC_getModel('multilanguageemailtemplates')->storeMultiLanguageEmailTemplate($data);
            }
        }else{
            MJTC_includer::MJTC_getModel('emailtemplate')->storeEmailTemplate($data);
        }
        $url = admin_url("admin.php?page=majesticsupport_emailtemplate&for=" . MJTC_request::MJTC_getVar('for'));
        wp_redirect($url);
        exit;
    }

}

$emailtemplateController = new MJTC_emailtemplateController();
?>

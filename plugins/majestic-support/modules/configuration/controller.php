<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_configurationController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'configurations');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_configurations':
                    $msconfigid = MJTC_request::MJTC_getVar('msconfigid');
                    if (isset($msconfigid)) {
                        majesticsupport::$_data['msconfigid'] = $msconfigid;
                    }
                    $ck = MJTC_includer::MJTC_getModel('configuration')->getCheckCronKey();
                    if ($ck == false) {
                        MJTC_includer::MJTC_getModel('configuration')->genearateCronKey();
                    }
                    MJTC_includer::MJTC_getModel('configuration')->getConfigurations();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'configuration');
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

    static function saveconfiguration() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-configuration') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('configuration')->storeConfiguration($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_configuration&msconfigid=general");
        }
        if(isset($data['call_from']) && $data['call_from'] == 'notification' && is_admin()){
            $url = admin_url("admin.php?page=majesticsupport_web-notification-setting");    
        }
        wp_redirect($url);
        exit;
    }

}

$configurationController = new MJTC_configurationController();
?>

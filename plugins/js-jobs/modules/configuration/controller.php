<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSConfigurationController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('configuration')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'configurations');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_configurations':
                case 'admin_configurationsemployer':
                case 'admin_configurationsjobseeker':
                    JSJOBSincluder::getJSModel('configuration')->getConfigurationsForForm();
                    break;
                case 'admin_cronjob':
                    JSJOBSincluder::getJSModel('configuration')->getCronKey(jsjobslib::jsjobs_md5(date_i18n('Y-m-d')));
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'configurations');
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function saveconfiguration() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'formconfiguration') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $layout = JSJOBSrequest::getVar('jsjobslt');
        $result = JSJOBSincluder::getJSModel('configuration')->storeConfig($data);
        $msg = JSJOBSMessages::getMessage($result, "configuration");
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_configuration&jsjobslt=" . $layout ) , 'configuration');
        wp_redirect($url);
        die();
    }

}

$JSJOBSConfigurationController = new JSJOBSConfigurationController();
?>

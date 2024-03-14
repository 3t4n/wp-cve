<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSSalaryrangeController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('salaryrange')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'salaryrange');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_salaryrange':
                    JSJOBSincluder::getJSModel('salaryrange')->getAllSalaryRange();
                    break;
                case 'admin_formsalaryrange':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formsalaryrange') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('salaryrange')->getSalaryRangebyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'salaryrange');
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

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-salaryrange') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrange')->deleteSalaryRanges($ids);
        $msg = JSJOBSMessages::getMessage($result, 'salaryrange');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange'),"salaryrange");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-salaryrange') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrange')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('salaryrange');
        $url = admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange&_wpnonce=' . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-salaryrange') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrange')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('salaryrange');
        $url = admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange&_wpnonce=' . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savesalaryrange() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-salaryrange') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('salaryrange')->storeSalaryRange($data);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_salaryrange&jsjobslt=salaryrange'),"salaryrange");
        $msg = JSJOBSMessages::getMessage($result, 'salaryrange');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSSalaryrangeController = new JSJOBSSalaryrangeController();
?>

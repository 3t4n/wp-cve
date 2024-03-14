<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsalaryrangetypeController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('salaryrangetype')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'salaryrangetype');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_salaryrangetype':
                    JSJOBSincluder::getJSModel('salaryrangetype')->getAllSalaryRangeType();
                    break;
                case 'admin_formsalaryrangetype':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formsalaryrangetype') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('salaryrangetype')->getSalaryRangeTypebyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'salaryrangetype');
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

    function savesalaryrangetype() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-salaryrangetype') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('salaryrangetype')->storeSalaryRangeType($data);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_salaryrangetype&jsjobslt=salaryrangetype'),"salaryrangetype");
        $msg = JSJOBSMessages::getMessage($result, 'salaryrangetype');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-salaryrangetype') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrangetype')->deleteSalaryRangesType($ids);
        $msg = JSJOBSMessages::getMessage($result, 'salaryrangetype');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_salaryrangetype&jsjobslt=salaryrangetype'),"salaryrangetype");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-salaryrangetype') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrangetype')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('salaryrangetype');
        $url = admin_url('admin.php?page=jsjobs_salaryrangetype&jsjobslt=salaryrangetype&_wpnonce=' . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-salaryrangetype') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('salaryrangetype')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('salaryrangetype');
        $url = admin_url('admin.php?page=jsjobs_salaryrangetype&jsjobslt=salaryrangetype&_wpnonce=' . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSsalaryrangetypeController = new JSJOBSSalaryrangetypeController();
?>

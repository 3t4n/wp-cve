<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobtypeController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
        $this->_msgkey = JSJOBSincluder::getJSModel('jobtype')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'jobtypes');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_jobtypes':
                    JSJOBSincluder::getJSModel('jobtype')->getAllJobTypes();
                    break;
                case 'admin_formjobtype':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formjobtype') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('jobtype')->getJobTypebyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jobtypes');
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

    function savejobtype() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-jobtype') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('jobtype')->storeJobType($data);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_jobtype&jsjobslt=jobtypes'),"jobtype");
        $msg = JSJOBSMessages::getMessage($result, 'jobtype');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-jobtype') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('jobtype')->deleteJobsType($ids);
        $msg = JSJOBSMessages::getMessage($result, 'jobtype');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_jobtype&jsjobslt=jobtypes"),"jobtype");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-jobtype') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('jobtype')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('jobtype');
        $url = admin_url("admin.php?page=jsjobs_jobtype&jsjobslt=jobtypes&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-jobtype') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('jobtype')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('jobtype');
        $url = admin_url("admin.php?page=jsjobs_jobtype&jsjobslt=jobtypes&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSJobtypeController = new JSJOBSJobtypeController();
?>

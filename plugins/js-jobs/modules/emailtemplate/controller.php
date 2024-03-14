<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSEmailtemplateController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('emailtemplate')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'emailtemplate');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_emailtemplate':
                    $tempfor = JSJOBSrequest::getVar('for', null, 'ew-cm');
                    JSJOBSincluder::getJSModel('emailtemplate')->getTemplate($tempfor);
                    jsjobs::$_data[1] = $tempfor;
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'emailtemplate');
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

    function saveemailtemplate() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-emailtemplate') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $templatefor = $data['templatefor'];
        $result = JSJOBSincluder::getJSModel('emailtemplate')->storeEmailTemplate($data);
        $msg = JSJOBSMessages::getMessage($result, 'emailtemplate');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);

        switch ($templatefor) {
            case 'company-new' : $tempfor = 'ew-cm';
                break;
            case 'company-delete' : $tempfor = 'd-cm';
                break;
            case 'company-status' : $tempfor = 'cm-sts';
                break;
            case 'company-rejecting' : $tempfor = 'cm-rj';
                break;
            case 'job-new' : $tempfor = 'ew-ob';
                break;
            case 'job-approval' : $tempfor = 'ob-ap';
                break;
            case 'job-delete' : $tempfor = 'ob-d';
                break;
            case 'resume-new' : $tempfor = 'ew-rm';
                break;
            case 'message-email' : $tempfor = 'ew-ms';
                break;
            case 'resume-approval' : $tempfor = 'rm-ap';
                break;
            case 'resume-rejecting' : $tempfor = 'rm-rj';
                break;
            case 'applied-resume_status' : $tempfor = 'ap-rs';
                break;
            case 'jobapply-jobapply' : $tempfor = 'ba-ja';
                break;
            case 'department-new' : $tempfor = 'ew-md';
                break;
            case 'employer-buypackage' : $tempfor = 'ew-rp';
                break;
            case 'jobseeker-buypackage' : $tempfor = 'ew-js';
                break;
            case 'job-alert' : $tempfor = 'jb-at';
                break;
            case 'job-alert-visitor' : $tempfor = 'jb-at-vis';
                break;
            case 'job-to-friend' : $tempfor = 'jb-to-fri';
                break;
        }
        $url = admin_url("admin.php?page=jsjobs_emailtemplate&for=" . $tempfor);
        wp_redirect($url);
        die();
    }

}

$JSJOBSEmailtemplateController = new JSJOBSEmailtemplateController();
?>

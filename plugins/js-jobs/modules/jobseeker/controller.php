<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSJobseekerController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'controlpanel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'jobseeker_report':
                    break;
                case 'controlpanel':
                    if(get_option( 'jsjobs_apply_visitor', '' ) != '')
                        delete_option( 'jsjobs_apply_visitor' );
                    $visitorview_js_controlpanel = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_js_controlpanel');
                    if ($visitorview_js_controlpanel != 1) {
                        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobseeker', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker
                            jsjobs::$_error_flag = true;
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs',  'jsjobspageid'=>jsjobs::getPageid()));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag = true;
                        }
                    }
                    if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                        $employerview_js_controlpanel = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('employerview_js_controlpanel');
                        if ($employerview_js_controlpanel != 1){
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(7,null,null,1);
                            jsjobs::$_error_flag_message_for=7;
                            jsjobs::$_error_flag = true;
                        }
                    }
		    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link = $link;
                        jsjobs::$_error_flag_message_for_link_text = $linktext;
                    }
                    //code for user related jobs
                    $uid = JSJOBSincluder::getObjectClass('user')->uid();
                    JSJOBSincluder::getJSModel('jobseeker')->getResumeStatusByUid($uid);
                    JSJOBSincluder::getJSModel('jobseeker')->getMyStats($uid);
                    JSJOBSincluder::getJSModel('jobseeker')->getConfigurationForControlPanel();
                    JSJOBSincluder::getJSModel('jobseeker')->getJobsByUid($uid);
                    JSJOBSincluder::getJSModel('jobseeker')->resumeIsAutoApproved($uid);
                    JSJOBSincluder::getJSModel('jobseeker')->getLatestJobs();
                    break;
                case 'mystats':
                    if(JSJOBSincluder::getObjectClass('user')->isEmployer()){
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(7,null,null,1);
                        jsjobs::$_error_flag_message_for=7;
                        jsjobs::$_error_flag = true;
                    } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                        $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobseeker', $layout, 1);
                        $linktext = __('Login','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                        jsjobs::$_error_flag_message_for=1;
                        jsjobs::$_error_flag_message_register_for=1; // register as jobseeker
                        jsjobs::$_error_flag = true;
                    } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                        $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs',  'jsjobspageid'=>jsjobs::getPageid()));
                        $linktext = __('Select role','js-jobs');
                        jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                        jsjobs::$_error_flag_message_for=9;
                        jsjobs::$_error_flag = true;
                    }else{
                        $uid = JSJOBSincluder::getObjectClass('user')->uid();
                        $result = JSJOBSincluder::getJSModel('jobseeker')->getMyStats($uid);
                        if($result == false){
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobseeker', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker
                            jsjobs::$_error_flag = true;
                        }
                    }
                    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link = $link;
                        jsjobs::$_error_flag_message_for_link_text = $linktext;
                    }
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jobseeker');
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

}

$JSJOBSJobseekerController = new JSJOBSJobseekerController();
?>

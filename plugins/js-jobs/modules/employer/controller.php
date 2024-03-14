<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSEmployerController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'controlpanel');
        if (self::canaddfile()) {
            $empflag  = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_employer');
            $guestflag = false;
            $visitorallowed = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_conrolpanel');
            $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
            $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
            
            if($isguest == true && $visitorallowed == true){
                $guestflag = true;
            }
            if($isguest == false && $isouruser == false && $visitorallowed == true){
                $guestflag = true;
            }

            switch ($layout) {
                case 'employer_report':
                    break;
                case 'controlpanel':
                    if (is_admin() || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1 || $guestflag == true)) {
                        $uid = JSJOBSincluder::getObjectClass('user')->uid();
                        JSJOBSincluder::getJSModel('employer')->getNewestResumeForEmployer($guestflag);
                        JSJOBSincluder::getJSModel('employer')->getApplliedResumeBYUid($uid);
                        JSJOBSincluder::getJSModel('employer')->getMyStats($uid);
                        JSJOBSincluder::getJSModel('employer')->getEmployerCpTabData($uid);
                        JSJOBSincluder::getJSModel('employer')->getLatestResume();
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for = 2;                
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('employer', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for = 1;        

                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for = 9;                
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link = $link;               
                            jsjobs::$_error_flag_message_for_link_text = $linktext;              
                        }
                        jsjobs::$_error_flag = true;
                    }
                    break;

            }
            if ($empflag == 0) {
                jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(5,null,null,1);
                jsjobs::$_error_flag = true;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'employer');
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

$JSJOBSEmployerController = new JSJOBSEmployerController();
?>

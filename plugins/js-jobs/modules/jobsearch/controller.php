<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjobSearchController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
        $this->_msgkey = JSJOBSincluder::getJSModel('jobsearch')->getMessagekey();        
    }

    function handleRequest() {

        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'jobsavesearch');
        if (self::canaddfile()) {
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jscontrolpanel');
            switch ($layout) {
                case 'jobsearch':
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker() || JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_js_jobsearch') == 1 ) {
                        JSJOBSincluder::getJSModel('jobsearch')->getJobSearchOptions();
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobsearch', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs', 'jsjobspageid'=>jsjobs::getPageid()));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9;
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link = $link;               
                            jsjobs::$_error_flag_message_for_link_text = $linktext;              
                        }
                        jsjobs::$_error_flag = true;
                    }
                    break;
                case 'jobsavesearch':
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                        $userid = JSJOBSincluder::getObjectClass('user')->uid();
                        JSJOBSincluder::getJSModel('jobsearch')->getMyJobSaveSearchbyUid($userid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('jobsearch', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs', 'jsjobspageid'=>jsjobs::getPageid()));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9;
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link = $link;               
                            jsjobs::$_error_flag_message_for_link_text = $linktext;              
                        }
                        jsjobs::$_error_flag = true;
                    }
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'jobsearch');
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

$JSJOBSjobSearchController = new JSJOBSjobSearchController();
?>
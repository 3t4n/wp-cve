<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSresumeSearchController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('resumesearch')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'resumesearch');
        if (self::canaddfile()) {
            $empflag  = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_employer');
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('emcontrolpanel');
            switch ($layout) {
                case 'resumesearch':
                    if (is_admin() || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) || JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_resumesearch') == 1 ) {
                        JSJOBSincluder::getJSModel('resumesearch')->getResumeSearchOptions();
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2; 
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('resumesearch', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1; 
                            jsjobs::$_error_flag_message_register_for=2; 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs', 'jsjobspageid'=>jsjobs::getPageid()));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9; 
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link=$link;
                            jsjobs::$_error_flag_message_for_link_text=$linktext;
                        }
                        jsjobs::$_error_flag = true;

                    }
                    break;
                case 'resumesavesearch':
                    if (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) {
                        $userid = JSJOBSincluder::getObjectClass('user')->uid();
                        JSJOBSincluder::getJSModel('resumesearch')->getMyResumeSearchesbyUid($userid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2; 
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('resumesearch', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1; 
                            jsjobs::$_error_flag_message_register_for=2; 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs', 'jsjobspageid'=>jsjobs::getPageid()));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9; 
                        }
                        if(isset($link) && isset($linktext)){
                            jsjobs::$_error_flag_message_for_link=$link;
                            jsjobs::$_error_flag_message_for_link_text=$linktext;
                        }
                        jsjobs::$_error_flag = true;
                    }
                    break;
            }
            if ($empflag == 0) {
                jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(5,null,null,1);
                jsjobs::$_error_flag_message_for=5; 
                jsjobs::$_error_flag = true;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'resumesearch');
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

    function removeSavedSearch() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-resumesearch') ) {
            die( 'Security check Failed' ); 
        }
        $id = JSJOBSrequest::getVar('jsjobsid');
        $callfrom = JSJOBSrequest::getVar('callfrom','',0);
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $result = JSJOBSincluder::getJSModel('resumesearch')->deleteResumeSearch($id, $uid);
        $msg = JSJOBSMessages::getMessage($result, 'resumesearch');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if($callfrom == 0){
            $url = jsjobs::makeUrl(array('jsjobsme'=>'resumesearch', 'jsjobslt'=>'resumesavesearch', 'jsjobspageid'=>jsjobs::getPageid()));
        }else{
            $url = jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'controlpanel', 'jsjobspageid'=>jsjobs::getPageid()));
        }
        
        wp_redirect($url);
        die();
    }

    function saveResumeSearch() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-resumesearch') ) {
             die( 'Security check Failed' ); 
        }
        $result = JSJOBSincluder::getJSModel('resumesearch')->storeResumeSearch();
        $msg = JSJOBSMessages::getMessage($result, 'resumesearch');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes',  'search'=>jsjobslib::jsjobs_str_replace(' ', '-', jsjobs::$_data['searchname']) . '-' . jsjobs::$_data['id'], 'jsjobspageid'=>jsjobs::getPageid()));
        wp_redirect($url);
        die();
    }

}

$JSJOBSresumeSearchController = new JSJOBSresumeSearchController();

?>

<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCoverLetterController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('coverletter')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'coverletters');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('jscontrolpanel');
            switch ($layout) {
                case 'mycoverletters':
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                        $uid = JSJOBSincluder::getObjectClass('user')->uid();
                        JSJOBSincluder::getJSModel('coverletter')->getMyCoverLettersbyUid($uid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('coverletter', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
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
                case 'viewcoverletter':
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker() || current_user_can('manage_options')) {
                        $id = JSJOBSrequest::getVar('jsjobsid');
                        JSJOBSincluder::getJSModel('coverletter')->getViewCoverLetter($id);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('coverletter', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
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
                case 'admin_formcoverletter':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('coverletter')->getCoverLetterbyId($id);                    
                    break;
                case 'admin_coverletters':
                    JSJOBSincluder::getJSModel('coverletter')->getAllCoverletters();
                    break;
                case 'addcoverletter':
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
						$id = JSJOBSrequest::getVar('jsjobsid');
                        if($id == ''){
                            $check = JSJOBSincluder::getJSModel('coverletter')->canAddCoverLetter($uid);
                        }else{
                            $check = JSJOBSincluder::getJSModel('coverletter')->getIfCoverLetterOwner($id);
                        }
                        if ($check == true) {
                            JSJOBSincluder::getJSModel('coverletter')->getCoverLetterbyId($id);
                        } else{
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(10,null,null,1);
                            jsjobs::$_error_flag_message_for=10;
                            jsjobs::$_error_flag = true;
                        }
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('coverletter', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=1; // register as jobseeker 
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
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
            $module = JSJOBSrequest::getVar($module, null, 'coverletter');
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

    function savecoverletter() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-coverletter') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('coverletter')->storeCoverLetter($data);
        $msg = JSJOBSMessages::getMessage($result, 'coverletter');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if(is_admin()){
            $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_coverletter&jsjobslt=coverletters"),"coverletter");
        }else{
            $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'mycoverletters')),"coverletter");
        }
        wp_redirect($url);
        exit();
    }

    function removecoverletter() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-coverletter') ) {
             die( 'Security check Failed' ); 
        }
        if(is_admin()){
            $ids = JSJOBSrequest::getVar('jsjobs-cb');
            $result = JSJOBSincluder::getJSModel('coverletter')->deleteCoverLetterAdmin($ids);
            $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_coverletter&jsjobslt=coverletters"),"coverletter");
        }else{
            $id = JSJOBSrequest::getVar('jsjobsid');
            $uid = JSJOBSincluder::getObjectClass('user')->uid();
            $result = JSJOBSincluder::getJSModel('coverletter')->deleteCoverLetter($id, $uid);
            $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'coverletter', 'jsjobslt'=>'mycoverletters')),"coverletter");
        }

        $msg = JSJOBSMessages::getMessage($result, 'coverletter');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-coverletter') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('coverletter')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('coverletter');
        $url = admin_url("admin.php?page=jsjobs_coverletter&jsjobslt=coverletters&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-coverletter') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('coverletter')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('coverletter');
        $url = admin_url("admin.php?page=jsjobs_coverletter&jsjobslt=coverletters&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }
}

$JSJOBScoverletterController = new JSJOBSCoverLetterController();
?>

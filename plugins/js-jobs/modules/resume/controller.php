<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSResumeController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('resume')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'resumes');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
            $empflag  = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_employer');
            $string = "'jscontrolpanel','emcontrolpanel','visitor','resume'" ;
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigForMultiple($string);
            switch ($layout) {
                case 'resumes':
                    $vars = array();
                    $resume_view_type = JSJOBSrequest::getVar('viewtype',null,1); // 1 for list view 2 for grid view
                    $resume_view_type=jsjobslib::jsjobs_str_replace("vt-","",$resume_view_type);
                    jsjobs::$_data['viewtype'] = $resume_view_type;
                    if($resume_view_type==2){ // switch list to grid show save serch
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    if ($id) {
                        $array = jsjobslib::jsjobs_explode('_', $id);
                        if ($array[0] == 'tags') {
                            unset($array[0]);
                            $array = implode(' ', $array);
                            $vars['tags'] = $array;
                            jsjobs::$_data['tags'] = $vars['tags'];
                        } else {
                            if(isset($array[1])){
                                $id = $array[1];
                                $clue = $id[0] . $id[1];
                                switch ($clue) {
                                    case '10': //Category
                                        $vars['category'] = jsjobslib::jsjobs_substr($id, 2);
                                        jsjobs::$_data['categoryid'] = $array[0] . '-' . $vars['category'];
                                        break;
                                    case '13': //Search
                                        $id = jsjobslib::jsjobs_substr($id, 2);
                                        jsjobs::$_data['searchid'] = $array[0] . '-' . $id;
                                        $vars['searchid'] = $id;
                                        break;
                                    case '14': //sorting in case of parama and no other option selected
                                        $sortby = $array[0];
                                        $id = '';
                                        break;
                                    default:
                                        $id = '';
                                        break;
                                }
                            }
                            // had to do this to handle a sorting in sef case
                            if(jsjobslib::jsjobs_strstr($id, 'asc') || jsjobslib::jsjobs_strstr($id, 'desc')){
                                jsjobs::$_data['sanitized_args']['sortby'] = $id;
                            }
                        }
                    } else {
                        $searchtext = JSJOBSrequest::getVar('search');
                        if ($searchtext) {
                            //parse id what is the meaning of it
                            $array = jsjobslib::jsjobs_explode('-', $searchtext);
                            $vars['searchid'] = $array[count($array) - 1];
                        } else {
                            $vars['searchid'] = '';
                        }
                        $id = JSJOBSrequest::getVar('category', 'get');
                        if ($id) {
                            $array = jsjobslib::jsjobs_explode('-', $id);
                            $id = $array[count($array) - 1];
                            $vars['category'] = (int) $id;
                        }
                    }
                    JSJOBSincluder::getJSModel('resume')->getResumes($vars);
                    break;
                case 'myresumes':
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                        JSJOBSincluder::getJSModel('resume')->getMyResumes($uid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('resume', $layout, 1);
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
                case 'viewresume':
                case 'admin_viewresume':
                    //$layout = 'viewresume';
                    $resumeid = '';
                    if (JSJOBSincluder::getObjectClass('user')->isjobseeker() || current_user_can('manage_options') || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) || JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_viewresume') == 1 ) {
                        $resumeid = JSJOBSrequest::getVar('jsjobsid');
                        $socialid = JSJOBSrequest::getVar('jsscid');
                        //check for the social id
                        if ((!is_numeric($resumeid) && $resumeid[0] . $resumeid[1] . $resumeid[2] == 'sc-') || $socialid != null) { // social
                            $idarray = jsjobslib::jsjobs_explode('-', $resumeid);
                            $profileid = $idarray[1];
                            jsjobs::$_data['socialprofileid'] = $profileid;
                            jsjobs::$_data['socialprofile'] = true;
                        } else {
                            $resumeowner = true;
                            $idarray = jsjobslib::jsjobs_explode('-', $resumeid);
                            $resumeid = $idarray[count($idarray) - 1];
                            $expiryflag = JSJOBSincluder::getJSModel('resume')->getResumeExpiryStatus($resumeid);
                            if(is_admin()){
                                $expiryflag = true;
                            }
                            if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                                if (JSJOBSincluder::getJSModel('resume')->getIfResumeOwner($resumeid)) {
                                    $expiryflag = true;
                                }else{
                                    jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(10,null,null,1);
                                    jsjobs::$_error_flag_message_for = 2;
                                    jsjobs::$_error_flag = true;
                                    break;
                                }
                            }
                            if ($expiryflag == false) {
                                jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(6,null,null,1);
                                jsjobs::$_error_flag_message_for=6;
                                jsjobs::$_error_flag = true;
                            } else {
                                JSJOBSincluder::getJSModel('resume')->getResumeById($resumeid);
                                jsjobs::$_data['socialprofile'] = false;
                            }
                        }
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('resume', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=2; // register as employer
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
                    $jobid = JSJOBSrequest::getVar('jobid');
                    $idarray = jsjobslib::jsjobs_explode('-', $jobid);
                    if (!empty($idarray)) {
                        $jobid = $idarray[count($idarray) - 1];
                    }
                    JSJOBSincluder::getJSModel('coverletter')->getCoverLetterByResumeAndJobID($resumeid, $jobid);
                    break;
                case 'resumebycategory':
                    if (is_admin() || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) || JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_resumecat') == 1 ) {
                        $resumeid = JSJOBSrequest::getVar('resumeid');
                        JSJOBSincluder::getJSModel('resume')->getResumeByCategory();
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('resume', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=2; // register as employer
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
                case 'admin_formresume':
                case 'addresume':
                    jsjobs::$_error_flag_message = null;
                    $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
                    $isguest = JSJOBSincluder::getObjectClass('user')->isguest();
                    $guest = false;
                    if($isguest == true){
                        $guest = true;
                    }
                    if($isguest == false && $isouruser == false){
                        $guest = true;
                    }

                    // Check user is guest and is allowed to add resume
                    $guestallowed = 0;

                    if ($guest) {
                        $guestallowed = $config_array['visitor_can_add_resume'];
                    }
                    if ((JSJOBSincluder::getObjectClass('user')->isjobseeker() && $config_array['formresume'] == 1) || ($guestallowed == 1 && $config_array['vis_jsformresume'] == 1) || is_admin()) {
                        jsjobs::$_data['resumeid'] = JSJOBSrequest::getVar('jsjobsid');

                        if(is_numeric(jsjobs::$_data['resumeid'])){
                            if(!is_admin()){
                                $check = JSJOBSincluder::getJSModel('resume')->getIfResumeOwner(jsjobs::$_data['resumeid']);
                            }
                        }else{
                            $check = JSJOBSincluder::getJSModel('resume')->canAddResume($uid);
                        }
                        if (is_admin() || $guestallowed == 1 || $check == true) {
                            if ($guestallowed == 1) {
                                if (isset($_COOKIE['wp-jsjobs'])) {
                                    $wp_jobs = sanitize_key(json_decode(jsjobslib::jsjobs_safe_decoding($_COOKIE['wp-jsjobs']),true));
                                    if(isset($wp_jobs['resumeid']))
                                        jsjobs::$_data['resumeid'] = $wp_jobs['resumeid'];
                                }
                            }
                            JSJOBSincluder::getJSModel('resume')->getResumeById(jsjobs::$_data['resumeid']);
                        }elseif(is_numeric(jsjobs::$_data['resumeid'])){
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(10,null,null,1);
                            jsjobs::$_error_flag_message_for=4;
                            jsjobs::$_error_flag = true;
                        }
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(3,null,null,1);
                            jsjobs::$_error_flag_message_for=3;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('resume', $layout, 1);
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
                case 'admin_formresume':
                    $resumeid = JSJOBSrequest::getVar('resumeid');
                    JSJOBSincluder::getJSModel('resume')->getResumebyId($resumeid);
                    break;
                case 'admin_formresume':
                    JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3);
                    break;
                case 'admin_formresumeuserfield':
                    $ff = JSJOBSrequest::getVar('ff');
                    if ($ff == "")
                        $ff = get_option( 'formresumeuserfield_ff');
                    else
                        update_option( 'formresumeuserfield_ff', $ff );
                    $result = JSJOBSincluder::getJSModel('resume')->getResumeUserFields($ff);
                    break;
                case 'admin_resumequeue':
                    JSJOBSincluder::getJSModel('resume')->getAllUnapprovedEmpApps();
                    break;
                case 'admin_resumes':
                    JSJOBSincluder::getJSModel('resume')->getAllEmpApps();
                    break;
            }
            if ($empflag == 0) {
                jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(5,null,null,1);
                jsjobs::$_error_flag = true;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'resume');
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function approveQueueResume() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'approve-resume') ) {
             die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $result = JSJOBSincluder::getJSModel('resume')->approveQueueResumeModel($id);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
        wp_redirect($url);
        die();
    }

    function rejectQueueResume() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'reject-resume') ) {
             die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $result = JSJOBSincluder::getJSModel('resume')->rejectQueueResumeModel($id);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
        wp_redirect($url);
        die();
    }


    function approveQueueAllResumes() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'approve-all-resume') ) {
             die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $alltype = JSJOBSrequest::getVar('objid');
        $result = JSJOBSincluder::getJSModel('resume')->approveQueueAllResumesModel($id, $alltype);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
        JSJOBSincluder::getJSModel('emailtemplate')->sendMail(3, 2, $id); // 3 for resume,2 for Approve resume
        wp_redirect($url);
        die();
    }

    function rejectQueueAllResumes() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'reject-all-resume') ) {
             die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('id');
        $alltype = JSJOBSrequest::getVar('objid');
        $result = JSJOBSincluder::getJSModel('resume')->rejectQueueAllResumesModel($id, $alltype);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
        wp_redirect($url);
        die();
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    /* STRAT EXPORT RESUMES */

    function resumeenforcedelete() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-resume') ) {
             die( 'Security check Failed' );
        }
        $resumeid = JSJOBSrequest::getVar('resumeid');
        $callfrom = JSJOBSrequest::getVar('callfrom');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $result = JSJOBSincluder::getJSModel('resume')->resumeEnforceDelete($resumeid, $uid);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);

        $nonce = wp_create_nonce('resume');
        if ($callfrom == 1) {
            $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumes&_wpnonce=" . $nonce);
        } else {
            $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
        }
        wp_redirect($url);
        die();
    }

    function empappreject() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'reject-employeer-application') ) {
             die( 'Security check Failed' );
        }
        $appid = JSJOBSrequest::getVar('resumeid');
        $result = JSJOBSincluder::getJSModel('resume')->empappReject($appid);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
        wp_redirect($url);
        die();
    }

    function saveresume() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-resume') ) {
             die( 'Security check Failed' );
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('resume')->storeResume($data);
        $adminresumelayout = (isset($_POST['isqueue']) && $_POST['isqueue'] == 1) ? 'resumequeue' : 'resumes';
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        if ($result == JSJOBS_SAVED) {
            if (is_admin()) {
                $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=".$adminresumelayout."&_wpnonce=" . $nonce);
            } else {
                $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes')),"resume");
            }
        } else {
            if (is_admin()) {
                $url = admin_url("admin.php?page=jsjobs_resume&_wpnonce=" . $nonce);
            } else {
                $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'addresume')),"formresume");
            }
        }
        wp_redirect($url);
        die();
    }

    function removeresume() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-resume') ) {
            die( 'Security check Failed' );
        }
        $data = JSJOBSrequest::get('post');
        $resumeid = JSJOBSrequest::getVar('jsjobs-cb');
        if (!isset($data['callfrom'])) {
            $data['callfrom'] = $callfrom = JSJOBSrequest::getVar('callfrom');
        }
        $result = JSJOBSincluder::getJSModel('resume')->deleteResume($resumeid);
        $msg = JSJOBSMessages::getMessage($result, 'resume');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('resume');
        if (is_admin()) {
            if ($data['callfrom'] == 1) {
                $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumes&_wpnonce=" . $nonce);
            } elseif ($data['callfrom'] == 2) {
                $url = admin_url("admin.php?page=jsjobs_resume&jsjobslt=resumequeue&_wpnonce=" . $nonce);
            }
        } else {
            $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'myresumes', 'jsjobspageid'=>jsjobs::getPageid())),"resume");
        }
        wp_redirect($url);
        die();
    }

    function getallresumefiles() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'getallfiles-resume') ) {
            die( 'Security check Failed' );
        }
        JSJOBSincluder::getJSModel('resume')->getAllResumeFiles();
    }

    function getresumefiledownloadbyid() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'download-resume') ) {
             die( 'Security check Failed' );
        }
        $fileid = JSJOBSrequest::getVar('jsjobsid');
        JSJOBSincluder::getJSModel('resume')->getResumeFileDownloadById($fileid);
    }

    function addviewresumedetail() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'view-resumedetail') ) {
            die( 'Security check Failed' );
        }
        $id = JSJOBSrequest::getVar('resumeid');
        $pageid = JSJOBSrequest::getVar('jsjobs_pageid');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        JSJOBSincluder::getJSModel('resume')->addViewContactDetail($id, $uid);
        $url = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume','jsjobsid'=>$id, 'jsjobspageid'=>$pageid));
        wp_redirect($url);
        die();
    }

}

$JSJOBSResumeController = new JSJOBSResumeController();
?>

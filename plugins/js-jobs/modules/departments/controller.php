<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSdepartmentsController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('departments')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'departments');
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        if (self::canaddfile()) {
            $empflag  = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('disable_employer');
            $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('emcontrolpanel');
            switch ($layout) {
                case 'mydepartments':
                    if (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) {
                        $companyid = JSJOBSrequest::getVar('companyid');
                        JSJOBSincluder::getJSModel('departments')->getMyDepartments($uid, $companyid);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('departments', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=2;
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
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
                case 'viewdepartment':
                    if (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1) {
                        $id = JSJOBSrequest::getVar('jsjobsid');
                        JSJOBSincluder::getJSModel('departments')->getViewDepartment($id);
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('departments', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=2;
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
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
                case 'admin_departments':
                    $companyid = JSJOBSrequest::getVar('companyid');
                    if ($companyid){
                        update_option( 'jsjobs_companyid_for_department', $companyid );
                    }else{
                        delete_option( 'jsjobs_companyid_for_department' );
                    }
                    JSJOBSincluder::getJSModel('departments')->getDepartments($companyid);
                    break;
                case 'adddepartment':
                case 'admin_formdepartment':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'save-department') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    if (is_admin() || (JSJOBSincluder::getObjectClass('user')->isemployer() && $empflag == 1)) {
                        $id = JSJOBSrequest::getVar('jsjobsid');
                        if($id == ''){
                            $check = JSJOBSincluder::getJSModel('departments')->canAddDepartment($uid);
                        }else{
                            if(!is_admin()){
                                $check = JSJOBSincluder::getJSModel('departments')->getIfDepartmentOwner($id);
                            }
                        }
                        if (is_admin() || $check == true) {
                            JSJOBSincluder::getJSModel('departments')->getDepartmentById($id);
                        } else{
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(10,null,null,1);
                            jsjobs::$_error_flag = true;
                            jsjobs::$_error_flag_message_for=4;
                        }
                    } else {
                        if (JSJOBSincluder::getObjectClass('user')->isguest()) {
                            $link = JSJOBSincluder::getJSModel('common')->jsMakeRedirectURL('departments', $layout, 1);
                            $linktext = __('Login','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(1 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=1;
                            jsjobs::$_error_flag_message_register_for=2;
                        } elseif (!JSJOBSincluder::getObjectClass('user')->isJSJobsUser()) {
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs'));
                            $linktext = __('Select role','js-jobs');
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(9 , $link , $linktext,1);
                            jsjobs::$_error_flag_message_for=9;
                        } elseif (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
                            jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(2,null,null,1);
                            jsjobs::$_error_flag_message_for=2;
                        }
                        jsjobs::$_error_flag = true;
                    }
                    if(isset($link) && isset($linktext)){
                        jsjobs::$_error_flag_message_for_link=$link;
                        jsjobs::$_error_flag_message_for_link_text=$linktext;
                    }
                    break;
                case 'admin_departmentqueue':
                    JSJOBSincluder::getJSModel('departments')->getAllUnapprovedDepartments();
                    break;
            }
            if ($empflag == 0) {
                jsjobs::$_error_flag_message = JSJOBSLayout::setMessageFor(5,null,null,1);
                jsjobs::$_error_flag_message_for=5;
                jsjobs::$_error_flag = true;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'departments');
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

    function approve() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'approve-department') ) {
            die( 'Security check Failed' );
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('departments')->departmentsApprove($ids);
        $msg = JSJOBSMessages::getMessage($result, 'departments');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('department');
        $url = admin_url("admin.php?page=jsjobs_departments&jsjobslt=departmentqueue&_wpnonce=".$nonce);
        wp_redirect($url);
        die();
    }

    function reject() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'reject-department') ) {
            die( 'Security check Failed' );
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('departments')->departmentsReject($ids);
        $msg = JSJOBSMessages::getMessage($result, 'departments');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('department');
        $url = admin_url("admin.php?page=jsjobs_departments&jsjobslt=departmentqueue&_wpnonce=".$nonce);
        wp_redirect($url);
        die();
    }

    function savedepartment() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-department') ) {
            die( 'Security check Failed' );
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('departments')->storeDepartment($data);
        if (isset($_COOKIE['cid_departments-decesion']))
            $cid = sanitize_key($_COOKIE['cid_departments']);
        else
            $cid = '';
        $admindepartmentlayout = (isset($_POST['isqueue']) && $_POST['isqueue'] == 1) ? 'departmentqueue' : 'departments';
        if ($result == JSJOBS_SAVED) {
            if (is_admin()) {
                $nonce = wp_create_nonce('department');
                $url = admin_url("admin.php?page=jsjobs_departments&jsjobslt=".$admindepartmentlayout."&companyid=" . $cid . "&_wpnonce=" . $nonce);
            } else {
                $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'mydepartments')),"department");
            }
        } else {
            if (is_admin()) {
                $nonce = wp_create_nonce('department');
                if (is_numeric($data['id'])) {
                    $url = admin_url("admin.php?page=jsjobs_departments&jsjobslt=formdepartment&jsjobsid=" . $data['id'] . "&_wpnonce=" . $nonce);
                } else {
                    $url = admin_url("admin.php?page=jsjobs_departments&jsjobslt=formdepartment&_wpnonce=".$nonce);
                }
            } else {
                $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'adddepartment')),"save-department");
            }
        }
        $msg = JSJOBSMessages::getMessage($result, 'departments');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-department') ) {
            die( 'Security check Failed' );
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('departments')->deleteDepartments($ids);
        $msg = JSJOBSMessages::getMessage($result, 'departments');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if (is_admin()) {
            $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_departments&jsjobslt=departments"),"department");
        } else {
            $url = wp_nonce_url(jsjobs::makeUrl(array('jsjobsme'=>'departments', 'jsjobslt'=>'mydepartments','jsjobspageid'=>JSJOBSrequest::getVar('jsjobspageid'))),"department");
        }
        wp_redirect($url);
        die();
    }

}

$JSJOBSdepartment = new JSJOBSdepartmentsController();
?>

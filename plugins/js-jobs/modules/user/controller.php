<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
class JSJOBSUserController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();

        $this->_msgkey = JSJOBSincluder::getJSModel('user')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'users');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_users':
                    JSJOBSincluder::getJSModel('user')->getAllUsers();
                    break;
                case 'admin_changerole':
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('user')->getChangeRolebyId($id);
                    break;
                case 'admin_userdetail':
                    $id = JSJOBSrequest::getVar('id');
                    JSJOBSincluder::getJSModel('user')->getUserData($id);
                    break;
                case 'admin_userstate_companies':
                    $companyuid = JSJOBSrequest::getVar('md');
                    $result = JSJOBSincluder::getJSModel('user')->getUserStatsCompanies($companyuid);
                    break;
                case 'admin_userstats':
                    JSJOBSincluder::getJSModel('user')->getUserStats();
                    break;
                case 'admin_userstate_resumes':
                    $resumeuid = JSJOBSrequest::getVar('ruid');
                    JSJOBSincluder::getJSModel('resume')->getUserStatsResumes($resumeuid);

                    break;
                case 'admin_userstate_jobs':
                    $jobuid = JSJOBSrequest::getVar('bd');
                    JSJOBSincluder::getJSModel('user')->getUserStatsJobs($jobuid);
                    break;
                case 'regemployer':
                case 'regjobseeker':
                case 'userregister':
                    $allow_reg_as_emp = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('showemployerlink');
                    $cpfrom = 0;
                    if($layout!="userregister"){
                        if ($layout == 'regemployer') {
                            if ($allow_reg_as_emp == 1) {
                                $cpfrom = 1;
                            } else {
                                $cpfrom = 2;
                            }
                        } else {
                            $cpfrom = 2;
                        }
                        $_SESSION['js_cpfrom'] = $cpfrom;
                        $layout = 'userregister';
                    }

                    $layout = 'userregister';
                    if($cpfrom != 0){
                        $_SESSION['js_cpfrom'] = $cpfrom;
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'user');
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

    function saveuserrole() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-userrole') ) {
             die( 'Security check Failed' );
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('user')->storeUserRole($data);
        $msg = JSJOBSMessages::getMessage($result, 'userrole');
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_user&jsjobslt=users"),"user");
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function assignuserrole() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'assign-userrole') ) {
             die( 'Security check Failed' );
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('user')->assignUserRole($data);
        $msg = JSJOBSMessages::getMessage($result, 'userrole');
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_user&jsjobslt=users"),"user");
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function changeuserstatus() {
        if(!is_admin()){
            return false;
        }
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-userrole') ) {
             die( 'Security check Failed' );
        }
        $userid = JSJOBSrequest::getVar('jsjobsid');
        $result = JSJOBSincluder::getJSModel('user')->changeUserStatus($userid);
        $msg = JSJOBSMessages::getMessage($result, 'user');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $detail = JSJOBSrequest::getVar('detail');
        if($detail == 1){
            $url = admin_url('admin.php?page=jsjobs_user&jsjobslt=userdetail&id='.$userid);
        }else{
            $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=users'),"user");
        }
        wp_redirect($url);
        die();
    }

    function deleteuser() {
        if(!is_admin()){
            return false;
        }
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-userrole') ) {
             die( 'Security check Failed' );
        }
        $userid = JSJOBSrequest::getVar('jsjobsid');
        $result = JSJOBSincluder::getJSModel('user')->deleteUser($userid);
        //var_dump($result); die();
        $msg = JSJOBSMessages::getMessage($result, 'user');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=users'),"user");
        wp_redirect($url);
        die();
    }

    function enforcedeleteuser() {
        if(!is_admin()){
            return false;
        }
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-userrole') ) {
            die( 'Security check Failed' );
        }
        $userid = JSJOBSrequest::getVar('jsjobsid');
        $result = JSJOBSincluder::getJSModel('user')->EnforceDeleteUser($userid);
        //var_dump($result); die();
        $msg = JSJOBSMessages::getMessage($result, 'user');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_user&jsjobslt=users'),"user");
        wp_redirect($url);
        die();
    }

    function sociallogin() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'social-login') ) {
            die( 'Security check Failed' );
        }
        $media = JSJOBSrequest::getVar('media');
        $result = null;
        if ($media) {
            switch ($media) {
                case 'facebook':
                    $medium = __('Facebook user', 'js-jobs');
                    $result = JSJOBSincluder::getObjectClass('facebook')->login();
                    break;
                case 'linkedin':
                    $medium = __('Linkedin user', 'js-jobs');
                    $result = JSJOBSincluder::getObjectClass('linkedin')->login();
                    break;
                case 'xing':
                    $medium = __('Xing user', 'js-jobs');
                    $result = JSJOBSincluder::getObjectClass('xing')->login();
                    break;
            }
        }
        switch ($result) {
            case 1: // client exit
                wp_die();
                break;
            case 2: // user login and has role
                JSJOBSMessages::setLayoutMessage(__('You are logged in as ') . ' ' . $medium,'updated',$this->_msgkey);
                break;
            case 3: // user login and not has role
                JSJOBSMessages::setLayoutMessage(__('You are logged in as ') . ' ' . $medium,'updated',$this->_msgkey);
                JSJOBSMessages::setLayoutMessage(__('Please select your role to continue'), 'updated',$this->_msgkey);
                $url = jsjobs::makeUrl(array('jsjobsme'=>'common', 'jsjobslt'=>'newinjsjobs', 'jsjobspageid'=>jsjobs::getPageid()));
                wp_redirect($url);
                die();
                break;
            case 4: // api not send data
                wp_die();
                break;
        }
        $url = jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid()));
        wp_redirect($url);
        die();
    }

    function logout() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'logout') ) {
            die( 'Security check Failed' );
        }
        $media = JSJOBSrequest::getVar('media');
        $result = null;
        if ($media) {
            switch ($media) {
                case 'facebook':
                    $medium = __('Facebook user', 'js-jobs');
                    $result = JSJOBSincluder::getObjectClass('facebook')->logout();
                    break;
                case 'linkedin':
                    $medium = __('Linkedin user', 'js-jobs');
                    $result = JSJOBSincluder::getObjectClass('linkedin')->logout();
                    break;
                case 'xing':
                    $medium = __('Xing user', 'js-jobs');
                    $result = JSJOBSincluder::getObjectClass('xing')->logout();
                    break;
            }
        }
        if ($result != null) {
            JSJOBSMessages::setLayoutMessage(__('You are logged out'), 'updated',$this->_msgkey);
        }
        if (isset($_COOKIE['PHPSESSID'])) {
            unset($_COOKIE['PHPSESSID']);
        }
        $url = jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid()));
        wp_redirect($url);
        die();
    }

}

$JSJOBSUserController = new JSJOBSUserController();
?>

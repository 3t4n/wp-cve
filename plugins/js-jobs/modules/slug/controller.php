<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSslugController {
    private $_msgkey;
    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('slug')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'slug');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_slug':
                    JSJOBSincluder::getJSModel('slug')->getSlug();
                    break;
            }
            $module = 'page';
            $module = JSJOBSrequest::getVar($module, null, 'slug');
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

    function saveSlug() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-slug') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->storeSlug($data);
        if($data['pagenum'] > 0){
            $nonce = wp_create_nonce('slug');
            $url = admin_url("admin.php?page=jsjobs_slug&pagenum=".$data['pagenum'] . '&_wpnonce=' . $nonce);
        }else{
            $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_slug"),"slug");
        }

        $msg = JSJOBSMessages::getMessage($result, 'slug');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function saveprefix() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'saveprefix-slug') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->savePrefix($data);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_slug"),"slug");
        $msg = JSJOBSMessages::getMessage($result, 'prefix');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function savehomeprefix() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'savehomeprefix-slug') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->saveHomePrefix($data);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_slug"),"slug");
        $msg = JSJOBSMessages::getMessage($result, 'prefix');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function resetallslugs() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'saveprefix-slug') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('slug')->resetAllSlugs();
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_slug"),"slug");
        $msg = JSJOBSMessages::getMessage($result, 'slug');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }
}

$JSJOBSslugController = new JSJOBSslugController();
?>

<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSAgeController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('age')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'ages');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_ages':
                    JSJOBSincluder::getJSModel('age')->getAllAges();
                    break;
                case 'admin_formages':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formages') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('age')->getJobAgesbyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'ages');
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

    function saveages() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-age') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_age&jsjobslt=ages"),"age");
        $result = JSJOBSincluder::getJSModel('age')->storeAges($data);
        $msg = JSJOBSMessages::getMessage($result, 'age');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function remove() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-age') ) {
             die( 'Security check Failed' ); 
        }
        if(!is_admin())
            return false;
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('age')->deleteAges($ids);
        $msg = JSJOBSMessages::getMessage($result, 'age');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);

        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_age&jsjobslt=ages"),"age");
        wp_redirect($url);
        die();
    }

    function publish() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-age') ) {
             die( 'Security check Failed' ); 
        }
        if(!is_admin())
            return false;
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('age')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('age');
        $url = admin_url("admin.php?page=jsjobs_age&jsjobslt=ages&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-age') ) {
             die( 'Security check Failed' ); 
        }
        if(!is_admin())
            return false;
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('age')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('age');
        $url = admin_url("admin.php?page=jsjobs_age&jsjobslt=ages&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSAgeController = new JSJOBSAgeController();
?>

<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSHighesteducationController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
        $this->_msgkey = JSJOBSincluder::getJSModel('highesteducation')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'highesteducations');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_highesteducations':
                    JSJOBSincluder::getJSModel('highesteducation')->getAllHighestEducations();
                    break;
                case 'admin_formhighesteducation':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formhighesteducation') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('highesteducation')->getHighestEducationbyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'highesteducation');
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

    function savehighesteducation() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-highesteducation') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('highesteducation')->storeHighestEducation($data);
        $url = wp_nonce_url(admin_url('admin.php?page=jsjobs_highesteducation&jsjobslt=highesteducations'),"highesteducation");
        $msg = JSJOBSMessages::getMessage($result, 'highesteducation');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-highesteducation') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('highesteducation')->deleteHighestEducations($ids);
        $msg = JSJOBSMessages::getMessage($result, 'highesteducation');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_highesteducation&jsjobslt=highesteducations"),"highesteducation");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-highesteducation') ) {
            die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('highesteducation')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('highesteducation');
        $url = admin_url("admin.php?page=jsjobs_highesteducation&jsjobslt=highesteducations&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-highesteducation') ) {
            die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $result = JSJOBSincluder::getJSModel('highesteducation')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('highesteducation');
        $url = admin_url("admin.php?page=jsjobs_highesteducation&jsjobslt=highesteducations&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSHighesteducationController = new JSJOBSHighesteducationController();
?>

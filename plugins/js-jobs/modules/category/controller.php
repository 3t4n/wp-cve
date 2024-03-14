<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCategoryController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('category')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'categories');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_categories':
                    JSJOBSincluder::getJSModel('category')->getAllCategories();
                    break;
                case 'admin_formcategory':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formcategory') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('category')->getCategorybyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'categories');
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

    function savecategory() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-category') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('category')->storeCategory($data);
        $msg = JSJOBSMessages::getMessage($result, 'category');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_category&jsjobslt=categories"),"category");
        wp_redirect($url);
        die();
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-category') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('category')->deleteCategories($ids);
        $msg = JSJOBSMessages::getMessage($result, 'category');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_category&jsjobslt=categories"),"category");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-category') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('category')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('category');
        $url = admin_url("admin.php?page=jsjobs_category&jsjobslt=categories&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-category') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('category')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('category');
        $url = admin_url("admin.php?page=jsjobs_category&jsjobslt=categories&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSCategoryController = new JSJOBSCategoryController();
?>

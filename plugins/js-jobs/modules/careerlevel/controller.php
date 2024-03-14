<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCareerlevelController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('careerlevel')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'careerlevels');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_careerlevels':
                    JSJOBSincluder::getJSModel('careerlevel')->getAllCareerLevels();
                    break;
                case 'admin_formcareerlevels':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formcareerlevels') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce(); 
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('careerlevel')->getJobCareerLevelbyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'careerlevels');
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

    function savecareerlevel() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-careerlevel') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('careerlevel')->storeCareerLevel($data);

        $msg = JSJOBSMessages::getMessage($result, 'careerlevel');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_careerlevel&jsjobslt=careerlevels"),"careerlevel");
        wp_redirect($url);
        die();
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-careerlevel') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('careerlevel')->deleteCareerLevels($ids);
        $msg = JSJOBSMessages::getMessage($result, 'careerlevel');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect(wp_nonce_url(admin_url("admin.php?page=jsjobs_careerlevel&jsjobslt=careerlevels"),"careerlevel"));
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-careerlevel') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('careerlevel')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('careerlevel');
        $url = admin_url("admin.php?page=jsjobs_careerlevel&jsjobslt=careerlevels&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-careerlevel') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('careerlevel')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('careerlevel');
        $url = admin_url("admin.php?page=jsjobs_careerlevel&jsjobslt=careerlevels&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSJOBSCareerlevelController = new JSJOBSCareerlevelController();
?>

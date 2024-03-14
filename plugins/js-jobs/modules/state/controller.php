<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSStateController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();

        $this->_msgkey = JSJOBSincluder::getJSModel('state')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'states');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_states':
                    $countryid = JSJOBSrequest::getVar('countryid');
                    if (!$countryid)
                        $countryid = get_option( 'jsjobs_countryid_for_state');
                    update_option( 'jsjobs_countryid_for_state', $countryid);
                    JSJOBSincluder::getJSModel('state')->getAllCountryStates($countryid);
                    break;
                case 'admin_formstate':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formstate') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('state')->getStatebyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'states');
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

    function remove() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-state') ) {
            die( 'Security check Failed' );
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $countryid = get_option( 'jsjobs_countryid_for_state');

        $result = JSJOBSincluder::getJSModel('state')->deleteStates($ids);
        $msg = JSJOBSMessages::getMessage($result, 'state');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid),"state");
        wp_redirect($url);
        die();
    }

    function publish() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-state') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $countryid = get_option( 'jsjobs_countryid_for_state');
        $result = JSJOBSincluder::getJSModel('state')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('state');
        $url = admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid . '&_wpnonce=' . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-state') ) {
            die( 'Security check Failed' );
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $countryid = get_option( 'jsjobs_countryid_for_state');
        $result = JSJOBSincluder::getJSModel('state')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('state');
        $url = admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid . '&_wpnonce=' . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function savestate() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-state') ) {
            die( 'Security check Failed' );
        }
        $data = JSJOBSrequest::get('post');
        $countryid = get_option( 'jsjobs_countryid_for_state');
        $result = JSJOBSincluder::getJSModel('state')->storeState($data, $countryid);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_state&jsjobslt=states&countryid=" . $countryid),"state");
        $msg = JSJOBSMessages::getMessage($result, 'state');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSStateController = new JSJOBSStateController();
?>

<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCurrencyController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('currency')->getMessagekey();        
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'currency');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_currency':
                    JSJOBSincluder::getJSModel('currency')->getAllCurrencies();
                    break;
                case 'admin_formcurrency':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formcurrency') ) {
                        JSJOBSincluder::getJSModel('common')->js_verify_nonce();
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    JSJOBSincluder::getJSModel('currency')->getCurrencybyId($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'currency');
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

    function savecurrency() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-currency') ) {
             die( 'Security check Failed' ); 
        }
        $data = JSJOBSrequest::get('post');
        $result = JSJOBSincluder::getJSModel('currency')->storeCurrency($data);
        $nonce = wp_create_nonce('currency');
        $url = admin_url("admin.php?page=jsjobs_currency&jsjobslt=currency&_wpnonce=" . $nonce);
        $msg = JSJOBSMessages::getMessage($result, 'currency');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-currency') ) {
             die( 'Security check Failed' ); 
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('currency')->deleteCurrencies($ids);
        $msg = JSJOBSMessages::getMessage($result, 'currency');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_currency&jsjobslt=currency"),"currency");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-currency') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('currency')->publishUnpublish($ids, 1); //  for publish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('currency');
        $url = admin_url("admin.php?page=jsjobs_currency&jsjobslt=currency&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-currency') ) {
             die( 'Security check Failed' ); 
        }
        $pagenum = JSJOBSrequest::getVar('pagenum');
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $result = JSJOBSincluder::getJSModel('currency')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSJOBSMessages::getMessage($result, 'record');
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $nonce = wp_create_nonce('currency');
        $url = admin_url("admin.php?page=jsjobs_currency&jsjobslt=currency&_wpnonce=" . $nonce);
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }
}
$JSJOBSCurrencyController = new JSJOBSCurrencyController();
?>

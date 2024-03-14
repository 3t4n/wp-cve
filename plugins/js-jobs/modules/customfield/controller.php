<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCustomfieldController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = JSJOBSincluder::getJSModel('customfield')->getMessagekey();
    }

    function handleRequest() {
        $layout = JSJOBSrequest::getLayout('jsjobslt', null, 'userfields');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_userfields':
                    $fieldfor = JSJOBSrequest::getVar('ff');
                    update_option( 'jsjobs_customfield_ff', $fieldfor );
                    JSJOBSincluder::getJSModel('customfield')->getUserFields($fieldfor);
                    jsjobs::$_data['fieldfor'] = $fieldfor;
                    break;
                case 'admin_formuserfield':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'formuserfield') ) {
                        die( 'Security check Failed' ); 
                    }
                    $id = JSJOBSrequest::getVar('jsjobsid');
                    $fieldfor = JSJOBSrequest::getVar('fieldfor');
                    if (empty($fieldfor))
                        $fieldfor = JSJOBSrequest::getVar('ff');
                    if (empty($fieldfor))
                        $fieldfor = get_option( 'jsjobs_customfield_ff' );

                    JSJOBSincluder::getJSModel('fieldordering')->getUserFieldbyId($id, $fieldfor);
                    if ($fieldfor == 3)
                        JSJOBSincluder::getJSModel('fieldordering')->getResumeSections($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsjobsme';
            $module = JSJOBSrequest::getVar($module, null, 'userfields');
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
        if(!is_admin())
            return false;
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-customfield') ) {
             die( 'Security check Failed' );
        }
        $ids = JSJOBSrequest::getVar('jsjobs-cb');
        $fieldfor = get_option( 'jsjobs_customfield_ff');
        $result = JSJOBSincluder::getJSModel('customfield')->deleteUserFields($ids);
        $msg = JSJOBSMessages::getMessage($result, 'customfield');
        $url = wp_nonce_url(admin_url("admin.php?page=jsjobs_customfield&jsjobslt=userfields&ff=" . $fieldfor),"fieldordering");
        JSJOBSMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

}

$JSJOBSCustomfieldController = new JSJOBSCustomfieldController();
?>

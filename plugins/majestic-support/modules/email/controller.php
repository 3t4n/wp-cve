<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_emailController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'emails');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_emails':
                    MJTC_includer::MJTC_getModel('email')->getEmails();
                    break;

                case 'admin_addemail':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid', 'get');
                    MJTC_includer::MJTC_getModel('email')->getEmailForForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'email');
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'majesticsupport')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'mstask')
            return false;
        else
            return true;
    }

    static function saveemail() {
        
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-email') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('email')->storeEmail($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_email&mjslay=emails");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'email', 'mjslay'=>'emails'));
        }
        wp_redirect($url);
        exit;
    }

    static function deleteemail() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-email') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('emailid');
        MJTC_includer::MJTC_getModel('email')->removeEmail($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_email&mjslay=emails");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'email', 'mjslay'=>'emails'));
        }
        wp_redirect($url);
        exit;
    }

}

$emailController = new MJTC_emailController();
?>

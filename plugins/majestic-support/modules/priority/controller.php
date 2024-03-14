<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_priorityController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'priorities');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_priorities':
                    MJTC_includer::MJTC_getModel('priority')->getPriorities();
                    break;
                case 'admin_addpriority':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid', 'get');
                    MJTC_includer::MJTC_getModel('priority')->getPriorityForForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'priority');
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

    static function savepriority() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-priority') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('priority')->storePriority($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_priority&mjslay=priorities");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'priority','mjslay'=>'priorities'));
        }
        wp_redirect($url);
        exit;
    }

    static function deletepriority() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-priority') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('priorityid');
        MJTC_includer::MJTC_getModel('priority')->removePriority($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_priority&mjslay=priorities");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'priority','mjslay'=>'priorities'));
        }
        wp_redirect($url);
        exit;
    }

    static function makedefault() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'make-default') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('priorityid');
        MJTC_includer::MJTC_getModel('priority')->makeDefault($id);
        $pagenum = MJTC_request::MJTC_getVar('pagenum');
        $url = "admin.php?page=majesticsupport_priority&mjslay=priorities";
        if ($pagenum)
            $url .= '&pagenum=' . $pagenum;
        wp_redirect($url);
        exit;
    }

    static function ordering() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'ordering') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('priorityid');
        MJTC_includer::MJTC_getModel('priority')->setOrdering($id);
        $pagenum = MJTC_request::MJTC_getVar('pagenum');
        $url = "admin.php?page=majesticsupport_priority&mjslay=priorities";
        if ($pagenum)
            $url .= '&pagenum=' . $pagenum;
        wp_redirect($url);
        exit;
    }

}

$priorityController = new MJTC_priorityController();
?>

<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_departmentController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'departments');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_departments':
                case 'departments':
                    majesticsupport::$_data['permission_granted'] = true;
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Department');
                    }
                    if (majesticsupport::$_data['permission_granted']) {
                        MJTC_includer::MJTC_getModel('department')->getDepartments();
                    }
                    break;
                case 'admin_adddepartment':
                case 'adddepartment':
                    $id = MJTC_request::MJTC_getVar('majesticsupportid');
                    majesticsupport::$_data['permission_granted'] = true;
                    if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                        $per_task = ($id == null) ? 'Add Department' : 'Edit Department';
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask($per_task);
                    }
                    if (majesticsupport::$_data['permission_granted'])
                        MJTC_includer::MJTC_getModel('department')->getDepartmentForForm($id);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'department');
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

    static function savedepartment() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-department') ) {
            die( 'Security check Failed' );
        }
        $data = MJTC_request::get('post');
        MJTC_includer::MJTC_getModel('department')->storeDepartment($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_department&mjslay=departments");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'department', 'mjslay'=>'departments'));
        }
        wp_redirect($url);
        exit;
    }

    static function deletedepartment() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-department') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('departmentid');
        MJTC_includer::MJTC_getModel('department')->removeDepartment($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=majesticsupport_department&mjslay=departments");
        } else {
            $url = majesticsupport::makeUrl(array('mjsmod'=>'department', 'mjslay'=>'departments'));
        }
        wp_redirect($url);
        exit;
    }

    static function changestatus() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-status') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('departmentid');
        MJTC_includer::MJTC_getModel('department')->changeStatus($id);
        $url = admin_url("admin.php?page=majesticsupport_department&mjslay=departments");
        $pagenum = MJTC_request::MJTC_getVar('pagenum');
        if ($pagenum)
            $url .= '&pagenum=' . $pagenum;
        wp_redirect($url);
        exit;
    }

    static function changedefault() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-default') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('departmentid');
        $default = MJTC_request::MJTC_getVar('default',null,0);
        MJTC_includer::MJTC_getModel('department')->changeDefault($id,$default);
        $url = admin_url("admin.php?page=majesticsupport_department&mjslay=departments");
        $pagenum = MJTC_request::MJTC_getVar('pagenum');
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
        $id = MJTC_request::MJTC_getVar('departmentid');
        MJTC_includer::MJTC_getModel('department')->setOrdering($id);
        $pagenum = MJTC_request::MJTC_getVar('pagenum');
        $url = "admin.php?page=majesticsupport_department&mjslay=departments";
        if ($pagenum)
            $url .= '&pagenum=' . $pagenum;
        wp_redirect($url);
        exit;
    }

}

$departmentController = new MJTC_departmentController();
?>

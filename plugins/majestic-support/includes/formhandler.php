<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_formhandler {

    function __construct() {
        add_action('init', array($this, 'MJTC_checkFormRequest'));
        add_action('init', array($this, 'MJTC_checkDeleteRequest'));
    }

    /*
     * Handle Form request
     */

    function MJTC_checkFormRequest() {
        majesticsupport::$_data['sanitized_args']['_wpnonce'] = wp_create_nonce("VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE");
        $formrequest = MJTC_request::MJTC_getVar('form_request', 'post');
        if ($formrequest == 'majesticsupport') {
            //handle the request
            $page_id = MJTC_Request::MJTC_getVar('page_id', 'GET');
            majesticsupport::setPageID($page_id);
            $modulename = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($modulename);
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            MJTC_includer::MJTC_include_file($module);
            $class = 'MJTC_' . $module . "Controller";
            $task = MJTC_request::MJTC_getVar('task');
            $obj = new $class;
            $obj->$task();
        }
    }

    /*
     * Handle Form request
     */

    function MJTC_checkDeleteRequest() {
        majesticsupport::$_data['sanitized_args']['_wpnonce'] = wp_create_nonce("VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE");
        $majesticsupport_action = MJTC_request::MJTC_getVar('action', 'get');
        if ($majesticsupport_action == 'mstask') {
            //handle the request
            $page_id = MJTC_Request::MJTC_getVar('page_id', 'GET');
            majesticsupport::setPageID($page_id);
            $modulename = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($modulename,'','');
            $module = MJTC_majesticsupportphplib::MJTC_str_replace('majesticsupport_', '', $module);
            if($module != ''){
                MJTC_includer::MJTC_include_file($module);
                $class = 'MJTC_' . $module . "Controller";
                $action = MJTC_request::MJTC_getVar('task');
                $obj = new $class;
                $obj->$action();
            }else{
                error_log( print_r( $_REQUEST, true ) );// temporary code to get the case when problem occurs(there are errors in log but no way to find the case that causes them)
            }
        }
    }

}

$formhandler = new MJTC_formhandler();
?>

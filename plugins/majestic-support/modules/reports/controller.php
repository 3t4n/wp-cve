<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_reportsController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'reports');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_reports':
                break;
                case 'admin_staffreport':
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        MJTC_includer::MJTC_getModel('reports')->getStaffReports();
                    }
                break;
                case 'admin_departmentreport':
                    MJTC_includer::MJTC_getModel('reports')->getDepartmentReports();
                break;
                case 'admin_userreport':
                    MJTC_includer::MJTC_getModel('reports')->getUserReports();
                break;
                case 'admin_staffdetailreport':
                case 'staffdetailreport':
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        if(is_admin()){
                            $id = MJTC_request::MJTC_getVar('id');
                            MJTC_includer::MJTC_getModel('reports')->getStaffDetailReportByStaffId($id);
                        }else{
                            majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Agent Reports');
                            if (majesticsupport::$_data['permission_granted']) {
                                $id = MJTC_request::MJTC_getVar('ms-id');
                                $return = MJTC_includer::MJTC_getModel('reports')->getStaffDetailReportByStaffId($id);
                                if(isset($return) AND $return === false)
                                    majesticsupport::$_data['permission_granted'] = false;

                            }
                        }
                    }
                break;
                case 'admin_departmentdetailreport':
                        $id = MJTC_request::MJTC_getVar('id');
                        MJTC_includer::MJTC_getModel('reports')->getDepartmentDetailReportByDepartmentId($id);
                break;
                case 'admin_stafftimereport':
                    if(in_array('agent',majesticsupport::$_active_addons) && in_array('timetracking',majesticsupport::$_active_addons)){

                        $id = MJTC_request::MJTC_getVar('id');
                        MJTC_includer::MJTC_getModel('reports')->getStaffTimingReportById($id);
                    }
                break;
                case 'admin_userdetailreport':
                    $id = MJTC_request::MJTC_getVar('id');
                    MJTC_includer::MJTC_getModel('reports')->getStaffDetailReportByUserId($id);
                break;
                case 'admin_overallreport':
                    MJTC_includer::MJTC_getModel('reports')->getOverallReportData();
                break;
                case 'staffreports':
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Agent Reports');
                        if (majesticsupport::$_data['permission_granted']) {
                            MJTC_includer::MJTC_getModel('reports')->getStaffReportsFE();
                        }
                    }
                break;
                case 'departmentreports':
                    if(in_array('agent',majesticsupport::$_active_addons)){
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('View Department Reports');
                        if (majesticsupport::$_data['permission_granted']) {
                            MJTC_includer::MJTC_getModel('reports')->getDepartmentReportsFE();
                        }
                    }
                case 'admin_satisfactionreport':
                    if(in_array('feedback', majesticsupport::$_active_addons)){
                        MJTC_includer::MJTC_getModel('feedback')->getSatisfactionReport();
                    }
                break;
            }
            $module = (is_admin()) ? 'page' : 'mjsmod';
            $module = MJTC_request::MJTC_getVar($module, null, 'reports');
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

}

$reportsController = new MJTC_reportsController();
?>

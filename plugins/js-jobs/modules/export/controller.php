<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSExportController {

    function __construct() {
        
    }

    function exportallresume() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'exportall-resume') ) {
             die( 'Security check Failed' ); 
        }
        $jobid = JSJOBSrequest::getVar('jobid');

        $return_value = JSJOBSincluder::getJSModel('export')->setAllExport($jobid);
        if (!empty($return_value)) {
            // Push the report now!
            $msg = __('JS resume export', 'js-jobs');
            $name = 'export-resumes';
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $name . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Lacation: excel.htm?id=yes");
            print $return_value;
            exit;
        }
        die();
    }

    function exportresume() {
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'export-resume') ) {
             die( 'Security check Failed' ); 
        }
        $resumeid = JSJOBSrequest::getVar('jsjobsid');
        $socialprofileid = JSJOBSrequest::getVar('jsscid');
        $jobid = JSJOBSrequest::getVar('jobid');
        $return_value = JSJOBSincluder::getJSModel('export')->setExport($jobid, $resumeid, $socialprofileid);
        if (!empty($return_value)) {
            $msg = __('JS resume export', 'js-jobs');
            // Push the report now!
            $name = 'export-resume';
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $name . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Lacation: excel.htm?id=yes");
            print $return_value;
        }
        die(); // export call ended
    }

}

?>

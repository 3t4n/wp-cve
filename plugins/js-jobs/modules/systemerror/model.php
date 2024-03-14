<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSsystemerrorModel {

    function getSystemErrors() {
        $inquery = '';
        // Pagination
        $query = "SELECT COUNT(`id`) FROM `" . jsjobs::$_db->prefix . "js_job_system_errors`";
        $query .= $inquery;
        $total = jsjobs::$_db->get_var($query);
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        // Data
        $query = " SELECT systemerror.*
					FROM `" . jsjobs::$_db->prefix . "js_job_system_errors` AS systemerror ";
        $query .= $inquery;
        $query .= " ORDER BY systemerror.created DESC LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobs::$_db->get_results($query);
        if (jsjobs::$_db->last_error != null) {
            $this->addSystemError();
        }
        return;
    }

    function addSystemError() {
        $error = jsjobs::$_db->last_error;
        $query_array = array('error' => $error,
            'uid' => get_current_user_id(),
            'isview' => 0,
            'created' => date("Y-m-d H:i:s")
        );

		$result = jsjobs::$_db->get_results("SHOW TABLES LIKE '" . jsjobs::$_db->prefix . "js_job_system_errors'");
		if(count($result) > 0){
           $row = JSJOBSincluder::getJSTable('systemerror');
           // $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
           if (!$row->bind($query_array)) {

           } elseif (!$row->store()) {

           }
		}

        return;
    }
    function getMessagekey(){
        $key = 'systemerror';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>

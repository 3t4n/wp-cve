<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_systemerrorModel {

    function getSystemErrors() {
        $inquery = '';
        // Pagination
        $query = "SELECT COUNT(`id`) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_system_errors`";
        $query .= $inquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        // Data
        $query = " SELECT systemerror.*
					FROM `" . majesticsupport::$_db->prefix . "mjtc_support_system_errors` AS systemerror ";
        $query .= $inquery;
        $query .= " ORDER BY systemerror.created DESC LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            $this->addSystemError();
        }
        return;
    }

    function addSystemError($error = null) {
        if($error == null) $error = majesticsupport::$_db->last_error;
        $query_array = array('error' => $error,
            'uid' => MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid(),
            'isview' => 0,
            'created' => date_i18n('Y-m-d H:i:s')
        );
        majesticsupport::$_db->replace(majesticsupport::$_db->prefix . 'mjtc_support_system_errors', $query_array);
        return;
    }

    function updateIsView($id) {
        if (!is_numeric($id))
            return false;
        $query = "UPDATE " . majesticsupport::$_db->prefix . "`mjtc_support_system_errors` set isview = 1 WHERE id = " . esc_sql($id);
        majesticsupport::$_db->Query($query);
        if (majesticsupport::$_db->last_error != null) {
            $this->addSystemError();
        }
    }

    function removeSystemError($id) {
        if ($id == 'all') {
            $query = "DELETE FROM `" . majesticsupport::$_db->prefix . "mjtc_support_system_errors` ";
            majesticsupport::$_db->query($query);
            MJTC_message::MJTC_setMessage(esc_html(__('System error has been deleted', 'majestic-support')), 'updated');
        }else{
            if (!is_numeric($id)){
                return false;
            }
            $row = MJTC_includer::MJTC_getTable('system_errors');
            if ($row->delete($id)) {
                MJTC_message::MJTC_setMessage(esc_html(__('System error has been deleted', 'majestic-support')), 'updated');
            } else {
                MJTC_message::MJTC_setMessage(esc_html(__('System error has not been deleted', 'majestic-support')), 'error');
            }
        }
        return;
    }

}

?>

<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_priorityModel {

    function getPriorities() {
        // Filter
        $prioritytitle = majesticsupport::$_search['priority']['title'];
        $pagesize = majesticsupport::$_search['priority']['pagesize'];
        $inquery = '';

        if ($prioritytitle != null){
            $inquery .= " WHERE priority.priority LIKE '%".esc_sql($prioritytitle)."%'";
        }

        majesticsupport::$_data['filter']['title'] = $prioritytitle;
        majesticsupport::$_data['filter']['pagesize'] = $pagesize;

        // Pagination
        if($pagesize){
            MJTC_pagination::MJTC_setLimit($pagesize);
        }
        $query = "SELECT COUNT(`id`) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ";
        $query .= $inquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        // Data
        $query = "SELECT priority.*
					FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority ";
        $query .= $inquery;
        $query .= " ORDER BY priority.ordering ASC LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function getPriorityForCombobox() {
        $query = "SELECT id, priority AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities`";
        if( in_array('agent',majesticsupport::$_active_addons) ){
            $agent = MJTC_includer::MJTC_getModel('agent')->isUserStaff();
        }else{
            $agent = false;
        }

        if (!is_admin() && !$agent) {
            $query .= ' WHERE ispublic = 1 ';
        }
        $query .= 'ORDER BY ordering ASC';
        $priorities = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return apply_filters('ms_priorities_for_combobox', $priorities);
    }

    function getDefaultPriorityID() {
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` WHERE isdefault = 1";
        $id = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $id;
    }

    function getPriorityForForm($id) {
        $result=array();
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT priority.*
						FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS priority
						WHERE priority.id = " . esc_sql($id);
            $result = majesticsupport::$_db->get_row($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        majesticsupport::$_data[0]=$result;
        return;
    }

    function storePriority($data) {
        if (!$this->validatePriority($data['priority'], $data['id'])) {
            MJTC_message::MJTC_setMessage(esc_html(__('Priority Title Already Exist', 'majestic-support')), 'error');
            return;
        }
        $data = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions
        $data['prioritycolour'] = $data['prioritycolor'];

        if (!$data['id']) { //new
            $data['ordering'] = $this->getNextOrdering();
        }
        $row = MJTC_includer::MJTC_getTable('priorities');
        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            $id = $row->id;
            if ($data['isdefault'] == 1) {
                $this->setDefaultPriority($id);
            }
            MJTC_message::MJTC_setMessage(esc_html(__('Priority has been stored', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Priority has not been stored', 'majestic-support')), 'error');
        }
        return;
    }

    private function validatePriority($priority, $id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT priority FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` WHERE id = " . esc_sql($id);
            $result = majesticsupport::$_db->get_var($query);
            if ($result == $priority) {
                return true;
            }
        }

        $query = 'SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_priorities` WHERE priority = "' . esc_sql($priority) . '"';
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if ($result == 0)
            return true;
        else
            return false;
    }

    private function getNextOrdering() {
        $query = "SELECT MAX(ordering) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities`";
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $result + 1;
    }

    function setDefaultPriority($id) {
        if (!is_numeric($id))
            return false;
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` SET isdefault = 2";
        majesticsupport::$_db->query($query);
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` SET isdefault = 1 WHERE id = " . esc_sql($id);
        majesticsupport::$_db->query($query);
        return;
    }

    function removePriority($id) {
        if (!is_numeric($id))
            return false;
        $canremove = $this->canRemovePriority($id);
        if ($canremove == 1) {
            $row = MJTC_includer::MJTC_getTable('priorities');
            if ($row->delete($id)) {
                MJTC_message::MJTC_setMessage(esc_html(__('Priority has been deleted', 'majestic-support')), 'updated');
            } else {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
                MJTC_message::MJTC_setMessage(esc_html(__('Priority has not been deleted', 'majestic-support')), 'error');
            }
        } elseif ($canremove == 2)
            MJTC_message::MJTC_setMessage(esc_html(__('Priority','majestic-support')).' '.esc_html(__('in use cannot deleted', 'majestic-support')), 'error');
        elseif ($canremove == 3)
            MJTC_message::MJTC_setMessage(esc_html(__('Default priority cannot delete', 'majestic-support')), 'error');

        return;
    }

    function makeDefault($id) {
        if (!is_numeric($id))
            return false;
        //Reset all priorities to non-default
        $query = "UPDATE `" . majesticsupport::$_db->prefix . 'mjtc_support_priorities` SET isdefault = 0';
        majesticsupport::$_db->query($query);
        //Make the selected priority as default
        $query = "UPDATE `" . majesticsupport::$_db->prefix . 'mjtc_support_priorities` SET isdefault = 1 WHERE id = ' . esc_sql($id);
        majesticsupport::$_db->query($query);
        if (majesticsupport::$_db->last_error == null) {
            MJTC_message::MJTC_setMessage(esc_html(__('Priority has been make default', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Priority has not been make default', 'majestic-support')), 'error');
        }
        return;
    }

    function setOrdering($id) {
        if (!is_numeric($id))
            return false;
        $order = MJTC_request::MJTC_getVar('order', 'get');
        if ($order == 'down') {
            $order = ">";
            $direction = "ASC";
        } else {
            $order = "<";
            $direction = "DESC";
        }
        $query = "SELECT t.ordering,t.id,t2.ordering AS ordering2 FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS t,`" . majesticsupport::$_db->prefix . "mjtc_support_priorities` AS t2 WHERE t.ordering $order t2.ordering AND t2.id = ".esc_sql($id)." ORDER BY t.ordering $direction LIMIT 1";
        $result = majesticsupport::$_db->get_row($query);
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` SET ordering = " . esc_sql($result->ordering) . " WHERE id = " . esc_sql($id);
        majesticsupport::$_db->query($query);
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` SET ordering = " . esc_sql($result->ordering2) . " WHERE id = " . esc_sql($result->id);
        majesticsupport::$_db->query($query);

        $row = MJTC_includer::MJTC_getTable('priorities');
        if ($row->update(array('id' => $id, 'ordering' => $result->ordering)) && $row->update(array('id' => $result->id, 'ordering' => $result->ordering2))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Priority','majestic-support')).' '.esc_html(__('ordering has been changed', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Priority','majestic-support')).' '.esc_html(__('ordering has not changed', 'majestic-support')), 'error');
        }
        return;
    }

    private function canRemovePriority($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT (
					(SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE priorityid = " . esc_sql($id) . ")
					) AS total";
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if ($result == 0) {
            $query = "SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` WHERE isdefault = 1 AND id = " . esc_sql($id);
            $result = majesticsupport::$_db->get_var($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            if ($result == 0)
                return 1;
            else
                return 3;
        } else
            return 2;
    }

    function getPriorityById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT priority FROM `" . majesticsupport::$_db->prefix . "mjtc_support_priorities` WHERE id = " . esc_sql($id);
        $priority = majesticsupport::$_db->get_var($query);
        return $priority;
    }

    function getAdminSearchFormDataPriority(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'priorities') ) {
            die( 'Security check Failed' );
        }
        $ms_search_array = array();
        $title = MJTC_request::MJTC_getVar('title');
        if ($title != '') {
            $ms_search_array['title'] = MJTC_majesticsupportphplib::MJTC_addslashes(MJTC_majesticsupportphplib::MJTC_trim($title));
        } else {
            $ms_search_array['title'] = '';
        }
        $ms_search_array['pagesize'] = absint(MJTC_request::MJTC_getVar('pagesize'));
        $ms_search_array['search_from_priority'] = 1;
        return $ms_search_array;
    }
}

?>

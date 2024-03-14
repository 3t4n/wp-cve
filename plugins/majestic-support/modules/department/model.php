<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_departmentModel {

    function getDepartments() {
        // Filter
        $isadmin = is_admin();
        $deptname = ($isadmin) ? 'departmentname' : 'ms-dept';

        $departmentname = isset(majesticsupport::$_search['department']) ? majesticsupport::$_search['department']['departmentname'] : '';
        $pagesize = isset(majesticsupport::$_search['department']) ? majesticsupport::$_search['department']['pagesize'] : '';

        $departmentname = majesticsupport::parseSpaces($departmentname);
        $inquery = '';
        if ($departmentname != null)
            $inquery .= " WHERE department.departmentname LIKE '%".esc_sql($departmentname)."%'";

        majesticsupport::$_data['filter'][$deptname] = $departmentname;
        majesticsupport::$_data['filter']['pagesize'] = $pagesize;

        // Pagination
        if($pagesize){
            MJTC_pagination::MJTC_setLimit($pagesize);
        }
        $query = "SELECT COUNT(`id`) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department";
        $query .= $inquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total,'departments');

        // Data
        $query = "SELECT department.*,email.email AS outgoingemail
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department
                    LEFT JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_email` AS email ON email.id = department.emailid ";
        $query .= $inquery;
        $query .= " ORDER BY department.ordering ASC,department.departmentname ASC LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function getDepartmentForForm($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT department.*,email.email AS outgoingemail
                        FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS department
                        JOIN `" . majesticsupport::$_db->prefix . "mjtc_support_email` AS email ON email.id = department.emailid
                        WHERE department.id = " . esc_sql($id);
            majesticsupport::$_data[0] = majesticsupport::$_db->get_row($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        return;
    }

    private function getNextOrdering() {
        $query = "SELECT MAX(ordering) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments`";
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $result + 1;
    }

    function storeDepartment($data) {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-department') ) {
            die( 'Security check Failed' );
        }
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $task_allow = ($data['id'] == '') ? 'Add Department' : 'Edit Department';
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask($task_allow);
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')) . ' ' . esc_html(majesticsupport::MJTC_getVarValue($task_allow)), 'error');
                return;
            }
        }

        if($data['sendmail'] == 1 && is_numeric($data['emailid'])){
            if ( in_array('emailpiping',majesticsupport::$_active_addons)) {
                $query = "SELECT emailaddress FROM `" . majesticsupport::$_db->prefix . "mjtc_support_ticketsemail` ";
                $emailaddresses = majesticsupport::$_db->get_results($query);
            }else{
                $emailaddresses = array();
            }
            $query = "SELECT email FROM `" . majesticsupport::$_db->prefix . "mjtc_support_email`
                WHERE id = ".esc_sql($data['emailid']);
            $email = majesticsupport::$_db->get_var($query);

            foreach ($emailaddresses as $edata) {
                if($email == $edata->emailaddress){
                    MJTC_message::MJTC_setMessage(esc_html(__('You cannot use this email, it is used in email piping', 'majestic-support')), 'error');
                    return;
                }
            }
        }

        if ($data['id'])
            $data['updated'] = date_i18n('Y-m-d H:i:s');
        else
            $data['created'] = date_i18n('Y-m-d H:i:s');

        $data = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions
        $data['departmentsignature'] = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['departmentsignature']);

        if (!$data['id']) { //new
            $data['ordering'] = $this->getNextOrdering();
        }
        if (isset($data['canappendsignature'])) { //new
            $data['canappendsignature'] = 1;
        }else{
            $data['canappendsignature'] = 0;
        }

        $row = MJTC_includer::MJTC_getTable('departments');

        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            if ($row->isdefault) {
                if ($row->isdefault == 1) {
                    $this->changeDefault($row->id, 0);
                } elseif ($row->isdefault == 2) {
                    $this->changeDefault($row->id, -1);
                }
            }
            MJTC_message::MJTC_setMessage(esc_html(__('The department has been stored', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('The department has not been stored', 'majestic-support')), 'error');
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
        $query = "SELECT t.ordering,t.id,t2.ordering AS ordering2 FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS t,`" . majesticsupport::$_db->prefix . "mjtc_support_departments` AS t2 WHERE t.ordering $order t2.ordering AND t2.id = ".esc_sql($id)." ORDER BY t.ordering $direction LIMIT 1";
        $result = majesticsupport::$_db->get_row($query);

        $row = MJTC_includer::MJTC_getTable('departments');
        if ($row->update(array('id' => $id, 'ordering' => $result->ordering)) && $row->update(array('id' => $result->id, 'ordering' => $result->ordering2))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Departments','majestic-support')).' '.esc_html(__('ordering has been changed', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Departments','majestic-support')).' '.esc_html(__('ordering has not changed', 'majestic-support')), 'error');
        }
        return;
    }

    function removeDepartment($id) {
        if (!is_numeric($id))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Delete Department');
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(esc_html(__('You are not allowed', 'majestic-support')), 'error');
                return;
            }
        }
        if ($this->canRemoveDepartment($id)) {

            $row = MJTC_includer::MJTC_getTable('departments');
            if ($row->delete($id)) {
                if(in_array('agent',majesticsupport::$_active_addons)){
                    $query = "DELETE
                                FROM `".majesticsupport::$_db->prefix . "mjtc_support_acl_role_access_departments`
                                WHERE departmentid = ".esc_sql($id);
                    majesticsupport::$_db->query($query);
                }
                MJTC_message::MJTC_setMessage(esc_html(__('The department has been deleted', 'majestic-support')), 'updated');
            } else {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                MJTC_message::MJTC_setMessage(esc_html(__('The department has not been deleted', 'majestic-support')), 'error');
            }
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('The department in use cannot be delete', 'majestic-support')), 'error');
        }
        return;
    }

    private function canRemoveDepartment($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT (
                    (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_tickets` WHERE departmentid = " . esc_sql($id) . ")
                    + (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE id = " . esc_sql($id) . " AND isdefault = 1) ";

                    if(in_array('agent', majesticsupport::$_active_addons)){
                        $query .= " + (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_acl_user_access_departments` WHERE departmentid = " . esc_sql($id) . ") ";
                    }

                    if(in_array('helptopic', majesticsupport::$_active_addons)){
                        $query .= " + (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_help_topics` WHERE departmentid = " . esc_sql($id) . ") ";
                    }

                    if(in_array('cannedresponses', majesticsupport::$_active_addons)){
                        $query .= " + (SELECT COUNT(id) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_department_message_premade` WHERE departmentid = " . esc_sql($id) . ")";
                    }

                    $query .= " ) AS total";
        $result = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        if ($result == 0)
            return true;
        else
            return false;
    }

    function getDepartmentForCombobox() {
        $query = "SELECT id, departmentname AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE status = 1";
        $query .= " ORDER BY ordering";
        $list = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $list;
    }

    function changeStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT status  FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE id=" . esc_sql($id);
           $status = majesticsupport::$_db->get_var($query);
       $status = 1 - $status;

       $row = MJTC_includer::MJTC_getTable('departments');
       if ($row->update(array('id' => $id, 'status' => $status))) {
            MJTC_message::MJTC_setMessage(esc_html(__('Department','majestic-support')).' '.esc_html(__('status has been changed', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Department','majestic-support')).' '.esc_html(__('status has not been changed', 'majestic-support')), 'error');
        }
        return;
    }

    function changeDefault($id,$default) {
        if (!is_numeric($id))
            return false;

        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_departments` SET isdefault = 0 WHERE id != " . esc_sql($id);
        majesticsupport::$_db->query($query);

        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_departments` SET isdefault = 1 - $default WHERE id=" . esc_sql($id);
        majesticsupport::$_db->query($query);

        if (majesticsupport::$_db->last_error == null) {
            MJTC_message::MJTC_setMessage(esc_html(__('Department','majestic-support')).' '.esc_html(__('default has been changed', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Department','majestic-support')).' '.esc_html(__('default has not been changed', 'majestic-support')), 'error');
        }
        return;
    }

    function getHelpTopicByDepartment() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-help-topic-by-department') ) {
            die( 'Security check Failed' );
        }
        if(!in_array('helptopic', majesticsupport::$_active_addons)){
            return;
        }

        $departmentid = MJTC_request::MJTC_getVar('val');
        if (!is_numeric($departmentid)){
            return false;
        }

        $query = "SELECT id, topic AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_help_topics` WHERE status = 1 AND departmentid = " . esc_sql($departmentid) ." ORDER BY ordering ASC";
        $list = majesticsupport::$_db->get_results($query);

        $query = "SELECT required FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE field='helptopic'";
        $isRequired = majesticsupport::$_db->get_var($query);

        $combobox = false;
        if(!empty($list)){
            $combobox = MJTC_formfield::MJTC_select('helptopicid', $list, '', esc_html(__('Select Help Topic', 'majestic-support')), array('class' => 'inputbox mjtc-support-select-field mjtc-form-select-field','data-validation'=>($isRequired ? 'required' : '')));
        }
        return $combobox;
    }

    function getPremadeByDepartment() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-premade-by-department') ) {
            die( 'Security check Failed' );
        }
        if(!in_array('cannedresponses', majesticsupport::$_active_addons)){
            return false;
        }
        $departmentid = MJTC_request::MJTC_getVar('val');
        if (!is_numeric($departmentid))
            return false;
        $query = "SELECT id, title AS text FROM `" . majesticsupport::$_db->prefix . "mjtc_support_department_message_premade` WHERE status = 1 AND departmentid = " . esc_sql($departmentid);
        $list = majesticsupport::$_db->get_results($query);
        $combobox = false;
        $html = '';
        if(!empty($list)){
            foreach($list as $premade){
                $html .= '<div class="mjtc-form-perm-msg" onclick="getpremade('.esc_js($premade->id).');">
                    <a href="javascript:void(0)" title="'.esc_html(__('Premade response','majestic-support')).'">'.wp_kses($premade->text, MJTC_ALLOWED_TAGS).'</a>
                </div>';


            }
        }else{
            $html = '<div class="mjtc-form-perm-msg">
                <div class = "permade-no-rec">'. esc_html(__('No Record Found','majestic-support')) .'</div>
            </div>';
        }

        return MJTC_majesticsupportphplib::MJTC_htmlentities($html);
    }

    function getSignatureByID($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT departmentsignature FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE id = " . esc_sql($id);
        $signature = majesticsupport::$_db->get_var($query);
        return $signature;
    }

    function getDepartmentById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT departmentname FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE id = " . esc_sql($id);
        $departmentname = majesticsupport::$_db->get_var($query);
        return $departmentname;
    }

    function getDefaultDepartmentID() {
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE isdefault = 1 OR isdefault = 2";
        $departmentid = majesticsupport::$_db->get_var($query);
        return $departmentid;
    }

    function getDepartmentIDForAutoAssign() {
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_departments` WHERE isdefault = 2 AND status = 1";
        $departmentid = majesticsupport::$_db->get_var($query);
        return $departmentid;
    }

    function getAdminDepartmentSearchFormData(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'departments') ) {
            die( 'Security check Failed' );
        }
        $ms_search_array = array();
        $isadmin = is_admin();
        $deptname = ($isadmin) ? 'departmentname' : 'ms-dept';
        $departmentname = MJTC_request::MJTC_getVar($deptname);
        if ($departmentname != '') {
            $ms_search_array['departmentname'] = MJTC_majesticsupportphplib::MJTC_addslashes(trim($departmentname));
        } else {
            $ms_search_array['departmentname'] = '';
        }
        $ms_search_array['pagesize'] = absint(MJTC_request::MJTC_getVar('pagesize'));
        $ms_search_array['search_from_department'] = 1;
        return $ms_search_array;
    }

}

?>

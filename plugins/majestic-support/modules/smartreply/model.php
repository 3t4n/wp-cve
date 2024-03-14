<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_smartreplyModel {

    function getSmartReplyies() {
        // Filter
        $isadmin = is_admin();
        $title = ($isadmin) ? 'title' : 'ms-title';

        $smartreplytitle = isset(majesticsupport::$_search['smartreply'][$title]) ? (majesticsupport::$_search['smartreply'][$title]): '';
        $pagesize = isset(majesticsupport::$_search['smartreply']['pagesize']) ? (majesticsupport::$_search['smartreply']['pagesize']): '';
        $inquery = '';

        if ($smartreplytitle != null){
            $inquery .= " WHERE smartreply.title LIKE '%".esc_sql($smartreplytitle)."%'";
        }

        majesticsupport::$_data['filter'][$title] = $smartreplytitle;
        majesticsupport::$_data['filter']['pagesize'] = $pagesize;

        // Pagination
        if($pagesize){
            MJTC_pagination::MJTC_setLimit($pagesize);
        }
        $query = "SELECT COUNT(`id`) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` AS smartreply ";
        $query .= $inquery;
        $total = majesticsupport::$_db->get_var($query);
        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        // Data
        $query = "SELECT smartreply.*
					FROM `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` AS smartreply ";
        $query .= $inquery;
        $query .= " ORDER BY smartreply.id DESC LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function getSmartReplyForForm($id) {
        $result=array();
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT smartreply.*
						FROM `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` AS smartreply
						WHERE smartreply.id = " . esc_sql($id);
            $result = majesticsupport::$_db->get_row($query);
            $result->ticketsubjects = json_decode($result->ticketsubjects);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        majesticsupport::$_data[0]=$result;
        return;
    }

    function storeSmartReply($data) {
        if ($data['id'] == '') {
            if ($this->validateSmartReply($data['title'])) {
                MJTC_message::MJTC_setMessage(esc_html(__('Smart Reply Title Already Exist', 'majestic-support')), 'error');
                return;
            }
        }
        $newdata = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions
        $ticketsubjects = [];
        foreach ($newdata['ticketsubjects'] as $ticketsubject) {
            if($ticketsubject!='')
            {
                $ticketsubjects[] = MJTC_majesticsupportphplib::MJTC_stripslashes($ticketsubject);
            }

        }
        $newdata['ticketsubjects'] = json_encode($ticketsubjects, true);

        $row = MJTC_includer::MJTC_getTable('smartreplies');
        if (isset($_POST['reply'])) {
            $newdata['reply'] = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['reply']);
        }
        $newdata = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($newdata);// remove slashes with quotes.
        $error = 0;

        if (!$row->bind($newdata)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            $id = $row->id;
            MJTC_message::MJTC_setMessage(esc_html(__('Smart Reply has been stored', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Smart Reply has not been stored', 'majestic-support')), 'error');
        }
        return;
    }

    private function validateSmartReply($title) {
        if (!$title)
            return false;
        $query = 'SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_smartreplies` WHERE title = "' . esc_sql($title) . '"';
        $result = majesticsupport::$_db->get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function removeSmartReply($id) {
        if (!is_numeric($id))
            return false;
        if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
            $allowed = MJTC_includer::MJTC_getModel('userpermissions')->MJTC_checkPermissionGrantedForTask('Delete Smart Reply');
            if ($allowed != true) {
                MJTC_message::MJTC_setMessage(__('You are not allowed', 'majestic-support'), 'error');
                return;
            }
        }
        $row = MJTC_includer::MJTC_getTable('smartreplies');
        if ($row->delete($id)) {
            MJTC_message::MJTC_setMessage(esc_html(__('Smart Reply has been deleted', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            MJTC_message::MJTC_setMessage(esc_html(__('Smart Reply has not been deleted', 'majestic-support')), 'error');
        }
        return;
    }

    function getSmartReplyById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT smartreply FROM `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` WHERE id = " . esc_sql($id);
        $smartreply = majesticsupport::$_db->get_var($query);
        return $smartreply;
    }

    function checkSmartReply(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'check-smart-reply') ) {
            die( 'Security check Failed' );
        }
        $limit = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('maximum_record_for_smart_reply');
        $subject = MJTC_request::MJTC_getVar('ticketSubject');
        $query = 'SELECT id,title, MATCH (ticketsubjects)
                    AGAINST ("'.esc_sql($subject).'"
                    IN NATURAL LANGUAGE MODE) AS relevance 
                    FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_smartreplies`
                    WHERE MATCH (ticketsubjects)
                    AGAINST("'.esc_sql($subject).'"
                    IN NATURAL LANGUAGE MODE) LIMIT '.esc_sql($limit).';';
        $replies = majesticsupport::$_db->get_results($query);
        $html = '';
        foreach ($replies as $reply) {
            $html .= "<span class=\"ms-ticket-detail-smartreply-add smartReplyFound\"  onclick=\"getSmartReply(".$reply->id.");\">
                        <span class=\"ms-smartreply-btn-text\" id=\"possible-reply\">
                            ". majesticsupport::MJTC_getVarValue($reply->title)."
                        </span>
                    </span>";
        }
        if (isset($html) && $html != '') {
            return json_encode(MJTC_majesticsupportphplib::MJTC_htmlentities($html));
        }
        return;
    }

    function getSmartReply(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-smart-reply') ) {
            die( 'Security check Failed' );
        }
        $id = MJTC_request::MJTC_getVar('val');
        $query = "SELECT usedby,reply FROM `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` WHERE id = ".esc_sql($id);
        $reply = majesticsupport::$_db->get_row($query);
        $usedby = $reply->usedby + 1;
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_smartreplies` SET `usedby`=".esc_sql($usedby)." WHERE id=".esc_sql($id);
        majesticsupport::$_db->query($query);
        return $reply->reply;
    }

    function getAdminSearchFormDataSmartReply(){
        $ms_search_array = array();
        $param = (is_admin()) ? 'title' : 'ms-title';
        $title = MJTC_request::MJTC_getVar($param);
        if ($title != '') {
            $ms_search_array[$param] = MJTC_majesticsupportphplib::MJTC_addslashes(MJTC_majesticsupportphplib::MJTC_trim($title));
        } else {
            $ms_search_array[$param] = '';
        }
        $ms_search_array['pagesize'] = absint(MJTC_request::MJTC_getVar('pagesize'));
        $ms_search_array['search_from_smartreply'] = 1;
        return $ms_search_array;
    }
}

?>

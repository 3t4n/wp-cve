<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCoverLetterModel {

    function getCoverLetterbyId($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_coverletters WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        return;
    }

    function getViewCoverLetter($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT cl.title,cl.description FROM " . jsjobs::$_db->prefix . "js_job_coverletters AS cl WHERE cl.id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
    }

    function getMyCoverLettersbyUid($u_id) {
        if ((is_numeric($u_id) == false))
            return false;

        $query = "SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` WHERE uid = " . $u_id;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total, 'mycoverletters');

        $query = "SELECT letter.id, letter.title,letter.created,letter.serverid,CONCAT(letter.alias,'-',letter.id) aliasid
                FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS letter
                WHERE letter.uid =" . $u_id;
        $query.=" LIMIT " . JSJOBSpagination::$_offset . "," . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);
        return;
    }

    function getAllCoverletters() {
        // Filter
        $title = jsjobs::$_search['coverletter']['title'];
        $status = jsjobs::$_search['coverletter']['status'];

        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            //$title = esc_sql($title);
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .= $clause . " status = " . $status;

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;

        //pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_coverletters ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //data
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_coverletters ";
        $query .= $inquery;
        $query .= " ORDER BY created DESC LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function deleteCoverLetter($coverletterid, $uid = '') {
        if (is_numeric($coverletterid) == false)
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $row = JSJOBSincluder::getJSTable('coverletter');
        $query = "SELECT COUNT(letter.id) FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS letter WHERE letter.id = " . $coverletterid . " AND letter.uid = " . $uid;
        $total = jsjobsdb::get_var($query);
        if ($total > 0) { // this search is same user
            $query = "SELECT COUNT(jobapply.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply WHERE jobapply.coverletterid = " . $coverletterid;
            $cvtotal = jsjobsdb::get_var($query);
            if ($cvtotal > 0) { // Cover letter in use
                return JSJOBS_IN_USE;
            }
            if (!$row->delete($coverletterid)) {
                return JSJOBS_DELETE_ERROR;
            }
        } else {
            return JSJOBS_DELETE_ERROR;
        }
        return JSJOBS_DELETED;
    }

    function deleteCoverLetterAdmin($ids) {
        foreach($ids AS $id){
            if(!is_numeric($id)) return false;
            $query = "SELECT COUNT(jobapply.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS jobapply WHERE jobapply.coverletterid = " . $id;
            $total = jsjobsdb::get_var($query);
            if ($total > 0) { // Cover letter in use
                return JSJOBS_IN_USE;
            }
            $row = JSJOBSincluder::getJSTable('coverletter');
            if (!$row->delete($id)) {
                return JSJOBS_DELETE_ERROR;
            }
        }
        return JSJOBS_DELETED;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSJOBSincluder::getJSTable('coverletter');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->coverletterCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                } else {
                    $total += 1;
                }
            }
        }
        if ($total == 0) {
            JSJOBSMessages::$counter = false;
            if ($status == 1)
                return JSJOBS_PUBLISHED;
            else
                return JSJOBS_UN_PUBLISHED;
        }else {
            JSJOBSMessages::$counter = $total;
            if ($status == 1)
                return JSJOBS_PUBLISH_ERROR;
            else
                return JSJOBS_UN_PUBLISH_ERROR;
        }
    }

    function coverletterCanUnpublish($id){
        return true;
    }

    function storeCoverLetter($data) {
        if (empty($data))
            return false;

        if (!empty($data['alias']))
            $c_l_alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $c_l_alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['title']);

        $c_l_alias = jsjobslib::jsjobs_strtolower(jsjobslib::jsjobs_str_replace(' ', '-', $c_l_alias));
        $data['alias'] = $c_l_alias;
        if(!is_admin()){
            $data['status'] = 1;
            if (isset($data['uid'])) {
                if($data['uid'] != JSJOBSincluder::getObjectClass('user')->uid() && $data['id'] == ''){
                    $data['uid'] = JSJOBSincluder::getObjectClass('user')->uid();
                }
            }
        }
        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        $data['description'] = JSJOBSincluder::getJSModel('common')->getSanitizedEditorData($_POST['description']);
        $row = JSJOBSincluder::getJSTable('coverletter');
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }

        return JSJOBS_SAVED;
    }

    function canAddCoverLetter($uid) {
        if (!is_numeric($uid))
            return false;
        return true;
    }

    function getCoverLetterByResumeAndJobID($resumeid, $jobid) {
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($jobid))
            return false;
        $query = "SELECT c.title AS ctitle, c.description AS cdescription
                    FROM `" . jsjobs::$_db->prefix . "js_job_jobapply` AS ja
                    JOIN `" . jsjobs::$_db->prefix . "js_job_coverletters` AS c ON c.id = ja.coverletterid
                    WHERE ja.cvid = " . $resumeid . " AND ja.jobid = " . $jobid;
        $result = jsjobs::$_db->get_row($query);
        jsjobs::$_data['coverletter'] = $result;
        return;
    }


    function getIfCoverLetterOwner($coverletterid) {
        if (!is_numeric($coverletterid))
            return false;
        $uid = JSJOBSincluder::getObjectClass('user')->uid();
        $query = "SELECT cletter.id
        FROM `" . jsjobs::$_db->prefix . "js_job_coverletters` AS cletter
        WHERE cletter.uid = " . $uid . "
        AND cletter.id =" . $coverletterid;
        $result = jsjobs::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'coverletter') ) {
            die( 'Security check Failed' );
        }
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = JSJOBSrequest::getVar('title');
        $jsjp_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjp_search_array['search_from_coverletter'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'coverletter') ) {
            die( 'Security check Failed' );
        }
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_coverletter']) && $wpjp_search_cookie_data['search_from_coverletter'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        jsjobs::$_search['coverletter']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : null;
        jsjobs::$_search['coverletter']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
    }

    function getMessagekey(){
        $key = 'coverletter';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}
?>

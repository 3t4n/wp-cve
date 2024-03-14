<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBScurrencyModel {

    function getCurrencybyId($id) {
        if (is_numeric($id) == false)
            return false;

        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_currencies WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);
        return;
    }

    function getCurrencyForCombo() {

        $query = "SELECT id, symbol AS text FROM `" . jsjobs::$_db->prefix . "js_job_currencies` WHERE status = 1 ORDER BY ordering ASC";
        $allcurrency = jsjobsdb::get_results($query);
        return $allcurrency;
    }

    function getDefaultCurrency() {

        $query = "SELECT currency.id FROM `" . jsjobs::$_db->prefix . "js_job_currencies` currency WHERE currency.default = 1 AND currency.status=1 ";
        $defaultValue = jsjobsdb::get_row($query);
        if (!$defaultValue) {
            $query = "SELECT id FROM `" . jsjobs::$_db->prefix . "js_job_currencies` WHERE status=1";
            $defaultValue = jsjobsdb::get_results($query);
        }
        return $defaultValue;
    }

    function getAllCurrencies() {
        // Filter
        $title = jsjobs::$_search['country']['title'];
        $status = jsjobs::$_search['country']['status'];
        $code = jsjobs::$_search['country']['code'];

        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .=$clause . " status = " . $status;
        if ($code != null)
            $inquery .=$clause . " code LIKE '%" . $code . "%'";

        jsjobs::$_data['filter']['title'] = $title;
        jsjobs::$_data['filter']['status'] = $status;
        jsjobs::$_data['filter']['code'] = $code;
        //Pagination
        $query = "SELECT count(id) FROM `" . jsjobs::$_db->prefix . "js_job_currencies` ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_currencies` $inquery ORDER BY ordering ASC ";
        $query .= " LIMIT " . JSJOBSpagination::$_offset . ", " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur SET cur.default = 0 WHERE cur.id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCurrencyExist($data['title']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_currencies";
                $data['ordering'] = jsjobsdb::get_var($query);
            }

            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ($data['default'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ($data['default'] == 1) {
                    $canupdate = true;
                }
            }
        }
        return $canupdate;
    }

    function storeCurrency($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSJOBS_ALREADY_EXIST)
            return JSJOBS_ALREADY_EXIST;

        $row = JSJOBSincluder::getJSTable('currency');
        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        if ($row->default == 1) {
            $this->updateIsDefault($row->id);
        }
        return JSJOBS_SAVED;
    }

    function isCurrencyExist($title) {
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_currencies WHERE title = '" . $title . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function deleteCurrencies($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('currency');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->currencyCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                }
            } else {
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSJOBSMessages::$counter = false;
            return JSJOBS_DELETED;
        } else {
            JSJOBSMessages::$counter = $notdeleted;
            return JSJOBS_DELETE_ERROR;
        }
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSJOBSincluder::getJSTable('currency');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->currencyCanUnpulish($id)) {
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

    function currencyCanUnpulish($currencyid) {
        $query = " SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.default = 1 ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function currencyCanDelete($currencyid) {
        if (is_numeric($currencyid) == false)
            return false;

        $query = " SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE currencyid = " . $currencyid . " OR dcurrencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_currencies` AS cur WHERE cur.id = " . $currencyid . " AND cur.default =1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCurrencyResumeAppliedForCombo() {
        $query = "SELECT id, symbol AS text FROM `" . jsjobs::$_db->prefix . "js_job_currencies` WHERE status = 1";
        $allcurrency = jsjobsdb::get_results($query);
        return $allcurrency;
    }

    function getDefaultCurrencyId() {
        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_currencies WHERE `default` = 1";
        $id = jsjobsdb::get_var($query);
        return $id;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'currency') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = JSJOBSrequest::getVar("title");
        $jsjp_search_array['status'] = JSJOBSrequest::getVar("status");
        $jsjp_search_array['code'] = JSJOBSrequest::getVar("code");
        $jsjp_search_array['search_from_currency'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'currency') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_currency']) && $wpjp_search_cookie_data['search_from_currency'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['code'] = $wpjp_search_cookie_data['code'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        jsjobs::$_search['country']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : '';
        jsjobs::$_search['country']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        jsjobs::$_search['country']['code'] = isset($jsjp_search_array['code']) ? $jsjp_search_array['code'] : '';
    }

    function getMessagekey(){
        $key = 'currency';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>

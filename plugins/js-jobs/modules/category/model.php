<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSCategoryModel {
    public $class_prefix = '';

    function __construct(){
        if(jsjobs::$theme_chk == 1){
            $this->class_prefix = 'jsjb-jm';
        }elseif(jsjobs::$theme_chk == 2){
            $this->class_prefix = 'jsjb-jh';
        }
    }

    function getCategorybyId($id,$count_flag = 0) {
        if (is_numeric($id) == false) return false;

        $query = " SELECT * FROM " . jsjobs::$_db->prefix . "js_job_categories WHERE id = " . $id;
        jsjobs::$_data[0] = jsjobsdb::get_row($query);

        if($count_flag == 3 || $count_flag == 2){
            $query = " SELECT count(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                       WHERE job.jobcategory = ".$id." AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() AND job.status = 1 ";
            jsjobs::$_data[0]->count = jsjobsdb::get_var($query);
        }else{
            jsjobs::$_data[0]->count = -1;
        }

        return;
    }

    function getAllCategories() {

        //Filters
        $categoryname = jsjobs::$_search['category']['searchname'];
        $status = jsjobs::$_search['category']['status'];

        $inquery = '';
        $statusop = 'WHERE parentid = 0';
        $filter_flag = 0;
        if ($categoryname != null) {
            $inquery .= " AND cat_title LIKE '%$categoryname%'";
            $statusop = 'WHERE 1 = 1 ';
            $filter_flag = 1;
        }
        if (is_numeric($status)) {
            $statusop = 'WHERE 1 = 1 ';
            $inquery .=" AND isactive = " . $status;
            $filter_flag = 1;
        }
        $inquery .= "";

        jsjobs::$_data['filter']['searchname'] = $categoryname;
        jsjobs::$_data['filter']['status'] = $status;
        //pagination
        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_categories  $statusop";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);
        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //data
        $result = array();
        $prefix = '|-- ';
        $query = "SELECT * FROM " . jsjobs::$_db->prefix . "js_job_categories $statusop ";
        $query .= $inquery;

        $categories = jsjobs::$_db->get_results($query);

        if($filter_flag == 0){
            if (isset($categories)) {
                foreach ($categories as $cat) {
                    $record = (object) array();
                    $record->id = $cat->id;
                    $record->cat_title = $cat->cat_title;
                    $record->alias = $cat->alias;
                    $record->isactive = $cat->isactive;
                    $record->isdefault = $cat->isdefault;
                    $record->ordering = $cat->ordering;
                    $result[] = $record;
                    $this->getCategoryChild($cat->id, $prefix, $result);
                }
            }
        }else{
            foreach ($categories as $cat) {
                if($cat->parentid != 0){
                    $cat->cat_title = '|--'.$cat->cat_title;
                }
                $result[] = (object) $cat;
            }

        }
        $totalresult = count($result);
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($totalresult);

        $finalresult = array();
        JSJOBSpagination::$_limit = JSJOBSpagination::$_limit + JSJOBSpagination::$_offset;
        if (JSJOBSpagination::$_limit >= $totalresult)
            JSJOBSpagination::$_limit = $totalresult;
        for ($i = JSJOBSpagination::$_offset; $i < JSJOBSpagination::$_limit; $i++) {
            $finalresult[] = $result[$i];
        }

        jsjobs::$_data[0] = $finalresult;

        return;
    }

    private function getCategoryChild($parentid, $prefix, &$result) {

        if (!is_numeric($parentid))
            return false;
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category WHERE category.parentid = " . $parentid ." ORDER by category.ordering ";
        $kbcategories = jsjobs::$_db->get_results($query);
        if (!empty($kbcategories)) {
            foreach ($kbcategories as $cat) {
                $subrecord = (object) array();
                $subrecord->id = $cat->id;
                $subrecord->cat_title = $prefix . __($cat->cat_title, 'js-job');
                $subrecord->alias = $cat->alias;
                $subrecord->isactive = $cat->isactive;
                $subrecord->isdefault = $cat->isdefault;
                $subrecord->ordering = $cat->ordering;
                $result[] = $subrecord;
                $this->getCategoryChild($cat->id, $prefix . '|-- ', $result);
            }
            return $result;
        }
    }

    function getCategoryForCombobox($themecall=null) {
        $result = array();
        $prefix = '|-- ';
        $query = "SELECT category.* from `" . jsjobs::$_db->prefix . "js_job_categories` AS category
                    WHERE category.parentid = 0 AND category.isactive = 1 ORDER by category.ordering";
        $knowledgebase = jsjobs::$_db->get_results($query);
        if (isset($knowledgebase)) {
            foreach ($knowledgebase as $kb) {
                $record = (object) array();
                $record->id = $kb->id;
                $record->cat_title = $kb->cat_title;
                $result[] = $record;
                $this->getCategoryChild($kb->id, $prefix, $result);
            }
        }

        $list = array();
        foreach ($result AS $category) {
            if(null != $themecall){
                //$list[$category->id] = $category->cat_title;
                $list[$category->cat_title] = intval($category->id);
            }else{
                $list[] = (object) array('id' => $category->id, 'text' => $category->cat_title);

            }
        }
        return $list;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_categories` SET isdefault = 0 WHERE id != " . $id;
        jsjobsdb::query($query);
    }

    function validateFormData(&$data) {
        $category = JSJOBSrequest::getVar('parentid');
        $inquery = ' ';
        if ($category) {
            $inquery .=" WHERE parentid = $category ";
        }
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCategoryExist($data['cat_title']);
            if ($result == true) {
                return JSJOBS_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . jsjobs::$_db->prefix . "js_job_categories " . $inquery;
                $data['ordering'] = jsjobsdb::get_var($query);
                if ($data['ordering'] == null)
                    $data['ordering'] = 1;
            }

            if ($data['isactive'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if (isset($data['isdefault']) AND $data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['jsjobs_isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['isactive'] = 1;
            } else {
                if ($data['isactive'] == 0) {
                    $data['isdefault'] = 0;
                } else {
                    if ($data['isdefault'] == 1) {
                        $canupdate = true;
                    }
                }
            }
        }
        return $canupdate;
    }

    function storeCategory($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === JSJOBS_ALREADY_EXIST)
            return JSJOBS_ALREADY_EXIST;

        if (!empty($data['alias']))
            $cat_title_alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $cat_title_alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($data['cat_title']);

        $cat_title_alias = jsjobslib::jsjobs_strtolower(jsjobslib::jsjobs_str_replace(' ', '-', $cat_title_alias));
        $cat_title_alias = jsjobslib::jsjobs_strtolower(jsjobslib::jsjobs_str_replace('/', '-', $cat_title_alias));
        $data['alias'] = $cat_title_alias;

        $row = JSJOBSincluder::getJSTable('categories');

        $data = jsjobs::sanitizeData($data);
        $data = JSJOBSincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return JSJOBS_SAVE_ERROR;
        }
        if (!$row->store()) {
            return JSJOBS_SAVE_ERROR;
        }
        if ($canupdate) {
            $this->updateIsDefault($row->id);
        }
        return JSJOBS_SAVED;
    }

    function deleteCategories($ids) {
        if (empty($ids))
            return false;
        $row = JSJOBSincluder::getJSTable('categories');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->categoryCanDelete($id) == true) {
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

        $row = JSJOBSincluder::getJSTable('categories');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'isactive' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->categoryCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'isactive' => $status))) {
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

    function categoryCanUnpublish($categoryid) {
        if (!is_numeric($categoryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_categories` WHERE id = " . $categoryid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function categoryCanDelete($categoryid) {
        if (!is_numeric($categoryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_companies` WHERE category = " . $categoryid . ")
                    +( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobcategory = " . $categoryid . ")
                    +( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE job_category = " . $categoryid . ")
                    +( SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_categories` WHERE id = " . $categoryid . " AND isdefault = 1)
                    AS total ";
        $total = jsjobsdb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCategoryExist($title) {

        $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_categories WHERE cat_title = '" . $title . "'";
        $result = jsjobsdb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getCategoriesForCombo() {
        $rows = $this->getCategoryForCombobox();
        return $rows;
    }

    function getsubcategories() {
        $categoryalias = JSJOBSrequest::getVar('category');
        $categoryid = JSJOBSincluder::getJSModel('job')->parseid($categoryalias);
        if (!is_numeric($categoryid))
            return false;
        $query = "SELECT count(cat.id)
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat
                    WHERE cat.parentid = " . $categoryid;
        $count = jsjobs::$_db->get_var($query);
        $query = "SELECT cat.cat_title
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat
                    WHERE cat.id = " . $categoryid;
        $cat_title = jsjobs::$_db->get_var($query);
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
        $subcategory_limit = $config_array['subcategory_limit'];
        $query = "SELECT cat.cat_title, CONCAT(cat.alias,'-',cat.id) AS aliasid,
                    (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobcategory = cat.id) AS totaljobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat
                    WHERE cat.parentid = " . $categoryid . " ORDER BY cat.ordering ASC LIMIT " . $subcategory_limit;
        $result = jsjobs::$_db->get_results($query);
        $html = '';
        $resume = JSJOBSrequest::getVar('resume');
        if(jsjobs::$theme_chk == 2){
            $prefix = $this->class_prefix.'-';
            $main_wrap = '';
        }else{
            $prefix = '';
            $main_wrap = 'js';
        }
        if (!empty($result)) {
            $html .= '<div class="'.$prefix.$main_wrap.'jobs-subcategory-wrapper">';
            foreach ($result AS $cat) {
                if ($resume == 1) {
                    $link = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'category'=>$cat->aliasid, 'jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid')));
                } else {
                    $link = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'category'=>$cat->aliasid, 'jsjobspageid'=>JSJOBSRequest::getVar('jsjobspageid')));
                }
                $html .= '  <div class="'.$prefix.'category-wrapper" style="width:100%;">
                                <a href="' . $link . '">
                                <div class="'.$prefix.'jobs-by-categories-wrapper">
                                    <span class="'.$prefix.'title">' . $cat->cat_title . '</span>';
                if ($resume == 1) {
                    if($config_array['categories_numberofresumes'] == 1){
                        $html .= '<span class="'.$prefix.'totat-jobs">(' . $cat->totaljobs . ')</span>';
                    }
                }else{
                    if($config_array['categories_numberofjobs'] == 1){
                        $html .= '<span class="'.$prefix.'totat-jobs">(' . $cat->totaljobs . ')</span>';
                    }
                }
                $html .=    '</div>
                            </a>
                        </div>';
            }
            if ($count > $subcategory_limit) {
                $html .= '  <div class="showmore-wrapper">
                                <a href="#" class="showmorebutton" data-title="' . $cat_title . '" data-id="' . $categoryalias . '">' . __('Show More', 'js-jobs') . '</a>
                            </div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    private function getAllParentListTillRoot($categoryid,&$parentsarray){
        if(!is_numeric($categoryid)) return false;
        $query = "SELECT id, cat_title, parentid FROM `".jsjobs::$_db->prefix."js_job_categories` WHERE id = " . $categoryid;
        $result = jsjobs::$_db->get_row($query);
        if($result){
            $parentsarray[$result->id] = $result->cat_title;
            if(is_numeric($result->parentid) && $result->parentid != 0){
                $categoryid = $result->parentid;
                $this->getAllParentListTillRoot($categoryid,$parentsarray);
            }
        }
        return;
    }

    function getsubcategorypopup() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $category = JSJOBSrequest::getVar('category');
        $categoryid = JSJOBSincluder::getJSModel('job')->parseid($category);
        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('category');
        $subcategory_limit = $config_array['subcategory_limit'];
        $resume = JSJOBSrequest::getVar('resume');
        if (!is_numeric($categoryid))
            return false;
        if($resume == 1){
            $query = "SELECT cat.cat_title, CONCAT(cat.alias,'-',cat.id) AS aliasid,cat.id AS categoryid,
                        (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` WHERE job_category = cat.id AND status = 1 AND searchable = 1) AS totaljobs
                        FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat
                        WHERE cat.parentid = " . $categoryid;
        }else{
            $query = "SELECT cat.cat_title, CONCAT(cat.alias,'-',cat.id) AS aliasid,cat.id AS categoryid,
                        (SELECT COUNT(id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` WHERE jobcategory = cat.id) AS totaljobs
                        FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS cat
                        WHERE cat.parentid = " . $categoryid;
        }
        $result = jsjobs::$_db->get_results($query);
        foreach($result AS $cat_child){
            if($resume == 1){
                $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                    ,(SELECT count(resume.id) FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                        where resume.job_category = category.id AND resume.status = 1 AND resume.searchable = 1)  AS totaljobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
                    WHERE category.isactive = 1 AND category.parentid = ".$cat_child->categoryid." ORDER BY category.ordering ASC LIMIT ".$subcategory_limit;
            }else{
                $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                    ,(SELECT count(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                        where job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                    FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
                    WHERE category.isactive = 1 AND category.parentid = ".$cat_child->categoryid." ORDER BY category.ordering ASC LIMIT ".$subcategory_limit;
            }
            $cat_child->subcat = jsjobs::$_db->get_results($query);
        }
        $html = '';
        if (!empty($result)) {
            if(jsjobs::$theme_chk == 2){
                $prefix = $this->class_prefix.'-';
            $html .= '<div class="'.$prefix.'jobs-subcategory-wrapper">';
                $main_wrap = '';
            }else{
                $prefix = '';
                $main_wrap = 'js';
            $html .= '<div class="'.$prefix.'jsjobs-subcategory-wrapper">';
            }
            foreach ($result AS $cat) {
                if ($resume == 1) {
                    $link = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'category'=>$cat->aliasid, 'jsjobspageid'=>JSJOBSRequest::getVar('page_id')));
                } else {
                    $link = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'category'=>$cat->aliasid, 'jsjobspageid'=>JSJOBSRequest::getVar('page_id')));
                }
                $html .= '  <div data-id="' . $cat->aliasid . '" class="'.$prefix.'category-wrapper" style="width:50%;">
                                <a href="' . $link . '">
                                <div class="'.$prefix.'jobs-by-categories-wrapper">
                                    <span class="'.$prefix.'title">' . __($cat->cat_title,'js-jobs') . '</span>';
                        if ($resume == 1) {
                            if($config_array['categories_numberofresumes'] == 1){
                                $html .= '<span class="'.$prefix.'totat-jobs">(' . $cat->totaljobs . ')</span>';
                            }
                        }else{
                            if($config_array['categories_numberofjobs'] == 1){
                                $html .= '<span class="'.$prefix.'totat-jobs">(' . $cat->totaljobs . ')</span>';
                            }
                        }
                $html .= '
                                </div>
                                </a>';
                if (!empty($cat->subcat)) {
                    $html .= '<div class="'.$prefix.$main_wrap.'jobs-subcategory-wrapper" style="display:none;">';
                    $subcount = 0;
                    foreach ($cat->subcat AS $sub_cat) {
                        if($resume == 1){
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'resumes', 'category'=>$sub_cat->aliasid, 'jsjobspageid'=>JSJOBSRequest::getVar('page_id')));
                        }else{
                            $link = jsjobs::makeUrl(array('jsjobsme'=>'job', 'jsjobslt'=>'jobs', 'category'=>$sub_cat->aliasid, 'jsjobspageid'=>JSJOBSRequest::getVar('page_id')));
                        }
                        $html .= '  <div class="'.$prefix.'category-wrapper" style="width:100%;">
                                        <a href="' . $link . '">
                                        <div class="'.$prefix.'jobs-by-categories-wrapper">
                                            <span class="'.$prefix.'title">' . __($sub_cat->cat_title,'js-jobs') . '</span>';
                        if ($resume == 1) {
                            if($config_array['categories_numberofresumes'] == 1){
                                $html .= '<span class="'.$prefix.'totat-jobs">(' . $sub_cat->totaljobs . ')</span>';
                            }
                        }else{
                            if($config_array['categories_numberofjobs'] == 1){
                                $html .= '<span class="'.$prefix.'totat-jobs">(' . $sub_cat->totaljobs . ')</span>';
                            }
                        }
                        $html .=    '</div>
                                    </a>
                                </div>';
                        $subcount++;
                    }
                    if ($subcount >= $subcategory_limit) {
                        $html .= '  <div class="'.$prefix.'showmore-wrapper">
                                        <a href="#" class="'.$prefix.'showmorebutton" onclick="getPopupAjax(\'' . $cat->aliasid . '\', \'' . $cat->cat_title . '\');">' . __('Show More', 'js-jobs') . '</a>
                                    </div>';
                    }
                    $html .= '</div>';
                }

                $html .= '</div>';
            }
            $html .= '</div>';
        }
        // Navigation get all parents
        $parentsarray = array();
        $this->getAllParentListTillRoot($categoryid,$parentsarray);
        if(!empty($parentsarray)){
            $html .= '<ul class="jsjobs_cat_popup_navigation">';
            foreach($parentsarray AS $pcatid => $pcattitle){
                $html .= '<li onclick="getPopupAjax('.$pcatid.',\''.$pcattitle.'\');">'.$pcattitle.'</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
    function getDefaultCategoryId() {

        $query = "SELECT id FROM " . jsjobs::$_db->prefix . "js_job_categories WHERE isdefault = 1";
        $id = jsjobsdb::get_var($query);
        return $id;
    }

    function getTitleByCategory($id) {
        if(!is_numeric($id)) return false;
        $query = "SELECT cat_title FROM " . jsjobs::$_db->prefix . "js_job_categories WHERE id = " . $id;
        $title = jsjobsdb::get_var($query);
        return $title;
    }

    function getMessagekey(){
        $key = 'category';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function getTopCategories($limit){
        $query = "SELECT category.id,category.cat_title AS title
            ,(SELECT count(job.id) FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                where job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() AND job.status = 1)  AS totaljobs
            FROM `" . jsjobs::$_db->prefix . "js_job_categories` AS category
            WHERE category.isactive = 1 having totaljobs > 0 ORDER BY totaljobs DESC LIMIT ".$limit;
        $data = jsjobs::$_db->get_results($query);
        return $data;
    }
    //search cookies data
    function getSearchFormDataCategory(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchname'] = JSJOBSrequest::getVar('searchname');
        $jsjp_search_array['status'] = JSJOBSrequest::getVar('status');
        $jsjp_search_array['search_from_category'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedCategory(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_category']) && $wpjp_search_cookie_data['search_from_category'] == 1){
            $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableCategory($jsjp_search_array){
        jsjobs::$_search['category']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;
        jsjobs::$_search['category']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
    }

}

?>

<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSslugModel {

    private $_params_flag;
    private $_params_string;

    function __construct() {
        $this->_params_flag = 0;
    }

    function getSlug() {
    // Filter
        $slug = jsjobs::$_search['slug']['slug'];

        $inquery = '';
        if ($slug != null){
            $inquery .= " AND slug.slug LIKE '%".$slug."%'";
        }
        jsjobs::$_data['slug'] = $slug;

        //pagination
        $query = "SELECT COUNT(id) FROM ".jsjobs::$_db->prefix."js_job_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $total = jsjobsdb::get_var($query);

        jsjobs::$_data['total'] = $total;
        jsjobs::$_data[1] = JSJOBSpagination::getPagination($total);

        //Data
        $query = "SELECT *
                  FROM ".jsjobs::$_db->prefix ."js_job_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $query .= " LIMIT " . JSJOBSpagination::$_offset . " , " . JSJOBSpagination::$_limit;
        jsjobs::$_data[0] = jsjobsdb::get_results($query);

        return;
    }


    function storeSlug($data) {
        if (empty($data)) {
            return false;
        }
        $row = JSJOBSincluder::getJSTable('slug');
        foreach ($data as $id => $slug) {
            if($id != '' && is_numeric($id)){
                $slug = sanitize_title($slug);
                if($slug != ''){
                    $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_slug
                            WHERE slug = '" . $slug."' ";
                    $slug_flag = jsjobsdb::get_var($query);
                    if($slug_flag > 0){
                        continue;
                    }else{
                        $row->update(array('id' => $id, 'slug' => $slug));
                    }
                }
            }
        }


/*
        if(!is_numeric($data['id']))return false;
        $data['slug'] = sanitize_title($data['slug']);
        if ($data['id'] != 0) {
            if ($data['slug'] == ''){ // get default value
                $query = "SELECT defaultslug FROM " . jsjobs::$_db->prefix . "js_job_slug
                          WHERE id = " . $data['id'];
                $defaultslug = jsjobsdb::get_var($query);
                $data['slug'] = $defaultslug;
            }else{
                $query = "SELECT COUNT(id) FROM " . jsjobs::$_db->prefix . "js_job_slug
                        WHERE slug = '" . $data['slug']."' ";
                $slug_flag = jsjobsdb::get_var($query);
                if($slug_flag > 0){
                     update_option('rewrite_rules', '');
                    return JSJOBS_ALREADY_EXIST;
                }
            }
            if (!$row->bind($data)){
                 update_option('rewrite_rules', '');
                return JSJOBS_SAVE_ERROR;
            }
            if (!$row->store()){
                 update_option('rewrite_rules', '');
                return JSJOBS_SAVE_ERROR;
            }
             update_option('rewrite_rules', '');
            return JSJOBS_SAVED;
        }
        */
        update_option('rewrite_rules', '');
        return JSJOBS_SAVED;
    }

    function savePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = ($data['prefix']);
        if($data['prefix'] == ''){
            return JSJOBS_SAVE_ERROR;
        }
        $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_config
                    SET configvalue = '".$data['prefix']."'
                    WHERE configname = 'slug_prefix'";
        if(jsjobsdb::query($query)){
             update_option('rewrite_rules', '');
            return JSJOBS_SAVED;
        }else{
             update_option('rewrite_rules', '');
            return JSJOBS_SAVE_ERROR;
        }
    }

    function saveHomePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = ($data['prefix']);
        if($data['prefix'] == ''){
            return JSJOBS_SAVE_ERROR;
        }
        $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_config
                    SET configvalue = '".$data['prefix']."'
                    WHERE configname = 'home_slug_prefix'";
        if(jsjobsdb::query($query)){
             update_option('rewrite_rules', '');
            return JSJOBS_SAVED;
        }else{
             update_option('rewrite_rules', '');
            return JSJOBS_SAVE_ERROR;
        }
    }

    function resetAllSlugs() {
        $query = "UPDATE " . jsjobs::$_db->prefix . "js_job_slug
                    SET slug = defaultslug ";
        if(jsjobsdb::query($query)){
             update_option('rewrite_rules', '');
            return JSJOBS_SAVED;
        }else{
             update_option('rewrite_rules', '');
            return JSJOBS_SAVE_ERROR;
        }
    }

    function getOptionsForEditSlug() {
        check_ajax_referer( 'wp_js_jm_nonce_check', 'wpnoncecheck' );
        $slug = JSJOBSrequest::getVar('slug');
        $html = '<span class="popup-top">
                    <span id="popup_title" >' . __("Edit","js-jobs")." ". __("Slug", "js-jobs") . '</span>
                        <img id="popup_cross" alt="popup cross" onClick="closePopup();" src="' . JSJOBS_PLUGIN_URL . 'includes/images/popup-close.png"></span>';

        $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . __('Slug','js-jobs').' '. __('Name', 'js-jobs') . '<font class="jsjobs_required-notifier">*</font></div>
                         <div class="popup-field-obj">' . JSJOBSformfield::text('slugedit', isset($slug) ? $slug : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        $html .='<div class="js-submit-container js-slug-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                    ' . JSJOBSformfield::button('save', __('Save', 'jsjobs'), array('class' => 'button savebutton','onClick'=>'getFieldValue();'));
        $html .='</div>';
        $html = htmlentities($html);
        return json_encode($html);
    }

    function getDefaultSlugFromSlug($layout) {
        $query = "SELECT  defaultslug FROM `".jsjobs::$_db->prefix."js_job_slug` WHERE slug = '".$layout."'";
        $val = jsjobs::$_db->get_var($query);
        return sanitize_title($val);
    }

    function getSlugFromFileName($layout,$module) {
        $where_query = '';
        if($layout == 'controlpanel'){
            if($module == 'jobseeker'){
                $where_query = " AND defaultslug = 'jobseeker-control-panel'";
            }elseif($module == 'employer'){
                $where_query = " AND defaultslug = 'employer-control-panel'";
            }
        }
        if($layout == 'mystats'){
            if($module == 'jobseeker'){
                $where_query = " AND defaultslug = 'jobseeker-my-stats'";
            }elseif($module == 'employer'){
                $where_query = " AND defaultslug = 'employer-my-stats'";
            }
        }
        $query = "SELECT slug FROM `".jsjobs::$_db->prefix."js_job_slug` WHERE filename = '".$layout."' ".$where_query;
        $val = jsjobs::$_db->get_var($query);
        return $val;
    }

    function getSlugString($home_page = 0) {

            //$query = "SELECT slug AS value, pkey AS akey FROM `".jsjobs::$_db->prefix."js_job_slug`";
            global $wp_rewrite;
            $rules = json_encode($wp_rewrite->rules);
            $query = "SELECT slug AS value FROM `".jsjobs::$_db->prefix."js_job_slug`";
            $val = jsjobs::$_db->get_results($query);
            $string = '';
            $bstring = '';
            //$rules = json_encode($rules);
            $prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
            $homeprefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
            foreach ($val as $slug) {
                    if($home_page == 1){
                        $slug->value = $homeprefix.$slug->value;
                    }
                    if(jsjobslib::jsjobs_strpos($rules,$slug->value) === false){
                        $string .= $bstring. $slug->value;
                    }else{
                        $string .= $bstring.$prefix. $slug->value;
                    }
                $bstring = '|';
            }
        return $string;
    }

    function getRedirectCanonicalArray() {
        global $wp_rewrite;
        $slug_prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
        $homeprefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
        $rules = json_encode($wp_rewrite->rules);
        $query = "SELECT slug AS value FROM `".jsjobs::$_db->prefix."js_job_slug`";
        $val = jsjobs::$_db->get_results($query);
        $string = array();
        $bstring = '';
        foreach ($val as $slug) {
            $slug->value = $homeprefix.$slug->value;
            $string[] = $bstring.$slug->value;
            $bstring = '/';
        }
        return $string;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'slug') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $jsjp_search_array['slug'] = JSJOBSrequest::getVar("slug");
        $jsjp_search_array['search_from_slug'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $nonce = JSJOBSrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'slug') ) {
            die( 'Security check Failed' ); 
        }
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            $wpjp_search_cookie_data = jsjobs::sanitizeData($_COOKIE['jsjob_jsjobs_search_data']);
            $wpjp_search_cookie_data = json_decode( jsjobslib::jsjobs_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_slug']) && $wpjp_search_cookie_data['search_from_slug'] == 1){
            $jsjp_search_array['slug'] = $wpjp_search_cookie_data['slug'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        jsjobs::$_search['slug']['slug'] = isset($jsjp_search_array['slug']) ? $jsjp_search_array['slug'] : '';
    }

    function getMessagekey(){
        $key = 'slug';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>

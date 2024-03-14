<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_slugModel {

    private $_params_flag;
    private $_params_string;

    function __construct() {
        $this->_params_flag = 0;
    }

    function getSlug() {
        // Filter
        $slug = majesticsupport::$_search['slug']['slug'];

        $inquery = '';
        if ($slug != null){
            $inquery .= " AND slug.slug LIKE '%".esc_sql($slug)."%'";
        }
        majesticsupport::$_data['slug'] = $slug;

        // Pagination
        $query = "SELECT COUNT(id) FROM ".majesticsupport::$_db->prefix."mjtc_support_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $total = majesticsupport::$_db->get_var($query);

        majesticsupport::$_data['total'] = $total;
        majesticsupport::$_data[1] = MJTC_pagination::MJTC_getPagination($total);

        //Data
        $query = "SELECT *
                  FROM ".majesticsupport::$_db->prefix ."mjtc_support_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $query .= " LIMIT " . MJTC_pagination::MJTC_getOffset() . ", " . MJTC_pagination::MJTC_getLimit();
        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);

        return;
    }


    function storeSlug($data) {
        if (empty($data)) {
            return false;
        }
        $row = MJTC_includer::MJTC_getTable('slug');
        foreach ($data as $id => $slug) {
            if($id != '' && is_numeric($id)){
                $slug = sanitize_title($slug);
                if($slug != ''){
                    $query = "SELECT COUNT(id) FROM " . majesticsupport::$_db->prefix . "mjtc_support_slug
                            WHERE slug = '" . esc_sql($slug)."' ";
                    $slug_flag = majesticsupport::$_db->get_var($query);
                    if($slug_flag > 0){
                        continue;
                    }else{
                        $row->update(array('id' => $id, 'slug' => $slug));
                    }
                }
            }
        }
        update_option('rewrite_rules', '');
        MJTC_message::MJTC_setMessage(esc_html(__('Slugs/Slug has been stored', 'majestic-support')), 'updated');
        return;
    }

    function savePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = ($data['prefix']);
        if($data['prefix'] == ''){
            MJTC_message::MJTC_setMessage(esc_html(__('Prefix has not been stored', 'majestic-support')), 'error');
            return;
        }
        $query = "UPDATE " . majesticsupport::$_db->prefix . "mjtc_support_config
                    SET configvalue = '".esc_sql($data['prefix'])."'
                    WHERE configname = 'slug_prefix'";
        if(majesticsupport::$_db->query($query)){
            update_option('rewrite_rules', '');
            MJTC_message::MJTC_setMessage(esc_html(__('Prefix has been stored', 'majestic-support')), 'updated');
            return;
        }else{
            update_option('rewrite_rules', '');
        	MJTC_message::MJTC_setMessage(esc_html(__('Prefix has not been stored', 'majestic-support')), 'error');
            return;
        }
    }

    function saveHomePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = ($data['prefix']);
        if($data['prefix'] == ''){
            MJTC_message::MJTC_setMessage(esc_html(__('Prefix has not been stored', 'majestic-support')), 'error');
            return;
        }
        $query = "UPDATE " . majesticsupport::$_db->prefix . "mjtc_support_config
                    SET configvalue = '".esc_sql($data['prefix'])."'
                    WHERE configname = 'home_slug_prefix'";
        if(majesticsupport::$_db->query($query)){
            update_option('rewrite_rules', '');
            MJTC_message::MJTC_setMessage(esc_html(__('Prefix has been stored', 'majestic-support')), 'updated');
            return;
        }else{
            update_option('rewrite_rules', '');
            MJTC_message::MJTC_setMessage(esc_html(__('Prefix has not been stored', 'majestic-support')), 'error');
            return;
        }
    }

    function resetAllSlugs() {
        $query = "UPDATE " . majesticsupport::$_db->prefix . "mjtc_support_slug
                    SET slug = defaultslug ";
        if(majesticsupport::$_db->query($query)){
            update_option('rewrite_rules', '');
            MJTC_message::MJTC_setMessage(esc_html(__('Slugs/Slug has been stored', 'majestic-support')), 'updated');
            return;
        }else{
            update_option('rewrite_rules', '');
            MJTC_message::MJTC_setMessage(esc_html(__('Slugs/Slug has been stored', 'majestic-support')), 'updated');
            return;
        }
    }

    function getOptionsForEditSlug() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-options-for-edit-slug') ) {
            die( 'Security check Failed' );
        }
        $slug = MJTC_request::MJTC_getVar('slug');
        $html = '<span class="userpopup-top">
                    <span id="userpopup-heading" class="userpopup-heading" >' . esc_html(__("Edit",'majestic-support'))." ". esc_html(__("Slug",'majestic-support')) . '</span>
                        <img alt="'. esc_html(__("Close",'majestic-support')).'" onClick="closePopup();" class="userpopup-close" src="'.esc_url(MJTC_PLUGIN_URL).'includes/images/close-icon-white.png" />
                    </span>';
        $html .= '<div class="userpopup-search">
                    <div class="popup-field-title">' . esc_html(__('Slug','majestic-support')).' '. esc_html(__('Name','majestic-support')) . ' <span style="color: red;"> *</span></div>
                         <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_text('slugedit', isset($slug) ? MJTC_majesticsupportphplib::MJTC_trim($slug) : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                    </div>';
        $html .='<div class="popup-act-btn-wrp">
                    ' . wp_kses(MJTC_formfield::MJTC_button('save', esc_html(__('Save', 'majestic-support')), array('class' => 'button savebutton popup-act-btn','onClick'=>'getFieldValue();')), MJTC_ALLOWED_TAGS);
        $html .='</div>';
        $html = MJTC_majesticsupportphplib::MJTC_htmlentities($html);
        return json_encode($html);
    }

    function getDefaultSlugFromSlug($layout) {
        $query = "SELECT  defaultslug FROM `".majesticsupport::$_db->prefix."mjtc_support_slug` WHERE defaultslug = '".esc_sql($layout)."'";
        $val = majesticsupport::$_db->get_var($query);
        return sanitize_title($val);
    }

    function getSlugFromFileName($layout,$module) {
        $query = "SELECT slug FROM `".majesticsupport::$_db->prefix."mjtc_support_slug` WHERE filename = '".esc_sql($layout)."'";
        $val = majesticsupport::$_db->get_var($query);
        return $val;
    }

    function getSlugString($home_page = 0) {
        global $wp_rewrite;
        $rules = json_encode($wp_rewrite->rules);
        $query = "SELECT slug AS value FROM `".majesticsupport::$_db->prefix."mjtc_support_slug`";
        $val = majesticsupport::$_db->get_results($query);
        $string = '';
        $bstring = '';
        //$rules = json_encode($rules);
        $prefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('slug_prefix');
        $homeprefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('home_slug_prefix');
        foreach ($val as $slug) {
            if($home_page == 1){
                $slug->value = $homeprefix.$slug->value;
            }
            if(MJTC_majesticsupportphplib::MJTC_strpos($rules,$slug->value) === false){
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
        $slug_prefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('slug_prefix');
        $homeprefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('home_slug_prefix');
        $rules = json_encode($wp_rewrite->rules);
        $query = "SELECT slug AS value FROM `".majesticsupport::$_db->prefix."mjtc_support_slug`";
        $val = majesticsupport::$_db->get_results($query);
        $string = array();
        $bstring = '';
        foreach ($val as $slug) {
            $slug->value = $homeprefix.$slug->value;
            $string[] = $bstring.$slug->value;
            $bstring = '/';
        }
        return $string;
    }

    function getAdminSearchFormDataSlug(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'slug') ) {
            die( 'Security check Failed' );
        }
        $ms_search_array = array();
        $ms_search_array['slug'] = MJTC_request::MJTC_getVar('slug');
        $ms_search_array['search_from_slug'] = 1;
        return $ms_search_array;
    }

}

?>

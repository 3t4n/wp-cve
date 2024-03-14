<?php

/**
 * @package JS Jobs Manager
 * @version 2.0.1
 */
/*
  Plugin Name: JS Jobs Manager
  Plugin URI: http://www.joomsky.com
  Description: JS Job Manager is Word Press best job board plugin. It is easy to use and highly configurable. It fully accommodates job seekers and employers.
  Author: JoomSky
  Version: 2.0.1
  Text Domain: js-jobs
  Author URI: http://www.joomsky.com
 */

if (!defined('ABSPATH'))
    die('Restricted Access');

class jsjobs {

    public static $_path;
    public static $_pluginpath;
    public static $_data; /* data[0] for list , data[1] for total paginition ,data[2] fieldsorderring , data[3] userfield for form , data[4] for reply , data[5] for ticket history  , data[6] for internal notes  , data[7] for ban email  , data['ticket_attachment'] for attachment */
    public static $_pageid;
    public static $_db;
    public static $_configuration;
    public static $_sorton;
    public static $_sortorder;
    public static $_ordering;
    public static $_sortlinks;
    public static $_msg;
    public static $_error_flag;
    public static $_error_flag_message;
    public static $_currentversion;
    public static $_error_flag_message_for;
    public static $_error_flag_message_for_link;
    public static $_error_flag_message_for_link_text;
    public static $_error_flag_message_register_for;
    public static $theme_chk;
    public static $_search;
    public static $_captcha;
    public static $_jsjobsession;

    function __construct() {
        self::includes();
        //  self::registeractions();
        self::$_path = plugin_dir_path(__FILE__);
        self::$_pluginpath = plugins_url('/', __FILE__);
        self::$_data = array();
        self::$_error_flag = null;
        self::$_error_flag_message = null;
        self::$_currentversion = '201';
        self::$_jsjobsession = JSJOBSincluder::getObjectClass('wpjobsession');
        global $wpdb;
        self::$_db = $wpdb;
        JSJOBSincluder::getJSModel('configuration')->getConfiguration();
        register_activation_hook(__FILE__, array($this, 'jsjobs_activate'));
        register_deactivation_hook(__FILE__, array($this, 'jsjobs_deactivate'));
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_action('template_redirect', array($this, 'printResume'), 5); // Only for the print resume in wordpress
        add_action('template_redirect', array($this, 'pdf'), 5); // Only for the pdf in wordpress
        add_action('admin_init', array($this, 'jsjobs_activation_redirect'));//for post installation screens
        add_action('jsjobs_cronjobs_action', array($this,'jsjobs_cronjobs'));

        // search form cookies data
        add_action('admin_init', array($this,'jsjob_handle_search_form_data'));
        add_action('admin_init', array($this,'jsjob_handle_delete_cookies'));
        add_action('init', array($this,'jsjob_handle_search_form_data'));
        add_action( 'jsjob_delete_expire_session_data', array($this , 'jsjob_delete_expire_session_data') );
        add_action('wp_enqueue_scripts', array($this , 'js_for_google_map')); // add at front side
        add_action('admin_enqueue_scripts', array($this , 'js_for_google_map')); // to add at admin side
        if( !wp_next_scheduled( 'jsjob_delete_expire_session_data' ) ) {
            // Schedule the event
            wp_schedule_event( time(), 'daily', 'jsjob_delete_expire_session_data' );
        }
        $theme_chk = 0;
        $theme = get_option( 'template' );
        if($theme == 'job-manager'){
            $theme_chk = 1;
        }
        if($theme == 'job-hub'){
            $theme_chk = 2;// 2 for job hub
            define('JOB_MANAGER_IMAGE', get_template_directory_uri() .'/images');
            // this code id intilizing job_manager_options for job hub so that code is not duplicated.
            global $job_hub_options;
            global $job_manager_options;
            $job_manager_options = $job_hub_options;

        }
        self::$theme_chk = $theme_chk;
        // deactivate new block editor
        add_action( 'after_setup_theme', array($this , 'phi_theme_support'), 10, 2 );
        // job_manager_options global varible is intlized at the bottom of this file.
        add_filter('safe_style_css', array($this,'jsjobs_safe_style_css'));
        // If seo plugin is activated
        if (is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ){
            add_filter( 'aioseo_disable_shortcode_parsing', '__return_true' );
        }
	}
    function phi_theme_support() {
        remove_theme_support( 'widgets-block-editor' );
    }

    function js_for_google_map() {
        wp_enqueue_script("jsjobs-googlaChart","https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}");
    }

    function jsjobs_activation_redirect(){
        if (get_option('jsjobs_do_activation_redirect') == true) {
            update_option('jsjobs_do_activation_redirect',false);
            exit(wp_redirect(admin_url('admin.php?page=jsjobs_postinstallation&jsjobslt=stepone')));
        }
    }

    function printResume() {
        $printResume =JSJOBSrequest::getVar("jsjobslt");
        if ($printResume == 'printresume') {
            $resumeid = JSJOBSrequest::getVar('jsjobsid');
            $issocial = JSJOBSrequest::getVar('issocial');
            if ($issocial == 1) {
                jsjobs::$_data['socialprofileid'] = $resumeid;
                jsjobs::$_data['socialprofile'] = true;
            } else {
                JSJOBSincluder::getJSModel('resume')->getResumebyId($resumeid);
            }
            if(jsjobs::$theme_chk == 2){
                wp_enqueue_style( 'jsjb-jsjobs', JOB_HUB_CSS . '/jsjobs.css', false, false, false );// js jobs pages css
            }
            jsjobs::addStyleSheets();
            JSJOBSincluder::include_file('viewresume', 'resume');
            exit();
        }
    }

    function pdf() {
        $pdf =JSJOBSrequest::getVar("jsjobslt");
        if ($pdf == 'pdf') {
            $resumeid = JSJOBSrequest::getVar('jsjobsid');
            if (!$resumeid) {
                $profileid = JSJOBSrequest::getVar('jsscid');
                jsjobs::$_data['socialprofilepdf'] = true;
            } else {
                JSJOBSincluder::getJSModel('resume')->getResumeById($resumeid);
            }
            JSJOBSincluder::include_file('pdf', 'resume');
            exit();
        }
    }

    function jsjobs_activate() {
        include_once 'includes/activation.php';
        JSJOBSactivation::jsjobs_activate();
	add_option('jsjobs_do_activation_redirect', true);
    }

    function jsjobs_deactivate() {
        include_once 'includes/deactivation.php';
        JSJOBSdeactivation::jsjobs_deactivate();
    }

    /*
     * Include the required files
     */

    function includes() {
        // php 8.1 issues
        require_once 'includes/jsjobslib.php';
        if (is_admin()) {
            include_once 'includes/jsjobsadmin.php';
        }
        include_once 'includes/jsjobs-hooks.php';
        include_once 'includes/captcha.php';
        include_once 'includes/recaptchalib.php';
        include_once 'includes/layout.php';
        include_once 'includes/pagination.php';
        include_once 'includes/includer.php';
        include_once 'includes/formfield.php';
        include_once 'includes/request.php';
        include_once 'includes/formhandler.php';
        include_once 'includes/ajax.php';
        require_once 'includes/constants.php';
        require_once 'includes/messages.php';
        require_once 'includes/jsjobsdb.php';
        include_once 'includes/shortcodes.php';
        include_once 'includes/paramregister.php';
        include_once 'includes/breadcrumbs.php';
        include_once 'includes/dashboardapi.php';
        // Widgets
        include_once 'includes/widgets/searchjobs.php';
    }

    /*
     * Localization
     */

    public function load_plugin_textdomain() {
        load_plugin_textdomain('js-jobs', false, jsjobslib::jsjobs_dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /*
     * function for the Style Sheets
     */

    static function addStyleSheets() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('jsjob-bootstrap', JSJOBS_PLUGIN_URL . 'includes/css/bootstrap.min.css');
        wp_enqueue_style('jsjob-tokeninput', JSJOBS_PLUGIN_URL . 'includes/css/tokeninput.css');
        wp_enqueue_script('jsjob-commonjs', JSJOBS_PLUGIN_URL . 'includes/js/common.js');
        if(jsjobs::$theme_chk == 1){
            $class_prefix = 'jsjb-jm';
        }else{
            $class_prefix = 'jsjb-jh';
        }
        // vars are defined to support job hub and job manager with minimum changes to plugin code.
        wp_localize_script('jsjob-commonjs', 'common', array('ajaxurl' => admin_url('admin-ajax.php'),'insufficient_credits' => __('You have insufficient credits, you can not perform this action','js-jobs'),'theme_chk_prefix'=> $class_prefix,'theme_chk_number'=>jsjobs::$theme_chk, 'wp_jm_nonce' => wp_create_nonce('wp_js_jm_nonce_check') ));
        wp_enqueue_script('jsjob-formvalidator', JSJOBS_PLUGIN_URL . 'includes/js/jquery.form-validator.js');
        if(jsjobs::$theme_chk == 0 || is_admin()){
            wp_enqueue_script('jsjob-tokeninput', JSJOBS_PLUGIN_URL . 'includes/js/jquery.tokeninput.js');
        }
        wp_enqueue_script('chosen', JSJOBS_PLUGIN_URL . 'includes/js/chosen/chosen.jquery.min.js');
    }

    /*
     * function to get the pageid from the wpoptions
     */

    public static function getPageid() {
        if(jsjobs::$_pageid != ''){
            return jsjobs::$_pageid;
        }else{
            $pageid = JSJOBSrequest::getVar('page_id','GET');
            if($pageid){
                return $pageid;
            }else{ // in case of categories popup
                $module = JSJOBSrequest::getVar('jsjobsme');
                if($module == 'category'){
                    $pageid = JSJOBSrequest::getVar('page_id','POST');
                    if($pageid)
                        return $pageid;
                }
            }
            $id = 0;
            $pageid = jsjobs::$_db->get_var("SELECT configvalue FROM `".jsjobs::$_db->prefix."js_job_config` WHERE configname = 'default_pageid'");
            if ($pageid)
                $id = $pageid;
            return $id;
        }
    }

    public static function setPageID($id) {
        jsjobs::$_pageid = $id;
    }

    /*
     * function to parse the spaces in given string
     */

    public static function parseSpaces($string) {
        return jsjobslib::jsjobs_str_replace('%20', ' ', $string);
    }

    public static function tagfillin($string) {
        return jsjobslib::jsjobs_str_replace(' ', '_', $string);
    }

    public static function tagfillout($string) {
        return jsjobslib::jsjobs_str_replace('_', ' ', $string);
    }

    static function sanitizeData($data){
        if($data == null){
            return $data;
        }
        if(is_array($data)){
            return map_deep( $data, 'sanitize_text_field' );
        }else{
            return sanitize_text_field( $data );
        }
    }

    static function makeUrl($args = array()){
        global $wp_rewrite;

        $pageid = JSJOBSrequest::getVar('jsjobspageid');

        if(is_numeric($pageid)){
            $permalink = get_the_permalink($pageid);
        }else{
            if(isset($args['jsjobspageid']) && is_numeric($args['jsjobspageid'])){
                $permalink = get_the_permalink($args['jsjobspageid']);
            }else{
                $permalink = get_the_permalink();
            }
        }
        if (!$wp_rewrite->using_permalinks()){
            if(!jsjobslib::jsjobs_strstr($permalink, 'page_id') && !jsjobslib::jsjobs_strstr($permalink, '?p=')) {
                $page['page_id'] = get_option('page_on_front');
                $args = $page + $args;
            }
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }

        if(isset($args['jsjobsme']) && isset($args['jsjobslt'])){
            // Get the original query parts
            $redirect = @parse_url($permalink);
            if (!isset($redirect['query']))
                $redirect['query'] = '';

            if(jsjobslib::jsjobs_strstr($permalink, '?')){ // if variable exist
                $redirect_array = jsjobslib::jsjobs_explode('?', $permalink);
                $_redirect = $redirect_array[0];
            }else{
                $_redirect = $permalink;
            }

            if($_redirect[jsjobslib::jsjobs_strlen($_redirect) - 1] == '/'){
                $_redirect = jsjobslib::jsjobs_substr($_redirect, 0, jsjobslib::jsjobs_strlen($_redirect) - 1);
            }
            // If is layout
            $changename = false;
            if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/js-vehicle-manager.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-support-ticket/js-support-ticket.php')){
                $changename = true;
            }

            if (isset($args['jsjobslt'])) {
                $layout = '';
                $layout = JSJOBSincluder::getJSModel('slug')->getSlugFromFileName($args['jsjobslt'],$args['jsjobsme']);
                global $wp_rewrite;
                $slug_prefix = JSJOBSincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
                if($_redirect == site_url()){
                    $layout = $slug_prefix.$layout;
                }
                /*
                else{
                    if($_redirect == site_url()){
                        $layout = $slug_prefix.$layout;
                    }else{
                        $rules = json_encode($wp_rewrite->rules);
                        if(jsjobslib::jsjobs_strpos($rules,$slug_prefix.$layout) !== false){
                            $layout = $slug_prefix. $layout;
                        }
                    }
                }
                */
                $_redirect .= '/' . $layout;
            }
            // If is jobid
            if (isset($args['jobid'])) {
                $_redirect .= '/' . $args['jobid'];
            }
            // If is list
            if (isset($args['list'])) {
                $_redirect .= '/' . $args['list'];
            }
            // If is jsjobs_id
            if (isset($args['jsjobsid'])) {
                $jsjobs_id = $args['jsjobsid'];
                //$layout = jsjobslib::jsjobs_str_replace('jm-', '', $layout);
                if($args['jsjobslt'] == 'viewjob'){
                    $job_seo = JSJOBSincluder::getJSModel('configuration')->getConfigValue('job_seo');
                    if(!empty($job_seo)){
                        $job_seo = JSJOBSincluder::getJSModel('job')->makeJobSeo($job_seo , $jsjobs_id);
                        if($job_seo != ''){
                            $id = JSJOBSincluder::getJSModel('common')->parseID($jsjobs_id);
                            $jsjobs_id = $job_seo.'-'.$id;
                        }
                    }
                }elseif($args['jsjobslt'] == 'viewcompany'){
                    $company_seo = JSJOBSincluder::getJSModel('configuration')->getConfigValue('company_seo');
                    if(!empty($company_seo)){
                        $company_seo = JSJOBSincluder::getJSModel('company')->makeCompanySeo($company_seo , $jsjobs_id);
                        if($company_seo != ''){
                            $id = JSJOBSincluder::getJSModel('common')->parseID($jsjobs_id);
                            $jsjobs_id = $company_seo.'-'.$id;
                        }
                    }
                }elseif($args['jsjobslt'] == 'viewresume'){
                    $resume_seo = JSJOBSincluder::getJSModel('configuration')->getConfigValue('resume_seo');
                    if(!empty($resume_seo)){
                        $resume_seo = JSJOBSincluder::getJSModel('resume')->makeResumeSeo($resume_seo , $jsjobs_id);
                        if($resume_seo != ''){
                            $id = JSJOBSincluder::getJSModel('common')->parseID($jsjobs_id);
                            $jsjobs_id = $resume_seo.'-'.$id;
                        }
                    }
                }

                $_redirect .= '/' . $jsjobs_id;
            }

            // If is ta
            if (isset($args['ta'])) {
                $_redirect .= '/' . $args['ta'];
            }
            // If is ta
            if (isset($args['viewtype'])) { // resume list or grid view
                $_redirect .= '/vt-' . $args['viewtype'];
            }
            // If is jsscid
            if (isset($args['jsscid'])) {
                $_redirect .= '/sc-' . $args['jsscid'];
            }
            // If is category
            if (isset($args['category'])) {
                $category = $args['category'];
                $array = jsjobslib::jsjobs_explode('-', $category);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_10' . $id;
                $_redirect .= '/' . $finalstring;
            }
            // If is tags
            if (isset($args['tags'])) {
                $tags = $args['tags'];
                $finalstring = 'tags' . '_' . $tags;
                $_redirect .= '/' . $finalstring;
            }
            // If is jobtype
            if (isset($args['jobtype'])) {
                $jobtype = $args['jobtype'];
                $array = jsjobslib::jsjobs_explode('-', $jobtype);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_11' . $id;
                $_redirect .= '/' . $finalstring;
            }
            // If is company
            if (isset($args['company'])) {
                $company = $args['company'];
                $array = jsjobslib::jsjobs_explode('-', $company);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_12' . $id;
                $_redirect .='/' . $finalstring;
            }
            // If is search
            if (isset($args['search'])) {
                $search = $args['search'];
                $array = jsjobslib::jsjobs_explode('-', $search);
                $count = count($array);
                $id = $array[$count - 1];
                unset($array[$count - 1]);
                $string = implode("-", $array);
                $finalstring = $string . '_13' . $id;
                $_redirect .='/' . $finalstring;
            }
            // If is city
            if (isset($args['city'])) {
                $alias = JSJOBSincluder::getJSModel('city')->getCityNamebyId($args['city']);
                $alias = JSJOBSincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_14' . $args['city'];
            }

            // If is sortby
            if (isset($args['sortby'])) {
                //$_redirect .= '/sortby-' . $args['sortby'];
                $_redirect .= '/' . $args['sortby'];
            }
            // login redirect
            if (isset($args['jsjobsredirecturl'])) {
                //$_redirect .= '/sortby-' . $args['sortby'];
                $_redirect .= '/' . $args['jsjobsredirecturl'];
            }

            return $_redirect;
        }else{ // incase of form
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }

    }

    static function bjencode($array){
        return jsjobslib::jsjobs_safe_encoding(json_encode($array));
    }

    static function bjdecode($array){
        return json_decode(jsjobslib::jsjobs_safe_decoding($array));
    }

    function jsjobs_safe_style_css(){
        $styles[] = 'display';
        $styles[] = 'color';
        $styles[] = 'width';
        $styles[] = 'max-width';
        $styles[] = 'min-width';
        $styles[] = 'height';
        $styles[] = 'min-height';
        $styles[] = 'max-height';
        $styles[] = 'background-color';
        $styles[] = 'border';
        $styles[] = 'border-bottom';
        $styles[] = 'border-top';
        $styles[] = 'border-left';
        $styles[] = 'border-right';
        $styles[] = 'border-color';
        $styles[] = 'padding';
        $styles[] = 'padding-top';
        $styles[] = 'padding-bottom';
        $styles[] = 'padding-left';
        $styles[] = 'padding-right';
        $styles[] = 'margin';
        $styles[] = 'margin-top';
        $styles[] = 'margin-bottom';
        $styles[] = 'margin-left';
        $styles[] = 'margin-right';
        $styles[] = 'background';
        $styles[] = 'font-weight';
        $styles[] = 'font-size';
        $styles[] = 'text-align';
        $styles[] = 'text-decoration';
        $styles[] = 'text-transform';
        $styles[] = 'line-height';
        $styles[] = 'visibility';
        $styles[] = 'cellspacing';
        $styles[] = 'data-id';
        $styles[] = 'cursor';
        $styles[] = 'vertical-align';
        $styles[] = 'float';
        $styles[] = 'position';
        $styles[] = 'left';
        $styles[] = 'right';
        $styles[] = 'bottom';
        $styles[] = 'top';
        $styles[] = 'z-index';
        $styles[] = 'overflow';
        return $styles;
    }

    function jsjob_handle_search_form_data(){
        JSJOBSincluder::getObjectClass('handlesearchcookies');
    }

    function jsjob_handle_delete_cookies(){

        if(isset($_COOKIE['jsjob_addon_return_data'])){
            jsjobslib::jsjobs_setcookie('jsjob_addon_return_data' , '' , time() - 3600, COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                jsjobslib::jsjobs_setcookie('jsjob_addon_return_data' , '' , time() - 3600, SITECOOKIEPATH);
            }
        }

        if(isset($_COOKIE['jsjob_addon_install_data'])){
            jsjobslib::jsjobs_setcookie('jsjob_addon_install_data' , '' , time() - 3600);
        }
    }

    public static function removeusersearchcookies(){
        if(isset($_COOKIE['jsjob_jsjobs_search_data'])){
            jsjobslib::jsjobs_setcookie('jsjob_jsjobs_search_data' , '' , time() - 3600 , COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                jsjobslib::jsjobs_setcookie('jsjob_jsjobs_search_data' , '' , time() - 3600 , SITECOOKIEPATH);
            }
        }
    }

    public static function setusersearchcookies($cookiesval , $jsjp_search_array){
        if(!$cookiesval)
            return false;
        $data = json_encode( $jsjp_search_array );
        $data = jsjobslib::jsjobs_safe_encoding($data);
        jsjobslib::jsjobs_setcookie('jsjob_jsjobs_search_data' , $data , 0 , COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            jsjobslib::jsjobs_setcookie('jsjob_jsjobs_search_data' , $data , 0 , SITECOOKIEPATH);
        }
    }

    function jsjob_delete_expire_session_data(){
        global $wpdb;
        $wpdb->query('DELETE  FROM '.$wpdb->prefix.'js_job_jsjobsessiondata WHERE sessionexpire < "'. time() .'"');
    }

}

$jsjobs = new jsjobs();
// lost your password link hook
add_action( 'login_form_bottom', 'jsjobaddLostPasswordLink' );
function jsjobaddLostPasswordLink() {
    if(jsjobs::$theme_chk == 1){
            $class_prefix = 'jsjb-jm';
    }else{
        $class_prefix = 'jsjb-jh';
    }
   return '<a class="'.$class_prefix.'-lost-password" href="'.site_url().'/wp-login.php?action=lostpassword">'. __('Lost your password','js-jobs') .'?</a>';
}

add_action('init', 'jsjobs_custom_init_session', 1);

function jsjobs_custom_init_session() {

    // if(is_user_logged_in()){
        if(isset($_COOKIE['jsjobs_apply_visitor'])){
            $layout = JSJOBSrequest::getVar('jsjobslt');
            if($layout != null && $layout != 'addresume'){ // reset the session id
                setcookie('jsjobs_apply_visitor' , '' , time() - 3600 , COOKIEPATH);
                if ( SITECOOKIEPATH != COOKIEPATH ){
                    setcookie('jsjobs_apply_visitor' , '' , time() - 3600 , SITECOOKIEPATH);
                }
            }
        }
        if(isset($_COOKIE['wp-jsjobs'])){
            $resumeid = sanitize_key(json_decode(jsjobslib::jsjobs_safe_decoding($_COOKIE['wp-jsjobs']),true));
            if(isset($resumeid['resumeid'])){
                $layout = JSJOBSrequest::getVar('jsjobslt');
                if($layout != null && $layout != 'addresume'){ // reset the session id
                    jsjobslib::jsjobs_setcookie('wp-jsjobs' , '' , time() - 3600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                        jsjobslib::jsjobs_setcookie('wp-jsjobs' , '' , time() - 3600 , SITECOOKIEPATH);
                    }
                }
            }
        }
    // }
}

function jsjobs_register_plugin_styles(){
    wp_enqueue_script('jquery');
    include_once 'includes/css/style_color.php';
    wp_enqueue_style('jsjob-jobseeker-style', JSJOBS_PLUGIN_URL . 'includes/css/jobseekercp.css');
    wp_enqueue_style('jsjob-employer-style', JSJOBS_PLUGIN_URL . 'includes/css/employercp.css');
    wp_enqueue_style('jsjob-style', JSJOBS_PLUGIN_URL . 'includes/css/style.css');
    wp_enqueue_style('jsjob-style-tablet', JSJOBS_PLUGIN_URL . 'includes/css/style_tablet.css',array(),'','(min-width: 481px) and (max-width: 780px)');
    wp_enqueue_style('jsjob-style-mobile-landscape', JSJOBS_PLUGIN_URL . 'includes/css/style_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 650px)');
    wp_enqueue_style('jsjob-style-mobile', JSJOBS_PLUGIN_URL . 'includes/css/style_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jsjob-chosen-style', JSJOBS_PLUGIN_URL . 'includes/js/chosen/chosen.min.css');
    if (is_rtl()) {
        wp_register_style('jsjob-style-rtl', JSJOBS_PLUGIN_URL . 'includes/css/stylertl.css');
        wp_enqueue_style('jsjob-style-rtl');
    }
    wp_enqueue_style('jsjobs-css-ie', JSJOBS_PLUGIN_URL . 'includes/css/jsjobs-ie.css');
    wp_style_add_data( 'jsjobs-css-ie', 'conditional', 'IE' );

}

add_action( 'wp_enqueue_scripts', 'jsjobs_register_plugin_styles' );

function jsjobs_admin_register_plugin_styles() {
    wp_enqueue_style('jsjob-admin-desktop-css', JSJOBS_PLUGIN_URL . 'includes/css/jsjobsadmin_desktop.css',array(),'','all');
    wp_enqueue_style('jsjob-admin-mobile-css', JSJOBS_PLUGIN_URL . 'includes/css/jsjobsadmin_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jsjob-admin-mobile-landscape-css', JSJOBS_PLUGIN_URL . 'includes/css/jsjobsadmin_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 660px)');
    wp_enqueue_style('jsjob-admin-tablet-css', JSJOBS_PLUGIN_URL . 'includes/css/jsjobsadmin_tablet.css',array(),'','(min-width: 481px) and (max-width: 780px)');
    if (is_rtl()) {
        wp_register_style('jsjob-admincss-rtl', JSJOBS_PLUGIN_URL . 'includes/css/admincssrtl.css');
        wp_enqueue_style('jsjob-admincss-rtl');
    }
}
add_action( 'admin_enqueue_scripts', 'jsjobs_admin_register_plugin_styles' );


    // remove a template redirect from within a custom plugin.
    add_action( 'template_redirect', 'intilize_job_manager_varibales', 5 );
    function intilize_job_manager_varibales(){

        if(jsjobs::$theme_chk == 2){
            // this code id intilizing job_manager_options for job hub so that code is not duplicated.
            global $job_hub_options;
            global $job_manager_options;
            $job_manager_options = $job_hub_options;
        }
    }

    add_action( 'wp_head', 'add_meta_tags' , 10 );
    function add_meta_tags(){
        $layout = JSJOBSrequest::getVar('jsjobslt');
        if($layout == 'viewjob' || $layout == 'viewresume'){
            $upid = JSJOBSrequest::getVar('jsjobsid');
            $id  = JSJOBSincluder::getJSModel('common')->parseID($upid);
            if(is_numeric($id) && $id > 0){
                if($layout == 'viewjob'){
                    $query = "SELECT job.tags,job.metakeywords
                        FROM `" . jsjobs::$_db->prefix . "js_job_jobs` AS job
                        WHERE job.id = " . $id;
                    $data = jsjobsdb::get_row($query);
                    if($data != ''){
                        if($data->metakeywords != ''){
                            echo '<meta name="keywords"  content="'.esc_attr($data->metakeywords).'">';
                        }
                        if($data->tags != ''){
                            echo '<meta name="tags"  content="'.esc_attr($data->tags).'">';
                        }
                    }
                }else{
                    $query = "SELECT resume.tags,resume.keywords
                        FROM `" . jsjobs::$_db->prefix . "js_job_resume` AS resume
                        WHERE resume.id = " . $id;
                    $data = jsjobsdb::get_row($query);
                    if($data != ''){
                        if($data->keywords != ''){
                            echo '<meta name="keywords"  content="'.esc_attr($data->keywords).'">';
                        }
                        if($data->tags != ''){
                            echo '<meta name="tags"  content="'.esc_attr($data->tags).'">';
                        }
                    }
                }

            }
        }

    return;
    }

add_filter( 'login_redirect', 'jsjobs_login_redirect', 10, 3 );
function jsjobs_login_redirect($redirect_to, $request, $user){
   //is there a user to check?
   global $user;
   if ( isset( $user->roles ) && is_array( $user->roles ) ) {
       //check for admins
       if ( in_array( 'administrator', $user->roles ) ) {
           return $redirect_to;
       } else {
           $query = "SELECT roleid FROM `".jsjobs::$_db->prefix."js_job_users` WHERE uid = " . $user->id;
           $roleid = jsjobsdb::get_var($query);
           $url = '/';
           if($roleid == 2){
               $url = jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid(),'jsjobsme'=>'jobseeker','jsjobslt'=>'controlpanel'));
           }elseif($roleid == 1){
               $url = jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid(),'jsjobsme'=>'employer','jsjobslt'=>'controlpanel'));
           }
           return $url;
       }
   } else {
       return $redirect_to;
   }
}

function checkJSJOBSPluginInfo($slug){
    if(file_exists(WP_PLUGIN_DIR . '/'.$slug) && is_plugin_active($slug)){
        $text = __("Activated","js-jobs");
        $disabled = "disabled";
        $class = "js-btn-activated";
        $availability = "-1";
    }else if(file_exists(WP_PLUGIN_DIR . '/'.$slug) && !is_plugin_active($slug)){
        $text = __("Active Now","js-jobs");
        $disabled = "";
        $class = "js-btn-green js-btn-active-now";
        $availability = "1";
    }else if(!file_exists(WP_PLUGIN_DIR . '/'.$slug)){
        $text = __("Install Now","js-jobs");
        $disabled = "";
        $class = "js-btn-install-now";
        $availability = "0";
    }
    return array("text" => $text, "disabled" => $disabled, "class" => $class, "availability" => $availability);
}

?>

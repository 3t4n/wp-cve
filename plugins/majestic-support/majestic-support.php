<?php

/**
 * @package Majestic Support
 * @author Ahmad Bilal
 * @version 1.0.1
 */
/*
  Plugin Name: Majestic Support
  Plugin URI: https://www.majesticsupport.com
  Description: Majestic Support is a trusted open source ticket system. Majestic Support is a simple, easy to use, web-based customer support system. User can create ticket from front-end. Majestic Support comes packed with lot features than most of the expensive(and complex) support ticket system on market. Majestic Support provide you best industry Majestic Support system.
  Author: Ahmad Bilal
  Version: 1.0.1
  Text Domain: majestic-support
  
 */

if (!defined('ABSPATH'))
    die('Restricted Access');

class majesticsupport {

    public static $_path;
    public static $_pluginpath;
    public static $_data; /* data[0] for list , data[1] for total paginition ,data[2] userfieldsforview , data[3] userfield for form , data[4] for reply , data[5] for ticket history  , data[6] for internal notes  , data[7] for ban email  , data['ticket_attachment'] for attachment */
    public static $_pageid;
    public static $_db;
    public static $_config;
    public static $_sorton;
    public static $_sortorder;
    public static $_ordering;
    public static $_sortlinks;
    public static $_msg;
    public static $_wpprefixforuser;
    public static $_colors;
    public static $_active_addons;
    public static $_addon_query;
    public static $_currentversion;
    public static $_search;
    public static $_captcha;
    public static $_mjtcsession;


    function __construct() {
        // php 8.1 issues
        require_once 'includes/majesticsupportphplib.php';
        // to check what addons are active and create an array.
        $plugin_array = get_option('active_plugins');
        $addon_array = array();
        foreach ($plugin_array as $key => $value) {
            $plugin_name = pathinfo($value, PATHINFO_FILENAME);
            if(MJTC_majesticsupportphplib::MJTC_strstr($plugin_name, 'majestic-support-')){
                if($plugin_name != ''){
                    $addon_array[] = MJTC_majesticsupportphplib::MJTC_str_replace('majestic-support-', '', $plugin_name);
                }
            }
        }
        self::$_active_addons = $addon_array;
        // above code is its right place
        self::includes();
        self::mjtcLoadWpCoreFiles();
        self::registeractions();
        self::$_path = plugin_dir_path(__FILE__);
        self::$_pluginpath = plugins_url('/', __FILE__);
        self::$_data = array();
        self::$_search = array();
        self::$_captcha = array();
        self::$_currentversion = '101';
        self::$_addon_query = array('select'=>'','join'=>'','where'=>'');
        self::$_mjtcsession = MJTC_includer::MJTC_getObjectClass('wphdsession');
        global $wpdb;
        self::$_db = $wpdb;
        if(is_multisite()) {
            self::$_wpprefixforuser = $wpdb->base_prefix;
        }else{
            self::$_wpprefixforuser = self::$_db->prefix;
        }
        add_filter('cron_schedules',array($this,'majesticsupport_customschedules'));
        add_filter('the_content', array($this, 'checkRequest'));
        MJTC_includer::MJTC_getModel('configuration')->getConfiguration();
        register_activation_hook(__FILE__, array($this, 'MJTC_activate'));
        register_deactivation_hook(__FILE__, array($this, 'MJTC_deactivate'));
        if(version_compare(get_bloginfo('version'),'5.1', '>=')){ //for wp version >= 5.1
            add_action('wp_insert_site', array($this, 'majesticsupport_new_site')); //when new site is added in multisite
        }else{ //for wp version < 5.1
            add_action('wpmu_new_blog', array($this, 'majesticsupport_new_blog'), 10, 6);
        }
        add_filter('wpmu_drop_tables', array($this, 'majesticsupport_delete_site')); //when site is deleted in multisite

        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_action('majesticsupport_updateticketstatus', array($this,'updateticketstatus'));
        add_action('majesticsupport_checkforaddonsupdate', array($this,'checkforaddonsupdate'));
        if(in_array('actions',majesticsupport::$_active_addons)){
            add_action('template_redirect', array($this, 'printTicket'), 5); // Only for the print ticket in wordpress
        }
        add_action('admin_init', array($this, 'majesticsupport_activation_redirect'));
        add_action( 'wp_footer', array($this,'checkScreenTag') );
        add_action( 'resetnotificationvalues', array($this, 'resetNotificationValues'));
        //for style sheets
        add_action('wp_head', array($this,'ms_register_plugin_styles'));
        add_action('admin_enqueue_scripts', array($this,'ms_admin_register_plugin_styles') );
        add_action('reset_ms_aadon_query', array($this,'reset_ms_aadon_query') );
        add_action('majesticsupport_ticketviaemail', array($this,'ticketviaemail'));// this also handles ticket over due and ticket feedback
        add_action('init', array($this,'ms_handle_public_cronjob'));
        add_action('admin_init', array($this,'ms_handle_search_form_data'));
        add_action('admin_init', array($this,'ms_handle_delete_cookies'));
        add_action('init', array($this,'ms_handle_search_form_data'));
        add_action( 'ms_delete_expire_session_data', array($this , 'mjtc_delete_expire_session_data') );
        add_filter('safe_style_css', array($this,'mjtc_safe_style_css'));
        if( !wp_next_scheduled( 'ms_delete_expire_session_data' ) ) {
            // Schedule the event
            wp_schedule_event( time(), 'daily', 'ms_delete_expire_session_data' );
        }
        add_action( 'upgrader_process_complete', array($this , 'majesticsupport_upgrade_completed'), 10, 2 );
        // If seo plugin is activated
        if (is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ){
            add_filter( 'aioseo_disable_shortcode_parsing', '__return_true' );
        }
    }

    function majesticsupport_upgrade_completed( $upgrader_object, $options ) {
        // The path to our plugin's main file
        $our_plugin = plugin_basename( __FILE__ );
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == $our_plugin ) {
                    // restore colors data
                    $filepath = MJTC_PLUGIN_PATH . 'includes/css/style.php';
                    $filestring = file_get_contents($filepath);
                    $themedata = $this->MJTC_getCurrentTheme();
                    if (!empty($themedata)) {
                        $this->MJTC_replaceString($filestring, 1, $themedata);
                        $this->MJTC_replaceString($filestring, 2, $themedata);
                        $this->MJTC_replaceString($filestring, 3, $themedata);
                        $this->MJTC_replaceString($filestring, 4, $themedata);
                        $this->MJTC_replaceString($filestring, 5, $themedata);
                        $this->MJTC_replaceString($filestring, 6, $themedata);
                        $this->MJTC_replaceString($filestring, 7, $themedata);
                        file_put_contents($filepath, $filestring);
                    }
                    // restore colors data end
                    update_option('ms_currentversion', self::$_currentversion);
                    include_once MJTC_PLUGIN_PATH . 'includes/updates/updates.php';
                    MJTC_updates::MJTC_checkUpdates('101');
                    MJTC_includer::MJTC_getModel('majesticsupport')->updateColorFile();
                }
            }
        }
    }

    function MJTC_replaceString(&$filestring, $colorNo, $data) {
        if (MJTC_majesticsupportphplib::MJTC_strstr($filestring, '$color' . $colorNo)) {
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '$color' . $colorNo);
            $path2 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, ';', $path1);
            $filestring = substr_replace($filestring, '$color' . $colorNo . ' = "' . $data['color' . $colorNo] . '";', $path1, $path2 - $path1 + 1);
        }
    }

    function MJTC_getCurrentTheme() {
        $optiondata = get_option('ms_set_theme_colors');
        $theme = array();
        if (!empty($optiondata)) {
            $filestring = json_decode($optiondata, true);
            $theme['color1'] = $filestring['color1'];
            $theme['color2'] = $filestring['color2'];
            $theme['color3'] = $filestring['color3'];
            $theme['color4'] = $filestring['color4'];
            $theme['color5'] = $filestring['color5'];
            $theme['color6'] = $filestring['color6'];
            $theme['color7'] = $filestring['color7'];
        }                                                    
        return $theme;
    }

    function majesticsupport_customschedules($schedules){
        $schedules['halfhour'] = array(
           'interval' => 1800,
           'display'=> 'Half hour'
        );
       return $schedules;
    }

    function MJTC_activate($network_wide = false) {
        include_once 'includes/activation.php';
        if(function_exists('is_multisite') && is_multisite() && $network_wide){
            global $wpdb;
            $blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogs as $blog_id){
                switch_to_blog( $blog_id );
                MJTC_activation::MJTC_activate();
                restore_current_blog();
            }
        }else{
            MJTC_activation::MJTC_activate();
        }
        wp_schedule_event(time(), 'daily', 'majesticsupport_updateticketstatus');
        add_option('majesticsupport_do_activation_redirect', true);
        wp_schedule_event(time(), 'halfhour', 'majesticsupport_ticketviaemail');// this also handles ticket overdue (bcz of hors configuration)
        wp_schedule_event(time(), 'daily', 'majesticsupport_checkforaddonsupdate');

    }

    function majesticsupport_new_site($new_site){
        $pluginname = plugin_basename(__FILE__);
        if(is_plugin_active_for_network($pluginname)){
            include_once 'includes/activation.php';
            switch_to_blog($new_site->blog_id);
            MJTC_activation::MJTC_activate();
            restore_current_blog();
        }
    }

    function majesticsupport_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta){
        $pluginname = plugin_basename(__FILE__);
        if(is_plugin_active_for_network($pluginname)){
            include_once 'includes/activation.php';
            switch_to_blog($blog_id);
            MJTC_activation::MJTC_activate();
            restore_current_blog();
        }
    }

    function majesticsupport_delete_site($tables){
        include_once 'includes/deactivation.php';
        $tablestodrop = MJTC_deactivation::MJTC_tables_to_drop();
        foreach($tablestodrop as $tablename){
            $tables[] = $tablename;
        }
        return $tables;
    }

    function majesticsupport_activation_redirect(){
        if (get_option('majesticsupport_do_activation_redirect')) {
            delete_option('majesticsupport_do_activation_redirect');
            exit(wp_redirect(admin_url('admin.php?page=majesticsupport_postinstallation&mjslay=stepone')));
        }
    }

    function ms_handle_public_cronjob(){
        $action = MJTC_request::MJTC_getVar('mscron','get',null);
        if ($action) {
            switch ($action) {
                case 'ticketviaemail':
                    do_action('majesticsupport_ticketviaemail');
                    break;
                case 'updateticketstatus':
                    do_action('majesticsupport_updateticketstatus');
                    break;
                case 'checkforaddonsupdate':
                    do_action('majesticsupport_checkforaddonsupdate');
                    break;
            }
            exit();
        }
    }

    function mjtc_safe_style_css(){
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

    function ms_handle_search_form_data(){

        $isadmin = is_admin();
        $mjslay = '';
        if(isset($_REQUEST['mjslay'])){
            $mjslay = majesticsupport::MJTC_sanitizeData($_REQUEST['mjslay']);// MJTC_sanitizeData() function uses wordpress santize functions
        }elseif(isset($_REQUEST['page'])){
            $mjslay = majesticsupport::MJTC_sanitizeData($_REQUEST['page']);// MJTC_sanitizeData() function uses wordpress santize functions
        }elseif(isset($_REQUEST['mjtcslay'])){
            $mjslay = majesticsupport::MJTC_sanitizeData($_REQUEST['mjtcslay']);// MJTC_sanitizeData() function uses wordpress santize functions
        }
        $layoutname = MJTC_majesticsupportphplib::MJTC_explode("majesticsupport_", $mjslay);// admin page has wpjobportal_ prefix
        if(isset($layoutname[1])){
            $mjslay = $layoutname[1];
        }        
        $callfrom = 3;
        if(isset($_REQUEST['MS_form_search']) && $_REQUEST['MS_form_search'] == 'MS_SEARCH'){
            $callfrom = 1;
        }elseif(MJTC_request::MJTC_getVar('pagenum', 'get', null) != null){
            $callfrom = 2;
        }

        $setcookies = false;
        $ticket_search_cookie_data = '';
        $ms_search_array = array();
        switch($mjslay){
            case 'tickets':
            case 'myticket':
            case 'ticket':
            case 'staffmyticket':
                $search_userfields = MJTC_includer::MJTC_getObjectClass('customfields')->userFieldsForSearch(1);
                if($callfrom == 1){
                    if(is_admin()){
                        $ms_search_array = MJTC_includer::MJTC_getModel('ticket')->getAdminTicketSearchFormData($search_userfields);
                    }else{
                        $ms_search_array = MJTC_includer::MJTC_getModel('ticket')->getFrontSideTicketSearchFormData($search_userfields);
                    }
                    $setcookies = true;
                }elseif($callfrom == 2){
                    $ms_search_array = MJTC_includer::MJTC_getModel('ticket')->getCookiesSavedSearchDataTicket($search_userfields);
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                MJTC_includer::MJTC_getModel('ticket')->setSearchVariableForTicket($ms_search_array,$search_userfields);
            break;
            case 'departments':
            case 'department':
                $deptname = (is_admin()) ? 'departmentname' : 'ms-dept';
                if($callfrom == 1){
                    $ms_search_array = MJTC_includer::MJTC_getModel('department')->getAdminDepartmentSearchFormData();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_department'])){
                        $ms_search_array['departmentname'] = $ticket_search_cookie_data['departmentname'];
                        $ms_search_array['pagesize'] = $ticket_search_cookie_data['pagesize'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                // Departments
                majesticsupport::$_search['department']['departmentname'] = isset($ms_search_array['departmentname']) ? $ms_search_array['departmentname'] : null;
                majesticsupport::$_search['department']['pagesize'] = isset($ms_search_array['pagesize']) ? $ms_search_array['pagesize'] : null;
            break;
            case 'erasedatarequests':
                if($callfrom == 1 && is_admin()){
                    $nonce = MJTC_request::MJTC_getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'erase-data-requests') ) {
                        die( 'Security check Failed' );
                    }
                    $ms_search_array = MJTC_includer::MJTC_getModel('gdpr')->getAdminSearchFormDataGDPR();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_gdpr'])){
                        $ms_search_array['email'] = $ticket_search_cookie_data['email'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                // gdpr
                majesticsupport::$_search['gdpr']['email'] = isset($ms_search_array['email']) ? $ms_search_array['email'] : null;
            break;
            case 'priorities':
            case 'priority':
                if($callfrom == 1 && is_admin()){
                    $ms_search_array = MJTC_includer::MJTC_getModel('priority')->getAdminSearchFormDataPriority();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_priority'])){
                        $ms_search_array['title'] = $ticket_search_cookie_data['title'];
                        $ms_search_array['pagesize'] = $ticket_search_cookie_data['pagesize'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                // priority
                majesticsupport::$_search['priority']['title'] = isset($ms_search_array['title']) ? $ms_search_array['title'] : null;
                majesticsupport::$_search['priority']['pagesize'] = isset($ms_search_array['pagesize']) ? $ms_search_array['pagesize'] : null;
            break;
            case 'smartreplies':
            case 'smartreply':
                $title = (is_admin()) ? 'title' : 'ms-title';
                if($callfrom == 1){
                    $nonce = MJTC_request::MJTC_getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'smart-replies') ) {
                        die( 'Security check Failed' );
                    }
                    if (MJTC_request::MJTC_getVar($title) != '') {
                        $ms_search_array[$title] = MJTC_majesticsupportphplib::MJTC_addslashes(MJTC_majesticsupportphplib::MJTC_trim(MJTC_request::MJTC_getVar($title)));
                    } else {
                        $ms_search_array[$title] = '';
                    }
                    $ms_search_array['search_from_smartreply'] = 1;
                    $ms_search_array = MJTC_includer::MJTC_getModel('smartreply')->getAdminSearchFormDataSmartReply();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_smartreply'])){
                        $ms_search_array[$title] = $ticket_search_cookie_data[$title];
                        $ms_search_array['pagesize'] = $ticket_search_cookie_data['pagesize'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                // smartreply
                majesticsupport::$_search['smartreply'][$title] = isset($ms_search_array[$title]) ? $ms_search_array[$title] : null;
                majesticsupport::$_search['smartreply']['pagesize'] = isset($ms_search_array['pagesize']) ? $ms_search_array['pagesize'] : null;
            break;
            case 'slug':
                if($callfrom == 1 && is_admin()){
                    $ms_search_array = MJTC_includer::MJTC_getModel('slug')->getAdminSearchFormDataSlug();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_slug'])){
                        $ms_search_array['slug'] = $ticket_search_cookie_data['slug'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                // system emails
                majesticsupport::$_search['slug']['slug'] = isset($ms_search_array['slug']) ? $ms_search_array['slug'] : null;
            break;
            case 'emails':
            case 'email':
                if($callfrom == 1 && is_admin()){
                    $ms_search_array = MJTC_includer::MJTC_getModel('email')->getAdminSearchFormDataEmails();
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if($ticket_search_cookie_data != '' && isset($ticket_search_cookie_data['search_from_email'])){
                        $ms_search_array['email'] = $ticket_search_cookie_data['email'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                // system emails
                majesticsupport::$_search['email']['email'] = isset($ms_search_array['email']) ? $ms_search_array['email'] : null;
            break;
            case 'departmentreport':
            case 'userreport':
            case 'staffreport':
            case 'departmentdetailreport':
            case 'userdetailreport':
            case 'stafftimereport':
                if($callfrom == 1 && is_admin()){
                    $nonce = MJTC_request::MJTC_getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'reports') ) {
                        die( 'Security check Failed' );
                    }
                    $ms_search_array['date_start'] = MJTC_request::MJTC_getVar('date_start');
                    $ms_search_array['date_end'] = MJTC_request::MJTC_getVar('date_end');
                    $ms_search_array['uid'] = MJTC_request::MJTC_getVar('uid');
                    $ms_search_array['search_from_reports'] = 1;
                    $setcookies = true;
                }elseif($callfrom == 2 && is_admin()){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if(!empty($ticket_search_cookie_data) && isset($ticket_search_cookie_data['search_from_reports'])){
                        $ms_search_array['date_start'] = $ticket_search_cookie_data['date_start'];
                        $ms_search_array['date_end'] = $ticket_search_cookie_data['date_end'];
                        $ms_search_array['uid'] = $ticket_search_cookie_data['uid'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                majesticsupport::$_search['report']['date_start'] = isset($ms_search_array['date_start']) ? $ms_search_array['date_start'] : null;
                majesticsupport::$_search['report']['date_end'] = isset($ms_search_array['date_end']) ? $ms_search_array['date_end'] : null;
                majesticsupport::$_search['report']['uid'] = isset($ms_search_array['uid']) ? $ms_search_array['uid'] : null;
            break;
            case 'staffreports':
                if($callfrom == 1){
                    $ms_search_array['ms-date-start'] = MJTC_request::MJTC_getVar('ms-date-start');
                    $ms_search_array['ms-date-end'] = MJTC_request::MJTC_getVar('ms-date-end');
                    $ms_search_array['search_from_reports_staff'] = 1;
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if(!empty($ticket_search_cookie_data) && isset($ticket_search_cookie_data['search_from_reports_staff'])){
                        $ms_search_array['ms-date-start'] = $ticket_search_cookie_data['ms-date-start'];
                        $ms_search_array['ms-date-end'] = $ticket_search_cookie_data['ms-date-end'];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                majesticsupport::$_search['report']['ms-date-start'] = isset($ms_search_array['ms-date-start']) ? $ms_search_array['ms-date-start'] : null;
                majesticsupport::$_search['report']['ms-date-end'] = isset($ms_search_array['ms-date-end']) ? $ms_search_array['ms-date-end'] : null;
            break;
            case 'admin_staffdetailreport':
            case 'staffdetailreport':
                $start_date = is_admin() ? 'date_start' : 'ms-date-start';
                $end_date = is_admin() ? 'date_end' : 'ms-date-end';
                if($callfrom == 1){
                    $nonce = MJTC_request::MJTC_getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'staff-detail-report') ) {
                        die( 'Security check Failed' );
                    }
                    $ms_search_array[$start_date] = MJTC_request::MJTC_getVar($start_date);
                    $ms_search_array[$end_date] = MJTC_request::MJTC_getVar($end_date);
                    $ms_search_array['search_from_reports_detail'] = 1;
                    $setcookies = true;
                }elseif($callfrom == 2){
                    if(isset($_COOKIE['ms_ticket_search_data'])){
                        $ticket_search_cookie_data = majesticsupport::MJTC_sanitizeData($_COOKIE['ms_ticket_search_data']);// MJTC_sanitizeData() function uses wordpress santize functions
                        $ticket_search_cookie_data = json_decode( MJTC_majesticsupportphplib::MJTC_safe_decoding($ticket_search_cookie_data) , true );
                    }
                    if(!empty($ticket_search_cookie_data) && isset($ticket_search_cookie_data['search_from_reports_detail'])){
                        $ms_search_array[$start_date] = $ticket_search_cookie_data[$start_date];
                        $ms_search_array[$end_date] = $ticket_search_cookie_data[$end_date];
                    }
                }else{
                    majesticsupport::removeusersearchcookies();
                }
                majesticsupport::$_search['report'][$start_date] = isset($ms_search_array[$start_date]) ? $ms_search_array[$start_date] : null;
                majesticsupport::$_search['report'][$end_date] = isset($ms_search_array[$end_date]) ? $ms_search_array[$end_date] : null;
            break;
            case 'ticketdetail':
                $ticketid = MJTC_request::MJTC_getVar('majesticsupportid');
                if (in_array('agent', majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) { //staff
                    if(current_user_can('ms_support_ticket')){
                        $timecookies['ticket_time_start'][$ticketid] = date("Y-m-d h:i:s");
                    }else{
                        majesticsupport::$_data['permission_granted'] = MJTC_includer::MJTC_getModel('ticket')->validateTicketDetailForStaff($ticketid);
                        if (majesticsupport::$_data['permission_granted']) { // validation passed
                            if(in_array('timetracking', majesticsupport::$_active_addons)){
                                $timecookies['ticket_time_start'][$ticketid] = date("Y-m-d h:i:s");
                            }
                        }
                    }
                } else { // user
                    if(current_user_can('ms_support_ticket') || current_user_can('ms_support_ticket_tickets')){
                        if(in_array('timetracking', majesticsupport::$_active_addons)){
                            $timecookies['ticket_time_start'][$ticketid] = date("Y-m-d h:i:s");
                        }
                    }
                }
                if(isset($timecookies['ticket_time_start'][$ticketid])){
                    $val = 'ticket_time_start_'.esc_attr($ticketid);
                    MJTC_majesticsupportphplib::MJTC_setcookie($val , $timecookies['ticket_time_start'][$ticketid] , 0, COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                        MJTC_majesticsupportphplib::MJTC_setcookie('majesticsupport-timetack' , $timecookies , 0, SITECOOKIEPATH);
                    }
                }
            break;
        }

        if($setcookies){
            majesticsupport::setusersearchcookies($setcookies,$ms_search_array);
        }
    }

    function ms_handle_delete_cookies(){

        if(isset($_COOKIE['ms_addon_return_data'])){
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , '' , time() - 3600, COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , '' , time() - 3600, SITECOOKIEPATH);
            }
        }

        if(isset($_COOKIE['ms_addon_install_data'])){
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_install_data' , '' , time() - 3600);
        }
    }

    public static function removeusersearchcookies(){
        if(isset($_COOKIE['ms_ticket_search_data'])){
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_ticket_search_data' , '' , time() - 3600 , COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                MJTC_majesticsupportphplib::MJTC_setcookie('ms_ticket_search_data' , '' , time() - 3600 , SITECOOKIEPATH);
            }
        }
    }

    public static function setusersearchcookies($cookiesval, $ms_search_array){
        if(!$cookiesval)
            return false;
        $data = json_encode( $ms_search_array );
        $data = MJTC_majesticsupportphplib::MJTC_safe_encoding($data);
        MJTC_majesticsupportphplib::MJTC_setcookie('ms_ticket_search_data' , $data , 0 , COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_ticket_search_data' , $data , 0 , SITECOOKIEPATH);
        }
    }

    function mjtc_delete_expire_session_data(){
        global $wpdb;
        $wpdb->query('DELETE  FROM '.$wpdb->prefix.'mjtc_support_mjtcsessiondata WHERE sessionexpire < "'. time() .'"');
    }

    /*
     * Update Ticket status every day schedule in the cron job
     */

    function updateticketstatus() {
        MJTC_includer::MJTC_getModel('ticket')->updateTicketStatusCron();
        if(in_array('overdue', majesticsupport::$_active_addons)){ // markticket overdue if duedate is passed.
            MJTC_includer::MJTC_getModel('overdue')->markTicketOverdueCron();
        }
    }

    function checkforaddonsupdate() {
        $addone_count = MJTC_includer::MJTC_getModel('majesticsupport')->showUpdateAvaliableAlert();
        if ($addone_count != 0) { 
            $url = admin_url("?page=majesticsupport_premiumplugin&mjslay=addonstatus");?>
            <div class="notice notice-info is-dismissible">
                <p>
                    <?php esc_attr(__('Hey there! We have recently launched a fresh update for the add-ons. Dont forget to update the add-ons to enjoy the greatest features!', 'majestic-support' )); ?>
                    <a href="<?php echo esc_url($url) ?>">
                        <?php echo esc_attr(__("View","majestic-support")); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }

    /*
     * Email Piping every hourly schedule in the cron job
     */

    function printTicket() {
        $layout = MJTC_request::MJTC_getVar('mjslay');
        if ($layout == 'printticket') {
            $ticketid = MJTC_request::MJTC_getVar('majesticsupportid');
            if(in_array('agent', majesticsupport::$_active_addons)){
                majesticsupport::$_data['user_staff'] = MJTC_includer::MJTC_getModel('agent')->isUserStaff();
            }else{
                majesticsupport::$_data['user_staff'] = false;
            }

            MJTC_includer::MJTC_getModel('ticket')->getTicketForDetail($ticketid);
            majesticsupport::addStyleSheets();
            majesticsupport::ms_register_plugin_styles();
            majesticsupport::$_data['print'] = 1; //print flag to handle appearnce
            MJTC_includer::MJTC_include_file('ticketdetail', 'ticket');
            exit();
        }
    }

    function MJTC_deactivate($network_wide = false) {
        include_once 'includes/deactivation.php';
        if(function_exists('is_multisite') && is_multisite() && $network_wide){
            global $wpdb;
            $blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogs as $blog_id){
                switch_to_blog( $blog_id );
                MJTC_deactivation::MJTC_deactivate();
                restore_current_blog();
            }
        }else{
            MJTC_deactivation::MJTC_deactivate();
        }
    }

    function ms_login_redirect( $redirect_to, $request, $user ) {
        //is there a user to check?
        global $user;
        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            //check for admins
            if ( in_array( 'administrator', $user->roles ) ) {
                // redirect them to the default place
                return $redirect_to;
            } else {
                $redirecturl = MJTC_request::MJTC_getVar('redirect_to');
                if(majesticsupport::$_config['login_redirect'] == 1 && $redirecturl == null){
                    $pageid = majesticsupport::getPageid();
                    $link = "index.php?page_id=".$pageid;
                    return $link;
                }elseif($redirecturl != null){
                    return $redirecturl;
                }else{
                    return home_url();
                }
            }
        } else {
            return $redirect_to;
        }
    }

    function resetNotificationValues(){ // config and key values empty
        
    }

    function registeractions() {
        //Ticket Action Hooks
        add_action('ms-ticketcreate', array($this, 'ticketcreate'), 10, 1);
        add_action('ms-ticketreply', array($this, 'ticketreply'), 10, 1);
        add_action('ms-ticketclose', array($this, 'ticketclose'), 10, 1);
        add_action('ms-ticketdelete', array($this, 'ticketdelete'), 10, 1);
        add_action('ms-ticketbeforelisting', array($this, 'ticketbeforelisting'), 10, 1);
        add_action('ms-ticketbeforeview', array($this, 'ticketbeforeview'), 10, 1);
        //Email Hooks
        add_action('ms-beforeemailticketcreate', array($this, 'beforeemailticketcreate'), 10, 4);
        add_action('ms-beforeemailticketreply', array($this, 'beforeemailticketreply'), 10, 4);
        add_action('ms-beforeemailticketclose', array($this, 'beforeemailticketclose'), 10, 4);
        add_action('ms-beforeemailticketdelete', array($this, 'beforeemailticketdelete'), 10, 4);
    }

    //Funtions for Ticket Hooks
    function ticketcreate($ticketobject) {
        return $ticketobject;
    }

    function ticketreply($ticketobject) {
        return $ticketobject;
    }

    function ticketclose($ticketobject) {
        return $ticketobject;
    }

    function ticketdelete($ticketobject) {
        return $ticketobject;
    }

    function ticketbeforelisting($ticketobject) {
        return $ticketobject;
    }

    function ticketbeforeview($ticketobject) {
        return $ticketobject;
    }

    //Funtion for Email Hooks
    function beforeemailticketcreate($recevierEmail, $subject, $body, $senderEmail) {
        return;
    }

    function beforeemailticketdelete($recevierEmail, $subject, $body, $senderEmail) {
        return;
    }

    function beforeemailticketreply($recevierEmail, $subject, $body, $senderEmail) {
        return;
    }

    function beforeemailticketclose($recevierEmail, $subject, $body, $senderEmail) {
        return;
    }

    /*
     * Include the required files
     */

    function includes() {
        if (is_admin()) {
            include_once 'includes/majesticsupportadmin.php';
        }
        if(in_array('widgets', majesticsupport::$_active_addons)){
            include_once 'includes/pageswidget.php';
        }

        include_once 'includes/captcha.php';
        include_once 'includes/recaptchalib.php';
        include_once 'includes/layout.php';
        include_once 'includes/pagination.php';
        include_once 'includes/includer.php';
        include_once 'includes/formfield.php';
        include_once 'includes/request.php';
        include_once 'includes/breadcrumbs.php';
        include_once 'includes/formhandler.php';
        include_once 'includes/shortcodes.php';
        include_once 'includes/paramregister.php';

        include_once 'includes/message.php';
        include_once 'includes/ajax.php';
        include_once 'includes/ms-hooks.php';
        require_once 'includes/constants.php';
    }

    /*
     * Include the wp core files
     */

    function mjtcLoadWpCoreFiles() {
        add_action('majesticsupport_load_wp_plugin_file', array($this,'majesticsupport_load_wp_plugin_file') );
        add_action('majesticsupport_load_wp_admin_file', array($this,'majesticsupport_load_wp_admin_file') );
        add_action('majesticsupport_load_wp_file', array($this,'majesticsupport_load_wp_file') );
        add_action('majesticsupport_load_wp_pcl_zip', array($this,'majesticsupport_load_wp_pcl_zip') );
        add_action('majesticsupport_load_wp_upgrader', array($this,'majesticsupport_load_wp_upgrader') );
        add_action('majesticsupport_load_wp_ajax_upgrader_skin', array($this,'majesticsupport_load_wp_ajax_upgrader_skin') );
        add_action('majesticsupport_load_wp_plugin_upgrader', array($this,'majesticsupport_load_wp_plugin_upgrader') );
        add_action('majesticsupport_load_wp_translation_install', array($this,'majesticsupport_load_wp_translation_install') );
        add_action('majesticsupport_load_phpass', array($this,'majesticsupport_load_phpass') );
    }

    /*
     * Localization
     */

    public function load_plugin_textdomain() {
        if(!load_plugin_textdomain('majestic-support')){
            load_plugin_textdomain('majestic-support', false, MJTC_majesticsupportphplib::MJTC_dirname(plugin_basename(__FILE__)) . '/languages/');
        }else{
            load_plugin_textdomain('majestic-support');
        }
    }

    /*
     * Check the current request and handle according to it
     */

    function checkRequest($content) {
        return $content;
    }

    /*
     * function for the Style Sheets
     */

    static function addStyleSheets() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('majesticsupport-commonjs',MJTC_PLUGIN_URL.'includes/js/common.js');
        wp_enqueue_script('majesticsupport-responsivetablejs',MJTC_PLUGIN_URL.'includes/js/responsivetable.js');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('majesticsupport-formvalidator',MJTC_PLUGIN_URL.'includes/js/jquery.form-validator.js');
        wp_enqueue_script( 'majestic-support-cmain-js', MJTC_PLUGIN_URL . 'includes/js/common_main.js', array( 'jquery' ), false, true );
        if(in_array('notification', majesticsupport::$_active_addons)){
            wp_localize_script('commonjs', 'common', array('apiKey_firebase' => majesticsupport::$_config['apiKey_firebase'],'authDomain_firebase'=> majesticsupport::$_config['authDomain_firebase'],'databaseURL_firebase'=>majesticsupport::$_config['databaseURL_firebase'], 'projectId_firebase' => majesticsupport::$_config['projectId_firebase'], 'storageBucket_firebase' => majesticsupport::$_config['storageBucket_firebase'], 'messagingSenderId_firebase' => majesticsupport::$_config['messagingSenderId_firebase']));
        }
        //to localize validation error messages
        $js = '
        jQuery.formUtils.LANG = {
            errorTitle: "'.esc_html(__("Form submission failed!",'majestic-support')).'",
            requiredFields: "'.esc_html(__("You have not answered all required fields",'majestic-support')).'",
            badTime: "'.esc_html(__("You have not given a correct time",'majestic-support')).'",
            badEmail: "'.esc_html(__("You have not given a correct e-mail address",'majestic-support')).'",
            badTelephone: "'.esc_html(__("You have not given a correct phone number",'majestic-support')).'",
            badSecurityAnswer: "'.esc_html(__("You have not given a correct answer to the security question",'majestic-support')).'",
            badDate: "'.esc_html(__("You have not given a correct date",'majestic-support')).'",
            lengthBadStart: "'.esc_html(__("The input value must be between ",'majestic-support')).'",
            lengthBadEnd: "'.esc_html(__(" characters",'majestic-support')).'",
            lengthTooLongStart: "'.esc_html(__("The input value is longer than ",'majestic-support')).'",
            lengthTooShortStart: "'.esc_html(__("The input value is shorter than ",'majestic-support')).'",
            notConfirmed: "'.esc_html(__("Input values could not be confirmed",'majestic-support')).'",
            badDomain: "'.esc_html(__("Incorrect domain value",'majestic-support')).'",
            badUrl: "'.esc_html(__("The input value is not a correct URL",'majestic-support')).'",
            badCustomVal: "'.esc_html(__("The input value is incorrect",'majestic-support')).'",
            badInt: "'.esc_html(__("The input value was not a correct number",'majestic-support')).'",
            badSecurityNumber: "'.esc_html(__("Your social security number was incorrect",'majestic-support')).'",
            badUKVatAnswer: "'.esc_html(__("Incorrect UK VAT Number",'majestic-support')).'",
            badStrength: "'.esc_html(__("The password isn't strong enough",'majestic-support')).'",
            badNumberOfSelectedOptionsStart: "'.esc_html(__("You have to choose at least ",'majestic-support')).'",
            badNumberOfSelectedOptionsEnd: "'.esc_html(__(" answers",'majestic-support')).'",
            badAlphaNumeric: "'.esc_html(__("The input value can only contain alphanumeric characters ",'majestic-support')).'",
            badAlphaNumericExtra: "'.esc_html(__(" and ",'majestic-support')).'",
            wrongFileSize: "'.esc_html(__("The file you are trying to upload is too large",'majestic-support')).'",
            wrongFileType: "'.esc_html(__("The file you are trying to upload is of the wrong type",'majestic-support')).'",
            groupCheckedRangeStart: "'.esc_html(__("Please choose between ",'majestic-support')).'",
            groupCheckedTooFewStart: "'.esc_html(__("Please choose at least ",'majestic-support')).'",
            groupCheckedTooManyStart: "'.esc_html(__("Please choose a maximum of ",'majestic-support')).'",
            groupCheckedEnd: "'.esc_html(__(" item(s)",'majestic-support')).'",
            badCreditCard: "'.esc_html(__("The credit card number is not correct",'majestic-support')).'",
            badCVV: "'.esc_html(__("The CVV number was not correct",'majestic-support')).'"
        };
        ';
        wp_add_inline_script('ms-formvalidator',$js);
    }

    public static function ms_register_plugin_styles(){
        global $wp_styles;
        if (!isset($wp_styles->queue)) {
            wp_enqueue_style('majesticsupport-main-css', MJTC_PLUGIN_URL . 'includes/css/style.css');
            // responsive style sheets
            wp_enqueue_style('majesticsupport-desktop-css', MJTC_PLUGIN_URL . 'includes/css/style_desktop.css',array(),'','(min-width: 783px) and (max-width: 1280px)');
            wp_enqueue_style('majesticsupport-tablet-css', MJTC_PLUGIN_URL . 'includes/css/style_tablet.css',array(),'','(min-width: 668px) and (max-width: 782px)');
            wp_enqueue_style('majesticsupport-mobile-css', MJTC_PLUGIN_URL . 'includes/css/style_mobile.css',array(),'','(min-width: 481px) and (max-width: 667px)');
            wp_enqueue_style('majesticsupport-oldmobile-css', MJTC_PLUGIN_URL . 'includes/css/style_oldmobile.css',array(),'','(max-width: 480px)');
            if(is_rtl()){
                wp_enqueue_style('majesticsupport-main-css-rtl', MJTC_PLUGIN_URL . 'includes/css/stylertl.css');
            }
            $color = require_once(MJTC_PLUGIN_PATH . 'includes/css/style.php');
            wp_enqueue_style('majesticsupport-color-css', MJTC_PLUGIN_URL . 'includes/css/color.css');
        } else {    
            MJTC_includer::MJTC_getModel('majesticsupport')->checkIfMainCssFileIsEnqued();
        }
    }

    public static function ms_admin_register_plugin_styles() {
        wp_register_style('mjsupport-bootstrapcss', MJTC_PLUGIN_URL . 'includes/css/bootstrap.min.css');
        wp_register_style('mjsupport-admincss', MJTC_PLUGIN_URL . 'includes/css/admincss.css');
        wp_enqueue_style('mjsupport-admincss');
        if(is_rtl()){
            wp_register_style('mjsupport-admincss-rtl', MJTC_PLUGIN_URL . 'includes/css/admincssrtl.css');
            wp_enqueue_style('mjsupport-admincss-rtl');
        }
    }

    /*
     * function to get the pageid from the wpoptions
     */

    public static function getPageid() {
        if(majesticsupport::$_pageid != ''){
            return majesticsupport::$_pageid;
        }else{
            $pageid = MJTC_request::MJTC_getVar('page_id','GET');
            if($pageid){
                return $pageid;
            }else{ // in case of categories popup
                $query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = 'default_pageid'";
                $pageid = majesticsupport::$_db->get_var($query);
                return $pageid;
            }
        }
    }

    public static function setPageID($id) {
        majesticsupport::$_pageid = $id;
        return;
    }

    static function MJTC_sanitizeData($data){
        if($data == null){
            return $data;
        }
        if(is_array($data)){
            return map_deep( $data, 'sanitize_text_field' );
        }else{
            return sanitize_text_field( $data );
        }
    }

    public static function MJTC_getVarValue($text_string) {
        $translations = get_translations_for_domain('majestic-support');
        $translation  = $translations->translate( $text_string );
        return esc_html($translation);
    }

    /*
     * function to parse the spaces in given string
     */

    public static function parseSpaces($string) {
        // php 8 issue for str_replce
        if($string == ''){
            return $string;
        }
        return MJTC_majesticsupportphplib::MJTC_str_replace('%20',' ',$string);
    }

    static function checkScreenTag(){
        if(!is_admin()){
            if (majesticsupport::$_config['support_screentag'] == 1) { // we need to show the support ticket tag
                if (majesticsupport::$_config['support_custom_img'] == '0') {
                    $img_scr = MJTC_PLUGIN_URL.'includes/images/support.png';
                } else {
                    $maindir = wp_upload_dir();
                    $basedir = $maindir['baseurl'];
                    $datadirectory = majesticsupport::$_config['data_directory'];
                    $img_scr = $basedir . '/' . $datadirectory.'/supportImg/'.esc_attr(majesticsupport::$_config['support_custom_img']);
                }
                if (isset(majesticsupport::$_config['support_custom_txt']) && majesticsupport::$_config['support_custom_txt'] != '') {
                    $support_txt = majesticsupport::$_config['support_custom_txt'];
                } else {
                    $support_txt = "Support";
                }
                $location = 'left';
                switch (majesticsupport::$_config['screentag_position']) {
                    case 1: // Top left
                        break;
                    case 2: // Top right
                        $location = 'right';
                        break;
                    case 3: // middle left
                        break;
                    case 4: // middle right
                        $location = 'right';
                        break;
                    case 5: // bottom left
                        break;
                    case 6: // bottom right
                        $location = 'right';
                        break;
                }

                $html ='
                        <div id="mjtc-support_screentag">
                        <a class="mjtc-support_screentag_anchor" href="' . esc_url(site_url('?page_id=' . esc_attr(majesticsupport::$_config['default_pageid']))) . '">';
                if($location == 'right'){
                    $html .= '<img class="mjtc-support_screentag_image" alt="screen tag" src="'.esc_url($img_scr).'" /><span class="text">'.esc_html(majesticsupport::MJTC_getVarValue($support_txt)).'</span>';
                }else{
                    $html .= '<span class="text">'.esc_html(majesticsupport::MJTC_getVarValue($support_txt)).'</span><img class="mjtc-support_screentag_image" alt="screen tag" src="'.esc_url($img_scr).'" />';
                }
                $html .= '</a>
                        </div>';
                        $majesticsupport_js ='
                            jQuery(document).ready(function(){
                                jQuery("div#mjtc-support_screentag").css("'.esc_attr($location).'","-"+(jQuery("div#mjtc-support_screentag span.text").width() + 25)+"px");
                                jQuery("div#mjtc-support_screentag").css("opacity",1);
                                jQuery("div#mjtc-support_screentag").hover(
                                    function(){
                                        jQuery(this).animate({'.esc_attr($location).': "+="+(jQuery("div#mjtc-support_screentag span.text").width() + 25)}, 1000);
                                    },
                                    function(){
                                        jQuery(this).animate({'.esc_attr($location).': "-="+(jQuery("div#mjtc-support_screentag span.text").width() + 25)}, 1000);
                                    }
                                );
                            });';
                        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                echo wp_kses($html, MJTC_ALLOWED_TAGS);
            }
        }
    }

    static function makeUrl($args = array()){
        global $wp_rewrite;

        $pageid = MJTC_request::MJTC_getVar('mspageid');
        if(is_numeric($pageid)){
            $permalink = get_the_permalink($pageid);
        }else{
            if(isset($args['mspageid']) && is_numeric($args['mspageid'])){
                $permalink = get_the_permalink($args['mspageid']);
            }else{
                $permalink = get_the_permalink();
            }
        }

        if (!$wp_rewrite->using_permalinks() || is_feed()){
            if(!MJTC_majesticsupportphplib::MJTC_strstr($permalink, 'page_id') && !MJTC_majesticsupportphplib::MJTC_strstr($permalink, '?p=')){
                $page['page_id'] = get_option('page_on_front');
                $args = $page + $args;
            }
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }

        if(isset($args['mjsmod']) && isset($args['mjslay'])){
            // Get the original query parts
            $redirect = @parse_url($permalink);
            if (!isset($redirect['query']))
                $redirect['query'] = '';

            if(MJTC_majesticsupportphplib::MJTC_strstr($permalink, '?')){ // if variable exist
                $redirect_array = MJTC_majesticsupportphplib::MJTC_explode('?', $permalink);
                $_redirect = $redirect_array[0];
            }else{
                $_redirect = $permalink;
            }

            if($_redirect[MJTC_majesticsupportphplib::MJTC_strlen($_redirect) - 1] == '/'){
                $_redirect = MJTC_majesticsupportphplib::MJTC_substr($_redirect, 0, MJTC_majesticsupportphplib::MJTC_strlen($_redirect) - 1);
            }

            // If is layout
            $changename = false;
            if(file_exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/mjtc-vehicle-manager.php')){
                $changename = true;
            }
            if (isset($args['mjslay'])) {
                $layout = '';
                $layout = MJTC_includer::MJTC_getModel('slug')->getSlugFromFileName($args['mjslay'],$args['mjsmod']);
                global $wp_rewrite;
                $slug_prefix = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('home_slug_prefix');
                if(is_home() || is_front_page()){
                    if($_redirect == site_url()){
                        $layout = $slug_prefix.$layout;
                    }
                }else{
                    if($_redirect == site_url()){
                        $layout = $slug_prefix.$layout;
                    }
                }
                $_redirect .= '/' . $layout;
            }
            // If is list
            if (isset($args['list'])) {
                $_redirect .= '/' . $args['list'];
            }
            // If is sortby
            if (isset($args['sortby'])) {
                $_redirect .= '/' . $args['sortby'];
            }
            // If is majesticsupport_ticketid
            if (isset($args['majesticsupportid'])) {
                $_redirect .= '/' . $args['majesticsupportid'];
                if($args['mjslay'] == 'addticket'){
                    $_redirect .= '_10';// 10 for ticket id
                }
            }

            if (isset($args['edd_order_id'])) {
                $_redirect .= '/' . $args['edd_order_id'].'_11';// 11 for easy digital downloads id
            }

            if (isset($args['uid'])) {
                $_redirect .= '/' . $args['uid'].'_12';// 12 for user id
            }

            if (isset($args['paidsupportid'])) {
                $_redirect .= '/' . $args['paidsupportid'].'_13';// 13 for paid support id
            }
            if (isset($args['formid'])){
                $_redirect .= '/' . $args['formid'].'_15';// 15 multi form id
            }


            if (isset($args['ms-id'])){
                $_redirect .= '/' . $args['ms-id'];
            }
            if (isset($args['ms-date-start'])){
                $_redirect .= '/date-start:' . $args['ms-date-start'];
            }
            if (isset($args['ms-date-end'])){
                $_redirect .= '/date-end:' . $args['ms-date-end'];
            }
            if (isset($args['mjtc_redirecturl'])){
                $_redirect .= '/?mjtc_redirecturl=' . $args['mjtc_redirecturl'];
            }
            if (isset($args['token'])){
                $_redirect .= '/?token=' . $args['token'];
            }
            if (isset($args['successflag'])){
                $_redirect .= '/?successflag=' . $args['successflag'];
            }
            return $_redirect;
        }else{ // incase of form
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }
    }

    function reset_ms_aadon_query(){
        majesticsupport::$_addon_query = array('select'=>'','join'=>'','where'=>'');
    }

    function majesticsupport_load_wp_plugin_file() {
        $wp_admin_url = admin_url('includes/plugin.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_admin_file() {
        $wp_admin_url = admin_url('includes/admin.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_file() {
        $wp_admin_url = admin_url('includes/file.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_pcl_zip() {
        $wp_admin_url = admin_url('includes/class-pclzip.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_ajax_upgrader_skin() {
        $wp_admin_url = admin_url('includes/class-wp-ajax-upgrader-skin.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_upgrader() {
        $wp_admin_url = admin_url('includes/class-wp-upgrader.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_plugin_upgrader() {
        $wp_admin_url = admin_url('includes/class-plugin-upgrader.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_translation_install() {
        $wp_admin_url = admin_url('includes/translation-install.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_wp_plugin_install() {
        $wp_admin_url = admin_url('includes/plugin-install.php');
        $wp_admin_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_admin_url);
        require_once($wp_admin_path);
    }

    function majesticsupport_load_phpass() {
        $wp_site_url = site_url('wp-includes/class-phpass.php');
        $wp_site_path = MJTC_majesticsupportphplib::MJTC_str_replace(site_url('/'), ABSPATH, $wp_site_url);
        require_once($wp_site_path);
    }

    function ticketviaemail() {// this funtion also handles ticket overdue bcz of hours confiuration
        if(in_array('overdue', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('overdue')->updateTicketStatusToOverDueCron();// this funtions handles the overdue of tickets by cron
        }
        if(in_array('feedback', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('ticket')->sendFeedbackMail();// this funtions handles the the feedback email
        }
        if(in_array('emailpiping', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getController('emailpiping')->registerReadEmails();
            MJTC_includer::MJTC_getModel('emailpiping')->getAllEmailsForTickets();
        }
    }
}

add_action('init', 'mjtc_custom_init_session', 1);
function mjtc_custom_init_session() {
    wp_enqueue_script("jquery");
    majesticsupport::addStyleSheets();
}

// add the filter
$majesticsupport = new majesticsupport();

add_filter( 'login_form_middle', 'msAddLostPasswordLink' );
function msAddLostPasswordLink($content) {
   return $content.'
   <a href="'.site_url().'/wp-login.php?action=lostpassword">'. esc_html(__('Lost your password','majestic-support')) .'?</a>';
}

add_filter( 'login_form_middle', 'msAddRegisterLink' );
function msAddRegisterLink($content) {
    if(get_option('users_can_register')){
        $content .= ' <a href="'.esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport','mjslay'=>'userregister'))).'">'. esc_html(__('Register','majestic-support')) .'</a>';
    }
    return $content;
}

add_action( 'ms_addon_update_date_failed', 'msaddonUpdateDateFailed' );
function msaddonUpdateDateFailed(){
    die();
}

add_filter('style_loader_tag', 'msW3cValidation', 10, 2);
add_filter('script_loader_tag', 'msW3cValidation', 10, 2);
function msW3cValidation($tag, $handle) {
    return MJTC_majesticsupportphplib::MJTC_preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

if(!empty(majesticsupport::$_active_addons)){
    require_once 'includes/addon-updater/msupdater.php';
    $MJTC_SUPPORTTICKETUpdater  = new MJTC_SUPPORTTICKETUpdater();
}

if(is_file('includes/updater/updater.php')){
    include_once 'includes/updater/updater.php';
}
// file for admin review
if(is_admin() && is_file('includes/classes/msadminreviewbox.php')){
    
}

function ms_get_avatar($uid, $class = ''){
    $defaultImage = MJTC_PLUGIN_URL . '/includes/images/user.png';
    $avatar = '<img alt="image" src="'.esc_url($defaultImage).'" class="'.esc_attr($class).'" />';
    if(is_numeric($uid) && $uid){
        $avatar = get_avatar($uid, 96, $defaultImage, '', array('class'=>$class));
    }else{
        $avatar = '<img alt="image" src="'.esc_url($defaultImage).'" class="'.esc_attr($class).'" />';
    }
    return $avatar;
}

function mjtc_checkPluginInfo($slug){
    if(file_exists(WP_PLUGIN_DIR . '/'.$slug) && is_plugin_active($slug)){
        $text = esc_html(__("Activated",'majestic-support'));
        $disabled = "disabled";
        $class = "mjtc-btn-activated";
        $availability = "-1";
    }else if(file_exists(WP_PLUGIN_DIR . '/'.$slug) && !is_plugin_active($slug)){
        $text = esc_html(__("Active Now",'majestic-support'));
        $disabled = "";
        $class = "mjtc-btn-green mjtc-btn-active-now";
        $availability = "1";
    }else if(!file_exists(WP_PLUGIN_DIR . '/'.$slug)){
        $text = esc_html(__("Install Now",'majestic-support'));
        $disabled = "";
        $class = "mjtc-btn-install-now";
        $availability = "0";
    }
    return array("text" => $text, "disabled" => $disabled, "class" => $class, "availability" => $availability);
}

?>

<?php
/**
 * WP Prayer Engine class file.
 * @package Forms
 * @author Go Prayer
 * @version 2.0.8
 */

/*

Plugin Name: WP Prayer

Plugin URI: https://www.goministry.com/

Description:  Create your prayer or pray for others.

Author: Go Prayer

Author URI: https://www.goprayer.com/

Version: 2.0.8

Text Domain: wp-prayer

Domain Path: /lang/
License: GPLv2 or later
*/

if ( ! defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

// final class reCaptchaV3
// {
//
//     public function __construct()  { }
//
//     public function init()
//     {
//         add_action( 'login_enqueue_scripts', array($this, 'load_login_scripts') );
//     }
//
//     public static function load_login_scripts()
//     {  wp_enqueue_script( 'custom-recaptcha', plugin_dir_url( __FILE__ ) . 'captcha.js' );
//       $option = unserialize(get_option('_wpe_prayer_engine_settings'));
//        $site=$option['wpe_prayer_site_key'];
//         wp_enqueue_script( 'recaptchav3', 'https://www.recaptcha.net/recaptcha/api.js?render='.$site.'', array(), '3', true);
//
//     }
// }
//
// add_action( 'init', array( new reCaptchaV3(), 'init' ) );

add_action('init', function () {
    //wp_enqueue_script should be called inside a hook
     // wp_enqueue_script( "load-captcha", plugin_dir_url( dirname( __FILE__ ) ) . 'wp-prayer 2/captcha.js', array( 'jquery' ) );
    //wp_enqueue_script("load-wg_captcha", 'https://www.google.com/recaptcha/api.js', array(), '3', true);

     $option = unserialize(get_option('_wpe_prayer_engine_settings'));

     if(isset($option['wpe_captcha']) && $option['wpe_captcha'] == 'true')
     {
       // require_once( ABSPATH . 'wp-content/plugins/wp-prayer 2/captcha.js' );
       wp_enqueue_script( "load-captcha", plugin_dir_url( dirname( __FILE__ ) ) . 'wp-prayer 2/captcha.js', array( 'jquery' ) );
       $site=$option['wpe_prayer_site_key'];
       wp_enqueue_script( "recaptchav3",'https://www.recaptcha.net/recaptcha/api.js?render='.$site.'', array(), '3', true);
     }


    //Sessions should always be started in 'init' hook.
});


if ( ! class_exists('WP_Prayer_Engine')) {

    /**
     * Main plugin class
     * @author Go Prayer
     * @package Forms
     */
    class WP_Prayer_Engine
    {

        /**
         * List of Modules.
         * @var array
         */

        private $modules = array();

        /**
         * Intialize variables, files and call actions.
         * @var array
         */

        public function __construct()
        {
            
            error_reporting( E_ERROR | E_PARSE );
            $this->_define_constants();
            $this->_load_files();
            register_activation_hook(__FILE__, array($this, 'plugin_activation'));
            register_deactivation_hook(__FILE__, array($this, 'plugin_deactivation'));
            add_action('plugins_loaded', array($this, 'load_plugin_languages'));
            add_action('init', array($this, '_init'));
            add_action('wp_ajax_wpe_ajax_call', array($this, 'wpe_ajax_call'));
            add_action('wp_ajax_nopriv_wpe_ajax_call', array($this, 'wpe_ajax_call'));
            add_shortcode('wp-prayer-engine', array($this, 'wpe_select_engine_type'));
            //add_shortcode( 'wp-prayer-engine', array( $this, 'wpe_shortcode_callback' ) );
            add_shortcode('wp-prayer-praise', array($this, 'wpe_praise_shortcode_callback'));

            

            //add_action('wpe_prayer_received_perday', array( $this, 'wpe_prayer_received_perday_autoemail'));
        }

        function wpe_select_engine_type($atts)
        {

            //todo::change this quick fix
            if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'edit' && isset($_REQUEST['post'])) {
                return;
            }
            ob_start();
			$isinarry=0;
			if (is_array($atts)) {if(in_array('form', $atts)) {$isinarry=1;}}
            if ($isinarry=1 && is_array($atts) && array_key_exists('type', $atts)) {

                if ($atts['type'] === 'prayer') {

                    echo $this->wpe_shortcode_callback($atts);

                } elseif ($atts['type'] === 'praise') {

                    echo $this->wpe_shortcode_callback($atts);

                } else {

                    //Do nothing prayer type is unknown

                }

            } elseif ($isinarry=1) {

                //No type is specified

                echo $this->wpe_shortcode_callback($atts);

            } else {
                echo $this->wpe_shortcode_callback($atts);
            }
			
            return ob_get_clean();

        }

        /**
         * Ajax Call
         */

        function wpe_ajax_call()
        {
			//var_dump(123);
			//exit;

            // check_ajax_referer('wpe-call-nonce', 'nonce');
			// check_ajax_referer( 'platform_security', 'security' );


            $operation = sanitize_text_field($_POST['operation']);

            // $value = sanitize_text_field($_POST);
            $value['prayer_id'] = sanitize_text_field($_POST['prayer_id']);
            $value['user_ip'] = sanitize_text_field($_POST['user_ip']);

            if (isset($operation)) {

                echo $this->$operation($value);

            }

            die();

        }


        /**
         * Call WordPress hooks.
         */

        function _init()
        {

            global $wpdb;
            
            add_action('admin_menu', array($this, 'create_menu'));
            

            if ( ! is_admin()) {

                add_action('wp_enqueue_scripts', array($this, 'wpe_frontend_scripts'));

            }
            

        }

        /**
         * Eneque scripts at frontend.
         */

        function wpe_frontend_scripts()
        {

            $scripts = array();

            wp_enqueue_script('jquery');

            $scripts[] = array(

                'handle' => 'wpp-frontend',

                'src' => WPE_JS.'frontend.js',

                'deps' => array(),

            );

            $where = apply_filters('wpe_script_position', true);

            if ($scripts) {

                foreach ($scripts as $script) {

                    wp_register_script($script['handle'], $script['src'], $script['deps'], '', $where);

                }

            }

            $frontend_styles = array(

                'wpp-frontend' => WPE_CSS.'frontend.css',

            );

            if ($frontend_styles) {

                foreach ($frontend_styles as $frontend_style_key => $frontend_style_value) {

                    wp_register_style($frontend_style_key, $frontend_style_value);

                }

            }

        }

        /**
         * Process slug and display view in the backend.
         */

        function processor()
        {

            $return = '';

            if (isset($_GET['page'])) {
                $page = sanitize_text_field($_GET['page']);
            } else {
                $page = 'wpe_view_overview';
            }

            $pageData = explode('_', $page);
            $obj_type = $pageData[2];
            $obj_operation = $pageData[1];

            if (count($pageData) < 3) {
                die('Cheating!');
            }

            try {
                if (count($pageData) > 3) {
                    $obj_type = $pageData[2].'_'.$pageData[3];
                }

                $factoryObject = new FactoryControllerWPE();
                $viewObject = $factoryObject->create_object($obj_type);
                $viewObject->display($obj_operation);

            } catch (Exception $e) {
                echo WPE_Template::show_message(array('error' => $e->getMessage()));
            }

        }

        /**
         * Create backend navigation.
         */

        function create_menu()
        {

            global $navigations;

            $pagehook1 = add_menu_page(__('WP Prayer', WPE_TEXT_DOMAIN), __('WP Prayer', WPE_TEXT_DOMAIN),
                'wpe_admin_overview', WPE_SLUG, array($this, 'processor'));


            if (current_user_can('manage_options')) {

                $role = get_role('administrator');

                $role->add_cap('wpe_admin_overview');

            }

            $this->load_modules_menu();

            add_action('load-'.$pagehook1, array($this, 'wpe_backend_scripts'));

        }


        /**
         * Read models and create backend navigation.
         */

        function load_modules_menu()
        {

            $modules = $this->modules;

            $pagehooks = array();

            if (is_array($modules)) {

                foreach ($modules as $module) {

                    $object = new $module;

                    if (method_exists($object, 'navigation')) {

                        if ( ! is_array($object->navigation())) {

                            continue;

                        }

                        foreach ($object->navigation() as $nav => $title) {


                            if (current_user_can('manage_options') && is_admin()) {

                                $role = get_role('administrator');

                                $role->add_cap($nav);

                            }
                            $submenu = add_submenu_page(WPE_SLUG, $title, $title, $nav, $nav,
                                array($this, 'processor'));

                            //Enqueue scripts based on modules
                            if (method_exists($object, 'do_extra')) {
                                $object->do_extra($submenu);
                            }

                            $pagehooks[] = $submenu;

                            if (method_exists($object, 'do_extra')) {
                                $object->do_extra($submenu);
                            }

                        }

                    }

                }

            }

            if (is_array($pagehooks)) {

                foreach ($pagehooks as $pagehook) {
                    add_action('load-'.$pagehook, array($this, 'wpe_backend_scripts'));
                }
            }

        }


        /**
         * Eneque scripts in the backend.
         */

        function wpe_backend_scripts()
        {

            wp_enqueue_style('wp-color-picker');

            wp_enqueue_style('thickbox');

            $wp_scripts = array('jquery', 'thickbox', 'wp-color-picker', 'jquery-ui-datepicker');

            if ($wp_scripts) {

                foreach ($wp_scripts as $wp_script) {

                    wp_enqueue_script($wp_script);

                }

            }

            $scripts = array();

            $scripts[] = array(

                'handle' => 'wpp-backend-bootstrap',

                'src' => WPE_JS.'bootstrap.min.js',

                'deps' => array(),

            );

            $scripts[] = array(

                'handle' => 'wpp-backend',

                'src' => WPE_JS.'backend.js',

                'deps' => array(),

            );

            if ($scripts) {

                foreach ($scripts as $script) {

                    wp_enqueue_script($script['handle'], $script['src'], $script['deps']);

                }

            }


            

            $admin_styles = array(
                'flippercode-bootstrap' => WPE_CSS.'bootstrap.min.css',

                'wpp-backend-style' => WPE_CSS.'backend.css',

                'wpp-frontend-style' => WPE_CSS.'frontend.css',
                

            );


            if ($admin_styles) {

                foreach ($admin_styles as $admin_style_key => $admin_style_value) {
                    wp_enqueue_style($admin_style_key, $admin_style_value);
                }

            }

            $wcsl_js_lang = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wpe-call-nonce'),
                'confirm' => __('Are you sure to delete item ?', 'wpe-text-domain'),
            );
            
            $script = "var wcsl_js_lang = " . wp_json_encode($wcsl_js_lang) . ";";
            
            wp_enqueue_script('wpp-frontend');
            wp_add_inline_script('wpp-frontend', $script, 'before');

        }

        /**
         * Load plugin language file.
         */

        function load_plugin_languages()
        {
            load_plugin_textdomain(WPE_TEXT_DOMAIN, false, WPE_FOLDER.'/lang/');
        }

        

        /**
         * Call hook on plugin activation for both multi-site and single-site.
         * @param  boolean $network_wide Network activated or not.
         */

        function plugin_activation($network_wide = null)
        {

            if (is_multisite() && $network_wide) {

                global $wpdb;

                $currentblog = $wpdb->blogid;

                $activated = array();

                $sql = "SELECT blog_id FROM {$wpdb->blogs}";

                $blog_ids = $wpdb->get_col($wpdb->prepare($sql, null));

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);

                    $this->wpe_activation();

                    $activated[] = $blog_id;

                }

                switch_to_blog($currentblog);

                update_site_option('op_activated', $activated);			

            } else {

                $this->wpe_activation();

            }

			// Clean database to remove prayer users that doesnt have the assosiated prayer active
			global $wpdb;
			$table_prefix = $wpdb->base_prefix;
			$user_table = $table_prefix."prayer_users";
			$prayer_table = $table_prefix."prayer_engine";

			$clean_db_query = "DELETE FROM $user_table WHERE prayer_id NOT IN (SELECT prayer_id FROM $prayer_table)";

			$wpdb->query( $clean_db_query );
            
            
            //if ( ! wp_next_scheduled( 'wpe_prayer_received_perday' ) ) {            
            //wp_schedule_event( time(), 'daily', 'wpe_prayer_received_perday' );            
            //}

        }

        /**
         * Call hook on plugin deactivation for both multi-site and single-site.
         * @param  boolean $network_wide True or False.
         */

        function plugin_deactivation($network_wide)
        {

            if (is_multisite() && $network_wide) {

                global $wpdb;

                $currentblog = $wpdb->blogid;

                $activated = array();

                $sql = "SELECT blog_id FROM {$wpdb->blogs}";

                $blog_ids = $wpdb->get_col($wpdb->prepare($sql, null));

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);

                    $this->wpe_deactivation();

                    $activated[] = $blog_id;

                }

                switch_to_blog($currentblog);

                update_site_option('op_activated', $activated);

            } else {

                $this->wpe_deactivation();

            }


            wp_clear_scheduled_hook('wpe_prayer_received_perday');

        }

        /**
         * Perform tasks on plugin deactivation.
         */

        function wpe_deactivation()
        {

        }

        /**
         * Perform tasks on plugin deactivation.
         */

        function wpe_activation()
        {

            global $wpdb;

            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            $modules = $this->modules;
            $pagehooks = array();

            if (is_array($modules)) {
                foreach ($modules as $module) {
                    $object = new $module;
                    if (method_exists($object, 'install')) {
                        $tables[] = $object->install();
                    }
                }
            }

            if (is_array($tables)) {
                foreach ($tables as $i => $sql) {
                    dbDelta($sql);
                }
            }
        }

        /**
         * Define all constants.
         */

        private function _define_constants()
        {

            global $wpdb;

            if ( ! defined('WPE_SLUG')) {
                define('WPE_SLUG', 'wpe_view_overview');
            }
            if ( ! defined('WPE_VERSION')) {
                define('WPE_VERSION', '1.0.0');
            }
            if ( ! defined('WPE_TEXT_DOMAIN')) {
                define('WPE_TEXT_DOMAIN', 'wp-prayer');
            }
            if ( ! defined('WPE_FOLDER')) {
                define('WPE_FOLDER', basename(dirname(__FILE__)));
            }
            if ( ! defined('WPE_DIR')) {
                define('WPE_DIR', plugin_dir_path(__FILE__));
            }
            if ( ! defined('WPE_CORE_CLASSES')) {
                define('WPE_CORE_CLASSES', WPE_DIR.'core/');
            }
            if ( ! defined('WPE_CONTROLLER')) {
                define('WPE_CONTROLLER', WPE_CORE_CLASSES);
            }
            if ( ! defined('WPE_CORE_CONTROLLER_CLASS')) {
                define('WPE_CORE_CONTROLLER_CLASS', WPE_CORE_CLASSES.'class.controller.php');
            }
            if ( ! defined('WPE_Model')) {
                define('WPE_Model', WPE_DIR.'modules/');
            }
            if ( ! defined('WPE_URL')) {
                define('WPE_URL', plugin_dir_url(WPE_FOLDER).WPE_FOLDER.'/');
            }
            if ( ! defined('WPE_INC_URL')) {
                define('WPE_INC_URL', WPE_URL.'core/');
            }
			  if ( ! defined('WPE_VIEWS_PATH')) {
                define('WPE_VIEWS_PATH', 'wpe_CLASSES'.'view');
            }
            if ( ! defined('WPE_CSS')) {
                define('WPE_CSS', WPE_URL.'/assets/css/');
            }
            if ( ! defined('WPE_JS')) {
                define('WPE_JS', WPE_URL.'/assets/js/');
            }
            if ( ! defined('WPE_IMAGES')) {
                define('WPE_IMAGES', WPE_URL.'/assets/images/');
            }
            if ( ! defined('WPE_FONTS')) {
                define('WPE_FONTS', WPE_URL.'fonts/');
            }
            if ( ! defined('WPE_TBL_PRAYER')) {
                define('WPE_TBL_PRAYER', $wpdb->prefix."prayer_engine");
            }
            if ( ! defined('WPE_TBL_PRAYER_USERS')) {
                define('WPE_TBL_PRAYER_USERS', $wpdb->prefix."prayer_users");
            }

        }

        /**
         * Load all required core classes.
         */

        private function _load_files()
        {

            $files_to_include = array(
                'class.tabular.php',
                'class.template.php',
                'abstract.factory.php',
                'class.controller-factory.php',
                'class.model-factory.php',
                'class.controller.php',
                'class.model.php',
                'class.validation.php',
                'class.database.php',
            );

            foreach ($files_to_include as $file) {
                require_once(WPE_CORE_CLASSES.$file);
            }

            // Load all modules.
            $core_modules = array('overview', 'prayer', 'email_settings', 'settings', 'export');

            if (is_array($core_modules)) {

                foreach ($core_modules as $module) {

                    $file = WPE_Model.$module.'/model.'.$module.'.php';

                    if (file_exists($file)) {

                        include_once($file);

                        $class_name = 'WPE_Model_'.ucwords($module);

                        array_push($this->modules, $class_name);

                    }

                }

            }

        }


        /**
         * Display Prayer Requests and Form on the frontend using wp-prayer-engine shortcode.
         * @param  array $atts Template Options.
         * @param  string $content Content.
         */

        function wpe_shortcode_callback($atts, $content = null)
        {

            try {

                ob_start();
                $factoryObject = new FactoryControllerWPE();
                $viewObject = $factoryObject->create_object('shortcode');

                // Set shortcode attributes
                extract(shortcode_atts(array('form' => 'true'), $atts));

                if ($atts) {

                    if (isset($form) && $form) {

                        $output = $viewObject->display('wp-prayer-engine-form', $atts);
                    } else {

                        $output = $viewObject->display('wp-prayer-engine', $atts, false);
                    }

                } else {
                    //error generating
                    $output = $viewObject->display('wp-prayer-engine', $atts, false);
                }

                $output = ob_get_clean();

                return $output;

            } catch (Exception $e) {
                echo WPE_Template::show_message(array('error' => $e->getMessage()));
            }

            return true;

        }


        /**
         * Display Prayer Praise Reports submitted on the frontend using wp-prayer-praise shortcode.
         * @param  array $atts Template Options.
         * @param  string $content Content.
         */

        function wpe_praise_shortcode_callback($atts, $content = null)
        {

            try {

                ob_start();

                $factoryObject = new FactoryControllerWPE();

                $viewObject = $factoryObject->create_object('shortcode');

                $output = $viewObject->display('wp-prayer-praise', $atts, false);

                $output = ob_get_clean();

                return $output;

            } catch (Exception $e) {

                echo WPE_Template::show_message(array('error' => $e->getMessage()));

            }

        }


        /**
         * Method to save prayer performed by user
         * @param  array $value prayer id
         */

        function wpe_do_pray($value)
        {
            $modelFactory = new FactoryModelWPE();


            if ($value['user_ip'] != '') {
                $value['user_ip'] = base64_decode($value['user_ip']);
            }


            $value['user_id'] = get_current_user_id();

            


            $prayer_obj = $modelFactory->create_object('prayer');

            $prayer = $prayer_obj->fetch(array(array('prayer_id', '=', $_POST['prayer_id'])));

            

            $prayer = (array)$prayer[0];


            $prayer_performed_obj = $modelFactory->create_object('prayers_performed');

            
            //if ( ! wp_next_scheduled( 'wpe_prayer_received_perday' ) ) {            
            //wp_schedule_event( time(), 'daily', 'wpe_prayer_received_perday' );            
            //}

            if ($value['user_ip'] != '') {
                $prayer_performed_obj = $prayer_performed_obj->fetch(array(
                    array('prayer_id', '=', $_POST['prayer_id']),
                    array('user_ip', '=', $value['user_ip']),
                ));
            } else {
                $prayer_performed_obj = $prayer_performed_obj->fetch(array(
                    array('prayer_id', '=', $_POST['prayer_id']),
                    array('user_id', '=', $value['user_id']),
                ));
            }

            


            if (true || empty($prayer_performed_obj)) { 

                if ($value['user_ip'] != '') {
                    $result = $prayer_obj->save_prayer_users($_POST['prayer_id'], '', $value['user_ip']);
                } else {
                    $result = $prayer_obj->save_prayer_users($_POST['prayer_id'], $value['user_id'], null);
                }

                
                $settings = unserialize(get_option('_wpe_prayer_engine_settings'));

                if ($result !== false) {


                    
                    
                    // Auto-email
                    
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    
                    $user_info = get_userdata($value['user_id']);
                    
                    if(!empty($prayer['prayer_author_email']))
                    
                    $to = $prayer['prayer_author_email'];
                    
                    else{
                    
                    $prayer_author_info = get_userdata($prayer['prayer_author']);
                    
                    $to = $prayer_author_info->user_email;
                    
                    }
                    if (isset($settings['wpe_autoemail'])&& $settings['wpe_autoemail'] == 'true' && $prayer['prayer_lastname']=='*') {
                    if(!empty($to)){
                    $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                    add_filter('wp_mail_from', array($this, 'website_email'));
                    add_filter('wp_mail_from_name', array($this, 'website_name'));
                    
                    $subject = (isset($email_settings['wpe_email_prayed_subject']) and ! empty($email_settings['wpe_email_prayed_subject'])) ? $email_settings['wpe_email_prayed_subject'] : 'Someone prayed for you';
                    if (isset($email_settings['wpe_email_prayed_messages']) AND ! empty($email_settings['wpe_email_prayed_messages'])) {
                        $body = stripslashes($email_settings['wpe_email_prayed_messages']);
                        $body = str_replace(array(
                            '{prayer_author_name}',
                            '{prayer_messages}',
                            ), array(
                            $prayer['prayer_author_name'],
							$prayer['prayer_messages'],
                            ), $body);
                            } else {
                            $body = 'Hello '.$prayer['prayer_author_name'].', <br> <p>Someone prayed for you';
     						$body .= '<b>Request :</b> '.$prayer['prayer_messages'].'<br>';
							$body .= '<br>Blessings,<br/ >Prayer Team</p>';
                            $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
							$body .= '<br>'.$link1;
							}                                        
                   
                    wp_mail( $to, $subject, $body, $headers );
                    
                    return 'success';
                    }
                    }

                    return 'success';

                }

            }

            return 'failure';

        }

            public function website_email($sender)
            {
                $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                $sitename = strtolower($_SERVER['SERVER_NAME']);
                if (substr($sitename, 0, 4) == 'www.') {
                    $sitename = substr($sitename, 4);
                }
                $illegal_chars_username = array('(', ')', '<', '>', ',', ';', ':', '\\', '"', '[', ']', '@', "'", ' ');
                $username = str_replace($illegal_chars_username, "", get_option('blogname'));
                $sender_emailuser = (isset($email_settings['wpe_email_user']) and ! empty($email_settings['wpe_email_user'])) ? $email_settings['wpe_email_user'] : $username.'@'.$sitename;
                $sender_email = $sender_emailuser;

                return $sender_email;
            }

            public function website_name($name)
            {
                $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                $site_name = (isset($email_settings['wpe_email_from']) and ! empty($email_settings['wpe_email_from'])) ? $email_settings['wpe_email_from'] : get_option('blogname');

                return $site_name;
            }
        /**
         * Method to send email to prayer author daily
         */

        public function wpe_prayer_received_perday_autoemail()
        {
            $settings = unserialize(get_option('_wpe_prayer_engine_settings'));
            if (isset($settings['wpe_autoemail'])&& $settings['wpe_autoemail'] == 'true') {
            $modelFactory = new FactoryModelWPE();

            $prayer_performed_obj = $modelFactory->create_object('prayers_performed');


            $results = $prayer_performed_obj->fetch_prayers_recieved_perday();

            if ( ! empty($results)) {

                foreach ($results as $res):

                    // Auto-email

                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    if ( ! empty($res->prayer_author_email)) {
                        $to = $res->prayer_author_email;
                    } else {
                        $to = $res->user_email;
                    }

                    if ( ! empty($to) && $res->prayer_lastname=='*') {
                    $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                    add_filter('wp_mail_from', array($this, 'website_email'));
                    add_filter('wp_mail_from_name', array($this, 'website_name'));
                    
                    $subject = (isset($email_settings['wpe_email_prayed_subject']) and ! empty($email_settings['wpe_email_prayed_subject'])) ? $email_settings['wpe_email_prayed_subject'] : 'Someone prayed for you';
                    if (isset($email_settings['wpe_email_prayed_messages']) AND ! empty($email_settings['wpe_email_prayed_messages'])) {
                        $body = stripslashes($email_settings['wpe_email_prayed_messages']);
                        $body = str_replace(array(
                            '{prayer_author_name}',
                            '{prayer_messages}',
                            ), array(
                            $res->prayer_author_name,
							$res->prayer_messages,
                            ), $body);
                            } else {
                            $body = 'Hello '.$res->prayer_author_name.', <br> <p>Someone prayed for you';
     						$body .= '<b>Request :</b> '.$res->prayer_messages.'<br>';
							$body .= '<br>Blessings,<br/ >Prayer Team</p>';
                            $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
							$body .= '<br>'.$link1;
							}
                   
                    //wp_mail($to, $subject, $body, $headers);

                    }

                endforeach;

            }
            }
        }

    }

}

new WP_Prayer_Engine();


/*  add custome code  to create custome menu to hendle the comment sections */


//print_r($_GET);

$_SESSION['alert_code'] = "";
if(isset($_GET['action'])) {
if ($_GET['action'] == 'delete' && isset($_GET['action'])) {

    $where = array(

        "id" =>  sanitize_text_field($_GET['id']),

    );

    $wpdb->delete($table_prefix."prayer_comment", $where);

    $_SESSION['alert_code'] = 1; //For deletion

}
}

if (isset($_POST['update_comment'])) {

    global $wpdb, $table_prefix;


    $comment = sanitize_textarea_field($_POST['comment']);

    $status = sanitize_text_field($_POST['status']);

    $id = sanitize_text_field($_POST['comment_id']);

    $table = array(

        "comment_content" => $comment,

        "status" => $status,

    );

    $where = array(

        "id" => $id,

    );

    $rs = $wpdb->update($table_prefix."prayer_comment", $table, $where);

    if ($rs) {

        $_SESSION['alert_code'] = 2;  //Code 2 for update

    }

}


add_action('admin_menu', 'my_menu');


function my_menu()
{

    //add_submenu_page("wpe_view_overview", "prayer comment", "Prayer Comment", 6, "prayer_comment_manage", "manage_prayer_comment_section" );

    add_menu_page(__('WP Prayer Comment', WPE_TEXT_DOMAIN), __('WP Prayer Comment', WPE_TEXT_DOMAIN), 'manage_options',
        "prayer_commant", "prayer_commant_function");

}
function load_admin_assets() {

}
add_action( 'admin_enqueue_scripts','load_admin_assets');


function prayer_commant_function()
{
    // include("comment.php");
    ?>

    <?php
    echo '<script>function editaction(id) {jQuery("." + id).toggle();}</script>';
    echo "<script>jQuery(document).ready(function () { jQuery('#example').DataTable({});});</script>";
    //echo "<script>jQuery(function () { jQuery('#accordion').accordion({collapsible: true,heightStyle: 'content'});});</script>";
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <style>

        .pending {

            height: 14px;

            width: 13px;

            border: 2px solid green;

            float: left;

            background-color: green;

        }

        .approvel {

            height: 14px;

            width: 13px;

            border: 2px solid #fb8c00;

            float: left;

            background-color: #fb8c00;

        }

        .approvel_show {

            float: right;

            margin-right: 50%;

        }


    </style>

    </head>

    <body>


    <p>
    <h1><?php _e('Prayer request comments', WPE_TEXT_DOMAIN); ?> </h1> </p>

    <!--        <p>

    <div class="pending_show"><div class="pending"></div> This Color Show Approvel Status

    <div class="approvel_show"><span class="approvel"></span> This Color Show Pending Status</div>

    </div>

    </p>-->


    <?php if (isset($_SESSION['alert_code'])):

        switch ($_SESSION['alert_code']) {

            case 1:

                echo '<div id="message" class="updated notice notice-success is-dismissible"><p>', __('Deleted sucessfully.',
                    WPE_TEXT_DOMAIN), '</p></div>';

                break;


            case 2:

                echo '<div id="message" class="updated notice notice-success is-dismissible"><p>', __('Update sucessfully.',
                    WPE_TEXT_DOMAIN), '</p></div>';

                break;


            default:

                # code...

                break;

        }


    endif;


    //echo sprintf( __( '%s', WPE_TEXT_DOMAIN ), $_SESSION['msg'] );
    ?>



    <?php global $wpdb, $table_prefix;

    $status = 1;

    // echo "Select * from ".$table_prefix."prayer_comment  as ppc inner join ".$table_prefix."prayer_engine as ppe On ppc.prayer_id =  ppe.prayer_id";

    $getallcomment = $wpdb->get_results("Select * from ".$table_prefix."prayer_comment  as ppc inner join ".$table_prefix."prayer_engine as ppe On ppc.prayer_id =  ppe.prayer_id");

    //   prinr_r($getallcomment);

    foreach ($getallcomment as $comment): ?>

        <div class="wpgmp-overview comment_changes_<?php echo esc_html($comment->id); ?>"
             style="display:none;padding-bottom: 18px;">

            <form action="" enctype="multipart/form-data" method="post" name="update_comment">

                <div class="form-horizontal">

                    <div class="form-group">

                        <div class="col-md-3" style="float: left;padding-right: 37px">

                            <label for="request_type"><?php _e('Status', WPE_TEXT_DOMAIN); ?></label> <span
                                    class="inline-star" style="color:#F00;">*</span>

                        </div>

                        <div class="col-md-8">

                            <select name="status">

                                <option value="1" <?php echo esc_html(($comment->status == 1 ? "SELECTED" : "")); ?>><?php _e('Approved',
                                        WPE_TEXT_DOMAIN); ?></option>

                                <option value="0" <?php echo esc_html(($comment->status == 0 ? "SELECTED" : "")); ?>><?php _e('Pending',
                                        WPE_TEXT_DOMAIN); ?></option>

                            </select>

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-md-3" style="float: left;padding-right: 21px">

                            <label for="prayer_messages"><?php _e('Comment', WPE_TEXT_DOMAIN); ?></label><span
                                    class="inline-star" style="color:#F00;">*</span>

                        </div>

                        <div class="col-md-8">

                            <textarea class="form-control" name="comment"
                                      rows="5"><?php echo esc_html($comment->comment_content); ?></textarea>

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="col-md-8">

                            <div class="row">

                                <div class="col-md-12">

                                    <input type="hidden" name="comment_id" value="<?php echo esc_html($comment->id); ?>">

                                    <input class="btn btn-primary" name="update_comment" type="submit"
                                           value="<?php _e('Update', WPE_TEXT_DOMAIN); ?>">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group"></div>

                </div>

            </form>

        </div>

    <?php endforeach; ?>

    <table name="commit" id="example" class="wp-list-table widefat fixed striped pages display">

        <thead>

        <tr>

            <th><?php _e('IP address', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Comment', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Name', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Email', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Status', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Date', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Action', WPE_TEXT_DOMAIN); ?></th>

        </tr>

        </thead>

        <tfoot>

        <tr>

            <th><?php _e('IP address', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Comment', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Name', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Email', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Status', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Date', WPE_TEXT_DOMAIN); ?></th>

            <th><?php _e('Action', WPE_TEXT_DOMAIN); ?></th>

        </tr>

        </tfoot>

        <tbody>

        <?php $status = 1;

        $offset = get_option('gmt_offset');

        $getallcomment = $wpdb->get_results("Select * from ".$table_prefix."prayer_comment  as ppc

	inner join ".$table_prefix."prayer_engine as ppe On ppc.prayer_id =  ppe.prayer_id");

        foreach ($getallcomment as $comment): ?>

            <tr>

                <td class="title column-title has-row-actions column-primary page-title"><?php echo esc_html($comment->comment_author_IP); ?></td>

                <td class="title column-title has-row-actions column-primary page-title"><?php echo esc_html($comment->comment_content); ?></td>

                <td class="title column-title has-row-actions column-primary page-title"><?php echo esc_html($comment->comment_author); ?></td>

                <td class="title column-title has-row-actions column-primary page-title"><?php echo esc_html($comment->comment_author_email); ?></td>

                <td class="title column-title has-row-actions column-primary page-title"><?php echo esc_html(($comment->status == 1 ? "Approved" : "Pending")); ?></td>
				<?php $ptime = date_i18n(get_option('date_format'),strtotime( $comment->comment_date )+$offset*3600 ).' '.date_i18n(get_option('time_format'),strtotime( $comment->comment_date )+$offset*3600 );?>
                <td class="title column-title has-row-actions column-primary page-title"><?php echo esc_html($ptime); ?></td>

                <td class="title column-title has-row-actions column-primary page-title">
                    <div class="button action"
                         onclick="editaction('comment_changes_<?php echo esc_html($comment->id); ?>' );"><?php _e('Edit',
                            WPE_TEXT_DOMAIN); ?></div>
                    <a href="<?php echo home_url()."/wp-admin/admin.php?page=prayer_commant&action=delete&id=".esc_html($comment->id); ?>"
                       class="button action" onclick=" return confirm('<?php _e('Are you sure to delete item ?',
                        WPE_TEXT_DOMAIN); ?>')"><?php _e('Delete', WPE_TEXT_DOMAIN); ?></a></td>

            </tr>

        <?php if ($comment->status == 0):

        $status = 0;
		if(empty($pray)) {$pray='';}
        echo "<script>jQuery('.view_prayer_'".(isset($pray) && is_object($pray)) ? isset($pray->prayer_id) : ''.").attr('Style', 'background-color: #fb8c00;');</script>";

        ?>

        <?php

        endif; ?>

        <?php endforeach;

        if ($status == 1):

        if (isset($pray)) {echo "<script> jQuery('.view_prayer_'".$pray->prayer_id.").attr('style', 'background-color: green;');</script>";}

        endif; ?>

        </tbody>

    </table>


    <?php //endforeach;
    ?>


    <?php
    die;

}


global $wpdb;

$table_name = $wpdb->prefix.'prayer_comment';

if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

    //table not in database. Create new table

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (

	`id` int(11) NOT NULL AUTO_INCREMENT,

	`prayer_id` int(11) NOT NULL,

	`comment_author` varchar(255) NOT NULL,

	`comment_author_email` varchar(255) NOT NULL,

	`comment_author_url` varchar(100) NOT NULL,

	`comment_author_IP` varchar(255) NOT NULL,

	`comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

	`comment_content` text NOT NULL,

	`comment_parent` int(11) NOT NULL,

	`user_id` int(11) NOT NULL,

	`status` int(11) NOT NULL DEFAULT '0',

	PRIMARY KEY (`id`)

	) $charset_collate;";

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

    dbDelta($sql);

} else {

}

add_action( 'admin_footer', 'custom_admin_footer_script' );
add_action( 'wp_footer', 'custom_admin_footer_script' );

function custom_admin_footer_script() { ?>
    <script>var textareaNames=["prayer_messages","wpe_email_req_messages","wpe_email_praise_messages","wpe_email_admin_messages","wpe_terms_and_condition","wpe_email_prayed_messages","wpe_thankyou"];textareaNames.forEach(function(e){var a=document.getElementsByName(e)[0];if(a){var s=a.value.replace(/\\(?=[\\/"'])/g,"");a.value=s}});</script>
<?php }
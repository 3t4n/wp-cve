<?php
/**
 * Plugin Name: LH Zero Spam
 * Plugin URI: https://lhero.org/portfolio/lh-zero-spam/
 * Description: This is a very lightweight anti spam plugin utilising JavaScript nonce security to prevent comment and registration spam.
 * Version: 1.13
 * Requires PHP: 7.0
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
 * Text Domain: lh_zero_spam
 * Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* LH Zero Spam plugin class
*/


if (!class_exists('LH_Zero_spam_plugin')) {

class LH_Zero_spam_plugin {
    
    
    private static $instance;

    
    static function return_plugin_namespace(){

        return 'lh_zero_spam';

    }
    
    static function plugin_name(){
        
        return 'LH Zero Spam';
        
    }
    
    
    static function return_input_text(){
        
        $return = '<input id="lh_zero_spam-nonce_value" name="lh_zero_spam-nonce_value" class="lh_zero_spam-nonce_value" type="hidden" size="15" value="" />';
     
        return $return;
        
    }
    
    static function output_input_text(){
        
        echo '<noscript><strong>'.__("Please switch on Javascript to enable registration", self::return_plugin_namespace() ).'</strong></noscript>'."\n";

        //echo self::return_input_text(); 

        wp_enqueue_script(self::return_plugin_namespace().'-script');
        
    }
    
    static function curpageurl() {
        
    	$pageURL = 'http';
    
    	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
    	    
    		$pageURL .= "s";
    		
        }
    
    	$pageURL .= "://";
    
    	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
    	    
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    
    	} else {
    	    
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    		
        }
    
    	return $pageURL;
    	
    }
    
    static function protect_wplogin_and_wpregister_directly(){
        $ABSPATH_MY = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, ABSPATH);
        
        $simple_current_url = remove_query_arg(array_keys($_GET), self::curpageurl());
        
        if ((in_array($ABSPATH_MY.'wp-login.php', get_included_files()) || in_array($ABSPATH_MY.'wp-register.php', get_included_files()) ) || (isset($_GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') || $_SERVER['PHP_SELF']== '/wp-login.php'){
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST')  {
    
                if (!empty($_POST['log']) && (empty($_POST['lh_zero_spam-nonce_value']) or !wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], 'lh_zero_spam_nonce'))){
                
                    //print_r($_POST);
        
                    wp_die( __( 'wp-login.php protected by ', self::return_plugin_namespace() ).self::plugin_name() );   
                
                } 
    
            }
        
        }
    
    }
    


    public function add_custom_comment_fields($fields) {
    
        if (!is_user_logged_in() ) {
    
            $fields[ 'lh_zero_spam' ] =   "<noscript><p><strong>". __('Please switch on Javascript to enable commenting', self::return_plugin_namespace() )."</strong></p></noscript>\n";
            wp_enqueue_script(self::return_plugin_namespace().'-script');
    
        }
    
        return $fields;
    
    }





    public function preprocess_comment( $commentdata ) {
        
		$valid = false;
        global $wp;

        if ( (is_user_logged_in()) or (!empty($_POST['lh_zero_spam-nonce_value']) && wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], "lh_zero_spam_nonce")) or isset($wp->query_vars['rest_route']) or ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) ) {

            return $commentdata;

        } else {

            //print_r($_POST);
            wp_die( __( 'comments protected by ', self::return_plugin_namespace() ).self::plugin_name() );   

        }

    }


    public function comment_form( $postid ) {
       
        echo  "<noscript><strong>". __('Please switch on Javascript to enable commenting', self::return_plugin_namespace() )."</strong></noscript>\n\n\n";
        
        wp_enqueue_script(self::return_plugin_namespace().'-script');
        
    }


    public function add_class_to_form($args){
        
        if (is_string($args['class_form'])){
        
            $args['class_form'] .= ' '.self::return_plugin_namespace().'-add_nonce';
            $args['class_form'] = trim($args['class_form']);
        
            
        } elseif (is_array($args['class_form'])){
         
            $args['class_form'][] =  self::return_plugin_namespace().'-add_nonce';   
            $args['class_form'] = array_unique($args['class_form']);
        
        }
        
        return $args;
        
    }


    public function add_nonce_to_wp_login(){
        
        echo '<noscript><strong>'.__("Please switch on Javascript to enable login", self::return_plugin_namespace() ).'</strong></noscript>'."\n";
    
        wp_enqueue_script(self::return_plugin_namespace().'-script');    
        
    }

    public function protect_wp_login($user, $username, $password){
        
        if ( ($GLOBALS['pagenow'] === 'wp-login.php') && ($_SERVER['REQUEST_METHOD'] === 'POST') && !empty($_POST['user_login']) ) {
    
            if (!empty($_POST['lh_zero_spam-nonce_value']) && wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], 'lh_zero_spam_nonce')){
    
                return $user;    
        
            } else {
        
                //print_r($_POST);
                wp_die( __( 'wp-login.php protected by ', self::return_plugin_namespace() ).self::plugin_name() ); 
        
            }
        
        }
    
        return $user;    
        
    }


    public function add_indieauth_support($current_user_id, $client_id ){
        
        self::output_input_text();
        
    }

    public function add_custom_registration_fields($fields) {
    
        self::output_input_text();
    
    }


    public function preprocess_registration( $errors, $sanitized_user_login, $user_email ) {
    
        if ( !wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], "lh_zero_spam_nonce") ) {
    
            $errors->add( 'spam_error', __( '<strong>ERROR</strong>: Your registration may be spam or you need to activate javascript.', self::return_plugin_namespace()) );
    
        }
    
        return $errors;
    
    }



    public function maybe_add_bp_signup_errors( $result ) {
        
        if (empty($result['errors'])){
            
            if ( empty($_POST['lh_zero_spam-nonce_value']) or !wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], "lh_zero_spam_nonce") ) {

                $errors = new WP_Error();
                $errors->add( 'spam_error',  __( '<strong>ERROR</strong>: Your registration may be spam or you need to activate javascript.', self::return_plugin_namespace()) );
                $result['errors'] = $errors;

            }
            
        }
    
        return $result;
    
    
    }


    public function bp_signup_validate($result) {
    		
        global $wp;
    
        if ( (is_user_logged_in()) or (!empty($_POST['lh_zero_spam-nonce_value']) and wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], "lh_zero_spam_nonce")) ) {
    
            return $result;
    
    
        } else {
    
            //print_r($_POST);
            wp_die( __( 'buddypress registration protected by ', self::return_plugin_namespace() ).self::plugin_name() ); 
        
        }
    
    }

    public function add_custom_checkout_field($checkout){
    
        echo '<noscript><p><strong>'. __('Please switch on Javascript to enable ordering', self::return_plugin_namespace() ).'</strong></p></noscript>'."\n";
    
        wp_enqueue_script(self::return_plugin_namespace().'-script');    
    
    }

    public function woocommerce_spam_validation( $data, $errors ){
        
        global $wp;
        
        if ( (is_user_logged_in()) or (!empty($_POST['lh_zero_spam-nonce_value']) and wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], "lh_zero_spam_nonce")) or isset($wp->query_vars['rest_route']) or ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) ) {
    
            return true;
    
        } else {
    
            $errors->add( 'validation', 'You appear not to have javascript switched on' );
    
        }
     
    }


    public function wpmu_validate_user_signup($results){  
        
        if ( (is_user_logged_in()) or (!empty($_POST['lh_zero_spam-nonce_value']) and wp_verify_nonce( $_POST['lh_zero_spam-nonce_value'], "lh_zero_spam_nonce")) or isset($wp->query_vars['rest_route']) or ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) ) {
            
            
        } else {
      
    
            $error = new WP_Error( 'generic', __( "Please enable Javascript to complete registration", self::return_plugin_namespace() ) );
    
            $results['errors'] = $error;
    
        }
            
        return $results;
        
    }

    public function register_core_script(){
        
        if (!is_admin()){
            
            if (!class_exists('LH_Register_file_class')) {
             
                include_once('includes/lh-register-file-class.php');
            
            }
        
            $add_array = array();
            $add_array['defer'] = 'defer';
            $add_array['data-nonce_holder'] = wp_create_nonce("lh_zero_spam_nonce");
        
            $lh_zeros_spam_core_script = new LH_Register_file_class( self::return_plugin_namespace().'-script', plugin_dir_path( __FILE__ ).'scripts/lh-zero-spam.js', plugins_url( '/scripts/lh-zero-spam.js', __FILE__ ), true, array(), false, $add_array);
        
            unset($add_array);
        
        }
    
        
    }


    public function force_script_enqueue($classes, $action){
        
        wp_enqueue_script(self::return_plugin_namespace().'-script');
        
        return $classes;
        
    }


    public function plugin_init(){
    
        
        self::protect_wplogin_and_wpregister_directly();    
    
        //potentially load translations
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' );
        
        //Standard comment protection
        add_filter('comment_form_default_fields', array($this,'add_custom_comment_fields'));
        add_action( 'preprocess_comment', array( $this, 'preprocess_comment' ), 9, 1);
        add_action('comment_form', array($this,'comment_form'), 10, 1 );
        add_filter('comment_form_defaults', array($this,'add_class_to_form'),10000,1);
        
        //protect wp-login.php
        $priority = PHP_INT_MAX - 5;
        add_action('login_form', array( $this, 'add_nonce_to_wp_login' ), $priority);
        add_filter('authenticate', array( $this, 'protect_wp_login' ), 10,3);
        add_filter('indieauth_authorization_form', array( $this, 'add_indieauth_support' ), 10,2);
        
        //standard registration protection
        add_action( 'register_form', array($this,"add_custom_registration_fields"));
        add_action( 'lh_bp_check_ins-register_form_shortcode_output', array($this,"add_custom_registration_fields"));
        add_filter( 'registration_errors', array( $this, 'preprocess_registration' ), 10, 3 );
        add_filter( 'bp_core_validate_user_signup', array($this,'maybe_add_bp_signup_errors'), 100000,1);
        
        //add it to the check in registration form
        add_action( 'lh_bp_better_signup-register_form_shortcode_output', array($this,'add_custom_registration_fields'));
        
        //Buddypress registration protection, this may not be the best hook
        add_action( 'bp_before_account_details_fields', array($this, 'add_custom_registration_fields'));
        add_action( 'bp_core_validate_user_signup', array( $this, 'bp_signup_validate' ),5,1 );
        
        //Woocommerce Spam order protection
        add_action( 'woocommerce_after_order_notes', array($this, 'add_custom_checkout_field'), 10,1);
        add_action( 'woocommerce_after_checkout_validation', array($this, 'woocommerce_spam_validation'), 10, 2);
        
        //wp-signup protection
        add_action('signup_extra_fields', array($this, 'add_custom_registration_fields'));
        add_filter('wpmu_validate_user_signup', array($this, 'wpmu_validate_user_signup'), 10, 1);
        
        //register the core script
        add_action( 'wp_loaded', array($this, 'register_core_script'), 10 );  
        add_action( 'login_init', array($this, 'register_core_script'), 10 );
        
        //force the script on wp-login
        add_filter('login_body_class',array($this, 'force_script_enqueue'), 10 , 2);

    }

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        
        if (null === self::$instance) {
            
            self::$instance = new self();
            
        }
 
        return self::$instance;
        
    }



    public function __construct() {
        
        //run whatever on plugins loaded
        add_action( 'plugins_loaded', array($this,'plugin_init'),1);
    
    }

}


$lh_zero_spam_instance = LH_Zero_spam_plugin::get_instance();

}

?>
<?php
/*
  Plugin Name: Antideo Email Validator
  Plugin URI: https://wordpress.org/plugins/antideo-email-validator/
  Description: This plugin will <strong>detect and block disposable, temporary, fake email address</strong> every time an email is submitted. It maintains its own local blacklist.
  Version: 1.0.8
  Author: Antideo
  Author URI: https://www.antideo.com/
  License: GPLv2 or later
  Text Domain: antideo-email-validator
 */

# NOPE #
defined('ABSPATH') or die('Nope nope nope...');
define( 'ADEV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include_once (ABSPATH . 'wp-admin/includes/plugin.php');    

$ADEV_Plugin = new ADEV_AntideoEmailValidator();

class ADEV_AntideoEmailValidator
{
    const API_EMAIL = 'http://api.antideo.com/wpemail/';
    const API_DISPOSABLE_EMAIL = 'http://api.antideo.com/disposable_emails/';
    const API_VERIFY = 'http://api.antideo.com/verify_plugin_API/';
    const API_SAVE_BLACK_LIST = 'http://api.antideo.com/save_black_listed_domains/';

    private $deaFound = false;
    private $reason = "";
    private $apiToken = "";
    private $isValidToken = false;

    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'loadTextDomain'));

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'uninstall'));

        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_init', array($this, 'settings'));

        add_filter('plugin_action_links', array($this, 'addActionLinks'), 10, 5);

        if (isset($_POST['_wpcf7']) && is_plugin_active("contact-form-7/wp-contact-form-7.php") ) {
            // Contact Form 7
            add_filter('wpcf7_validate_email', array($this, 'cf7Error'), 5, 2);
            add_filter('wpcf7_validate_email*', array($this, 'cf7Error'), 5, 2);
        } else if (isset($_POST['frm_action']) && is_plugin_active('formidable/formidable.php') ) {
            // Formidable
            add_action('frm_validate_entry', array($this, 'formidableError'), 1, 2);
        } else if (is_plugin_active('contact-form-plugin/contact_form.php') && isset($_POST['cntctfrm_contact_email'])) { //contact form BWS
            add_filter('cntctfrm_check_form', array($this, 'bwsError'), 11);
        } else if ((isset($_POST['action']) && $_POST['action'] == 'nf_ajax_submit') && is_plugin_active('ninja-forms/ninja-forms.php') ) {
            //ninja forms
            add_filter('ninja_forms_submit_data',array($this, 'ninjaError'), 5, 1);
        } else { // Other plugins that used is_email
            add_filter('is_email', array($this, 'isEmail'), 11, 2);
            add_filter('registration_errors', array($this, 'deaError'));
            add_filter('user_profile_update_errors', array($this, 'deaError'));
            add_filter('login_errors', array($this, 'deaError'));
        }

        add_action( 'admin_notices', array($this,'admin_notice') );

        add_action('rest_api_init', function () {
            register_rest_route( 
                'antideo-email-validator/v1', 
                'disposable_emails/(?P<last_updated_date>[0-9-]+)',
                array(
                    'methods'  => 'GET',
                    'callback' => array($this, "get_disposable_emails"),
                    'permission_callback' => '__return_true'
                )
            );
        });

        add_action('rest_api_init', function () {
            register_rest_route( 
                'antideo-email-validator/v1', 
                'disposable_emails/',
                array(
                    'methods'  => 'GET',
                    'callback' => array($this, "get_disposable_emails"),
                    'permission_callback' => '__return_true'
                )
            );
        });
        add_action( 'admin_notices', array($this,'admin_notice_api_key_success') );
        
    }

    function admin_notice_api_key_success() {
        if($this->isValidToken){
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html__('You are now subscribed to premium version of the plugin','antideo-email-validator'); ?></p>
            </div>
            <?php
        }
    }

    function get_disposable_emails($request)
    {
        global $wpdb;
        $token = get_option('adev_token');
        $last_updated_date = $request['last_updated_date'];
        $url = self::API_DISPOSABLE_EMAIL . $last_updated_date . '?apiKey=' . $token;
        $response = wp_remote_get($url, array('timeout' => 120));
        $responseBody = wp_remote_retrieve_body($response);
        $dataObj = @json_decode($responseBody);
        $now = date("Y-m-d H:i:s");
        update_option('adev_token_expired_message', '', false);
        if($dataObj->result){
            foreach($dataObj->result as $key=>$value){
                $sql = "INSERT INTO {$wpdb->prefix}adev_disposable_domains (domain, created_time) VALUES (%s,%s)";
                $sql = $wpdb->prepare($sql, $value,$now);
                $wpdb->query($sql);
            }
        }else{
            if($dataObj->is_active == "0"){
                update_option('adev_token_expired_message', 'Your api token has been expired', false);
            }
        }
    }

    function admin_notice() {
        settings_errors( 'adev_token' );
        settings_errors( 'adev_same_domain' );
        settings_errors( 'adev_same_email' );
        settings_errors( 'adev_token_expired_message' );
    }

    private function getErrorMessage()
    {
        $message = esc_html__( 'You have entered an invalid email address, Please try again with a valid email address','antideo-email-validator');
        switch ($this->reason) {
            case 'invalid_syntax':
                $message = esc_html__( 'You have entered an invalid email address, Please try again with a valid email address','antideo-email-validator');
                break;
            case 'disposable_email':
                $message = esc_html__( 'You have entered a disposable email address, Please try again with a valid email address','antideo-email-validator');
                break;
            case 'free_email':
                $message = esc_html__( 'You have entered a free email address, Please enter your business email address','antideo-email-validator');
                break;
            case 'generic_email':
                $message = esc_html__( 'You have entered a generic email address, Please enter your business email address','antideo-email-validator');
                break;            
            case 'invalid_mx_record':
                $message = esc_html__( 'The email provider does not have a valid mx record, Please try again with a valid email address','antideo-email-validator');
                break;
            case 'invalid_host':
                $message = esc_html__( 'You have entered an invalid email address, Please try again with a valid email address','antideo-email-validator');
                break;
            case 'black_listed_email':
            case 'black_listed_domain':
                $message = esc_html__( 'You have entered a black listed email/domain, Please try again with a valid email address','antideo-email-validator');
                break;
            case 'scam_email':
                $message = esc_html__( 'You have entered a scam email address, Please try again with a non scam email address','antideo-email-validator');
                break;
            case 'spam_email':
                $message = esc_html__( 'You have entered a spam email address, Please try again with a non spam email address','antideo-email-validator');
                break;    
        }
        return $message;
    }

    public function formidableError($result,$values)
    {
        foreach ($values['item_meta'] as $key => $value) {
            if (preg_match("/^\S+@\S+\.\S+$/", $value)) {
                $email = $value;
                $is_email = $this->isEmail($email,$email);
                if(!$is_email){
                    $result['ct_error'] = $this->getErrorMessage();
                }
                return $result;
            }
        }
    }

    public function bwsError()
    {
        if (!(empty(sanitize_email($_POST['cntctfrm_contact_email']))) && (sanitize_email($_POST['cntctfrm_contact_email']) != '')) {
            $email = sanitize_email($_POST['cntctfrm_contact_email']);
            $is_email = $this->isEmail($email,$email);
            if(!$is_email){
                $cntctfrm_error_message['error_email'] = $this->getErrorMessage();
            }
            return ;
        }
    }

    public function ninjaError($form_data)
    {
        foreach ($form_data['fields'] as $key => $field) {
            $value = $field['value'];
            if (preg_match('/@.+\./', $value) && strpos($value, "\n") === false && strpos($value, '\n') === false) {
                $email = $value;
                $is_email = $this->isEmail($email,$email);
                if(!$is_email){
                    $field_id = $field['id'];
                    $form_data['errors']['fields'][$field_id] = $this->getErrorMessage();
                }
            }            
        }
        return $form_data;
    }

    public function cf7Error($result,$tags)
    {
        $tags = new WPCF7_FormTag($tags);
        $name = $tags->name;
        $email = sanitize_email($_POST[$name]);
        $is_email = $this->isEmail($email,$email);
        if(!$is_email){
            $result->invalidate($tags, $this->getErrorMessage());
        }
        return $result;
    }

    public function deaError($errors)
    {
        if ($this->deaFound) {

            $message = $this->getErrorMessage();

            if ($errors instanceof WP_Error) {
                $errors->add('disposable_email', $message);
            } elseif(is_string($errors)) {
                $errors .= '<br>' . $message;
            }

            $this->deaFound = false;
            $this->reason = "";
        }
        return $errors;
    }

    public function loadTextDomain()
    {
        load_plugin_textdomain('antideo-email-validator');
    }

    public function activate()
    {
        $token = get_option('adev_token');

        if (!$token || !$this->isValidToken($token)) {
            update_option('adev_token', '', false);
        }

        if (!get_option('adev_token_expired_message')) {
            update_option('adev_token_expired_message', '', false);
        }

        if (!get_option('adev_whitelist')) {
            $email =  wp_get_current_user()->user_email;
            update_option('adev_whitelist', $email, false);
        }

        if (!get_option('adev_blacklist')) {
            update_option('adev_blacklist', '', false);
        }
        
        if (!get_option('adev_domain_whitelist')) {
            $domain = $_SERVER['HTTP_HOST'];
            update_option('adev_domain_whitelist', $domain, false);
        }

        if (!get_option('adev_domain_blacklist')) {
            update_option('adev_domain_blacklist', '', false);
        }       

        if (get_option('adev_allow_free_email') === false) {
            update_option('adev_allow_free_email', 1, false);
        }
        
        if (get_option('adev_allow_role_business_email') === false) {
            update_option('adev_allow_role_business_email', 1, false);
        }

        if (get_option('adev_allow_disposable_email') === false) {
            update_option('adev_allow_disposable_email', 0, false);
        }

        if (get_option('adev_attempts') === false) {
            update_option('adev_attempts', 0, false);
        }

        if (get_option('adev_ignored_uris') === false) {
            update_option('adev_ignored_uris', '/wp-admin/admin.php?page=mailpoet-', false);
        }

        $this->create_tables();
    }

    public function create_tables()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'adev_disposable_domains';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            created_time datetime,
            domain varchar(250) NOT NULL,
            PRIMARY KEY id (id)
        ) DEFAULT CHARACTER SET utf8;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        $table_name = $wpdb->prefix . 'adev_blocked_emails';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            created_time datetime ,
            updated_time datetime ,
            email varchar(250) NOT NULL,
            `type` varchar(50) NOT NULL,
            block_count int DEFAULT 1, 
            PRIMARY KEY id (id),
            CONSTRAINT email UNIQUE (email)
        ) DEFAULT CHARACTER SET utf8 ;";

        dbDelta( $sql );
    }

    public function drop_tables()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'adev_disposable_domains';
        $sql = "DROP TABLE IF EXISTS $table_name";
       
        $wpdb->query($sql);

        $table_name = $wpdb->prefix . 'adev_blocked_emails';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }

    public function uninstall()
    {
        delete_option('adev_token');
        delete_option('adev_token_expired_message');
        
        delete_option('adev_whitelist');
        delete_option('adev_blacklist');
        
        delete_option('adev_domain_whitelist');
        delete_option('adev_domain_blacklist');
        
        delete_option('adev_allow_free_email');
        delete_option('adev_allow_role_business_email');
        delete_option('adev_allow_disposable_email');
        
        delete_option('adev_ignored_uris');
        delete_option('adev_attempts');

        $this->drop_tables();
    }

    public function menu()
    {
        $iconpng = ADEV_PLUGIN_URL . 'assets/antideo_16x16.png';
    
        add_menu_page(
            "Antideo Email Validator", // $page_title,
            "Antideo Email Validator", // $menu_title,
            'manage_options', // $capability,
            'antideo-email-validator-dashboard', // $menu_slug,
            "",
            $iconpng,
            76
        );
      
        add_submenu_page(
            'antideo-email-validator-dashboard', // plugins parent
            "Antideo Email Validator Dashboard", // $page_title,
            "Dashboard", // $menu_title,
            'manage_options', // $capability,
            'antideo-email-validator-dashboard', // $menu_slug,
            array($this, 'dashboard') // $function
        );

        add_submenu_page(
            'antideo-email-validator-dashboard', // plugins parent
            "Settings", // $page_title,
            'Settings', // $menu_title,
            'manage_options', // $capability,
            'antideo-email-validator-settings', // $menu_slug,
            array($this, 'settingsPage') // function
        ); 

        add_submenu_page(
            'antideo-email-validator-dashboard', // plugins parent
            "About Antideo Email Validator", // $page_title,
            'About Antideo', // $menu_title,
            'manage_options', // $capability,
            'antideo-email-validator-about', // $menu_slug,
            array($this, 'aboutUs') // function
        ); 
    }

    function addActionLinks($actions, $plugin_file)
    {
        static $plugin;

        if (!isset($plugin)) {
            $plugin = plugin_basename(__FILE__);
        }

        if ($plugin == $plugin_file) {
            $settings = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=antideo-email-validator-settings')) . '">' . __('Settings', 'antideo-email-validator') . '</a>';

            $actions = array_merge(array(
                'settings' => $settings,
            ), $actions);
        }

        return $actions;
    }

    public function aboutUs()
    {
        include plugin_dir_path(__FILE__) . '/about-us.php';
    }

    public function dashboard()
    {
        include plugin_dir_path(__FILE__) . '/dashboard.php';
    }

    public function settingsPage()
    {
        include plugin_dir_path(__FILE__) . '/settings.php';
    }

    public function settings()
    {
        register_setting('adev-settings-group', 'adev_token', array($this, 'validateToken'));
        
        register_setting('adev-settings-group', 'adev_whitelist', array($this, 'cleanList'));
        register_setting('adev-settings-group', 'adev_blacklist', array($this, 'checkEmail'));
                
        register_setting('adev-settings-group', 'adev_domain_whitelist', array($this, 'cleanList'));
        register_setting('adev-settings-group', 'adev_domain_blacklist', array($this, 'checkDomain'));
        
        register_setting('adev-settings-group', 'adev_ignored_uris', array($this, 'cleanList'));
        
        register_setting('adev-settings-group', 'adev_allow_role_business_email');
        register_setting('adev-settings-group', 'adev_allow_free_email');
        register_setting('adev-settings-group', 'adev_allow_disposable_email');         
        
        if($this->apiToken == ''){
			$adev_blacklist = "";
			if(isset($_POST['adev_blacklist'])){
				$adev_blacklist = sanitize_textarea_field($_POST['adev_blacklist']);
			}
			
            $list1 = array_filter(array_map(array($this,'getHostPart'), explode("\n", $adev_blacklist)));
			
			$adev_domain_blacklist = "";
			if(isset($_POST['adev_domain_blacklist'])){
				$adev_domain_blacklist = sanitize_textarea_field($_POST['adev_domain_blacklist']);
			}
			
            $list2 = array_filter(array_map('trim', explode("\n", $adev_domain_blacklist)));
            
            $list3 = array_unique(array_merge($list1, $list2));

            if(sizeof($list3)){     
                
                $fields = array(
                    'domains' => $list3,
                    'url'=>$this->getBaseUrl()
                );
        
                $fields_string = http_build_query($fields);

                $ch = curl_init();

                //set the url, number of POST vars, POST data
                curl_setopt($ch,CURLOPT_URL, self::API_SAVE_BLACK_LIST);
                curl_setopt($ch,CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                
                //execute post
                $result = curl_exec($ch);
                             
                //close connection
                curl_close($ch);
            }                     
        }
    }

    private function getHostPart($email){
        $parts = explode('@', $email);
        return trim(end($parts));
    }

    public function validateToken($token)
    {
        if (!$token || !$this->isValidToken($token)) {
            return '';
        }

        $this->isValidToken = true;
        $this->apiToken = $token;
        return $token;
    }

    private function getBaseUrl() 
    {
        $currentPath = $_SERVER['PHP_SELF']; 
        $pathInfo = pathinfo($currentPath); 
        $hostName = $_SERVER['HTTP_HOST']; 
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
        return $protocol.$hostName.$pathInfo['dirname']."/";
    }

    private function getAPIEndPoint(){
        return str_replace("wp-admin/","", $this->getBaseUrl())."wp-json/antideo-email-validator/v1/disposable_emails/";
    }

    public function isValidToken($token)
    {
        global $wp_version;
        
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        $url = self::API_VERIFY . '?apiKey=' . $token .'&version=' . $wp_version . '&referer=' . $referer . '&apiUrl='.$this->getAPIEndPoint();

        $response = wp_remote_get($url, array('timeout' => 60));
        $responseBody = wp_remote_retrieve_body($response);
        $dataObj = @json_decode($responseBody);

        if (!$dataObj) {        
            $errorMessage = "The API key you have entered is not valid";
            add_settings_error('adev_token', 'adev_token', $errorMessage);
            return false;
        }

        if ($dataObj->status == 'success') {
            return true;
        }

        $errorMessage = "The API key you have entered is not valid";
        add_settings_error('adev_token', 'adev_token', $errorMessage);

        return false;
    }

    public function cleanList($list)
    {
        $cleanList = array_unique(array_filter(array_map('trim', explode("\n", $list))));
        natcasesort($cleanList);
        if(!$this->isValidToken){
            $cleanList = array_slice($cleanList, 0, 50);
        }
        return implode("\n", $cleanList);
    }
    
    public function checkEmail($list)
    {
        if(sizeof(array_intersect( explode("\n", sanitize_textarea_field($_POST['adev_whitelist'])), explode("\n", sanitize_textarea_field($_POST['adev_blacklist'])) ))){
            $errorMessage = "Same email can not be added in the blacklist and the whitelist";
            add_settings_error('adev_same_email', 'adev_same_email', $errorMessage);
            return "";
        }
        $cleanList = array_unique(array_filter(array_map('trim', explode("\n", $list))));
        natcasesort($cleanList);
        if(!$this->isValidToken){
            $cleanList = array_slice($cleanList, 0, 50);
        }
        return implode("\n", $cleanList);
    }
    
    public function checkDomain($list)
    {
		$adev_domain_whitelist = "";
		if(isset($_POST['adev_domain_whitelist'])){
			$adev_domain_whitelist = sanitize_textarea_field($_POST['adev_domain_whitelist']);
		}
		
		$adev_domain_blacklist = "";
		if(isset($_POST['adev_domain_blacklist'])){
			$adev_domain_blacklist = sanitize_textarea_field($_POST['adev_domain_blacklist']);
		}
		
        if(sizeof(array_intersect( explode("\n", $adev_domain_whitelist), explode("\n", $adev_domain_blacklist)))){
            $errorMessage = "Same domain cannot be added in the blacklist and the whitelist";
            add_settings_error('adev_same_domain', 'adev_same_domain', $errorMessage);
            return "";
        }
        $cleanList = array_unique(array_filter(array_map('trim', explode("\n", $list))));
        natcasesort($cleanList);
        if(!$this->isValidToken){
            $cleanList = array_slice($cleanList, 0, 50);
        }
        return implode("\n", $cleanList);
    }

    protected function flatten($array)
    {
        $result = '';

        foreach ($array as $value) {
            if (is_array($value)) {
                $result .= $this->flatten($value);
            } elseif (is_scalar($value)) {
                $result .= $value;
            }
        }

        return $result;
    }

    private $requestContents;

    protected function getRequestContents()
    {
        if ($this->requestContents === null) {
            $this->requestContents = '';
            if (!empty($_POST)) {
                $this->requestContents .= $this->flatten($_POST);
            }

            if (!empty($_GET)) {
                $this->requestContents .= $this->flatten($_GET);
            }
        }

        return $this->requestContents;
    }

    private $results = [];

    public function isEmail($isEmail, $email)
    {
        if (!$isEmail) {
            return $isEmail;
        }

        $parts = explode('@', $email);
        $domain = end($parts);

        if(isset($this->results[$email])) {
            return $this->results[$email];
        }

        return $this->results[$email] = $this->deaEmailCheck($email);
    }

    public function deaEmailCheck($email)
    {
        $parts = explode('@', $email);
        $domain = end($parts);
        $token = get_option('adev_token');

        if (!stripos($this->getRequestContents(), $domain)) {
            return true;
        }  
        
        update_option('adev_attempts', get_option('adev_attempts') + 1, false);

        $ignoredURIs = explode("\n", get_option('adev_ignored_uris'));
        if($ignoredURIs) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if(strpos($requestUri, 'admin-ajax.php') && isset($_SERVER['HTTP_REFERER'])) {
                $requestUri = $_SERVER['HTTP_REFERER'];
            }

            foreach ($ignoredURIs as $uri) {
                if ($uri != '' && stripos($requestUri, $uri) !== false) {
                    return true;
                }
            }
        }

        $blacklist = explode("\n", get_option('adev_blacklist'));
        $whitelist = explode("\n", get_option('adev_whitelist'));
        
        $domain_blacklist = explode("\n", get_option('adev_domain_blacklist'));
        $domain_whitelist = explode("\n", get_option('adev_domain_whitelist'));

        if (in_array($email, $whitelist)) {
            return true;
        }
		
	$white_listed = false;
	foreach($domain_whitelist as $key => $value){
    	    if ($value && str_ends_with($domain, $value) == true) {
            	$white_listed = true;
           }
	}
        
        if ($white_listed) {
            return true;
        }

        if (in_array($email, $blacklist)) {
            $this->deaFound = true;
            $this->reason = "black_listed_email";
            $this->save_blocked_email($email);
            return false;
        }
       

	$black_listed = false;
	foreach($domain_blacklist as $key => $value){             
           if ($value && str_ends_with($domain, $value) == true) {
        	$black_listed = true;
    	    }
	}
 
        if ($black_listed) {
            $this->deaFound = true;
            $this->reason = "black_listed_domain";
            $this->save_blocked_email($email);
            return false;
        }

        $row = $this->get_local_record($email);

        if(!is_null($row)){
            if($row->type == 'disposable_email'){
                if(get_option('adev_allow_disposable_email')  != "1" ){
                    $this->deaFound = true;
                    $this->reason =  $row->type;
                    $this->save_blocked_email($email);
                    return false;
                }
            }else if($row->type == 'generic_email'){
                if(get_option('adev_allow_role_business_email')  != "1" ){
                    $this->deaFound = true;
                    $this->reason =  $row->type;
                    $this->save_blocked_email($email);
                    return false;
                }
            }else  if($row->type == 'free_email'){
                if(get_option('adev_allow_free_email')  != "1" ){
                    $this->deaFound = true;
                    $this->reason =  $row->type;
                    $this->save_blocked_email($email);
                    return false;
                }
            }else{
                $this->deaFound = true;
                $this->reason =  $row->type;
                $this->save_blocked_email($email);
                
                if($this->reason === "invalid_host"){
                    include_once 'local.php';
                    $result = adev_getLocalValidationResult($email);
                
                    if($result['valid_mx_records'] = 1){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            
        }   
        
        if($this->is_disposable_provider($domain)){
            if(get_option('adev_allow_disposable_email')  != "1" ){
                $this->deaFound = true;
                $this->reason =  "disposable_email";
                $this->save_blocked_email($email);
                return false;
            }
        }   

        include_once 'local.php';
      
        $result = adev_getLocalValidationResult($email);

        if(!$result['valid_format']) {
            $this->deaFound = true;
            $this->reason = "invalid_syntax";
            $this->save_blocked_email($email);
            return false;
        }

        if(!$result['valid_host']){
              if( $result['valid_mx_records'] = 1){
                return true;
            } else {
                $this->deaFound = true;
                $this->reason = 'invalid_host';            
                $this->save_blocked_email($email);
                return false;
            }
            // $this->deaFound = true;
            // $this->reason = 'invalid_host';            
            // $this->save_blocked_email($email);
            // return false;
        }
        if ($token) {
            if(!$result['valid_mx_records']){
                $this->deaFound = true;
                $this->reason = 'invalid_mx_records';
                $this->save_blocked_email($email);
                return false;
            }
        }

        if($result['disposable_email_provider']){
            if(get_option('adev_allow_disposable_email')  != "1" ){
                $this->deaFound = true;
                $this->reason = "disposable_email";
                $this->save_blocked_email($email);
                return false;
            }
        }

        if($result['role_or_business_email']){
            if(get_option('adev_allow_role_business_email') != "1" ){
                $this->deaFound = true;
                $this->reason = "generic_email";
                $this->save_blocked_email($email);
                return false;
            }
        }

        if($result['free_email_provider']){
            if(get_option('adev_allow_free_email')  != "1" ){
                $this->deaFound = true;
                $this->reason = "free_email";
                $this->save_blocked_email($email);
                return false;
            }
        }

        return true; 
    }

    private function save_blocked_email($email)
    {
        global $wpdb;
        $now = date("Y-m-d H:i:s");
        $sql = "INSERT INTO {$wpdb->prefix}adev_blocked_emails (email, `type`,`created_time`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE block_count = block_count + 1, updated_time = %s ";
        $sql = $wpdb->prepare($sql, $email, $this->reason, $now, $now);
        $wpdb->query($sql);
    }

    private function get_local_record($email)
    {
        global $wpdb;
        $row =  $wpdb->get_row("SELECT email, `type` FROM {$wpdb->prefix}adev_blocked_emails WHERE email LIKE '$email' ");
        return $row;
    }

    private function is_disposable_provider($domain)
    {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) C FROM {$wpdb->prefix}adev_disposable_domains WHERE domain LIKE '$domain' ");

        if($count > 0){
            return true;
        }
        return false;
    }
}

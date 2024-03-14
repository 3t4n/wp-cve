<?php
/*
  Plugin Name: WP Upload Restriction
  Plugin URI: https://wordpress.org/plugins/wp-upload-restriction/
  Description: This plugin allows you to control upload of files based on file types and sizes.
  Version: 2.2.8
  Author: Sajjad Hossain
  Author URI: http://www.sajjadhossain.com
 */
if(!defined('ABSPATH')){ exit(); }

if(!defined('WP_UPLOAD_RESTRICTION_DB_VER')) {
    define('WP_UPLOAD_RESTRICTION_DB_VER', 1002);
}

class WPUploadRestriction {
    private $plugin_name;
    private $plugin_path;
    public $allowed_html = [
        'h4' => [],
        'p' => [],
        'div' => ['class' => []],
        'a' => ['href' => [], 'class' => [], 'data-row' => [], 'data' => []],
        'label' => ['for' => []],
        'input' => ['id' => [], 'name' => [], 'type' => [], 'value' => [], 'class' => [], 'checked' => [], 'maxlength' => [], 'size' => []],
        'select' => ['name' => []],
        'option' => ['value' => []],
        'tr' => ['id' => []],
        'td' => ['colspan' => []],
    ];

    /**
     * Constructor
     */
    public function __construct() {
        $this->plugin_path = basename(dirname(__FILE__));
        $this->addActions();
        $this->addFilters();
    }

    /**
     * Adds actions
     */
    private function addActions() {
        add_action('init', array($this, 'updateDB'));
        add_action('admin_init', array($this, 'adminInit'));
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueJS'));
        add_action('wp_ajax_get_selected_mimes_by_role', array($this, 'getSelectedMimeTypesByRole'));
        add_action('wp_ajax_save_selected_mimes_by_role', array($this, 'saveSelectedMimeTypesByRole'));
        add_action('wp_ajax_save_custom_type', array($this, 'saveCustomType'));
        add_action('wp_ajax_delete_custom_type', array($this, 'deleteCustomType'));
    }

    /**
     * Adds filters
     */
    private function addFilters() {
        add_filter('plugin_action_links', array($this, 'addSettingsLink'), 10, 2);
        add_filter('upload_mimes', array($this, 'restictMimes'), 10, 1);
        add_filter('upload_size_limit', array($this, 'restrictUploadSize'), 10);
        add_filter('wpur_mime_types', array($this, 'addCustomTypes'), 10);
    }

    /**
     * Loads text domain
     */
    public function adminInit() {
        load_plugin_textdomain('wp_upload_restriction', false,  $this->plugin_path . '/languages');
        wp_register_style('wp-upload-restrictions-styles', plugins_url('css/wp-upload-restrictions-styles.css', __FILE__), [], '2.2.8');
    }
    
    /**
     * Enqueue JS file
     * 
     * @param type $hook
     */
    public function enqueueJS($hook){
        if( 'wp-upload-restriction/settings.php' == $hook ) {
            wp_enqueue_script( 'wp-upload-restriction-js', plugins_url('js/wp-upload-restriction.js', __FILE__), array('jquery'), '2.2.8' );
        } 
    }

    /**
     * Add a submenu for settings page under Settings menu
     */
    public function addAdminMenu() {
        add_submenu_page('options-general.php', 'WP Upload Restriction', 'WP Upload Restriction', 'manage_options', 'wp-upload-restriction/settings.php');
    }

    /**
     * Add settings link in Plugins page.
     * 
     * @param array $links
     * @param string $file
     * @return array
     */
    public function addSettingsLink($links, $file) {

        if (is_null($this->plugin_name)) {
            $this->plugin_name = plugin_basename(__FILE__);
        }

        if ($file == $this->plugin_name) {
            $settings_link = '<a href="options-general.php?page=wp-upload-restriction/settings.php">' . esc_html__('Settings', 'wp_upload_restriction') . '</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Deletes selected MIMEs option
     */
    public function uninstall() {
        global $wp_roles;
        
        delete_option('wpur_selected_mimes');
        delete_site_option('wpur_db_version');
        
        foreach($wp_roles->roles as $role => $details){
            delete_option('wpur_selected_mimes_' . $role);
        }
    }

    /**
     * Filters allowed MIME types array.
     * 
     * @global object $user
     * @param array $mimes
     * @return array
     */
    public function restictMimes($mimes) {
        $user = wp_get_current_user();
        $user_roles = $user->roles;

        if(empty($user_roles)){
            return $mimes;
        }

        $selected_mimes = array();
	    $has_setup = false;
        
        foreach ($user_roles as $role){
            $roles_selected_mimes = get_option('wpur_selected_mimes_' . $role, false);

            if($roles_selected_mimes !== false){
                $selected_mimes = array_merge($selected_mimes, $roles_selected_mimes);
                $has_setup = true;
            }
        }
		
        if(!$has_setup){
            return $mimes;
        }

        if (empty($selected_mimes)) {
            return $selected_mimes;
        }

        if (function_exists('current_user_can')) {
            $unfiltered = $user ? user_can($user, 'unfiltered_html') : current_user_can('unfiltered_html');
        }

        if (empty($unfiltered)) {
            unset($selected_mimes['htm|html']);
        }

        return $selected_mimes;
    }

    /**
     * Restricts file upload based on file size.
     * 
     * @global type $current_user
     * @param type $size
     * @return type
     */
    public function restrictUploadSize($size){
        global $current_user;
        
        if($current_user->roles){
            $upload_size = 0;
            $restrict = false;
        
            foreach($current_user->roles as $role){
                if($this->isUploadSizeRestricted($role)){
                    $allowed_size = $this->getRoleMaxUploadSize($role, true);                
                    $upload_size = max(array($upload_size, $allowed_size));
                    $restrict = true;
                }
            }
            
            if($restrict){
                return $upload_size;
            }
        }
        
        return $size;
    }

    /**
     * Process settings form post.
     * 
     * @return boolean
     */
    public function saveSelectedMimeTypesByRole() {
        if($this->canUserAccess()){
            $request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
            $nonce = filter_input(INPUT_POST, 'wpur_nonce');
            $role = sanitize_text_field(filter_input(INPUT_POST, 'role'));

            if ($request_method == 'POST' 
                    && wp_verify_nonce($nonce, 'wp-upload-restrict')
                    && !empty($role)
                    && in_array($role, $this->getAllRolesArray())) {
                
                $mime_types = array_map(function($value){
                            return sanitize_text_field($value);
                        }, filter_input(INPUT_POST, 'types', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));
                $restrict_upload_size = sanitize_text_field(filter_input(INPUT_POST, 'restrict_upload_size', FILTER_SANITIZE_NUMBER_INT));
                $upload_size = sanitize_text_field(filter_input(INPUT_POST, 'upload_size', FILTER_SANITIZE_NUMBER_INT));
                $upload_size_unit = sanitize_text_field(filter_input(INPUT_POST, 'upload_size_unit'));

                $this->setRolesMaxUploadSize($role, $restrict_upload_size, $upload_size, $upload_size_unit);
                
                if (!empty($mime_types)) {
                    $types = array();
                    foreach ($mime_types as $type_str) {
                        list($ext, $mime) = explode('::', $type_str);
                        $types[$ext] = $mime;
                    }

                    update_option('wpur_selected_mimes_' . $role, $types);
                    echo 'yes';
                }
                else {
                    update_option('wpur_selected_mimes_' . $role, array());
                    echo 'yes';
                }
                wp_die();
            }

            echo 'no';
            wp_die();
        }
    }

    /**
     * Modifies extention for display.
     * 
     * @param string $ext
     * @return string
     */
    public function processExtention($ext) {
        if (strpos($ext, '|')) {
            $pieces = array_map(function($value){
                                    return esc_attr($value);
                                }, explode('|', $ext));
            $ext = implode(', ', $pieces);
        }

        return $ext;
    }

    /**
     * Returns WordPress supported MIME types. 
     * 
     * @return array
     */
    public function getWPSupportedMimeTypes() {
        $wp_mime_types = wp_get_mime_types();
        unset($wp_mime_types['swf'], $wp_mime_types['exe']);
        ksort($wp_mime_types);
        $wp_mime_types = apply_filters('wpur_mime_types', $wp_mime_types);
        return $wp_mime_types;
    }

    /**
     * Returns user selected MIME types.
     * 
     * @return array
     */
    public function getSelectedMimeTypes($role) {
        return get_option('wpur_selected_mimes_' . $role, false);
    }
    
    /**
     * Shows role wise selected MIME types
     */
    public function getSelectedMimeTypesByRole(){
        $nonce = filter_input(INPUT_POST, 'wpur_nonce');
        if($this->canUserAccess() && wp_verify_nonce($nonce, 'wpur-ajax-req')){
            $role = filter_input(INPUT_POST, 'role');
            
            if(!empty($role) && in_array($role, $this->getAllRolesArray())){
                
                $wp_mime_types = $this->getWPSupportedMimeTypes();
                $selected_mimes = $this->getSelectedMimeTypes($role);
                $restrict_upload_size = $this->isUploadSizeRestricted($role);
                $upload_size_unit = get_option('wpur_max_upload_unit_' . $role, 'MB');
                $upload_size = $this->getRoleMaxUploadSize($role, false, $upload_size_unit);
        
                $check_all = $selected_mimes === false;

                ob_start();
                require_once dirname(__FILE__) . '/content.php';
                $content = ob_get_contents();		
                ob_end_clean();
                
                echo wp_kses($content, $this->allowed_html);
                
                wp_die();
            }
        }
    }

    /**
     * Checks if given role exists or not in user's roles list
     * 
     * @param object $user
     * @param string $role
     * @return boolean
     */
    private function hasRole($user, $role) {
        if (!empty($user)) {
            return in_array($role, $user->roles);
        }

        return false;
    }

    /**
     * Returns all roles
     * 
     * @global type $wp_roles
     * @return type
     */
    public function getAllRoles(){
        global $wp_roles;

        return $wp_roles->roles;
    }
    
    /**
     * Returns an array of all roles machine names
     * 
     * @global array $wp_roles
     * @return array
     */
    private function getAllRolesArray(){
        global $wp_roles;
        
        $roles = array();
        
        foreach($wp_roles->roles as $role => $details){
            $roles[] = $role;
        }
        
        return $roles;
    }
    
    /**
     * Return the allowed max upload size for the given role.
     * 
     * @param string $role
     * @return int
     */
    public function getRoleMaxUploadSize($role, $in_bytes = false, $upload_size_unit = 'MB'){
        $upload_size_byte = get_option('wpur_max_upload_' . $role, 0);

        if($upload_size_byte){
            if($in_bytes){
                return $upload_size_byte;
            }
            else{
                $upload_size = $upload_size_byte / $this->getMultiplier($upload_size_unit);
                return round($upload_size, 0);
            }
        }
        
        return 0;
    }
    
    /**
     * Checks if upload restriction be applied for the selected user
     * 
     * @param string $role
     * @return int
     */
    public function isUploadSizeRestricted($role){
        return get_option('wpur_max_upload_restrict' . $role, 0);
    }

    /**
     * Saves upload size options.
     * 
     * @param string $role
     * @param int $restrict_upload_size
     * @param int $size
     * @param string $size_unit
     */
    private function setRolesMaxUploadSize($role, $restrict_upload_size, $size, $size_unit){
        if($role){
            $size_in_byte = ($size ? $size : 0) * $this->getMultiplier($size_unit);
            update_option('wpur_max_upload_' . $role, $size_in_byte);
            update_option('wpur_max_upload_unit_' . $role, $size_unit);
            update_option('wpur_max_upload_restrict' . $role, (int) $restrict_upload_size);
        }
    }

    /**
     * Saves the custom post type to options table
     */
    public function saveCustomType(){
        $nonce = filter_input(INPUT_POST, 'wpur_nonce');
        if($this->canUserAccess() && wp_verify_nonce($nonce, 'wpur-ajax-req')){
            $custom_types = $this->getCustomTypes();

            if(empty($custom_types)){
                $custom_types = array();
            }

            $ext = sanitize_text_field(filter_input(INPUT_POST, 'ext'));
            $mime = sanitize_text_field(filter_input(INPUT_POST, 'mime'));

            if($ext && $mime){
                $custom_types[$ext] = $mime;
                update_option('wpur_custom_types', $custom_types);

                echo json_encode(array(
                    'success'=> 'yes',
                    'types' => wp_kses($this->prepareCustomTypeHTML(), $this->allowed_html)
                ));
            }
            else{
                echo json_encode(array(
                    'success' => 'no',
                    'error' => __('Required information is missing.', 'wp_upload_restriction')
                ));
            }

            wp_die();
        }
    }

    /**
     * Deletes the custom post type from options table and revokes permission given for this type to different roles
     */
    public function deleteCustomType(){
        $nonce = filter_input(INPUT_POST, 'wpur_nonce');
        if($this->canUserAccess() && wp_verify_nonce($nonce, 'wpur-ajax-req')){
            $ext = sanitize_text_field(filter_input(INPUT_POST, 'ext'));

            if($ext) {
                $custom_types = $this->getCustomTypes();
                if(!empty($custom_types) && !empty($custom_types[$ext])){
                    unset($custom_types[$ext]);
                    update_option('wpur_custom_types', $custom_types);
                    $this->revokeGrantFromRoles($ext);
                }

                echo 'yes';
            }
            else{
                echo 'no';
            }

            wp_die();
        }
    }

    /**
     * Revokes permission given for this type to different roles
     *
     * @param $ext string The extention of the custom type
     */
    private function revokeGrantFromRoles($ext){
        $roles  = $this->getAllRolesArray();

        foreach($roles as $role){
            $selected_mimes = $this->getSelectedMimeTypes($role);

            if(!empty($selected_mimes) && !empty($selected_mimes[$ext])) {
                unset($selected_mimes[$ext]);
                update_option('wpur_selected_mimes_' . $role, $selected_mimes);
            }
        }
    }

    /**
     * Returns list of custom types
     */
    public function getCustomTypes(){
        $custom_types = get_option('wpur_custom_types');
        return $custom_types;
    }

    /**
     * Merges custom types to WP default mime type list in run-time.
     *
     * @param $wp_mime_types WP default MIME types
     * @return array
     */
    public function addCustomTypes($wp_mime_types){
        $custom_types = $this->getCustomTypes();

        if(!empty($custom_types)){
            $wp_mime_types = array_merge($wp_mime_types, $custom_types);
        }

        return $wp_mime_types;
    }

    /**
     * Prepares list of custom types
     *
     * @return string
     */
    public function prepareCustomTypeHTML()
    {
        $custom_types = $this->getCustomTypes();
        $html = '';

        if ($custom_types) {
            $i = 1;
            foreach ($custom_types as $ext => $mime) {
                $html .= '<tr id="row-' . $i . '">
                        <td>' . $ext . '</td>
                        <td>' . $mime . '</td>
                        <td><a href="#" data-row="row-' . $i . '" data="' . $ext . '" class="del-mime">Delete</a></td>
                       </tr>';
                $i++;
            }
        }
        else {
            $html = '<tr><td colspan="3">' . __('No custom types found.', 'wp_upload_restriction') . '</td></tr>';
        }

        return $html;
    }

    /**
     * Returns multiplier for converting the limit to byte.
     * 
     * @param string $size_unit
     * @return int
     */
    private function getMultiplier($size_unit){
        switch($size_unit){ 
            case 'MB':
                return 1048576;
            case 'KB': 
                return 1024;
            default:
                return 1;
        }
    }

    /**
     * For updating database on version upgrade
     */
    public function updateDB(){
        $current_db_ver = get_site_option('wpur_db_version', 1001);

        if($current_db_ver < WP_UPLOAD_RESTRICTION_DB_VER){
            for($i = ($current_db_ver + 1); $i <= WP_UPLOAD_RESTRICTION_DB_VER; $i++){
                $function_name = 'updateDB' . $i;
                $this->$function_name();
                update_site_option('wpur_db_version', $i);
            }
        }
    }
    
    /**
     * DB update 1002
     */
    private function updateDB1002(){
        $roles = $this->getAllRoles();
        $selected_mimes = get_option('wpur_selected_mimes', false);
        $all_mimes = $this->getWPSupportedMimeTypes();
        
        foreach($roles as $role => $details){
            if($role == 'administrator' || $selected_mimes === false){       
                update_option('wpur_selected_mimes_' . $role, $all_mimes);
            }
            else{
                update_option('wpur_selected_mimes_' . $role, $selected_mimes);
            }
        }
        
        delete_option('wpur_selected_mimes');
    }

    /**
     * Check user's access
     */
    private function canUserAccess(){
        return current_user_can('manage_options');
    }
}

$wpUploadRestriction = new WPUploadRestriction();

register_uninstall_hook(__FILE__, array('WPUploadRestriction', 'uninstall'));
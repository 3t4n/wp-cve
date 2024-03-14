<?php

/*
Plugin Name: BSK Forms Validation
Plugin URI: https://www.bannersky.com/gravity-forms-custom-validation/
Description: The plugin help you validate users input and let users submit right data on gravity forms form. Such as mobile phone number, age by date field, ZIP code etc.
Version: 1.6.1
Author: BannerSky.com
Author URI: http://www.bannersky.com/

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, 
or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin Folder Path.
if ( ! defined( 'BSK_GFCV_DIR' ) ) {
    define( 'BSK_GFCV_DIR', plugin_dir_path( __FILE__ ) );
}
// Plugin Folder URL.
if ( ! defined( 'BSK_GFCV_URL' ) ) {
    define( 'BSK_GFCV_URL', plugin_dir_url( __FILE__ ) );
}

class BSK_GFCV {
	
    private static $instance;
    
	public static $_plugin_version = '1.6.1';
	private static $_bsk_gfcv_db_version = '1.2';
	private static $_bsk_gfcv_saved_db_version_option = '_bsk_gfcv_db_ver_';
    private static $_bsk_gfcv_plugin_db_upgrading = '_bsk_gfcv_db_upgrading_';
	
	public static $_bsk_gfcv_list_tbl_name = 'bsk_gfcv_list';
	public static $_bsk_gfcv_items_tbl_name = 'bsk_gfcv_items';
    public static $_bsk_gfcv_entries_tbl_name = 'bsk_gfcv_entries';
    public static $_bsk_gfcv_hits_tbl_name = 'bsk_gfcv_hits';

	public static $_bsk_gfcv_temp_option_prefix = '_bsk_gfcv_temp_';
	
	public static $ajax_loader = '';
    public static $delete_country_code_icon_url = '';

    public static $_supported_plugins = array();
	
	//objects
    public $_CLASS_OBJ_rules;
	public $_CLASS_OBJ_dashboard;
    public $_CLASS_OBJ_validation;
	
	public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof BSK_GFCV ) ) {
            global $wpdb;

            self::$instance = new BSK_GFCV;

            /*
            * Initialize variables 
            */
            self::$ajax_loader = '<img src="'.BSK_GFCV_URL.'images/ajax-loader.gif" />';
            self::$delete_country_code_icon_url = BSK_GFCV_URL.'images/delete-2.png';
            
            /*
            * plugin hook
            */
            register_activation_hook(__FILE__, array(self::$instance, 'bsk_gfcv_activate') );
            register_deactivation_hook( __FILE__, array(self::$instance, 'bsk_gfcv_deactivate') );
            register_uninstall_hook( __FILE__, 'BSK_GFCV::bsk_gfcv_uninstall' );
            
            self::$instance->init_form_plugins();
            
            /*
              * classes
              */
            require_once BSK_GFCV_DIR . 'classes/rules/rules.php';
            require_once BSK_GFCV_DIR . 'classes/dashboard/dashboard.php';
            require_once BSK_GFCV_DIR . 'classes/validation/validation.php';
            
            self::$instance->_CLASS_OBJ_rules = new BSK_GFCV_Rules();
            self::$instance->_CLASS_OBJ_dashboard = new BSK_GFCV_Dashboard();
            self::$instance->_CLASS_OBJ_validation = new BSK_GFCV_Validation();
            /*
            * Actions
            */
            add_action( 'admin_enqueue_scripts', array(self::$instance, 'bsk_gfcv_enqueue_scripts_n_css') );
            add_action( 'wp_enqueue_scripts', array(self::$instance, 'bsk_gfcv_enqueue_scripts_n_css') );
            add_action( 'init', array(self::$instance, 'bsk_gfcv_post_action') );
            
            add_action( 'plugins_loaded', array(self::$instance, 'bsk_gfcv_update_database_fun'), 10 );
        }

        return self::$instance;
	}
	
	function bsk_gfcv_activate( $network_wide ){
		self::$instance->bsk_gfcv_create_table();
	}
	
	function bsk_gfcv_deactivate(){
	}
	
	function bsk_gfcv_remove_tables_n_options(){
		global $wpdb;
		
        $table_list = $wpdb->prefix.'bsk_gfcv_list';
		$table_items = $wpdb->prefix.'bsk_gfcv_items';
        $table_entries = $wpdb->prefix.'bsk_gfcv_entries';
        $table_hits = $wpdb->prefix.'bsk_gfcv_hits';
		
		$wpdb->query("DROP TABLE IF EXISTS $table_list");
		$wpdb->query("DROP TABLE IF EXISTS $table_items");
        $wpdb->query("DROP TABLE IF EXISTS $table_entries");
        $wpdb->query("DROP TABLE IF EXISTS $table_hits");
		
		$sql = 'DELETE FROM `'.$wpdb->options.'` WHERE `option_name` LIKE "_bsk_gfcv%"';
		$wpdb->query( $sql );

        $sql = 'DELETE FROM `'.$wpdb->options.'` WHERE `option_name` LIKE "_bsk_forms_cv_ff_settings%"';
		$wpdb->query( $sql );
	}
	
	function bsk_gfcv_uninstall(){
		if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $has_active_pro_verison = false;
        $plugins = get_plugins();
        foreach( $plugins as $plugin_key => $data ){
            if( 'bsk-gravityforms-cv-pro/bsk-gravityforms-cv-pro.php' == $plugin_key && 
                is_plugin_active( $plugin_key ) ){
                $has_active_pro_verison = true;
                break;
            }
        }
        if( $has_active_pro_verison == true ){
            return;
        }
        
        self::$instance->bsk_gfcv_remove_tables_n_options();
	}
	
	function bsk_gfcv_enqueue_scripts_n_css(){
		
		wp_enqueue_script('jquery');
		
		if( is_admin() ){
			wp_enqueue_script( 
                                 'bsk-gfcv-admin', 
                                 BSK_GFCV_URL.'js/bsk-gfcv-admin.js',
                                 array( 'jquery' ), 
                                 filemtime( BSK_GFCV_DIR.'js/bsk-gfcv-admin.js' )
                             );
			wp_enqueue_style( 
                                'bsk-gfcv-admin', 
                                BSK_GFCV_URL.'css/bsk-gfcv-admin.css',
                                array(), 
                                filemtime( BSK_GFCV_DIR.'css/bsk-gfcv-admin.css' )
                            );
		}else{
			//
		}
	}
	
	function bsk_gfcv_create_table(){
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charset_collate = $wpdb->get_charset_collate();
		
		$list_table = $wpdb->prefix.self::$_bsk_gfcv_list_tbl_name;
		$sql = "CREATE TABLE $list_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `list_name` varchar(512) NOT NULL,
		  `list_type` varchar(512) NOT NULL,
          `check_way` VARCHAR(8) NOT NULL DEFAULT 'ANY',
          `extra` VARCHAR(512) NULL,
		  `date` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) $charset_collate;";
		dbDelta( $sql );
        
		$items_table = $wpdb->prefix.self::$_bsk_gfcv_items_tbl_name;
		$sql = "CREATE TABLE $items_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `list_id` int(11) NOT NULL,
		  `value` varchar(512) NOT NULL,
          `hits` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) $charset_collate;";
		dbDelta($sql);
        
        $entries_table = $wpdb->prefix.self::$_bsk_gfcv_entries_tbl_name;
		$sql = "CREATE TABLE $entries_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
          `forms` varchar(32) NOT NULL DEFAULT 'GF',
          `form_id` int(11) NOT NULL,
          `form_data` TEXT NOT NULL,
          `ip` varchar(256) NOT NULL,
          `submit_date` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) $charset_collate;";
		dbDelta($sql);
        
        $hits_table = $wpdb->prefix.self::$_bsk_gfcv_hits_tbl_name;
		$sql = "CREATE TABLE $hits_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
          `entry_id` int(11) NOT NULL,
          `field_id` FLOAT NOT NULL,
          `list_id` int(11) NOT NULL,
          `item_id` int(11) NOT NULL,
          `extra_data` varchar(512) NOT NULL,
          `submit_date` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) $charset_collate;";
		dbDelta($sql);
        
		update_option( self::$_bsk_gfcv_saved_db_version_option, self::$_bsk_gfcv_db_version );
	}

	function bsk_gfcv_post_action(){
		if( isset( $_POST['bsk_gfcv_action'] ) && strlen( sanitize_text_field( $_POST['bsk_gfcv_action']) ) > 0 ) {
            $action_name = sanitize_text_field( $_POST['bsk_gfcv_action'] );
			do_action( 'bsk_gfcv_' . $action_name, $_POST );
		}
		
		if( isset( $_GET['bsk-gfcv-action'] ) && strlen( sanitize_text_field( $_GET['bsk-gfcv-action'] ) ) > 0 ) {
            $action_name = sanitize_text_field( $_GET['bsk-gfcv-action'] );
			do_action( 'bsk_gfcv_' . $action_name, $_GET );
		}
	}
	
    public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__,  'Cheatin&#8217;', '1.0' );
	}
    
    public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__,  'Cheatin&#8217;', '1.0' );
	}
    
    function bsk_gfcv_update_database_fun(){
        $saved_db_version = get_option( self::$_bsk_gfcv_saved_db_version_option );
		if( version_compare( $saved_db_version, self::$_bsk_gfcv_db_version, '>=' ) ) {
			return;
		}
		
        $is_upgrading = get_option( self::$_bsk_gfcv_plugin_db_upgrading, false );
        if( $is_upgrading ){
            //already have instance doing upgrading so exit this one
            return;
        }
        update_option( self::$_bsk_gfcv_plugin_db_upgrading, true );
        
        global $wpdb;
					
        //upgrade db version to 2.0
		if( version_compare( $saved_db_version, '1.1', '<' ) ) {
            $sql = 'ALTER TABLE `'.$wpdb->prefix.self::$_bsk_gfcv_hits_tbl_name.'` CHANGE `field_id` `field_id` FLOAT(11) NOT NULL;';
            $wpdb->query( $sql );
        }

        if( version_compare( $saved_db_version, '1.2', '<' ) ) {
            $table_name = $wpdb->prefix . self::$_bsk_gfcv_entries_tbl_name;
            $sql = 'SHOW COLUMNS FROM `'.$table_name.'` LIKE \'forms\'';
            $return_rows = $wpdb->query( $sql );
            if ( $return_rows < 1 ) {
                $sql = 'ALTER TABLE `'.$table_name.'` ADD `forms` VARCHAR(32) NOT NULL DEFAULT \'GF\' AFTER `id`;';
                $wpdb->query( $sql );
            }
        }
        
        //update db version to latest
		update_option( self::$_bsk_gfcv_saved_db_version_option, self::$_bsk_gfcv_db_version );
        delete_option( self::$_bsk_gfcv_plugin_db_upgrading );
    }

    function init_form_plugins(){
        if ( ! function_exists( 'is_plugin_active' ) ){
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        
        //gravity forms
        if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ){
            $plugin_version = $all_plugins['gravityforms/gravityforms.php']['Version'];
            self::$_supported_plugins['GF'] = array( 'title' => 'Gravity Forms', 'version' => $plugin_version );
        }
        
		//ninja forms
        if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) ){
            $plugin_version = $all_plugins['ninja-forms/ninja-forms.php']['Version'];
            self::$_supported_plugins['NF'] = array( 'title' => 'Ninja Forms', 'version' => $plugin_version );
        }
		
		//formidable forms
        if ( is_plugin_active( 'formidable/formidable.php' ) ){
            $plugin_version = $all_plugins['formidable/formidable.php']['Version'];
            self::$_supported_plugins['FF'] = array( 'title' => 'Formidable Forms', 'version' => $plugin_version );
        }
		
		//Contact7 forms
        if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ){
			$plugin_version = $all_plugins['contact-form-7/wp-contact-form-7.php']['Version'];
            self::$_supported_plugins['CF7'] = array( 'title' => 'Contact Form 7', 'version' => $plugin_version );
		}
        
        //WPFoms
        if ( is_plugin_active( 'wpforms-lite/wpforms.php' ) || is_plugin_active( 'wpforms/wpforms.php' ) ){
            $plugin_version = '';
            if ( is_plugin_active( 'wpforms-lite/wpforms.php' ) ){
                $plugin_version = $all_plugins['wpforms-lite/wpforms.php']['Version'];
            } elseif ( is_plugin_active( 'wpforms/wpforms.php' ) ) {
                $plugin_version = $all_plugins['wpforms/wpforms.php']['Version'];
            }
			
            self::$_supported_plugins['WPF'] = array( 'title' => 'WPForms', 'version' => $plugin_version );
		}
    }
}

BSK_GFCV::instance();

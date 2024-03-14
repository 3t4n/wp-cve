<?php
/*
Plugin Name: Easy Username Updater
Description: Allow admin to update username.
Author: Yogesh C. Pant
Version: 1.0.5
*/
/**
* Description of easy-username-updater
 *
 * @package Easy Username Updater
 * @version 1.0.5
 * @author Yogesh C. Pant
 */
namespace EasyUserNameUpdater;
// Exit if accessed directly
if ( !defined( 'ABSPATH' )) exit;

Class EasyUsernameUpdater {
    /**
    * launch the hooks
    *
    * @access      public
    * @since       1.0.5
    */
    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        add_action('admin_menu', array($this, 'eup_user_list'));
        add_action('init', array($this, 'eup_include'));
        add_action('admin_init', array($this, 'eup_assets'));
    }

    /**
    * setting menu page.
    *
    * @access      public
    * @since       1.0.5
    */
    public function eup_user_list() {
        $allowed_group = 'manage_options';
        if (function_exists('add_submenu_page')) {
            add_submenu_page('users.php', __('Username Updater', 'easy_username_updater') , __('Username Updater', 'easy_username_updater') , $allowed_group, 'easy_username_updater', 'eup_edit_options');
            add_submenu_page(null, __('Update', 'easy_username_updater') , __('Update', 'easy_username_updater') , $allowed_group, 'eup_username_update', 'eup_user_update');
        }
    }

    /**
    * Include CSS File.
    *
    * @access      public
    * @since       1.0.5
    */
    public function eup_assets() {
     if( is_admin() ) {
         wp_register_style('style', plugins_url('css/eupstyle.css', __FILE__));
         wp_enqueue_style('style');
         wp_register_style('datatable-style', plugins_url('css/jquery.dataTables.min.css', __FILE__));
         wp_enqueue_style('datatable-style');
         wp_register_script( 'datatable-script', plugins_url( '/js/jquery.dataTables.min.js', __FILE__ ) );
         wp_enqueue_script( 'datatable-script' );
         wp_register_script( 'eup-script', plugins_url( '/js/eup-script.js', __FILE__ ) );
         wp_enqueue_script( 'eup-script' );

     }
    }

    /**
    * Include necessary files
    *
    * @access      public
    * @since       1.0.5
    */
    public function eup_include() {
      if( is_admin() ) {
         require_once (plugin_dir_path(__FILE__) . 'includes/eup-user-list.php');
         require_once (plugin_dir_path(__FILE__) . 'includes/eup-user-update.php');
      }
    }

    /**
    * user update function
    *
    * @access      public
    * @since       1.0.5
    */
    public function eup_update( $id,$name ) {
        $result=$this->db->update(
                    $this->db->prefix.'users', //table
                    array('user_login' => $name, 'display_name'=> $name), //data
                    array('id' => $id), //where
                    array('%s', '%s'), //data format
                    array('%d') //where format
        );
        return $result;
    }
    
    /**
    * user select function
    *
    * @access      public
    * @since       1.0.5
    */
    public function eup_select() {
        $records = $this->db->get_results("SELECT * FROM `" . $this->db->prefix . "users`");
        return $records;
    }
}
$eup = new EasyUsernameUpdater();
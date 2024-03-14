<?php 
/**
 * Plugin Name: Taskbuilder
 * Plugin URI: https://wordpress.org/plugins/taskbuilder/
 * Description: Wordpress Project & Task Management plugin. Easy to keep track of projects & tasks!
 * Version: 2.0.9
 * Author: Taskbuilder Team
 * Author URI: https://taskbuilder.net/
 * Requires at least: 4.4
 * Tested up to: 6.4.3
 * Text Domain: taskbuilder
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_Taskbuilder' ) ) :
  
  final class WP_Taskbuilder {
    public $version    = '2.0.9';
    public function __construct() {
      // define global constants
      $this->define_constants();
      // Include required files and classes
      $this->includes();
      add_action( 'init', array($this,'load_textdomain') );
      add_action( 'init', array( $this, 'wppm_check_download_file') );
      register_deactivation_hook( __FILE__, array($this,'deactivate') );
    }
    
    function define_constants() {
      $this->define('WPPM_STORE_URL', 'https://taskbuilder.net/');
      $this->define('WPPM_PLUGIN_FILE', __FILE__);
      $this->define('WPPM_ABSPATH', dirname(__FILE__) . '/');
      $this->define('WPPM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
      $this->define('WPPM_PLUGIN_BASENAME', plugin_basename(__FILE__));
      $this->define('WPPM_VERSION', $this->version);    
    }
    
    function load_textdomain(){
      $locale = apply_filters( 'plugin_locale', get_locale(), 'taskbuilder' );
      load_textdomain( 'taskbuilder', WP_LANG_DIR . '/taskbuilder/taskbuilder-' . $locale . '.mo' );
      load_plugin_textdomain( 'taskbuilder', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
    }

    public function includes() {
      include_once( WPPM_ABSPATH . 'includes/wppm-install.php' );
      include_once( WPPM_ABSPATH . 'includes/class-wppm-functions.php' );
      include( WPPM_ABSPATH.'includes/wppm_cron.php' );
      include_once( WPPM_ABSPATH . 'includes/wppm_actions.php' );
      if ($this->is_request('admin')) {
        include_once( WPPM_ABSPATH . 'includes/class-wppm-admin.php' );
      }
      if ($this->is_request('frontend')) {
        include_once( WPPM_ABSPATH . 'includes/class-wppm-frontend.php' );
      }
      if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
        include_once( WPPM_ABSPATH . 'includes/EDD_SL_Plugin_Updater.php' );
      }
      
   }

    public function define($name, $value) {
      if (!defined($name)) {
          define($name, $value);
      }
    }
    
    public function is_request($type) {
      switch ($type) {
        case 'admin' :
            return is_admin();
        case 'frontend' :
            return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
      }
    }

    public function wppm_check_download_file(){
      global $wpdb,$wppmfunction;
      if( isset($_REQUEST['wppm_attachment']) && isset($_REQUEST['tid']) && isset($_REQUEST['tac'])){
        $attach_id = intval(sanitize_text_field($_REQUEST['wppm_attachment']));
        $auth_code = (sanitize_text_field($_REQUEST['tac']));
        $task_id = intval(sanitize_text_field($_REQUEST['tid']));
        $task_auth_code = $wppmfunction->get_task_fields($task_id,'task_auth_code');
        $task_auth_code = sanitize_text_field($task_auth_code);
        if($task_auth_code == $auth_code){
          $this->wppm_file_download($attach_id);
    		} 
      }
    }

    public function wppm_file_download($attach_id){
      include WPPM_ABSPATH.'includes/admin/attachment/wppm_download_attachment.php';
    }

    public function deactivate() {
      // Remove cron jobs
      WPPMCron::wppm_unschedule_events();
    }

  }
  
endif;

new WP_Taskbuilder();

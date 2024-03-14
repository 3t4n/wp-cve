<?php

/**
 * Plugin Name:			WPForms GSheetConnector
 * Plugin URI:			   https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro
 * Description:			Send your WPForms data to your Google Sheets spreadsheet.
 * Requires at least: 	5.6
 * Requires PHP: 		   7.2
 * Author:       	   	GSheetConnector
 * Author URI:   		   https://www.gsheetconnector.com/
 * Version:      		   3.4.18
 * Text Domain:  		   gsheetconnector-wpforms
 * Domain Path:  		   languages
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
   exit;
}

define('WPFORMS_GOOGLESHEET_VERSION', '3.4.18');
define('WPFORMS_GOOGLESHEET_DB_VERSION', '3.4.18');
define('WPFORMS_GOOGLESHEET_ROOT', dirname(__FILE__));
define('WPFORMS_GOOGLESHEET_URL', plugins_url('/', __FILE__));
define('WPFORMS_GOOGLESHEET_BASE_FILE', basename(dirname(__FILE__)) . '/gsheetconnector-wpforms.php');
define('WPFORMS_GOOGLESHEET_BASE_NAME', plugin_basename(__FILE__));
define('WPFORMS_GOOGLESHEET_PATH', plugin_dir_path(__FILE__)); //use for include files to other files
define('WPFORMS_GOOGLESHEET_PRODUCT_NAME', 'Wpforms Google Sheet Connector');
define('WPFORMS_GOOGLESHEET_CURRENT_THEME', get_stylesheet_directory());
load_plugin_textdomain('gsheetconnector-wpforms', false, basename(dirname(__FILE__)) . '/languages');

/*
 * include utility classes
 */
if (!class_exists('Wpform_gs_Connector_Utility')) {
   include( WPFORMS_GOOGLESHEET_ROOT . '/includes/class-wpform-utility.php' );
}

function wpforms_Googlesheet_integration() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-wpforms-integration.php';
   //Include Library Files
  require_once WPFORMS_GOOGLESHEET_ROOT . '/lib/vendor/autoload.php';

  include_once( WPFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');
  
require_once plugin_dir_path(__FILE__) . 'includes/wpforms-panel.php';
   
}





add_action('wpforms_loaded', 'wpforms_Googlesheet_integration');

class WPforms_Gsheet_Connector_Init {

   public function __construct() {

      //run on activation of plugin
      register_activation_hook(__FILE__, array($this, 'wpform_gs_connector_activate'));

      //run on deactivation of plugin
      register_deactivation_hook(__FILE__, array($this, 'wpform_gs_connector_deactivate'));

      //run on uninstall
      register_uninstall_hook(__FILE__, array('WPforms_Gsheet_Connector_Init', 'wpform_gs_connector_uninstall'));

      // validate is wpforms plugin exist
      add_action('admin_init', array($this, 'validate_parent_plugin_exists'));

      // register admin menu under "Google Sheet" > "Integration"
      add_action('admin_menu', array($this, 'register_wpform_menu_pages'));

      // Display widget to dashboard
      add_action('wp_dashboard_setup', array($this, 'add_wpform_gs_connector_summary_widget'));

      // clear debug log data
      add_action('wp_ajax_wp_clear_logs', array($this, 'wp_clear_logs'));

      // verify the spreadsheet connection
      add_action('wp_ajax_verify_wpform_gs_integation', array($this, 'verify_wpform_gs_integation'));

      // load the js and css files
      add_action('init', array($this, 'load_css_and_js_files'));

      // Add custom link for our plugin
      add_filter('plugin_action_links_' . WPFORMS_GOOGLESHEET_BASE_NAME, array($this, 'wpform_gs_connector_plugin_action_links'));

      add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
      
      add_action( 'admin_init', array( $this, 'run_on_upgrade' ) );
      
      // redirect to integration page after update
      add_action('admin_init', array( $this, 'redirect_after_upgrade' ), 999 );

      // clear debug logs method using ajax for system status tab
      add_action('wp_ajax_wp_clear_debug_logs', array($this, 'wp_clear_debug_logs'));

   }


   /**
     * Plugin row meta.
     *
     * Adds row meta links to the plugin list table
     *
     * Fired by `plugin_row_meta` filter.
     *
     * @since 1.1.4
     * @access public
     *
     * @param array  $plugin_meta An array of the plugin's metadata, including
     *                            the version, author, author URI, and plugin URI.
     * @param string $plugin_file Path to the plugin file, relative to the plugins
     *                            directory.
     *
     * @return array An array of plugin row meta links.
     */
    public function plugin_row_meta( $plugin_meta, $plugin_file ) {
        if ( WPFORMS_GOOGLESHEET_BASE_NAME === $plugin_file ) {
            $row_meta = [
                'docs' => '<a href="https://support.gsheetconnector.com/kb-category/wpforms-gsheetconnector" target="_blank" aria-label="' . esc_attr( esc_html__( 'View Documentation', 'gsheetconnector-wpforms' ) ) . '" target="_blank">' . esc_html__( 'Docs', 'gsheetconnector-wpforms' ) . '</a>',
                'ideo' => '<a href="https://www.gsheetconnector.com/support" aria-label="' . esc_attr( esc_html__( 'Get Support', 'gsheetconnector-wpforms' ) ) . '" target="_blank">' . esc_html__( 'Support', 'gsheetconnector-wpforms' ) . '</a>',
            ];

            $plugin_meta = array_merge( $plugin_meta, $row_meta );
        }

        return $plugin_meta;
    }


   /**
    * Do things on plugin activation
    * @since 1.0
    */
   public function wpform_gs_connector_activate($network_wide) {
      global $wpdb;
      $this->run_on_activation();
      if (function_exists('is_multisite') && is_multisite()) {
         // check if it is a network activation - if so, run the activation function for each blog id
         if ($network_wide) {
            // Get all blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
            foreach ($blogids as $blog_id) {
               switch_to_blog($blog_id);
               $this->run_for_site();
               restore_current_blog();
            }
            return;
         }
      }
      // for non-network sites only
      $this->run_for_site();
   }

   /**
    * deactivate the plugin
    * @since 1.0
    */
   public function wpform_gs_connector_deactivate() {
      
   }

   /**
    *  Runs on plugin uninstall.
    *  a static class method or function can be used in an uninstall hook
    *
    *  @since 1.0
    */
   public static function wpform_gs_connector_uninstall() {
      global $wpdb;
      WPforms_Gsheet_Connector_Init::run_on_uninstall();
      if (function_exists('is_multisite') && is_multisite()) {
         //Get all blog ids; foreach of them call the uninstall procedure
         $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");

         //Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
         foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            WPforms_Gsheet_Connector_Init::delete_for_site();
            restore_current_blog();
         }
         return;
      }
      WPforms_Gsheet_Connector_Init::delete_for_site();
   }

   /**
    * Validate parent Plugin wpform exist and activated
    * @access public
    * @since 1.0
    */
   public function validate_parent_plugin_exists() {
      $plugin = plugin_basename(__FILE__);
      //if ((!is_plugin_active('wpforms-lite/wpforms.php') ) && (!is_plugin_active('wpforms/wpforms.php') )) {
      if (!class_exists('WPForms', true)) {
         add_action('admin_notices', array($this, 'wpforms_missing_notice'));
         add_action('network_admin_notices', array($this, 'wpforms_missing_notice'));
         deactivate_plugins($plugin);
         if (isset($_GET['activate'])) {
            // Do not sanitize it because we are destroying the variables from URL
            unset($_GET['activate']);
         }
      }
   }

   /**
    * If Contact Form 7 plugin is not installed or activated then throw the error
    *
    * @access public
    * @return mixed error_message, an array containing the error message
    *
    * @since 1.0 initial version
    */
   public function wpforms_missing_notice() {
      $plugin_error = Wpform_gs_Connector_Utility::instance()->admin_notice(array(
         'type' => 'error',
         'message' => 'WPForms Google Sheet Connector Add-on requires WPForms <a href="https://wordpress.org/plugins/wpforms-lite/" target="_blank">(Lite or PRO)</a> plugin to be installed and activated.'
      ));
      echo $plugin_error;
   }

   /**
    * Create/Register menu items for the plugin.
    * @since 1.0
    */
   public function register_wpform_menu_pages() {
      $current_role = Wpform_gs_Connector_Utility::instance()->get_current_user_role();
      add_submenu_page('wpforms-overview', __('Google Sheet', 'gsheetconnector-wpforms'), __('Google Sheet', 'gsheetconnector-wpforms'), $current_role, 'wpform-google-sheet-config', array($this, 'wpforms_google_sheet_config'));
   }

   /**
    * Google Sheets page action.
    * This method is called when the menu item "Google Sheets" is clicked.
    * @since 1.0
    */
   public function wpforms_google_sheet_config() {
      include( WPFORMS_GOOGLESHEET_PATH . "includes/pages/wpforms-gs-settings.php" );
   }

   /**
    * Add widget to the dashboard
    * @since 1.0
    */
   public function add_wpform_gs_connector_summary_widget() {
      wp_add_dashboard_widget('wpform_gs_dashboard', __('WPForms - GSheetConnector', 'gsheetconnector-wpforms')."<img style='width:60px' src='".WPFORMS_GOOGLESHEET_URL."assets/img/WPFormGSheet-Connector-logo.png'>", array($this, 'wpform_gs_connector_summary_dashboard'));
   }

   /**
    * Display widget conetents
    * @since 1.0
    */
   public function wpform_gs_connector_summary_dashboard() {
      include_once( WPFORMS_GOOGLESHEET_ROOT . '/includes/pages/wpform-dashboard-widget.php' );
   }

   /**
    * AJAX function - clear log file
    * @since 1.0
    */
   public function wp_clear_logs() {
        // nonce check
      check_ajax_referer( 'gs-ajax-nonce', 'security' );
      $wpexistDebugFile = get_option('wpf_gs_debug_log_file');
      $clear_file_msg ='';
      // check if debug unique log file exist or not then exists to clear file
      if (!empty($wpexistDebugFile) && file_exists($wpexistDebugFile)) {
       
       $handle = fopen ( $wpexistDebugFile, 'w');
        
        fclose( $handle );
        $clear_file_msg ='Logs are cleared.';
       }
       else{
        $clear_file_msg = 'No log file exists to clear logs.';
       }
           
       wp_send_json_success($clear_file_msg);
    }
    
    /**
    * AJAX function - clear log file for system status tab
    * @since 2.1
    */
    public function wp_clear_debug_logs() {
        // nonce check
        check_ajax_referer('gs-ajax-nonce', 'security');
        $handle = fopen(WP_CONTENT_DIR . '/debug.log', 'w');
        fclose($handle);
        wp_send_json_success();
    }

   /**
    * AJAX function - verifies the token
    *
    * @since 1.0
    */
   public function verify_wpform_gs_integation() {
      // nonce checksave_gs_settings
      check_ajax_referer('gs-ajax-nonce', 'security');

      /* sanitize incoming data */
      $Code = sanitize_text_field($_POST["code"]);

      if (!empty($Code)) {
         update_option('wpform_gs_access_code', $Code);
      } else {
         return;
      }

      if (get_option('wpform_gs_access_code') != '') {
         include_once( WPFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');
         wpfgsc_googlesheet::preauth(get_option('wpform_gs_access_code'));
         // update_option('wpform_gs_verify', 'valid');
         wp_send_json_success();
      } else {
         update_option('wpform_gs_verify', 'invalid');
         wp_send_json_error();
      }
   }


  

   /**
    * AJAX function - verifies the token
    *
    * @since 1.0
    */
   public function verify_wpform_gs_integation_new($Code ="") {
      

      /* sanitize incoming data */
      
      if (!empty($Code)) {
         update_option('wpform_gs_access_code', $Code);
      } else {
         return;
      }

      if (get_option('wpform_gs_access_code') != '') {
         include_once( WPFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');
         wpfgsc_googlesheet::preauth(get_option('wpform_gs_access_code'));
         update_option('wpform_gs_verify', 'valid');
      } else {
         update_option('wpform_gs_verify', 'invalid');
      }
   }


   public function load_css_and_js_files() {
      add_action('admin_print_styles', array($this, 'add_css_files'));
      add_action('admin_print_scripts', array($this, 'add_js_files'));
   }

   /**
    * enqueue CSS files
    * @since 1.0
    */
   public function add_css_files() {
      if (is_admin() && ( isset($_GET['page']) && ( $_GET['page'] == 'wpform-google-sheet-config' ) )) {
         wp_enqueue_style('wpform-gs-connector-css', WPFORMS_GOOGLESHEET_URL . 'assets/css/wpform-gs-connector.css', WPFORMS_GOOGLESHEET_VERSION, true);
         wp_enqueue_style('wpform-gs-connector-font', WPFORMS_GOOGLESHEET_URL . 'assets/css/font-awesome.min.css', WPFORMS_GOOGLESHEET_VERSION, true);
      }              
   }

   /**
    * enqueue JS files
    * @since 1.0
    */
   public function add_js_files() {
      if (is_admin() && ( isset($_GET['page']) && ( $_GET['page'] == 'wpform-google-sheet-config' ) )) {
         wp_enqueue_script('wpform-gs-connector-js', WPFORMS_GOOGLESHEET_URL . 'assets/js/wpform-gs-connector.js', WPFORMS_GOOGLESHEET_VERSION, true);
      }
      
      if ( is_admin() ) {
         wp_enqueue_script('wpform-gs-connector-notice-css', WPFORMS_GOOGLESHEET_URL . 'assets/js/wpforms-gs-connector-notice.js', WPFORMS_GOOGLESHEET_VERSION, true);
      }
   }

   /**
    * Add custom link for the plugin beside activate/deactivate links
    * @param array $links Array of links to display below our plugin listing.
    * @return array Amended array of links.    * 
    * @since 1.0
    */
   public function wpform_gs_connector_plugin_action_links($links) {
      // We shouldn't encourage editing our plugin directly.
      unset($links['edit']);
      // Add our custom links to the returned array value.[16102021]
      return array_merge(array(
         '<a href="' . admin_url('admin.php?page=wpform-google-sheet-config&tab=integration') . '">' . __('Settings', 'gsheetconnector-wpforms') . '</a>',
         '<a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro" target="_blank">' . __(' <span style="color: #ff0000; font-weight: bold;">Upgrade to PRO</span>')
                  ), $links);
   }

   /**
    * called on upgrade. 
    * checks the current version and applies the necessary upgrades from that version onwards
    * @since 1.0
    */
   public function run_on_upgrade() {
     $plugin_options = get_site_option('wpform_GS_info');
      
      if ($plugin_options['version'] <= "1.3") {
         $this->upgrade_database_20();
      }
      // update the version value
        $google_sheet_info = array(
            'version' => WPFORMS_GOOGLESHEET_VERSION,
            'db_version' => WPFORMS_GOOGLESHEET_DB_VERSION
        );
        // check if debug log file exists or not
        $wplogFilePathToDelete = WPFORMS_GOOGLESHEET_PATH . "logs/log.txt";
        // Check if the log file exists before attempting to delete
        if (file_exists($wplogFilePathToDelete)) {
            unlink($wplogFilePathToDelete);
        }

      // update the version value
      $wpform_GS_info = array(
         'version' => WPFORMS_GOOGLESHEET_VERSION,
         'db_version' => WPFORMS_GOOGLESHEET_DB_VERSION
      );
      update_site_option('wpform_GS_info', $wpform_GS_info);
   }
   
   public function upgrade_database_20() {
      global $wpdb;

      // look through each of the blogs and upgrade the DB
      if (function_exists('is_multisite') && is_multisite()) {
         //Get all blog ids;
         $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
         foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            $this->upgrade_helper_20();
            restore_current_blog();
         }
         return;
      }
      $this->upgrade_helper_20();
   }
   
   public function upgrade_helper_20() {
      // Add the transient to redirect.
      set_transient('wpform_gs_upgrade_redirect', true, 30);
   }
   
   public function redirect_after_upgrade() {
      if ( ! get_transient('wpform_gs_upgrade_redirect') ) {
         return;
      }
      $plugin_options = get_site_option( 'wpform_GS_info' );
      if( $plugin_options['version'] == "2.0") {
         delete_transient('wpform_gs_upgrade_redirect');
         wp_safe_redirect('admin.php?page=wpform-google-sheet-config&tab=integration');
      }
   }

   /**
    * Called on activation.
    * Creates the site_options (required for all the sites in a multi-site setup)
    * If the current version doesn't match the new version, runs the upgrade
    * @since 1.0
    */
   private function run_on_activation() {
      $plugin_options = get_site_option('wpform_GS_info');
      if (false === $plugin_options) {
         $wpform_GS_info = array(
            'version' => WPFORMS_GOOGLESHEET_VERSION,
            'db_version' => WPFORMS_GOOGLESHEET_DB_VERSION
         );
         update_site_option('wpform_GS_info', $wpform_GS_info);
      } else if (WPFORMS_GOOGLESHEET_DB_VERSION != $plugin_options['version']) {
         $this->run_on_upgrade();
      }
   }

   /**
    * Called on activation.
    * Creates the options and DB (required by per site)
    * @since 1.0
    */
   private function run_for_site() {
      if (!get_option('wpform_gs_access_code')) {
         update_option('wpform_gs_access_code', '');
      }
      if (!get_option('wpform_gs_verify')) {
         update_option('wpform_gs_verify', 'invalid');
      }
      if (!get_option('wpform_gs_token')) {
         update_option('wpform_gs_token', '');
      }
      if (!get_option('wpform_uninstall')) {
         update_option('wpform_uninstall', 'false');
      }
   }

   /**
    * Called on uninstall - deletes site specific options
    *
    * @since 1.0
    */
   private static function delete_for_site() {

      delete_option('wpform_gs_access_code');
      delete_option('wpform_gs_verify');
      delete_option('wpform_gs_token');
      delete_post_meta_by_key('wpform_gs_settings');
      delete_post_meta_by_key('wpform_gs_settings_new');
   }

   /**
    * Called on uninstall - deletes site_options
    *
    * @since 1.0
    */
   private static function run_on_uninstall() {
      if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
         exit();

      delete_site_option('wpform_GS_info');
   }

   /**
    * Build System Information String
    * @global object $wpdb
    * @return string
    * @since 1.2
    */
   public function get_wpforms_system_info() {

        global $wpdb;

        // Get WordPress version
        $wp_version = get_bloginfo('version');

        // Get theme info
        $theme_data = wp_get_theme();
        $theme_name_version = $theme_data->get('Name') . ' ' . $theme_data->get('Version');
        $parent_theme = $theme_data->get('Template');

        if (!empty($parent_theme)) {
            $parent_theme_data = wp_get_theme($parent_theme);
            $parent_theme_name_version = $parent_theme_data->get('Name') . ' ' . $parent_theme_data->get('Version');
        } else {
            $parent_theme_name_version = 'N/A';
        }

        
        // Check plugin version and subscription plan
        $plugin_version = defined('WPFORMS_GOOGLESHEET_VERSION') ? WPFORMS_GOOGLESHEET_VERSION : 'N/A';
        $subscription_plan = 'FREE';

        // Check Google Account Authentication
        // $api_token = get_option('gs_token');
        // $google_sheet = new CF7GSC_googlesheet_PRO();
        // $email_account = $google_sheet->gsheet_print_google_account_email();

        $api_token_auto = get_option('wpform_gs_token');

        if (!empty($api_token_auto)) {
            // The user is authenticated through the auto method
            $google_sheet_auto = new wpfgsc_googlesheet();
            $email_account_auto = $google_sheet_auto->gsheet_print_google_account_email();
            $connected_email = !empty($email_account_auto) ? esc_html($email_account_auto) : 'Not Auth';
        } else {
            // Auto authentication is the only method available
            $connected_email = 'Not Auth';
        }


        // $google_sheet = new CF7GSC_googlesheet_PRO();
        // $email_account = $google_sheet->gsheet_print_google_account_email_manual(); 
        //$api_status = empty($api_token) ? 'Not Authenticated' : 'Authenticated';

        // Check Google Permission
        $gs_verify_status = get_option('wpform_gs_verify');
        $search_permission = ($gs_verify_status === 'valid') ? 'Given' : 'Not Given';

    
        // Create the system info HTML
        $system_info = '<div class="system-statuswc">';
        $system_info .= '<h4><button id="show-info-button" class="info-button">GSheetConnector<span class="dashicons dashicons-arrow-down"></span></h4>';
        $system_info .= '<div id="info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>GSheetConnector</h3>';
        $system_info .= '<table>';
        $system_info .= '<tr><td>Plugin Version</td><td>' . esc_html($plugin_version) . '</td></tr>';
        $system_info .= '<tr><td>Plugin Subscription Plan</td><td>' . esc_html($subscription_plan) . '</td></tr>';
        $system_info .= '<tr><td>Connected Email Account</td><td>' . $connected_email . '</td></tr>';
        $system_info .= '<tr><td>Google Drive Permission</td><td>' . esc_html($search_permission) . '</td></tr>';
        $system_info .= '<tr><td>Google Sheet Permission</td><td>' . esc_html($search_permission) . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';
         // Add WordPress info
        // Create a button for WordPress info
        $system_info .= '<h2><button id="show-wordpress-info-button" class="info-button">WordPress Info<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="wordpress-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>WordPress Info</h3>';
        $system_info .= '<table>';
        $system_info .= '<tr><td>Version</td><td>' . get_bloginfo('version') . '</td></tr>';
        $system_info .= '<tr><td>Site Language</td><td>' . get_bloginfo('language') . '</td></tr>';
        $system_info .= '<tr><td>Debug Mode</td><td>' . (WP_DEBUG ? 'Enabled' : 'Disabled') . '</td></tr>';
        $system_info .= '<tr><td>Home URL</td><td>' . get_home_url() . '</td></tr>';
        $system_info .= '<tr><td>Site URL</td><td>' . get_site_url() . '</td></tr>';
        $system_info .= '<tr><td>Permalink structure</td><td>' . get_option('permalink_structure') . '</td></tr>';
        $system_info .= '<tr><td>Is this site using HTTPS?</td><td>' . (is_ssl() ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Is this a multisite?</td><td>' . (is_multisite() ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Can anyone register on this site?</td><td>' . (get_option('users_can_register') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Is this site discouraging search engines?</td><td>' . (get_option('blog_public') ? 'No' : 'Yes') . '</td></tr>';
        $system_info .= '<tr><td>Default comment status</td><td>' . get_option('default_comment_status') . '</td></tr>';

        $server_ip = $_SERVER['REMOTE_ADDR'];
        if ($server_ip == '127.0.0.1' || $server_ip == '::1') {
            $environment_type = 'localhost';
        } else {
            $environment_type = 'production';
        }
        $system_info .= '<tr><td>Environment type</td><td>' . esc_html($environment_type) . '</td></tr>';

        $user_count = count_users();
        $total_users = $user_count['total_users'];
        $system_info .= '<tr><td>User Count</td><td>' . esc_html($total_users) . '</td></tr>';

        $system_info .= '<tr><td>Communication with WordPress.org</td><td>' . (get_option('blog_publicize') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // info about active theme
        $active_theme = wp_get_theme();

        $system_info .= '<h2><button id="show-active-info-button" class="info-button">Active Theme<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="active-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Active Theme</h3>';
        $system_info .= '<table>';
        $system_info .= '<tr><td>Name</td><td>' . $active_theme->get('Name') .'</td></tr>';
        $system_info .= '<tr><td>Version</td><td>' . $active_theme->get('Version') .'</td></tr>';
        $system_info .= '<tr><td>Author</td><td>' . $active_theme->get('Author') .'</td></tr>';
        $system_info .= '<tr><td>Author website</td><td>' . $active_theme->get('AuthorURI') .'</td></tr>';
        $system_info .= '<tr><td>Theme directory location</td><td>' . $active_theme->get_template_directory() .'</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // Get a list of other plugins you want to check compatibility with
        $other_plugins = array(
            'plugin-folder/plugin-file.php', // Replace with the actual plugin slug
            // Add more plugins as needed
        );

        // Network Active Plugins
        if (is_multisite()) {
           $network_active_plugins = get_site_option('active_sitewide_plugins', array());
           if (!empty($network_active_plugins)) {
               $system_info .= '<h2><button id="show-netplug-info-button" class="info-button">Network Active plugins<span class="dashicons dashicons-arrow-down"></span></h2>';
               $system_info .= '<div id="netplug-info-container" class="info-content" style="display:none;">';
               $system_info .= '<h3>Network Active plugins</h3>';
               $system_info .= '<table>';
               foreach ($network_active_plugins as $plugin => $plugin_data) {
                   $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
                   $system_info .= '<tr><td>' . $plugin_data['Name'] . '</td><td>' . $plugin_data['Version'] . '</td></tr>';
               }
               // Add more network active plugin statuses here...
                $system_info .= '</table>';
                $system_info .= '</div>';
           }
        }
        // Active plugins
        $system_info .= '<h2><button id="show-acplug-info-button" class="info-button">Active plugins<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="acplug-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Active plugins</h3>';
        $system_info .= '<table>';

        // Retrieve all active plugins data
        $active_plugins_data = array();
        $active_plugins = get_option('active_plugins', array());
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $active_plugins_data[$plugin] = array(
                'name'    => $plugin_data['Name'],
                'version' => $plugin_data['Version'],
                'count'   => 0, // Initialize the count to zero
            );
        }

        // Count the number of active installations for each plugin
        $all_plugins = get_plugins();
        foreach ($all_plugins as $plugin_file => $plugin_data) {
            if (array_key_exists($plugin_file, $active_plugins_data)) {
                $active_plugins_data[$plugin_file]['count']++;
            }
        }

        // Sort plugins based on the number of active installations (descending order)
        uasort($active_plugins_data, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Display the top 5 most used plugins
        $counter = 0;
        foreach ($active_plugins_data as $plugin_data) {
            $system_info .= '<tr><td>' . $plugin_data['name'] . '</td><td>' . $plugin_data['version'] . '</td></tr>';
            // $counter++;
            // if ($counter >= 5) {
            //     break;
            // }
        }
        $system_info .= '</table>';
        $system_info .= '</div>';
        // Webserver Configuration
        $system_info .= '<h2><button id="show-server-info-button" class="info-button">Server<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="server-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Server</h3>';
        $system_info .= '<table>';
        $system_info .= '<p>The options shown below relate to your server setup. If changes are required, you may need your web host’s assistance.</p>';
        // Add Server information
        $system_info .= '<tr><td>Server Architecture</td><td>' . esc_html(php_uname('s')) . '</td></tr>';
        $system_info .= '<tr><td>Web Server</td><td>' . esc_html($_SERVER['SERVER_SOFTWARE']) . '</td></tr>';
        $system_info .= '<tr><td>PHP Version</td><td>' . esc_html(phpversion()) . '</td></tr>';
        $system_info .= '<tr><td>PHP SAPI</td><td>' . esc_html(php_sapi_name()) . '</td></tr>';
        $system_info .= '<tr><td>PHP Max Input Variables</td><td>' . esc_html(ini_get('max_input_vars')) . '</td></tr>';
        $system_info .= '<tr><td>PHP Time Limit</td><td>' . esc_html(ini_get('max_execution_time')) . ' seconds</td></tr>';
        $system_info .= '<tr><td>PHP Memory Limit</td><td>' . esc_html(ini_get('memory_limit')) . '</td></tr>';
        $system_info .= '<tr><td>Max Input Time</td><td>' . esc_html(ini_get('max_input_time')) . ' seconds</td></tr>';
        $system_info .= '<tr><td>Upload Max Filesize</td><td>' . esc_html(ini_get('upload_max_filesize')) . '</td></tr>';
        $system_info .= '<tr><td>PHP Post Max Size</td><td>' . esc_html(ini_get('post_max_size')) . '</td></tr>';
        $system_info .= '<tr><td>cURL Version</td><td>' . esc_html(curl_version()['version']) . '</td></tr>';
        $system_info .= '<tr><td>Is SUHOSIN Installed?</td><td>' . (extension_loaded('suhosin') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Is the Imagick Library Available?</td><td>' . (extension_loaded('imagick') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Are Pretty Permalinks Supported?</td><td>' . (get_option('permalink_structure') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>.htaccess Rules</td><td>' . esc_html(is_writable('.htaccess') ? 'Writable' : 'Non Writable') . '</td></tr>';
        $system_info .= '<tr><td>Current Time</td><td>' . esc_html(current_time('mysql')) . '</td></tr>';
        $system_info .= '<tr><td>Current UTC Time</td><td>' . esc_html(current_time('mysql', true)) . '</td></tr>';
        $system_info .= '<tr><td>Current Server Time</td><td>' . esc_html(date('Y-m-d H:i:s')) . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // Database Configuration
        $system_info .= '<h2><button id="show-database-info-button" class="info-button">Database<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="database-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Database</h3>';
        $system_info .= '<table>';
        $database_extension = 'mysqli';
        $database_server_version = $wpdb->get_var("SELECT VERSION() as version");
        $database_client_version = $wpdb->db_version();
        $database_username = DB_USER;
        $database_host = DB_HOST;
        $database_name = DB_NAME;
        $table_prefix = $wpdb->prefix;
        $database_charset = $wpdb->charset;
        $database_collation = $wpdb->collate;
        $max_allowed_packet_size = $wpdb->get_var("SHOW VARIABLES LIKE 'max_allowed_packet'");
        $max_connections_number = $wpdb->get_var("SHOW VARIABLES LIKE 'max_connections'");

        $system_info .= '<tr><td>Extension</td><td>' . esc_html($database_extension) . '</td></tr>';
        $system_info .= '<tr><td>Server Version</td><td>' . esc_html($database_server_version) . '</td></tr>';
        $system_info .= '<tr><td>Client Version</td><td>' . esc_html($database_client_version) . '</td></tr>';
        $system_info .= '<tr><td>Database Username</td><td>' . esc_html($database_username) . '</td></tr>';
        $system_info .= '<tr><td>Database Host</td><td>' . esc_html($database_host) . '</td></tr>';
        $system_info .= '<tr><td>Database Name</td><td>' . esc_html($database_name) . '</td></tr>';
        $system_info .= '<tr><td>Table Prefix</td><td>' . esc_html($table_prefix) . '</td></tr>';
        $system_info .= '<tr><td>Database Charset</td><td>' . esc_html($database_charset) . '</td></tr>';
        $system_info .= '<tr><td>Database Collation</td><td>' . esc_html($database_collation) . '</td></tr>';
        $system_info .= '<tr><td>Max Allowed Packet Size</td><td>' . esc_html($max_allowed_packet_size) . '</td></tr>';
        $system_info .= '<tr><td>Max Connections Number</td><td>' . esc_html($max_connections_number) . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // wordpress constants
        $system_info .= '<h2><button id="show-wrcons-info-button" class="info-button">WordPress Constants<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="wrcons-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>WordPress Constants</h3>';
        $system_info .= '<table>';
        // Add WordPress Constants information
        $system_info .= '<tr><td>ABSPATH</td><td>' . esc_html(ABSPATH) . '</td></tr>';
        $system_info .= '<tr><td>WP_HOME</td><td>' . esc_html(home_url()) . '</td></tr>';
        $system_info .= '<tr><td>WP_SITEURL</td><td>' . esc_html(site_url()) . '</td></tr>';
        $system_info .= '<tr><td>WP_CONTENT_DIR</td><td>' . esc_html(WP_CONTENT_DIR) . '</td></tr>';
        $system_info .= '<tr><td>WP_PLUGIN_DIR</td><td>' . esc_html(WP_PLUGIN_DIR) . '</td></tr>';
        $system_info .= '<tr><td>WP_MEMORY_LIMIT</td><td>' . esc_html(WP_MEMORY_LIMIT) . '</td></tr>';
        $system_info .= '<tr><td>WP_MAX_MEMORY_LIMIT</td><td>' . esc_html(WP_MAX_MEMORY_LIMIT) . '</td></tr>';
        $system_info .= '<tr><td>WP_DEBUG</td><td>' . (defined('WP_DEBUG') && WP_DEBUG ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>WP_DEBUG_DISPLAY</td><td>' . (defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>SCRIPT_DEBUG</td><td>' . (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>WP_CACHE</td><td>' . (defined('WP_CACHE') && WP_CACHE ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>CONCATENATE_SCRIPTS</td><td>' . (defined('CONCATENATE_SCRIPTS') && CONCATENATE_SCRIPTS ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>COMPRESS_SCRIPTS</td><td>' . (defined('COMPRESS_SCRIPTS') && COMPRESS_SCRIPTS ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>COMPRESS_CSS</td><td>' . (defined('COMPRESS_CSS') && COMPRESS_CSS ? 'Yes' : 'No') . '</td></tr>';
        // Manually define the environment type (example values: 'development', 'staging', 'production')
        $environment_type = 'development';

        // Display the environment type
        $system_info .= '<tr><td>WP_ENVIRONMENT_TYPE</td><td>' . esc_html($environment_type) . '</td></tr>';

        $system_info .= '<tr><td>WP_DEVELOPMENT_MODE</td><td>' . (defined('WP_DEVELOPMENT_MODE') && WP_DEVELOPMENT_MODE ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>DB_CHARSET</td><td>' . esc_html(DB_CHARSET) . '</td></tr>';
        $system_info .= '<tr><td>DB_COLLATE</td><td>' . esc_html(DB_COLLATE) . '</td></tr>';

        $system_info .= '</table>';
        $system_info .= '</div>';

        // Filesystem Permission
        $system_info .= '<h2><button id="show-ftps-info-button" class="info-button">Filesystem Permission <span class="dashicons dashicons-arrow-down"></span></button></h2>';
        $system_info .= '<div id="ftps-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Filesystem Permission</h3>';
        $system_info .= '<p>Shows whether WordPress is able to write to the directories it needs access to.</p>';
        $system_info .= '<table>';
        // Filesystem Permission information
        $system_info .= '<tr><td>The main WordPress directory</td><td>' . esc_html(ABSPATH) . '</td><td>' . (is_writable(ABSPATH) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The wp-content directory</td><td>' . esc_html(WP_CONTENT_DIR) . '</td><td>' . (is_writable(WP_CONTENT_DIR) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The uploads directory</td><td>' . esc_html(wp_upload_dir()['basedir']) . '</td><td>' . (is_writable(wp_upload_dir()['basedir']) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The plugins directory</td><td>' . esc_html(WP_PLUGIN_DIR) . '</td><td>' . (is_writable(WP_PLUGIN_DIR) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The themes directory</td><td>' . esc_html(get_theme_root()) . '</td><td>' . (is_writable(get_theme_root()) ? 'Writable' : 'Not Writable') . '</td></tr>';

        $system_info .= '</table>';
        $system_info .= '</div>';

        return $system_info;
    }
    
   public function display_error_log() {
        // Define the path to your debug log file
        $debug_log_file = WP_CONTENT_DIR . '/debug.log';

        // Check if the debug log file exists
        if (file_exists($debug_log_file)) {
            // Read the contents of the debug log file
            $debug_log_contents = file_get_contents($debug_log_file);

            // Split the log content into an array of lines
            $log_lines = explode("\n", $debug_log_contents);

            // Get the last 100 lines in reversed order
            $last_100_lines = array_slice(array_reverse($log_lines), 0, 100);

            // Join the lines back together with line breaks
            $last_100_log = implode("\n", $last_100_lines);

            // Output the last 100 lines in reversed order in a textarea
            ?>
            <textarea class="errorlog" rows="20" cols="80"><?php echo esc_textarea($last_100_log); ?></textarea>
            <?php
        } else {
            echo 'Debug log file not found.';
        }
    }

   

}

// Initialize the wpform google sheet connector class
$init = new WPforms_Gsheet_Connector_Init();